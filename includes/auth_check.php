<?php
/**
 * AUTH_CHECK.PHP
 * Vérification centralisée de l'authentification et des rôles
 * À inclure en haut de chaque page protégée
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/database.php';

/**
 * Vérifie l'authentification et retourne l'utilisateur connecté
 * Redirige vers login si non authentifié
 */
function checkAuth(string $requiredRole = null): array {
    global $pdo;
    
    $sessionToken = $_SESSION['coach_session_token'] ?? $_COOKIE['gbo_remember'] ?? null;
    
    if (!$sessionToken) {
        redirect('login.php', 'error', 'Veuillez vous connecter pour accéder à cette page.');
    }
    
    // Vérifier session en base avec rôle
    $stmt = $pdo->prepare("
        SELECT 
            c.id, c.coach_id, c.email, c.nom, c.prenom, 
            c.niveau, c.specialite, c.commune, c.photo, c.bio,
            c.role_id, c.est_admin, c.statut,
            r.nom as role_nom, r.label as role_label, r.permissions as role_permissions
        FROM sessions s
        JOIN coachs c ON s.coach_id = c.id
        LEFT JOIN roles r ON c.role_id = r.id
        WHERE s.session_token = ? 
        AND s.est_valide = TRUE 
        AND s.date_expiration > NOW()
        AND c.statut = 'actif'
    ");
    $stmt->execute([$sessionToken]);
    $user = $stmt->fetch();
    
    if (!$user) {
        // Nettoyer session invalide
        $_SESSION = [];
        setcookie('gbo_remember', '', ['expires' => time() - 3600, 'path' => '/']);
        redirect('login.php', 'error', 'Session expirée. Veuillez vous reconnecter.');
    }
    
    // Mettre à jour dernière activité
    $stmt = $pdo->prepare("UPDATE sessions SET derniere_activite = NOW() WHERE session_token = ?");
    $stmt->execute([$sessionToken]);
    
    // Décoder permissions JSON
    $user['permissions'] = json_decode($user['role_permissions'] ?? '[]', true);
    
    // Vérifier rôle requis
    if ($requiredRole && !hasPermission($user, $requiredRole)) {
        logAdminAction($user['id'], 'acces_refuse', null, null, [
            'page_demandee' => $_SERVER['REQUEST_URI'],
            'role_requis' => $requiredRole
        ]);
        redirect('dashboard_' . ($user['est_admin'] ? 'admin' : 'coach') . '.php', 
                 'error', 
                 'Vous n\'avez pas les permissions pour accéder à cette page.');
    }
    
    return $user;
}

/**
 * Vérifie si l'utilisateur a une permission spécifique
 */
function hasPermission(array $user, string $permission): bool {
    $permissions = $user['permissions'] ?? [];
    
    // Super admin a tous les droits
    if (in_array('*', $permissions)) {
        return true;
    }
    
    // Vérification exacte ou wildcard
    foreach ($permissions as $perm) {
        if ($perm === $permission) {
            return true;
        }
        // Wildcard ex: "gestion_*" match "gestion_coachs"
        if (strpos($perm, '*') !== false) {
            $pattern = str_replace('*', '.*', preg_quote($perm, '/'));
            if (preg_match('/^' . $pattern . '$/', $permission)) {
                return true;
            }
        }
    }
    
    return false;
}

/**
 * Vérifie si l'utilisateur est admin
 */
function isAdmin(array $user): bool {
    return ($user['est_admin'] ?? false) === true;
}

/**
 * Log d'action admin (audit trail)
 */
function logAdminAction(int $userId, string $action, ?string $table, ?int $targetId, array $details = []): void {
    global $pdo;
    
    try {
        $stmt = $pdo->prepare("
            INSERT INTO admin_logs (utilisateur_id, action, cible_table, cible_id, details, ip_address)
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $userId,
            $action,
            $table,
            $targetId,
            json_encode($details),
            $_SERVER['REMOTE_ADDR'] ?? null
        ]);
    } catch (PDOException $e) {
        error_log("Erreur log admin: " . $e->getMessage());
    }
}

/**
 * Récupère le nombre de notifications non lues pour un utilisateur
 */
function getUnreadCount(int $userId): int {
    global $pdo;
    
    $stmt = $pdo->prepare("
        SELECT COUNT(*) FROM notifications 
        WHERE coach_id = ? AND est_lue = FALSE
    ");
    $stmt->execute([$userId]);
    return (int) $stmt->fetchColumn();
}

/**
 * Récupère le nombre de messages non lus pour un utilisateur
 */
function getUnreadMessagesCount(int $userId): int {
    global $pdo;
    
    $stmt = $pdo->prepare("
        SELECT SUM(
            (SELECT COUNT(*) FROM messages m 
             WHERE m.conversation_id = cp.conversation_id 
             AND m.id > COALESCE(cp.dernier_lu_id, 0))
        ) as total
        FROM conversation_participants cp
        WHERE cp.coach_id = ?
    ");
    $stmt->execute([$userId]);
    return (int) ($stmt->fetchColumn() ?? 0);
}