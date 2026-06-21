<?php
/**
 * MESSAGES_ADMIN.PHP
 * Messagerie interne pour l'administration
 * Permissions : messagerie_admin
 */

require_once __DIR__ . '/../includes/auth_check.php';
$user = checkAuth('messagerie_admin');

// Récupérer toutes les conversations admin
$stmt = $pdo->prepare("
    SELECT 
        conv.id, conv.sujet, conv.type, conv.date_maj,
        (SELECT m.contenu FROM messages m WHERE m.conversation_id = conv.id ORDER BY m.date_creation DESC LIMIT 1) as dernier_msg,
        (SELECT COUNT(*) FROM messages m WHERE m.conversation_id = conv.id AND m.id > COALESCE(cp.dernier_lu_id, 0)) as non_lus
    FROM conversations conv
    JOIN conversation_participants cp ON conv.id = cp.conversation_id
    WHERE cp.coach_id = ? AND conv.type IN ('coach_admin', 'admin_interne') AND conv.statut = 'active'
    ORDER BY conv.date_maj DESC
");
$stmt->execute([$user['id']]);
$conversations = $stmt->fetchAll();

// Liste des coachs pour nouvelle conversation
$stmt = $pdo->query("SELECT id, coach_id, prenom, nom FROM coachs WHERE statut = 'actif' AND id != " . $user['id'] . " ORDER BY nom");
$coachsListe = $stmt->fetchAll();

$activeConvId = (int)($_GET['conv'] ?? ($conversations[0]['id'] ?? 0));

$pageTitle = 'Messagerie Admin — GBÔ';
require_once __DIR__ . '/../includes/head.php';
?>

<header class="site-header">
    <div class="wrap">
        <nav class="nav">
            <a href="dashboard_admin.php" class="brand">
                <div>
                    <div class="logo-text">GBÔ <span>ADMIN</span></div>
                    <div class="logo-sub">Messagerie interne</div>
                </div>
            </a>
            <div class="nav-cta">
                <a href="dashboard_admin.php" class="btn btn-ghost btn-sm">← Dashboard</a>
                <a href="logout.php" class="btn btn-ghost btn-sm" style="color:#ff4444;border-color:#ff444433">Déconnexion</a>
            </div>
        </nav>
    </div>
</header>

<section style="padding-top:90px;padding-bottom:20px;height:calc(100vh - 90px)">
    <div class="wrap" style="height:100%">
        <div class="chat-container" style="height:100%;max-height:none">
            
            <aside class="chat-sidebar">
                <div class="chat-sidebar-header" style="display:flex;justify-content:space-between;align-items:center">
                    <div>
                        <h3>💬 Conversations</h3>
                        <p style="font-size:12px;color:var(--muted);margin-top:4px"><?php echo count($conversations); ?> active(s)</p>
                    </div>
                    <button onclick="showNewConvModal()" class="btn btn-sm btn-primary" style="padding:6px 12px;font-size:12px">+ Nouveau</button>
                </div>
                
                <?php foreach ($conversations as $conv): 
                    $isActive = $conv['id'] == $activeConvId;
                ?>
                <a href="?conv=<?php echo $conv['id']; ?>" 
                   class="chat-contact <?php echo $isActive ? 'active' : ''; ?>"
                   style="text-decoration:none;color:inherit;display:block">
                    <div class="av" style="background:linear-gradient(135deg,var(--lime),#5b6b00);display:flex;align-items:center;justify-content:center;font-size:14px;font-weight:800;color:var(--noir)">
                        <?php echo strtoupper(substr($conv['sujet'], 0, 2)); ?>
                    </div>
                    <div class="info">
                        <b><?php echo htmlspecialchars($conv['sujet']); ?></b>
                        <span><?php echo htmlspecialchars(substr($conv['dernier_msg'] ?? 'Aucun message', 0, 35)); ?></span>
                    </div>
                    <?php if ($conv['non_lus'] > 0): ?>
                    <span style="background:var(--lime);color:var(--noir);font-size:10px;font-weight:800;border-radius:50%;width:20px;height:20px;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                        <?php echo $conv['non_lus']; ?>
                    </span>
                    <?php endif; ?>
                </a>
                <?php endforeach; ?>
            </aside>
            
            <div class="chat-main">
                <?php if ($activeConvId > 0): ?>
                <!-- Zone de chat identique à messages_coach.php -->
                <div class="chat-header">
                    <div class="av" style="background:linear-gradient(135deg,#ff5722,#e64a19)"></div>
                    <div>
                        <b style="font-size:14px">Administration</b>
                        <span style="font-size:12px;color:var(--muted)">En ligne</span>
                    </div>
                </div>
                
                <div class="chat-messages" id="chatMessages" data-conversation="<?php echo $activeConvId; ?>">
                    <div style="text-align:center;padding:20px;color:var(--muted)">
                        <div style="width:24px;height:24px;border:2px solid var(--line);border-top-color:var(--lime);border-radius:50%;animation:spin 1s linear infinite;margin:0 auto 10px"></div>
                        Chargement...
                    </div>
                </div>
                
                <div class="chat-input">
                    <input type="text" id="messageInput" placeholder="Message à l'administration..." maxlength="2000">
                    <button onclick="sendMessage()" id="sendBtn">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
                    </button>
                </div>
                <?php else: ?>
                <div style="display:flex;align-items:center;justify-content:center;height:100%;color:var(--muted)">
                    <div style="text-align:center">
                        <div style="font-size:48px;margin-bottom:16px">💬</div>
                        <h3>Sélectionnez une conversation</h3>
                        <p>ou <a href="#" onclick="showNewConvModal()" style="color:var(--lime)">démarrez-en une nouvelle</a></p>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<!-- Modal nouvelle conversation -->
<div class="modal" id="newConvModal" style="display:none">
    <div class="modal-box" style="max-width:500px">
        <button class="modal-close" onclick="hideNewConvModal()">×</button>
        <h3 style="margin-bottom:16px">Nouvelle conversation</h3>
        <form action="api/create_conversation.php" method="POST">
            <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
            
            <div class="field">
                <label>Destinataire</label>
                <select name="destinataire_id" required>
                    <option value="">Choisir un coach...</option>
                    <?php foreach ($coachsListe as $c): ?>
                    <option value="<?php echo $c['id']; ?>"><?php echo htmlspecialchars($c['prenom'] . ' ' . $c['nom'] . ' (' . $c['coach_id'] . ')'); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="field">
                <label>Sujet</label>
                <input type="text" name="sujet" placeholder="Objet de la conversation" required>
            </div>
            
            <div class="field">
                <label>Premier message</label>
                <textarea name="premier_message" rows="3" placeholder="Votre message..." required></textarea>
            </div>
            
            <button type="submit" class="btn btn-primary" style="width:100%">Créer la conversation</button>
        </form>
    </div>
</div>

<script>
const CSRF_TOKEN = '<?php echo generateCSRFToken(); ?>';
const CONVERSATION_ID = <?php echo $activeConvId; ?>;
let lastMessageId = 0;

function showNewConvModal() {
    document.getElementById('newConvModal').classList.add('open');
}
function hideNewConvModal() {
    document.getElementById('newConvModal').classList.remove('open');
}

// ... (même code JS que messages_coach.php pour loadMessages, sendMessage, pollMessages)
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>