<?php
/**
 * MESSAGES_COACH.PHP
 * Interface messagerie complète pour les coachs
 * Cahier des charges : V2 — Messagerie interne
 */

session_start();
require_once __DIR__ . '/../config/database.php';

// Vérifier auth
$sessionToken = $_SESSION['coach_session_token'] ?? $_COOKIE['gbo_remember'] ?? null;
if (!$sessionToken) {
    redirect('/gbo_africa_group/pages/login.php?role=coach', 'error', 'Veuillez vous connecter.');
}

$stmt = $pdo->prepare("
    SELECT s.coach_id, c.prenom, c.nom, c.niveau, c.coach_id as public_id
    FROM sessions s
    JOIN coachs c ON s.coach_id = c.id
    WHERE s.session_token = ? AND s.est_valide = TRUE AND s.date_expiration > NOW() AND c.statut = 'actif'
");
$stmt->execute([$sessionToken]);
$coach = $stmt->fetch();

if (!$coach) {
    redirect('/gbo_africa_group/pages/login.php?role=coach', 'error', 'Session expirée.');
}

$coachId = $coach['coach_id'];
$coachNom = $coach['prenom'] . ' ' . $coach['nom'];

// Récupérer les conversations
$stmt = $pdo->prepare("
    SELECT 
        conv.id,
        conv.sujet,
        conv.type,
        conv.date_maj,
        (SELECT COUNT(*) FROM messages m 
         WHERE m.conversation_id = conv.id 
         AND m.id > COALESCE(cp.dernier_lu_id, 0)) as non_lus,
        (SELECT m.contenu FROM messages m 
         WHERE m.conversation_id = conv.id 
         ORDER BY m.date_creation DESC LIMIT 1) as dernier_message,
        (SELECT m.date_creation FROM messages m 
         WHERE m.conversation_id = conv.id 
         ORDER BY m.date_creation DESC LIMIT 1) as dernier_date,
        (SELECT c.prenom FROM messages m 
         JOIN coachs c ON m.expediteur_id = c.id
         WHERE m.conversation_id = conv.id 
         ORDER BY m.date_creation DESC LIMIT 1) as dernier_expediteur
    FROM conversations conv
    JOIN conversation_participants cp ON conv.id = cp.conversation_id
    WHERE cp.coach_id = ? AND conv.statut = 'active'
    ORDER BY conv.date_maj DESC
");
$stmt->execute([$coachId]);
$conversations = $stmt->fetchAll();

// Conversation active (par défaut la première ou celle passée en param)
$activeConvId = (int)($_GET['conv'] ?? ($conversations[0]['id'] ?? 0));

$pageTitle = 'Messagerie — Espace Coach GBÔ';
require_once __DIR__ . '/../includes/head.php';
?>

<header class="site-header">
    <div class="wrap">
        <nav class="nav">
            <a href="dashboard_coach.php" class="brand">
                <div>
                    <div class="logo-text">GBÔ <span>COACH</span></div>
                    <div class="logo-sub">Messagerie</div>
                </div>
            </a>
            <div class="nav-cta" style="gap:16px">
                <span style="font-size:13px;color:var(--muted)">👤 <?php echo htmlspecialchars($coachNom); ?></span>
                <a href="dashboard_coach.php" class="btn btn-ghost btn-sm">← Dashboard</a>
                <a href="logout.php" class="btn btn-ghost btn-sm" style="color:#ff4444;border-color:#ff444433">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                </a>
            </div>
        </nav>
    </div>
</header>

<section style="padding-top:90px;padding-bottom:20px;height:calc(100vh - 90px)">
    <div class="wrap" style="height:100%">
        <div class="chat-container" style="height:100%;max-height:none">
            
            <!-- Sidebar conversations -->
            <aside class="chat-sidebar">
                <div class="chat-sidebar-header">
                    <h3>💬 Conversations</h3>
                    <p style="font-size:12px;color:var(--muted);margin-top:4px"><?php echo count($conversations); ?> conversation(s)</p>
                </div>
                
                <?php foreach ($conversations as $conv): 
                    $isActive = $conv['id'] == $activeConvId;
                    $hasUnread = $conv['non_lus'] > 0;
                ?>
                <a href="?conv=<?php echo $conv['id']; ?>" 
                   class="chat-contact <?php echo $isActive ? 'active' : ''; ?>"
                   style="text-decoration:none;color:inherit;display:block">
                    <div class="av" style="background:linear-gradient(135deg,var(--lime),#5b6b00);display:flex;align-items:center;justify-content:center;font-size:14px;font-weight:800;color:var(--noir)">
                        <?php echo strtoupper(substr($conv['sujet'], 0, 2)); ?>
                    </div>
                    <div class="info">
                        <b><?php echo htmlspecialchars($conv['sujet']); ?></b>
                        <span><?php echo htmlspecialchars(substr($conv['dernier_message'] ?? 'Aucun message', 0, 35)); ?></span>
                    </div>
                    <div style="text-align:right">
                        <div class="time" style="font-size:11px;color:var(--muted2)">
                            <?php 
                            $date = strtotime($conv['dernier_date'] ?? '');
                            echo $date ? date('H:i', $date) : '';
                            ?>
                        </div>
                        <?php if ($hasUnread): ?>
                        <span style="background:var(--lime);color:var(--noir);font-size:10px;font-weight:800;border-radius:50%;width:18px;height:18px;display:inline-flex;align-items:center;justify-content:center;margin-top:4px">
                            <?php echo $conv['non_lus']; ?>
                        </span>
                        <?php endif; ?>
                    </div>
                </a>
                <?php endforeach; ?>
            </aside>
            
            <!-- Zone de chat -->
            <div class="chat-main">
                <?php if ($activeConvId > 0): ?>
                <div class="chat-header">
                    <div class="av" style="background:linear-gradient(135deg,var(--lime),#5b6b00)"></div>
                    <div>
                        <b style="font-size:14px">
                            <?php 
                            $activeConv = array_filter($conversations, fn($c) => $c['id'] == $activeConvId);
                            $activeConv = $activeConv ? array_values($activeConv)[0] : null;
                            echo htmlspecialchars($activeConv['sujet'] ?? 'Conversation');
                            ?>
                        </b>
                        <span style="font-size:12px;color:var(--muted)">En ligne</span>
                    </div>
                </div>
                
                <div class="chat-messages" id="chatMessages" data-conversation="<?php echo $activeConvId; ?>">
                    <div style="text-align:center;padding:20px;color:var(--muted)">
                        <div class="loading-spinner" style="width:24px;height:24px;border:2px solid var(--line);border-top-color:var(--lime);border-radius:50%;animation:spin 1s linear infinite;margin:0 auto 10px"></div>
                        Chargement des messages...
                    </div>
                </div>
                
                <div class="chat-input">
                    <input type="text" id="messageInput" placeholder="Écrivez votre message..." maxlength="2000">
                    <button onclick="sendMessage()" id="sendBtn">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
                    </button>
                </div>
                <?php else: ?>
                <div style="display:flex;align-items:center;justify-content:center;height:100%;color:var(--muted)">
                    <div style="text-align:center">
                        <div style="font-size:48px;margin-bottom:16px">💬</div>
                        <h3 style="font-size:18px;margin-bottom:8px">Sélectionnez une conversation</h3>
                        <p>Choisissez une discussion dans la liste à gauche.</p>
                    </div>
                </div>
                <?php endif; ?>
            </div>
            
        </div>
    </div>
</section>

<style>
@keyframes spin { to { transform: rotate(360deg); } }
.chat-message { animation: messageIn 0.3s ease; }
@keyframes messageIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}
.chat-messages::-webkit-scrollbar { width: 6px; }
.chat-messages::-webkit-scrollbar-thumb { background: var(--line2); border-radius: 3px; }
</style>

<script>
const CSRF_TOKEN = '<?php echo generateCSRFToken(); ?>';
const CONVERSATION_ID = <?php echo $activeConvId; ?>;
let lastMessageId = 0;
let isPolling = false;

// Charger messages initiaux
async function loadMessages() {
    if (!CONVERSATION_ID) return;
    
    try {
        const res = await fetch(`api/get_messages.php?conversation_id=${CONVERSATION_ID}&limit=50`);
        const data = await res.json();
        
        if (data.success) {
            renderMessages(data.messages);
            if (data.messages.length > 0) {
                lastMessageId = Math.max(...data.messages.map(m => m.id));
            }
        }
    } catch (e) {
        console.error('Erreur chargement messages:', e);
    }
}

// Rendu des messages
function renderMessages(messages) {
    const container = document.getElementById('chatMessages');
    if (!container) return;
    
    container.innerHTML = messages.map(m => `
        <div class="chat-message ${m.est_moi ? 'sent' : 'received'}">
            ${!m.est_moi ? `<div style="font-size:11px;color:var(--lime);margin-bottom:4px;font-weight:600">${escapeHtml(m.expediteur)}</div>` : ''}
            ${escapeHtml(m.contenu)}
            <div class="time">${formatTime(m.date_creation)}</div>
        </div>
    `).join('');
    
    container.scrollTop = container.scrollHeight;
}

// Envoi message
async function sendMessage() {
    const input = document.getElementById('messageInput');
    const btn = document.getElementById('sendBtn');
    const contenu = input.value.trim();
    
    if (!contenu || !CONVERSATION_ID) return;
    
    input.disabled = true;
    btn.disabled = true;
    
    // Affichage optimiste
    const container = document.getElementById('chatMessages');
    const tempId = 'temp-' + Date.now();
    container.innerHTML += `
        <div class="chat-message sent" id="${tempId}" style="opacity:0.6">
            ${escapeHtml(contenu)}
            <div class="time">Envoi...</div>
        </div>
    `;
    container.scrollTop = container.scrollHeight;
    input.value = '';
    
    try {
        const res = await fetch('api/send_message.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-Token': CSRF_TOKEN
            },
            body: JSON.stringify({
                conversation_id: CONVERSATION_ID,
                contenu: contenu,
                type: 'texte'
            })
        });
        
        const data = await res.json();
        
        if (data.success) {
            document.getElementById(tempId)?.remove();
            lastMessageId = data.message.id;
            pollMessages(); // Recharger immédiatement
        } else {
            document.getElementById(tempId).innerHTML += '<div style="color:#ff4444;font-size:11px">⚠️ Échec de l\'envoi</div>';
        }
    } catch (e) {
        document.getElementById(tempId).innerHTML += '<div style="color:#ff4444;font-size:11px">⚠️ Erreur réseau</div>';
    } finally {
        input.disabled = false;
        btn.disabled = false;
        input.focus();
    }
}

// Polling pour nouveaux messages
async function pollMessages() {
    if (isPolling || !CONVERSATION_ID) return;
    isPolling = true;
    
    try {
        const res = await fetch(`api/get_messages.php?conversation_id=${CONVERSATION_ID}&last_id=${lastMessageId}`);
        const data = await res.json();
        
        if (data.success && data.messages.length > 0) {
            const container = document.getElementById('chatMessages');
            const wasAtBottom = container.scrollHeight - container.scrollTop <= container.clientHeight + 50;
            
            data.messages.forEach(m => {
                if (m.id > lastMessageId) {
                    container.innerHTML += `
                        <div class="chat-message ${m.est_moi ? 'sent' : 'received'}">
                            ${!m.est_moi ? `<div style="font-size:11px;color:var(--lime);margin-bottom:4px;font-weight:600">${escapeHtml(m.expediteur)}</div>` : ''}
                            ${escapeHtml(m.contenu)}
                            <div class="time">${formatTime(m.date_creation)}</div>
                        </div>
                    `;
                    lastMessageId = m.id;
                }
            });
            
            if (wasAtBottom) {
                container.scrollTop = container.scrollHeight;
            }
        }
    } catch (e) {
        console.error('Erreur polling:', e);
    } finally {
        isPolling = false;
    }
}

// Utilitaires
function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

function formatTime(dateStr) {
    const d = new Date(dateStr);
    return d.toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' });
}

// Event listeners
document.getElementById('messageInput')?.addEventListener('keypress', (e) => {
    if (e.key === 'Enter' && !e.shiftKey) {
        e.preventDefault();
        sendMessage();
    }
});

// Initialisation
if (CONVERSATION_ID) {
    loadMessages();
    setInterval(pollMessages, 3000); // Polling toutes les 3 secondes
}
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>