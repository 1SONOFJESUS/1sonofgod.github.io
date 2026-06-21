<?php
/**
 * API SEND_MESSAGE.PHP
 * Envoie un message dans une conversation
 * Méthode: POST AJAX
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

// Vérifier CSRF
$headers = getallheaders();
$csrfHeader = $headers['X-CSRF-Token'] ?? '';
if (!verifyCSRFToken($csrfHeader)) {
    http_response_code(403);
    echo json_encode(['success' => false, 'error' => 'Token CSRF invalide']);
    exit;
}

// Récupérer données JSON
$input = json_decode(file_get_contents('php://input'), true);
$conversationId = (int)($input['conversation_id'] ?? 0);
$contenu = trim($input['contenu'] ?? '');
$type = in_array($input['type'] ?? 'texte', ['texte', 'fichier', 'image']) ? $input['type'] : 'texte';

if (empty($contenu) || $conversationId <= 0) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Données invalides']);
    exit;
}

// Vérifier que le coach est participant
$stmt = $pdo->prepare("
    SELECT c.id, cp.coach_id 
    FROM conversations c
    JOIN conversation_participants cp ON c.id = cp.conversation_id
    JOIN sessions s ON cp.coach_id = s.coach_id
    WHERE c.id = ? AND s.session_token = ? AND s.est_valide = TRUE AND s.date_expiration > NOW()
");
$stmt->execute([$conversationId, $sessionToken]);
$conversation = $stmt->fetch();

if (!$conversation) {
    http_response_code(403);
    echo json_encode(['success' => false, 'error' => 'Accès non autorisé']);
    exit;
}

$coachId = $conversation['coach_id'];

// Insérer le message
$stmt = $pdo->prepare("
    INSERT INTO messages (conversation_id, expediteur_id, type, contenu, date_creation)
    VALUES (?, ?, ?, ?, NOW())
");
$stmt->execute([$conversationId, $coachId, $type, $contenu]);
$messageId = $pdo->lastInsertId();

// Mettre à jour dernier_lu pour l'expéditeur
$stmt = $pdo->prepare("
    UPDATE conversation_participants 
    SET dernier_lu_id = ? 
    WHERE conversation_id = ? AND coach_id = ?
");
$stmt->execute([$messageId, $conversationId, $coachId]);

// Créer notifications pour les autres participants
$stmt = $pdo->prepare("
    SELECT c.coach_id, c.prenom, c.nom 
    FROM conversation_participants cp
    JOIN coachs c ON cp.coach_id = c.id
    WHERE cp.conversation_id = ? AND cp.coach_id != ? AND cp.notifications = TRUE
");
$stmt->execute([$conversationId, $coachId]);
$participants = $stmt->fetchAll();

foreach ($participants as $p) {
    $stmt = $pdo->prepare("
        INSERT INTO notifications (coach_id, type, titre, message, lien, est_lue)
        VALUES (?, 'systeme', ?, ?, ?, FALSE)
    ");
    $stmt->execute([
        $p['coach_id'],
        'Nouveau message de ' . $coach['prenom'] ?? 'Coach',
        substr($contenu, 0, 100) . (strlen($contenu) > 100 ? '...' : ''),
        'dashboard_coach.php?tab=messages&conv=' . $conversationId
    ]);
}

// Mettre à jour date_maj conversation
$stmt = $pdo->prepare("UPDATE conversations SET date_maj = NOW() WHERE id = ?");
$stmt->execute([$conversationId]);

// Retourner le message créé
$stmt = $pdo->prepare("
    SELECT m.*, c.prenom, c.nom, c.coach_id as expediteur_public_id
    FROM messages m
    JOIN coachs c ON m.expediteur_id = c.id
    WHERE m.id = ?
");
$stmt->execute([$messageId]);
$message = $stmt->fetch();

echo json_encode([
    'success' => true,
    'message' => [
        'id' => $message['id'],
        'contenu' => $message['contenu'],
        'type' => $message['type'],
        'date_creation' => $message['date_creation'],
        'expediteur' => $message['prenom'] . ' ' . $message['nom'],
        'expediteur_id' => $message['expediteur_public_id'],
        'est_moi' => true
    ]
]);