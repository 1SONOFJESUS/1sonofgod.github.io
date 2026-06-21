<?php
/**
 * CONFIGURATION BASE DE DONNÉES — GBÔ AFRICA GROUP
 * Connexion PDO avec gestion d'erreurs et UTF-8
 */

// Paramètres de connexion (à adapter selon l'environnement)
define('DB_HOST', 'localhost');
define('DB_NAME', 'gbo_africa_group');
define('DB_USER', 'root');
define('DB_PASS', ''); // CHANGER EN PRODUCTION
define('DB_CHARSET', 'utf8mb4');

// Assurer que la session est démarrée avant d'utiliser $_SESSION
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Options PDO sécurisées
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES " . DB_CHARSET . " COLLATE utf8mb4_unicode_ci"
];

try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET,
        DB_USER,
        DB_PASS,
        $options
    );
} catch (PDOException $e) {
    error_log("Erreur connexion DB: " . $e->getMessage());
    die("Erreur de connexion à la base de données. Veuillez réessayer plus tard.");
}

/**
 * Fonction utilitaire : génère un token CSRF
 */
function generateCSRFToken(): string {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Fonction utilitaire : vérifie un token CSRF
 */
function verifyCSRFToken(string $token): bool {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Fonction utilitaire : nettoie les entrées utilisateur
 */
function sanitize(string $data): string {
    return htmlspecialchars(strip_tags(trim($data)), ENT_QUOTES, 'UTF-8');
}

/**
 * Fonction utilitaire : redirige avec message flash
 */
function redirect(string $url, string $type = '', string $message = ''): void {
    if ($type && $message) {
        $_SESSION['flash'] = ['type' => $type, 'message' => $message];
    }
    header("Location: " . $url);
    exit;
}

/**
 * Fonction utilitaire : affiche les messages flash
 */
function showFlash(): void {
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        echo '<div class="flash-message flash-' . $flash['type'] . '">';
        echo htmlspecialchars($flash['message']);
        echo '<button class="flash-close" onclick="this.parentElement.remove()">×</button>';
        echo '</div>';
    }
}