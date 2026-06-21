<?php
/**
 * CONFIGURATION GLOBALE GBÔ AFRICA GROUP
 * Fichier de configuration centralisé - À inclure en premier sur chaque page
 */

// Empêcher l'accès direct
if (!defined('GBO_ACCESS')) {
    define('GBO_ACCESS', true);
}

// === BASE DE DONNÉES ===
define('DB_HOST', 'localhost');
define('DB_NAME', 'gbo_africa');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

// === CHEMINS ===
define('ROOT_PATH', dirname(__DIR__) . '/');
define('INCLUDES_PATH', ROOT_PATH . 'includes/');
define('PAGES_PATH', ROOT_PATH . 'pages/');
define('ASSETS_PATH', ROOT_PATH . 'assets/');
define('CSS_PATH', ASSETS_PATH . 'css/');
define('JS_PATH', ASSETS_PATH . 'js/');
define('IMAGES_PATH', ASSETS_PATH . 'images/');
define('VIDEOS_PATH', ASSETS_PATH . 'videos/');

// === URLS ===
define('BASE_URL', 'http://localhost/gbo_africa_group/');
define('PAGES_URL', BASE_URL . 'pages/');
define('ASSETS_URL', BASE_URL . 'assets/');
define('CSS_URL', ASSETS_URL . 'css/');
define('JS_URL', ASSETS_URL . 'js/');
define('IMAGES_URL', ASSETS_URL . 'images/');
define('VIDEOS_URL', ASSETS_URL . 'videos/');

// === SESSION ===
if (session_status() === PHP_SESSION_NONE) {
    session_start();
    session_regenerate_id(true);
}

// === FUSEAU HORAIRE ===
date_default_timezone_set('Africa/Abidjan');

// === GESTION DES ERREURS (développement) ===
error_reporting(E_ALL);
ini_set('display_errors', 1);

// === CONNEXION BDD ===
try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET,
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]
    );
} catch (PDOException $e) {
    die("Erreur de connexion BDD : " . $e->getMessage());
}

// === FONCTIONS UTILITAIRES ===

/**
 * Nettoie une entrée utilisateur
 */
function clean($data) {
    return htmlspecialchars(strip_tags(trim($data)), ENT_QUOTES, 'UTF-8');
}

/**
 * Redirection sécurisée
 */
function redirect($url) {
    header("Location: " . $url);
    exit();
}

/**
 * Vérifie si l'utilisateur est connecté
 */
function isLoggedIn($role = null) {
    if (!isset($_SESSION['user_id'])) return false;
    if ($role && $_SESSION['user_role'] !== $role) return false;
    return true;
}

/**
 * Génère un token CSRF
 */
function generateToken() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Vérifie un token CSRF
 */
function verifyToken($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Messages flash
 */
function setFlash($type, $message) {
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

function getFlash() {
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}

/**
 * Récupère le titre de la page selon le fichier
 */
function getPageTitle($page) {
    $titles = [
        'accueil' => 'Accueil',
        'fitness' => 'GBÔ Fitness',
        'coach' => 'GBÔ Coach',
        'academy' => 'GBÔ Academy',
        'shop' => 'GBÔ Shop',
        'apropos' => 'À propos',
        'blog' => 'Blog',
        'contact' => 'Contact',
        'login_coach' => 'Connexion Coach',
        'login_client' => 'Connexion Client',
        'login_admin' => 'Connexion Administrateur',
        'dashboard_coach' => 'Dashboard Coach',
        'dashboard_client' => 'Dashboard Client',
        'dashboard_admin' => 'Dashboard Administrateur',
    ];
    return $titles[$page] ?? 'GBÔ AFRICA GROUP';
}
?>