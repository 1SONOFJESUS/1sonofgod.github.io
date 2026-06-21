<?php
/**
 * API MARK_READ.PHP
 * Marque une conversation comme lue
 */
session_start();
header('Content-Type: application/json');

require_once __DIR__ . '/../../config/database.php';

$sessionToken = $_SESSION['coach_session_token'] ?? null;
if (!$sessionToken) {
    http_response_code(401);
    echo json_encode(['success' => false]);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
$conversationId = (int)($input['conversation_id'] ?? 0);

$stmt = $pdo->prepare("
    SELECT cp.coach_id 
    FROM conversation_participants cp
    JOIN sessions s ON cp.coach_id = s.coach_id
    WHERE cp.conversation_id = ? AND s.session_token = ? AND s.est_valide = TRUE
");
$stmt->execute([$conversationId, $sessionToken]);
$participant = $stmt->fetch();

if (!$participant) {
    http_response_code(403);
    echo json_encode(['success' => false]);
    exit;
}

// Marquer dernier message comme lu
$stmt = $pdo->prepare("
    SELECT MAX(id) FROM messages WHERE conversation_id = ?
");
$stmt->execute([$conversationId]);
$maxId = $stmt->fetchColumn();

if ($maxId) {
    $stmt = $pdo->prepare("
        UPDATE conversation_participants 
        SET dernier_lu_id = ? 
        WHERE conversation_id = ? AND coach_id = ?
    ");
    $stmt->execute([$maxId, $conversationId, $participant['coach_id']]);
}

echo json_encode(['success' => true]);