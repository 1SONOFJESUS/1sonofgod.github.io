<?php
/**
 * MARK_ALL_READ.PHP
 * Marque toutes les notifications comme lues
 */
session_start();
require_once __DIR__ . '/../config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('dashboard_coach.php', 'error', 'Méthode non autorisée.');
}

if (!isset($_POST['csrf_token']) || !verifyCSRFToken($_POST['csrf_token'])) {
    redirect('dashboard_coach.php', 'error', 'Token invalide.');
}

// Vérifier auth
$sessionToken = $_SESSION['coach_session_token'] ?? null;
if (!$sessionToken) {
    redirect('/gbo_africa_group/pages/login.php?role=coach', 'error', 'Session expirée.');
}

// Récupérer coach_id
$stmt = $pdo->prepare("SELECT coach_id FROM sessions WHERE session_token = ? AND est_valide = TRUE AND date_expiration > NOW()");
$stmt->execute([$sessionToken]);
$session = $stmt->fetch();

if (!$session) {
    redirect('/gbo_africa_group/pages/login.php?role=coach', 'error', 'Session invalide.');
}

// Marquer comme lues
$stmt = $pdo->prepare("UPDATE notifications SET est_lue = TRUE WHERE coach_id = ? AND est_lue = FALSE");
$stmt->execute([$session['coach_id']]);

redirect('/gbo_africa_group/pages/dashboard_coach.php?tab=notif', 'success', 'Toutes les notifications ont été marquées comme lues.');