<?php
/**
 * API GET_MESSAGES.PHP
 * Récupère les messages d'une conversation (avec pagination)
 * Méthode: GET AJAX
 */
session_start();
header('Content-Type: application/json');

require_once __DIR__ . '/../../config/database.php';

// Vérifier auth
$sessionToken = $_SESSION['coach_session_token'] ?? null;
if (!$sessionToken) {
    http_response_code(401);
    echo json_encode(['success' => false, 'error' => 'Non authentifié']);
    exit;
}

$conversationId = (int)($_GET['conversation_id'] ?? 0);
$lastId = (int)($_GET['last_id'] ?? 0); // Pour polling : messages après cet ID
$limit = min((int)($_GET['limit'] ?? 50), 100);

if ($conversationId <= 0) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Conversation invalide']);
    exit;
}

// Vérifier accès
$stmt = $pdo->prepare("
    SELECT cp.coach_id, c.prenom, c.nom
    FROM conversation_participants cp
    JOIN sessions s ON cp.coach_id = s.coach_id
    JOIN coachs c ON cp.coach_id = c.id
    WHERE cp.conversation_id = ? AND s.session_token = ? AND s.est_valide = TRUE AND s.date_expiration > NOW()
");
$stmt->execute([$conversationId, $sessionToken]);
$participant = $stmt->fetch();

if (!$participant) {
    http_response_code(403);
    echo json_encode(['success' => false, 'error' => 'Accès non autorisé']);
    exit;
}

$coachId = $participant['coach_id'];

// Construire requête
$params = [$conversationId];
$where = "m.conversation_id = ?";
if ($lastId > 0) {
    $where .= " AND m.id > ?";
    $params[] = $lastId;
}

$stmt = $pdo->prepare("
    SELECT m.id, m.contenu, m.type, m.date_creation, m.expediteur_id,
           c.prenom, c.nom, c.coach_id as expediteur_public_id
    FROM messages m
    JOIN coachs c ON m.expediteur_id = c.id
    WHERE $where
    ORDER BY m.date_creation DESC
    LIMIT $limit
");
$stmt->execute($params);
$messages = $stmt->fetchAll();

// Marquer comme lus les messages reçus
if ($lastId === 0) {
    $stmt = $pdo->prepare("
        SELECT MAX(id) as max_id FROM messages 
        WHERE conversation_id = ? AND expediteur_id != ?
    ");
    $stmt->execute([$conversationId, $coachId]);
    $maxId = $stmt->fetchColumn();
    
    if ($maxId) {
        $stmt = $pdo->prepare("
            UPDATE conversation_participants 
            SET dernier_lu_id = ? 
            WHERE conversation_id = ? AND coach_id = ?
        ");
        $stmt->execute([$maxId, $conversationId, $coachId]);
    }
}

// Inverser pour ordre chronologique
$messages = array_reverse($messages);

echo json_encode([
    'success' => true,
    'messages' => array_map(function($m) use ($coachId) {
        return [
            'id' => $m['id'],
            'contenu' => $m['contenu'],
            'type' => $m['type'],
            'date_creation' => $m['date_creation'],
            'expediteur' => $m['prenom'] . ' ' . $m['nom'],
            'expediteur_id' => $m['expediteur_public_id'],
            'est_moi' => ($m['expediteur_id'] == $coachId)
        ];
    }, $messages),
    'coach_nom' => $participant['prenom'] . ' ' . $participant['nom']
]);