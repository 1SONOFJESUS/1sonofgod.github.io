<?php
/**
 * Traitement d'authentification pour l'espace coach
 * Simple implémentation : vérifie identifiant et mot de passe
 */
require_once __DIR__ . '/../includes/config.php';

// Nettoyage des entrées
$coach_id = isset($_POST['coach_id']) ? clean($_POST['coach_id']) : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';

if (!$coach_id || !$password) {
    setFlash('error', 'Identifiant et mot de passe requis.');
    redirect(BASE_URL . 'pages/login.php?role=coach');
}

try {
    $stmt = $pdo->prepare('SELECT id, coach_id, password_hash, role FROM coaches WHERE coach_id = ? LIMIT 1');
    $stmt->execute([$coach_id]);
    $coach = $stmt->fetch();

    if ($coach && password_verify($password, $coach['password_hash'])) {
        // Authentification réussie
        session_regenerate_id(true);
        $_SESSION['coach_id'] = $coach['coach_id'];
        $_SESSION['user_role'] = 'coach';
        setFlash('success', 'Connexion réussie.');
        redirect(BASE_URL . 'pages/dashboard_coach.php');
    } else {
        setFlash('error', 'Identifiant ou mot de passe incorrect.');
        redirect(BASE_URL . 'pages/login.php?role=coach');
    }
} catch (Exception $e) {
    setFlash('error', 'Erreur serveur lors de la connexion.');
    redirect(BASE_URL . 'pages/login.php?role=coach');
}

?>
<?php
/**
 * AUTH_COACH.PHP
 * Traitement du formulaire de connexion espace coach
 * Sécurité : CSRF, bruteforce, sessions sécurisées, logs
 */

session_start();
require_once __DIR__ . '/../config/database.php';

// Vérifier méthode POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('/gbo_africa_group/pages/login.php?role=coach', 'error', 'Méthode non autorisée.');
}

// Vérifier CSRF
if (!isset($_POST['csrf_token']) || !verifyCSRFToken($_POST['csrf_token'])) {
    redirect('/gbo_africa_group/pages/login.php?role=coach', 'error', 'Session invalide. Veuillez réessayer.');
}

// Récupérer et nettoyer les données
$coachId = strtoupper(sanitize($_POST['coach_id'] ?? ''));
$password = $_POST['password'] ?? '';
$remember = isset($_POST['remember']);

// Validation basique
if (empty($coachId) || empty($password)) {
    redirect('/gbo_africa_group/pages/login.php?role=coach', 'error', 'Veuillez remplir tous les champs.');
}

// Format ID Coach (GBO-XXXX)
if (!preg_match('/^GBO-\d{4,}$/', $coachId)) {
    redirect('/gbo_africa_group/pages/login.php?role=coach', 'error', 'Format d\'identifiant invalide. Utilisez GBO-XXXX.');
}

try {
    // Rechercher le coach
    $stmt = $pdo->prepare("
        SELECT id, coach_id, email, password_hash, nom, prenom, niveau, specialite, 
               commune, statut, tentatives_echouees, verrouille_jusqua
        FROM coachs 
        WHERE coach_id = ?
    ");
    $stmt->execute([$coachId]);
    $coach = $stmt->fetch();

    // Coach non trouvé
    if (!$coach) {
        // Log sécurité
        error_log("Tentative login échouée - ID inconnu: " . $coachId . " - IP: " . $_SERVER['REMOTE_ADDR']);
        redirect('/gbo_africa_group/pages/login.php?role=coach', 'error', 'Identifiant ou mot de passe incorrect.');
    }

    // Vérifier si le compte est verrouillé
    if ($coach['verrouille_jusqua'] && strtotime($coach['verrouille_jusqua']) > time()) {
        $attente = ceil((strtotime($coach['verrouille_jusqua']) - time()) / 60);
            redirect('/gbo_africa_group/pages/login.php?role=coach', 'error', 'Compte temporairement verrouillé. Réessayez dans ' . $attente . ' minutes.');
    }

    // Vérifier si le compte est actif
    if ($coach['statut'] !== 'actif') {
        redirect('/gbo_africa_group/pages/login.php?role=coach', 'error', 'Votre compte est ' . $coach['statut'] . '. Contactez l\'administration.');
    }

    // Vérifier le mot de passe
    if (!password_verify($password, $coach['password_hash'])) {
        // Incrémenter les tentatives échouées
        $tentatives = $coach['tentatives_echouees'] + 1;
        
        if ($tentatives >= 5) {
            // Verrouiller 30 minutes après 5 tentatives
            $verrouille = date('Y-m-d H:i:s', strtotime('+30 minutes'));
            $stmt = $pdo->prepare("UPDATE coachs SET tentatives_echouees = ?, verrouille_jusqua = ? WHERE id = ?");
            $stmt->execute([$tentatives, $verrouille, $coach['id']]);
            redirect('/gbo_africa_group/pages/login.php?role=coach', 'error', 'Compte verrouillé 30 minutes après 5 tentatives échouées.');
        } else {
            $stmt = $pdo->prepare("UPDATE coachs SET tentatives_echouees = ? WHERE id = ?");
            $stmt->execute([$tentatives, $coach['id']]);
            redirect('/gbo_africa_group/pages/login.php?role=coach', 'error', 'Identifiant ou mot de passe incorrect. (' . $tentatives . '/5)');
        }
    }

    // ✅ AUTHENTIFICATION RÉUSSIE

    // Réinitialiser les tentatives
    $stmt = $pdo->prepare("UPDATE coachs SET tentatives_echouees = 0, verrouille_jusqua = NULL, derniere_connexion = NOW() WHERE id = ?");
    $stmt->execute([$coach['id']]);

    // Générer token de session sécurisé
    $sessionToken = bin2hex(random_bytes(32));
    $expires = $remember ? '+30 days' : '+8 hours';
    $expirationDate = date('Y-m-d H:i:s', strtotime($expires));

    // Enregistrer la session en base
    $stmt = $pdo->prepare("
        INSERT INTO sessions (coach_id, session_token, ip_address, user_agent, date_expiration)
        VALUES (?, ?, ?, ?, ?)
    ");
    $stmt->execute([
        $coach['id'],
        $sessionToken,
        $_SERVER['REMOTE_ADDR'],
        substr($_SERVER['HTTP_USER_AGENT'] ?? 'Unknown', 0, 255),
        $expirationDate
    ]);

    // Stocker en session PHP
    $_SESSION['coach_session_token'] = $sessionToken;
    $_SESSION['coach_id'] = $coach['id'];
    $_SESSION['coach_public_id'] = $coach['coach_id'];
    $_SESSION['coach_nom'] = $coach['prenom'] . ' ' . $coach['nom'];
    $_SESSION['coach_niveau'] = $coach['niveau'];
    $_SESSION['coach_commune'] = $coach['commune'];
    $_SESSION['session_expires'] = strtotime($expirationDate);

    // Cookie "remember me" (30 jours)
    if ($remember) {
        setcookie('gbo_remember', $sessionToken, [
            'expires' => time() + 30 * 24 * 3600,
            'path' => '/',
            'secure' => true,
            'httponly' => true,
            'samesite' => 'Strict'
        ]);
    }

    // Log connexion réussie
    error_log("Connexion réussie - Coach: " . $coachId . " - IP: " . $_SERVER['REMOTE_ADDR']);

    redirect('dashboard_coach.php', 'success', 'Bienvenue, Coach ' . $coach['prenom'] . ' !');

} catch (PDOException $e) {
    error_log("Erreur auth: " . $e->getMessage());
    redirect('/gbo_africa_group/pages/login.php?role=coach', 'error', 'Erreur technique. Veuillez réessayer.');
}