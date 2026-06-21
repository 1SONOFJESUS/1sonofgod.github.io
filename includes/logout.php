<?php
require_once __DIR__ . '/config_session.php';
require_once __DIR__ . '/../config/database.php';

// Supprimer le token "Se souvenir de moi" en BDD
if (isset($_SESSION['user_id'])) {
    try {
        $pdo->prepare("DELETE FROM remember_tokens WHERE user_id = ?")
            ->execute([$_SESSION['user_id']]);
    } catch (PDOException $e) {
        // Silencieux
    }
}

// Supprimer le cookie
setcookie('gbo_remember', '', [
    'expires' => 1,
    'path' => '/gbo_africa_group/',
    'secure' => isset($_SERVER['HTTPS']),
    'httponly' => true,
    'samesite' => 'Lax'
]);

// Détruire la session
$_SESSION = [];
session_destroy();

header('Location: ../index.php');
exit;