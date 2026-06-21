<?php
/**
 * FONCTIONS GLOBALES GBÔ AFRICA GROUP
 * Fonctions réutilisables sur tout le site
 */

if (!defined('GBO_ACCESS')) {
    die('Accès direct interdit');
}

/**
 * Récupère les données d'une table
 */
function getAll($table, $orderBy = 'id DESC') {
    global $pdo;
    $stmt = $pdo->query("SELECT * FROM $table ORDER BY $orderBy");
    return $stmt->fetchAll();
}

/**
 * Récupère une ligne par ID
 */
function getById($table, $id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM $table WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

/**
 * Récupère les coachs actifs
 */
function getActiveCoaches() {
    global $pdo;
    $stmt = $pdo->query("SELECT * FROM coaches WHERE status = 'actif' ORDER BY nom");
    return $stmt->fetchAll();
}

/**
 * Récupère les formations
 */
function getFormations() {
    global $pdo;
    $stmt = $pdo->query("SELECT * FROM formations WHERE active = 1 ORDER BY titre");
    return $stmt->fetchAll();
}

/**
 * Récupère les articles du blog
 */
function getBlogPosts($limit = 6) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM blog_posts WHERE published = 1 ORDER BY date_pub DESC LIMIT ?");
    $stmt->execute([$limit]);
    return $stmt->fetchAll();
}

/**
 * Récupère les témoignages
 */
function getTestimonials() {
    global $pdo;
    $stmt = $pdo->query("SELECT * FROM testimonials WHERE active = 1 ORDER BY RAND() LIMIT 3");
    return $stmt->fetchAll();
}

/**
 * Récupère les produits du shop
 */
function getProducts($category = null) {
    global $pdo;
    if ($category) {
        $stmt = $pdo->prepare("SELECT * FROM products WHERE categorie = ? AND active = 1 ORDER BY nom");
        $stmt->execute([$category]);
    } else {
        $stmt = $pdo->query("SELECT * FROM products WHERE active = 1 ORDER BY nom");
    }
    return $stmt->fetchAll();
}

/**
 * Récupère les catégories de produits
 */
function getProductCategories() {
    global $pdo;
    $stmt = $pdo->query("SELECT DISTINCT categorie FROM products WHERE active = 1 ORDER BY categorie");
    return $stmt->fetchAll(PDO::FETCH_COLUMN);
}

/**
 * Récupère les catégories du blog
 */
function getBlogCategories() {
    global $pdo;
    $stmt = $pdo->query("SELECT DISTINCT categorie FROM blog_posts WHERE published = 1 ORDER BY categorie");
    return $stmt->fetchAll(PDO::FETCH_COLUMN);
}

/**
 * Enregistre un formulaire de contact
 */
function saveContact($data) {
    global $pdo;
    $stmt = $pdo->prepare("INSERT INTO contacts (nom, email, telephone, sujet, message, date_envoi) VALUES (?, ?, ?, ?, ?, NOW())");
    return $stmt->execute([$data['nom'], $data['email'], $data['telephone'], $data['sujet'], $data['message']]);
}

/**
 * Enregistre une candidature coach
 */
function saveCoachApplication($data) {
    global $pdo;
    $stmt = $pdo->prepare("INSERT INTO candidatures_coachs (nom, prenom, telephone, email, ville, niveau_experience, cv_path, motivation, date_candidature) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())");
    return $stmt->execute([
        $data['nom'], $data['prenom'], $data['telephone'], 
        $data['email'], $data['ville'], $data['niveau'], 
        $data['cv'], $data['motivation']
    ]);
}

/**
 * Enregistre une inscription formation
 */
function saveFormationInscription($data) {
    global $pdo;
    $stmt = $pdo->prepare("INSERT INTO inscriptions_formations (nom, email, telephone, formation_id, date_inscription) VALUES (?, ?, ?, ?, NOW())");
    return $stmt->execute([$data['nom'], $data['email'], $data['telephone'], $data['formation_id']]);
}

/**
 * Enregistre une demande entreprise
 */
function saveEntrepriseRequest($data) {
    global $pdo;
    $stmt = $pdo->prepare("INSERT INTO demandes_entreprises (entreprise, effectif, secteur, besoin, contact, email, telephone, date_demande) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())");
    return $stmt->execute([
        $data['entreprise'], $data['effectif'], $data['secteur'],
        $data['besoin'], $data['contact'], $data['email'], $data['telephone']
    ]);
}

/**
 * Récupère les données du coach connecté
 */
function getCurrentCoach() {
    if (!isLoggedIn('coach')) return null;
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM coaches WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    return $stmt->fetch();
}

/**
 * Récupère les données du client connecté
 */
function getCurrentClient() {
    if (!isLoggedIn('client')) return null;
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM clients WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    return $stmt->fetch();
}

/**
 * Récupère les données de l'admin connecté
 */
function getCurrentAdmin() {
    if (!isLoggedIn('admin')) return null;
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM administrateurs WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    return $stmt->fetch();
}

/**
 * Récupère les clients d'un coach
 */
function getCoachClients($coachId) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT c.* FROM clients c JOIN coach_client cc ON c.id = cc.client_id WHERE cc.coach_id = ?");
    $stmt->execute([$coachId]);
    return $stmt->fetchAll();
}

/**
 * Récupère les séances d'un coach
 */
function getCoachSessions($coachId, $limit = 50) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM seances WHERE coach_id = ? ORDER BY date_seance DESC LIMIT ?");
    $stmt->execute([$coachId, $limit]);
    return $stmt->fetchAll();
}

/**
 * Récupère les notifications
 */
function getNotifications($userId, $role, $limit = 10) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM notifications WHERE (user_id = ? AND user_role = ?) OR (user_id IS NULL AND user_role = 'all') ORDER BY date_notif DESC LIMIT ?");
    $stmt->execute([$userId, $role, $limit]);
    return $stmt->fetchAll();
}

/**
 * Compte les notifications non lues
 */
function countUnreadNotifications($userId, $role) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM notifications WHERE ((user_id = ? AND user_role = ?) OR (user_id IS NULL AND user_role = 'all')) AND lu = 0");
    $stmt->execute([$userId, $role]);
    return $stmt->fetchColumn();
}

/**
 * Marque une notification comme lue
 */
function markNotificationRead($notifId) {
    global $pdo;
    $stmt = $pdo->prepare("UPDATE notifications SET lu = 1 WHERE id = ?");
    return $stmt->execute([$notifId]);
}

/**
 * Récupère les statistiques coach
 */
function getCoachStats($coachId) {
    global $pdo;
    $stats = [];
    
    // Clients actifs
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM coach_client WHERE coach_id = ?");
    $stmt->execute([$coachId]);
    $stats['clients_actifs'] = $stmt->fetchColumn();
    
    // Séances cette semaine
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM seances WHERE coach_id = ? AND YEARWEEK(date_seance) = YEARWEEK(NOW())");
    $stmt->execute([$coachId]);
    $stats['seances_semaine'] = $stmt->fetchColumn();
    
    // Revenus du mois
    $stmt = $pdo->prepare("SELECT COALESCE(SUM(montant), 0) FROM paiements WHERE coach_id = ? AND MONTH(date_paiement) = MONTH(NOW()) AND YEAR(date_paiement) = YEAR(NOW())");
    $stmt->execute([$coachId]);
    $stats['revenus_mois'] = $stmt->fetchColumn();
    
    // Satisfaction moyenne
    $stmt = $pdo->prepare("SELECT COALESCE(AVG(note), 0) FROM evaluations WHERE coach_id = ?");
    $stmt->execute([$coachId]);
    $stats['satisfaction'] = round($stmt->fetchColumn(), 1);
    
    return $stats;
}

/**
 * Génère un slug URL-friendly
 */
function slugify($text) {
    $text = preg_replace('~[^\pL\d]+~u', '-', $text);
    $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
    $text = preg_replace('~[^-\w]+~', '', $text);
    $text = trim($text, '-');
    $text = preg_replace('~-+~', '-', $text);
    $text = strtolower($text);
    return empty($text) ? 'n-a' : $text;
}

/**
 * Formate un prix en FCFA
 */
function formatPrice($price) {
    return number_format($price, 0, ',', ' ') . ' FCFA';
}

/**
 * Formate une date
 */
function formatDate($date, $format = 'd/m/Y') {
    return date($format, strtotime($date));
}

/**
 * Tronque un texte
 */
function truncate($text, $length = 100) {
    if (strlen($text) <= $length) return $text;
    return substr($text, 0, $length) . '...';
}

/**
 * Upload un fichier
 */
function uploadFile($file, $directory, $allowedTypes = ['jpg', 'jpeg', 'png', 'pdf']) {
    $fileName = $file['name'];
    $fileTmp = $file['tmp_name'];
    $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    
    if (!in_array($fileExt, $allowedTypes)) {
        return ['success' => false, 'error' => 'Type de fichier non autorisé'];
    }
    
    $newName = uniqid() . '_' . slugify(pathinfo($fileName, PATHINFO_FILENAME)) . '.' . $fileExt;
    $destination = $directory . $newName;
    
    if (move_uploaded_file($fileTmp, $destination)) {
        return ['success' => true, 'path' => $newName];
    }
    
    return ['success' => false, 'error' => 'Erreur lors de l\'upload'];
}

/**
 * Envoie un email (simulation - à remplacer par PHPMailer)
 */
function sendEmail($to, $subject, $message) {
    $headers = 'From: contact@gbo-africa.com' . "\r\n";
    $headers .= 'Reply-To: contact@gbo-africa.com' . "\r\n";
    $headers .= 'Content-Type: text/html; charset=UTF-8' . "\r\n";
    return mail($to, $subject, $message, $headers);
}

/**
 * Log une action
 */
function logAction($action, $details = '') {
    global $pdo;
    $userId = $_SESSION['user_id'] ?? null;
    $userRole = $_SESSION['user_role'] ?? 'guest';
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    
    $stmt = $pdo->prepare("INSERT INTO logs (user_id, user_role, action, details, ip_address, date_log) VALUES (?, ?, ?, ?, ?, NOW())");
    $stmt->execute([$userId, $userRole, $action, $details, $ip]);
}

/**
 * Vérifie les permissions
 */
function checkPermission($requiredRole) {
    if (!isLoggedIn($requiredRole)) {
        setFlash('error', 'Accès non autorisé');
        redirect(BASE_URL . 'pages/login_' . $requiredRole . '.php');
    }
}

/**
 * Récupère les paramètres du site (modifiables via admin)
 */
function getSiteSetting($key) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT valeur FROM site_settings WHERE cle = ?");
    $stmt->execute([$key]);
    $result = $stmt->fetch();
    return $result ? $result['valeur'] : null;
}

/**
 * Met à jour un paramètre du site
 */
function updateSiteSetting($key, $value) {
    global $pdo;
    $stmt = $pdo->prepare("INSERT INTO site_settings (cle, valeur) VALUES (?, ?) ON DUPLICATE KEY UPDATE valeur = ?");
    return $stmt->execute([$key, $value, $value]);
}

/**
 * Récupère le contenu média (vidéo, images modifiables via admin)
 */
function getMedia($type, $position) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM media WHERE type = ? AND position = ? AND active = 1 ORDER BY ordre");
    $stmt->execute([$type, $position]);
    return $stmt->fetchAll();
}

/**
 * Récupère la vidéo de présentation
 */
function getPresentationVideo() {
    global $pdo;
    $stmt = $pdo->query("SELECT * FROM media WHERE type = 'video' AND position = 'presentation' AND active = 1 LIMIT 1");
    return $stmt->fetch();
}

/**
 * Récupère les images du hero
 */
function getHeroImages() {
    global $pdo;
    $stmt = $pdo->query("SELECT * FROM media WHERE type = 'image' AND position = 'hero' AND active = 1 ORDER BY ordre");
    return $stmt->fetchAll();
}

/**
 * Récupère les témoignages dynamiques
 */
function getDynamicTestimonials() {
    global $pdo;
    $stmt = $pdo->query("SELECT * FROM testimonials WHERE active = 1 ORDER BY RAND() LIMIT 3");
    return $stmt->fetchAll();
}

/**
 * Récupère les partenaires/clients
 */
function getPartners() {
    global $pdo;
    $stmt = $pdo->query("SELECT * FROM partners WHERE active = 1 ORDER BY nom");
    return $stmt->fetchAll();
}

/**
 * Récupère les événements à venir
 */
function getUpcomingEvents($limit = 3) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM events WHERE date_event >= CURDATE() AND active = 1 ORDER BY date_event ASC LIMIT ?");
    $stmt->execute([$limit]);
    return $stmt->fetchAll();
}

/**
 * Récupère les messages d'une conversation
 */
function getMessages($conversationId, $limit = 50) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM messages WHERE conversation_id = ? ORDER BY date_envoi DESC LIMIT ?");
    $stmt->execute([$conversationId, $limit]);
    return $stmt->fetchAll();
}

/**
 * Envoie un message
 */
function sendMessage($fromId, $fromRole, $toId, $toRole, $message) {
    global $pdo;
    
    // Créer ou récupérer la conversation
    $stmt = $pdo->prepare("SELECT id FROM conversations WHERE 
        (user1_id = ? AND user1_role = ? AND user2_id = ? AND user2_role = ?) OR
        (user1_id = ? AND user1_role = ? AND user2_id = ? AND user2_role = ?)
        LIMIT 1");
    $stmt->execute([$fromId, $fromRole, $toId, $toRole, $toId, $toRole, $fromId, $fromRole]);
    $conversation = $stmt->fetch();
    
    if (!$conversation) {
        $stmt = $pdo->prepare("INSERT INTO conversations (user1_id, user1_role, user2_id, user2_role, date_creation) VALUES (?, ?, ?, ?, NOW())");
        $stmt->execute([$fromId, $fromRole, $toId, $toRole]);
        $conversationId = $pdo->lastInsertId();
    } else {
        $conversationId = $conversation['id'];
    }
    
    // Insérer le message
    $stmt = $pdo->prepare("INSERT INTO messages (conversation_id, expediteur_id, expediteur_role, contenu, date_envoi, lu) VALUES (?, ?, ?, ?, NOW(), 0)");
    return $stmt->execute([$conversationId, $fromId, $fromRole, $message]);
}

/**
 * Récupère les conversations d'un utilisateur
 */
function getUserConversations($userId, $userRole) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT c.*, 
        CASE 
            WHEN c.user1_id = ? AND c.user1_role = ? THEN c.user2_id 
            ELSE c.user1_id 
        END as other_id,
        CASE 
            WHEN c.user1_id = ? AND c.user1_role = ? THEN c.user2_role 
            ELSE c.user1_role 
        END as other_role
        FROM conversations c 
        WHERE (c.user1_id = ? AND c.user1_role = ?) OR (c.user2_id = ? AND c.user2_role = ?)
        ORDER BY c.date_creation DESC");
    $stmt->execute([$userId, $userRole, $userId, $userRole, $userId, $userRole, $userId, $userRole]);
    return $stmt->fetchAll();
}

/**
 * Récupère les statistiques admin
 */
function getAdminStats() {
    global $pdo;
    $stats = [];
    
    $tables = ['clients', 'coaches', 'formations', 'seances', 'contacts', 'candidatures_coachs'];
    foreach ($tables as $table) {
        $stats[$table] = $pdo->query("SELECT COUNT(*) FROM $table")->fetchColumn();
    }
    
    // Revenus total
    $stats['revenus_total'] = $pdo->query("SELECT COALESCE(SUM(montant), 0) FROM paiements")->fetchColumn();
    
    // Nouveaux cette semaine
    $stats['nouveaux_clients'] = $pdo->query("SELECT COUNT(*) FROM clients WHERE date_inscription >= DATE_SUB(NOW(), INTERVAL 7 DAY)")->fetchColumn();
    
    return $stats;
}

/**
 * Récupère les dernières activités
 */
function getRecentActivities($limit = 10) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM logs ORDER BY date_log DESC LIMIT ?");
    $stmt->execute([$limit]);
    return $stmt->fetchAll();
}

/**
 * Récupère les demandes en attente
 */
function getPendingRequests() {
    global $pdo;
    $requests = [];
    
    // Candidatures coachs
    $stmt = $pdo->query("SELECT COUNT(*) FROM candidatures_coachs WHERE statut = 'en_attente'");
    $requests['candidatures'] = $stmt->fetchColumn();
    
    // Contacts non traités
    $stmt = $pdo->query("SELECT COUNT(*) FROM contacts WHERE traite = 0");
    $requests['contacts'] = $stmt->fetchColumn();
    
    // Demandes entreprises
    $stmt = $pdo->query("SELECT COUNT(*) FROM demandes_entreprises WHERE statut = 'en_attente'");
    $requests['entreprises'] = $stmt->fetchColumn();
    
    // Inscriptions formations
    $stmt = $pdo->query("SELECT COUNT(*) FROM inscriptions_formations WHERE statut = 'en_attente'");
    $requests['formations'] = $stmt->fetchColumn();
    
    return $requests;
}

/**
 * Met à jour le statut d'une demande
 */
function updateRequestStatus($table, $id, $status) {
    global $pdo;
    $stmt = $pdo->prepare("UPDATE $table SET statut = ? WHERE id = ?");
    return $stmt->execute([$status, $id]);
}

/**
 * Récupère les données pour le tableau de bord
 */
function getDashboardData($role) {
    $data = [];
    
    switch ($role) {
        case 'coach':
            $coach = getCurrentCoach();
            if ($coach) {
                $data['coach'] = $coach;
                $data['stats'] = getCoachStats($coach['id']);
                $data['clients'] = getCoachClients($coach['id']);
                $data['seances'] = getCoachSessions($coach['id'], 5);
                $data['notifications'] = getNotifications($coach['id'], 'coach', 5);
                $data['unread_notif'] = countUnreadNotifications($coach['id'], 'coach');
            }
            break;
            
        case 'client':
            $client = getCurrentClient();
            if ($client) {
                $data['client'] = $client;
                $data['seances'] = []; // À implémenter
                $data['notifications'] = getNotifications($client['id'], 'client', 5);
                $data['unread_notif'] = countUnreadNotifications($client['id'], 'client');
            }
            break;
            
        case 'admin':
            $data['stats'] = getAdminStats();
            $data['pending'] = getPendingRequests();
            $data['activities'] = getRecentActivities(10);
            $data['notifications'] = []; // Admin voit tout
            break;
    }
    
    return $data;
}

/**
 * Récupère les paramètres du questionnaire intelligent
 */
function getQuestionnaireRules() {
    global $pdo;
    $stmt = $pdo->query("SELECT * FROM questionnaire_rules WHERE active = 1 ORDER BY ordre");
    return $stmt->fetchAll();
}

/**
 * Calcule une recommandation basée sur les réponses
 */
function calculateRecommendation($answers) {
    global $pdo;
    
    // Logique de recommandation basée sur les règles en BDD
    $stmt = $pdo->prepare("
        SELECT offre_id, nb_seances, tarif_estime 
        FROM questionnaire_rules 
        WHERE objectif = ? AND niveau = ? AND format = ? AND frequence = ? AND budget = ?
        AND active = 1 
        LIMIT 1
    ");
    $stmt->execute([
        $answers['objectif'] ?? '',
        $answers['niveau'] ?? '',
        $answers['format'] ?? '',
        $answers['frequence'] ?? '',
        $answers['budget'] ?? ''
    ]);
    
    return $stmt->fetch();
}

/**
 * Sauvegarde les réponses du questionnaire
 */
function saveQuestionnaire($answers) {
    global $pdo;
    $stmt = $pdo->prepare("INSERT INTO questionnaire_reponses 
        (sexe, age, niveau, objectif, frequence, format, localisation, consentement, date_reponse) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())");
    return $stmt->execute([
        $answers['sexe'], $answers['age'], $answers['niveau'],
        $answers['objectif'], $answers['frequence'], $answers['format'],
        $answers['localisation'], $answers['consentement'] ?? 0
    ]);
}

/**
 * Récupère les données pour les graphiques
 */
function getChartData($type, $period = 'month') {
    global $pdo;
    
    switch ($type) {
        case 'seances':
            $stmt = $pdo->query("SELECT DATE_FORMAT(date_seance, '%Y-%m') as periode, COUNT(*) as total 
                FROM seances 
                WHERE date_seance >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
                GROUP BY periode 
                ORDER BY periode");
            return $stmt->fetchAll();
            
        case 'revenus':
            $stmt = $pdo->query("SELECT DATE_FORMAT(date_paiement, '%Y-%m') as periode, SUM(montant) as total 
                FROM paiements 
                WHERE date_paiement >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
                GROUP BY periode 
                ORDER BY periode");
            return $stmt->fetchAll();
            
        case 'inscriptions':
            $stmt = $pdo->query("SELECT DATE_FORMAT(date_inscription, '%Y-%m') as periode, COUNT(*) as total 
                FROM clients 
                WHERE date_inscription >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
                GROUP BY periode 
                ORDER BY periode");
            return $stmt->fetchAll();
            
        default:
            return [];
    }
}

/**
 * Exporte des données en CSV
 */
function exportCSV($data, $filename) {
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    
    $output = fopen('php://output', 'w');
    
    // En-têtes
    if (!empty($data)) {
        fputcsv($output, array_keys($data[0]));
    }
    
    // Données
    foreach ($data as $row) {
        fputcsv($output, $row);
    }
    
    fclose($output);
    exit();
}

/**
 * Génère un rapport PDF (nécessite une librairie PDF)
 */
function generatePDF($title, $content) {
    // À implémenter avec TCPDF ou FPDF
    // Pour l'instant, retourne un placeholder
    return ['success' => false, 'message' => 'Génération PDF à implémenter'];
}

/**
 * Vérifie si une fonctionnalité est activée
 */
function isFeatureEnabled($feature) {
    $setting = getSiteSetting('feature_' . $feature);
    return $setting === '1' || $setting === null; // Par défaut activé
}

/**
 * Récupère la version du site
 */
function getSiteVersion() {
    return getSiteSetting('site_version') ?? '1.0.0';
}

/**
 * Récupère les informations de maintenance
 */
function isMaintenanceMode() {
    return getSiteSetting('maintenance_mode') === '1';
}

/**
 * Affiche une page de maintenance
 */
function showMaintenance() {
    http_response_code(503);
    echo '<!DOCTYPE html><html><head><title>Maintenance</title></head><body style="background:#080A08;color:#fff;text-align:center;padding:50px;font-family:sans-serif;"><h1 style="color:#C6F202">GBÔ AFRICA GROUP</h1><h2>Maintenance en cours</h2><p>Le site est momentanément indisponible. Revenez bientôt.</p></body></html>';
    exit();
}

// Si mode maintenance et pas admin
if (isMaintenanceMode() && !isLoggedIn('admin')) {
    showMaintenance();
}
?>