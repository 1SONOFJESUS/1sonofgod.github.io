<?php
/**
 * FONCTIONS GLOBALES GBÔ
 */

require_once __DIR__ . '/config.php';

/**
 * Récupère tous les enregistrements d'une table
 * @param string $table Nom de la table
 * @param string $order Clause ORDER BY
 * @return array
 */
function getAll($table, $order = 'id ASC') {
    global $pdo;
    if (!$pdo) return [];
    try {
        $stmt = $pdo->query("SELECT * FROM $table ORDER BY $order");
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        return [];
    }
}

/**
 * Nettoie une chaîne pour l'affichage HTML
 * @param string $str
 * @return string
 */
function clean($str) {
    return htmlspecialchars($str ?? '', ENT_QUOTES, 'UTF-8');
}

/**
 * Génère un token CSRF
 * @return string
 */
function generateToken() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Vérifie un token CSRF
 * @param string $token
 * @return bool
 */
function verifyToken($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Tronque un texte
 * @param string $text
 * @param int $length
 * @return string
 */
function truncate($text, $length = 100) {
    if (strlen($text) <= $length) return $text;
    return substr($text, 0, $length) . '...';
}

/**
 * Formate un prix en FCFA
 * @param float $price
 * @return string
 */
function formatPrice($price) {
    return number_format($price, 0, ',', ' ') . ' FCFA';
}

/**
 * Récupère les formations
 * @return array
 */
function getFormations() {
    return getAll('formations', 'ordre ASC');
}

/**
 * Récupère les coachs actifs
 * @return array
 */
function getActiveCoaches() {
    return getAll('coachs', 'niveau ASC');
}

/**
 * Récupère les produits
 * @return array
 */
function getProducts() {
    return getAll('produits', 'categorie ASC, nom ASC');
}

/**
 * Récupère les catégories de produits
 * @return array
 */
function getProductCategories() {
    global $pdo;
    if (!$pdo) return [];
    try {
        $stmt = $pdo->query("SELECT DISTINCT categorie FROM produits ORDER BY categorie ASC");
        return array_column($stmt->fetchAll(), 'categorie');
    } catch (PDOException $e) {
        return [];
    }
}

// Démarrer la session si pas déjà active
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>