<?php
/**
 * Configuration centralisée des sessions GBÔ AFRICA GROUP
 * Inclure ce fichier en PREMIER sur chaque page (avant tout output HTML)
 */

if (session_status() === PHP_SESSION_NONE) {
    // Configuration des cookies de session
    $cookieParams = [
        'lifetime' => 0,
        'path' => '/gbo_africa_group/',
        'domain' => '',
        'secure' => isset($_SERVER['HTTPS']),
        'httponly' => true,
        'samesite' => 'Lax'
    ];
    
    session_set_cookie_params($cookieParams);
    session_start();
}

// === RECONNEXION AUTO VIA COOKIE "Se souvenir de moi" ===
if (!isset($_SESSION['user_id']) && isset($_COOKIE['gbo_remember'])) {
    require_once __DIR__ . '/../config/database.php';
    
    $token = $_COOKIE['gbo_remember'];
    $tokenHash = hash('sha256', $token);
    
    try {
        $stmt = $pdo->prepare("
            SELECT r.*, u.id as user_id, u.email, u.prenom, u.nom, u.role 
            FROM remember_tokens r
            JOIN utilisateurs u ON r.user_id = u.id
            WHERE r.token_hash = ? AND r.expires_at > NOW()
            LIMIT 1
        ");
        $stmt->execute([$tokenHash]);
        $remember = $stmt->fetch();
        
        if ($remember) {
            // Régénérer la session
            session_regenerate_id(true);
            $_SESSION['user_id'] = $remember['user_id'];
            $_SESSION['role'] = $remember['role'];
            $_SESSION['email'] = $remember['email'];
            $_SESSION['prenom'] = $remember['prenom'];
            $_SESSION['nom'] = $remember['nom'];
            
            // Renouveler le token (rotation de sécurité)
            $newToken = bin2hex(random_bytes(32));
            $newTokenHash = hash('sha256', $newToken);
            $expires = date('Y-m-d H:i:s', strtotime('+30 days'));
            
            $pdo->prepare("UPDATE remember_tokens SET token_hash = ?, expires_at = ? WHERE id = ?")
                ->execute([$newTokenHash, $expires, $remember['id']]);
            
            setcookie('gbo_remember', $newToken, [
                'expires' => time() + (30 * 24 * 60 * 60),
                'path' => '/gbo_africa_group/',
                'secure' => isset($_SERVER['HTTPS']),
                'httponly' => true,
                'samesite' => 'Lax'
            ]);
        } else {
            // Token invalide ou expiré → suppression
            setcookie('gbo_remember', '', ['expires' => 1, 'path' => '/gbo_africa_group/']);
        }
    } catch (PDOException $e) {
        // Silencieux en production
    }
}

// === FONCTIONS DE PROTECTION ===

/**
 * Vérifie si l'utilisateur est connecté, sinon redirige
 */
function requireAuth(string $redirectTo = '/gbo_africa_group/includes/login.php'): void {
    if (empty($_SESSION['user_id'])) {
        $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
        header("Location: $redirectTo");
        exit;
    }
}

/**
 * Vérifie le rôle requis, sinon redirige vers le bon dashboard ou déconnecte
 * @param string|array $allowedRoles Rôle(s) autorisé(s)
 */
function requireRole(string|array $allowedRoles): void {
    requireAuth();
    
    $allowedRoles = (array) $allowedRoles;
    $currentRole = $_SESSION['role'] ?? null;
    
    if (!in_array($currentRole, $allowedRoles, true)) {
        // Redirection intelligente selon le rôle réel
        $redirects = [
            'admin' => '/gbo_africa_group/admin/dashboard.php',
            'coach' => '/gbo_africa_group/coach/dashboard.php',
            'client' => '/gbo_africa_group/client/dashboard.php'
        ];
        
        $safeRedirect = $redirects[$currentRole] ?? '/gbo_africa_group/index.php';
        
        header("Location: $safeRedirect");
        exit;
    }
}

/**
 * Vérifie si l'utilisateur est admin
 */
function isAdmin(): bool {
    return ($_SESSION['role'] ?? null) === 'admin';
}

/**
 * Vérifie si l'utilisateur est coach
 */
function isCoach(): bool {
    return ($_SESSION['role'] ?? null) === 'coach';
}

/**
 * Vérifie si l'utilisateur est client
 */
function isClient(): bool {
    return ($_SESSION['role'] ?? null) === 'client';
}

/**
 * Définit un flash message
 */
function setFlash(string $type, string $message): void {
    $_SESSION['flash'][$type] = $message;
}

/**
 * Récupère et supprime un flash message
 */
function getFlash(string $type): ?string {
    $message = $_SESSION['flash'][$type] ?? null;
    unset($_SESSION['flash'][$type]);
    return $message;
}