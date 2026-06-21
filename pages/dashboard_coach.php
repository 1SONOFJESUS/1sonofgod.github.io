<?php
/**
 * DASHBOARD_COACH.PHP
 * Espace privé coach — Requiert authentification
 * Cahier des charges : V1.1 — UC-09, UC-10
 */

session_start();
require_once __DIR__ . '/../config/database.php';

// ==================== VÉRIFICATION AUTHENTIFICATION ====================

function checkAuth($pdo) {
    $sessionToken = $_SESSION['coach_session_token'] ?? $_COOKIE['gbo_remember'] ?? null;
    
    if (!$sessionToken) {
        redirect('login_coach.php', 'error', 'Veuillez vous connecter pour accéder à cet espace.');
    }
    
    // Vérifier la session en base
    $stmt = $pdo->prepare("
        SELECT s.*, c.coach_id, c.nom, c.prenom, c.niveau, c.specialite, c.commune, c.email, c.telephone
        FROM sessions s
        JOIN coachs c ON s.coach_id = c.id
        WHERE s.session_token = ? 
        AND s.est_valide = TRUE 
        AND s.date_expiration > NOW()
        AND c.statut = 'actif'
    ");
    $stmt->execute([$sessionToken]);
    $session = $stmt->fetch();
    
    if (!$session) {
        // Session invalide, déconnecter
        $_SESSION = [];
        setcookie('gbo_remember', '', ['expires' => time() - 3600, 'path' => '/']);
        redirect('login_coach.php', 'error', 'Session expirée. Veuillez vous reconnecter.');
    }
    
    // Mettre à jour dernière activité
    $stmt = $pdo->prepare("UPDATE sessions SET derniere_activite = NOW() WHERE session_token = ?");
    $stmt->execute([$sessionToken]);
    
    return $session;
}

// Vérifier auth
$coach = checkAuth($pdo);

// ==================== RÉCUPÉRATION DES DONNÉES ====================

$coachId = $coach['coach_id'];
$coachNom = $coach['prenom'] . ' ' . $coach['nom'];
$coachNiveau = $coach['niveau'];
$coachCommune = $coach['commune'];

// Stats
$stmt = $pdo->prepare("SELECT COUNT(*) FROM clients WHERE coach_id = ? AND statut = 'actif'");
$stmt->execute([$coach['coach_id']]);
$nbClients = $stmt->fetchColumn();

$stmt = $pdo->prepare("SELECT COUNT(*) FROM seances WHERE coach_id = ? AND date_seance BETWEEN ? AND ? AND statut IN ('confirmee', 'terminee')");
$stmt->execute([$coach['coach_id'], date('Y-m-d', strtotime('monday this week')), date('Y-m-d', strtotime('sunday this week'))]);
$nbSeancesSemaine = $stmt->fetchColumn();

// Notifications non lues
$stmt = $pdo->prepare("SELECT COUNT(*) FROM notifications WHERE coach_id = ? AND est_lue = FALSE");
$stmt->execute([$coach['coach_id']]);
$nbNotifications = $stmt->fetchColumn();

// Prochaines séances
$stmt = $pdo->prepare("
    SELECT s.*, c.nom as client_nom 
    FROM seances s
    LEFT JOIN clients c ON s.client_id = c.id
    WHERE s.coach_id = ? AND s.date_seance >= CURDATE() AND s.statut IN ('planifiee', 'confirmee')
    ORDER BY s.date_seance, s.heure_debut
    LIMIT 5
");
$stmt->execute([$coach['coach_id']]);
$prochainesSeances = $stmt->fetchAll();

// Clients
$stmt = $pdo->prepare("SELECT * FROM clients WHERE coach_id = ? ORDER BY statut DESC, nom");
$stmt->execute([$coach['coach_id']]);
$clients = $stmt->fetchAll();

// Notifications
$stmt = $pdo->prepare("
    SELECT * FROM notifications 
    WHERE coach_id = ? 
    ORDER BY est_lue ASC, date_creation DESC
    LIMIT 10
");
$stmt->execute([$coach['coach_id']]);
$notifications = $stmt->fetchAll();

// Calendrier (mois courant)
$stmt = $pdo->prepare("
    SELECT DAY(date_seance) as jour, COUNT(*) as nb 
    FROM seances 
    WHERE coach_id = ? AND MONTH(date_seance) = MONTH(CURDATE()) AND YEAR(date_seance) = YEAR(CURDATE())
    GROUP BY DAY(date_seance)
");
$stmt->execute([$coach['coach_id']]);
$joursActifs = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);

$pageTitle = 'Dashboard — Espace Coach GBÔ';
require_once __DIR__ . '/../includes/head.php';
?>

<!-- Header Dashboard -->
<header class="site-header">
    <div class="wrap">
        <nav class="nav">
            <a href="dashboard_coach.php" class="brand">
                <div>
                    <div class="logo-text">GBÔ <span>COACH</span></div>
                    <div class="logo-sub">Espace sécurisé</div>
                </div>
            </a>
            <div class="nav-cta" style="gap:16px">
                <span style="font-size:13px;color:var(--muted)">👤 <?php echo htmlspecialchars($coachNom); ?></span>
                <a href="logout.php" class="btn btn-ghost btn-sm" style="color:#ff4444;border-color:#ff444433">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                    Déconnexion
                </a>
            </div>
        </nav>
    </div>
</header>

<section style="padding-top:100px;padding-bottom:40px">
    <div class="wrap">
        <?php showFlash(); ?>
        
        <!-- Bienvenue -->
        <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:12px;margin-bottom:24px">
            <div>
                <h1 style="font-size:28px">Bonjour, <span class="lime"><?php echo htmlspecialchars($coach['prenom']); ?></span> 👋</h1>
                <p class="muted" style="font-size:14px">
                    <?php echo htmlspecialchars($coachNiveau); ?> · <?php echo htmlspecialchars($coachCommune); ?> · 
                    Dernière connexion : <?php echo date('d/m/Y à H:i', strtotime($coach['derniere_activite'] ?? 'now')); ?>
                </p>
            </div>
            <span class="pill" style="background:rgba(198,242,2,.1);color:var(--lime);border-color:var(--lime)">
                Semaine du <?php echo date('d', strtotime('monday this week')); ?>-<?php echo date('d M Y', strtotime('sunday this week')); ?>
            </span>
        </div>
        
        <div class="dash">
            <!-- Sidebar -->
            <aside class="dash-side">
                <div class="u">
                    <div class="av" style="background:linear-gradient(135deg,var(--lime),#5b6b00);display:flex;align-items:center;justify-content:center;font-size:16px;font-weight:800;color:var(--noir)">
                        <?php echo strtoupper(substr($coach['prenom'], 0, 1) . substr($coach['nom'], 0, 1)); ?>
                    </div>
                    <div>
                        <b><?php echo htmlspecialchars($coach['prenom']); ?></b>
                        <span><?php echo htmlspecialchars($coachNiveau); ?> · <?php echo htmlspecialchars($coachCommune); ?></span>
                    </div>
                </div>
                
                <nav class="dnav" id="dnav">
                    <button class="active" data-d="dash" onclick="switchDash('dash')">
                        <svg class="i" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
                        Dashboard
                    </button>
                    <button data-d="cal" onclick="switchDash('cal')">
                        <svg class="i" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                        Calendrier
                    </button>
                    <button data-d="cli" onclick="switchDash('cli')">
                        <svg class="i" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                        Clients
                        <span class="b"><?php echo $nbClients; ?></span>
                    </button>
                    <button data-d="plan" onclick="switchDash('plan')">
                        <svg class="i" viewBox="0 0 24 24"><line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/></svg>
                        Planning
                    </button>
                    <button data-d="stat" onclick="switchDash('stat')">
                        <svg class="i" viewBox="0 0 24 24"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
                        Statistiques
                    </button>
                    <button data-d="notif" onclick="switchDash('notif')">
                        <svg class="i" viewBox="0 0 24 24"><path d="M18 8a6 6 0 0 0-12 0c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
                        Notifications
                        <?php if ($nbNotifications > 0): ?><span class="b"><?php echo $nbNotifications; ?></span><?php endif; ?>
                    </button>
                </nav>
                
                <div style="margin-top:auto;padding-top:14px;border-top:1px solid var(--line)">
                    <a href="logout.php" style="display:flex;align-items:center;gap:10px;color:var(--muted2);font-size:12px;padding:10px 12px;border-radius:8px;transition:.2s;text-decoration:none" onmouseover="this.style.color='#ff4444';this.style.background='rgba(255,0,0,.05)'" onmouseout="this.style.color='var(--muted2)';this.style.background='none'">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                        Déconnexion
                    </a>
                </div>
            </aside>
            
            <!-- Main Content -->
            <div class="dash-main">
                
                <!-- ===== DASHBOARD ===== -->
                <div class="dash-pane active" data-d="dash">
                    <div class="kpis">
                        <div class="kpi">
                            <span>Clients actifs</span>
                            <b><?php echo $nbClients; ?></b>
                        </div>
                        <div class="kpi">
                            <span>Séances (semaine)</span>
                            <b><?php echo $nbSeancesSemaine; ?></b>
                        </div>
                        <div class="kpi">
                            <span>Satisfaction</span>
                            <b><i>96%</i></b>
                        </div>
                        <div class="kpi">
                            <span>Revenus estimés</span>
                            <b style="color:var(--lime)">412k <small style="font-size:12px;color:var(--muted)">FCFA</small></b>
                        </div>
                    </div>
                    
                    <div class="grid g2">
                        <div class="card">
                            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:14px">
                                <h4 style="font-size:14px;text-transform:none;font-family:var(--body);font-weight:700">Prochaines séances</h4>
                                <a href="#" onclick="switchDash('plan')" style="font-size:11px;color:var(--lime)">Voir tout →</a>
                            </div>
                            <?php if (empty($prochainesSeances)): ?>
                                <p class="muted" style="font-size:13px">Aucune séance planifiée.</p>
                            <?php else: ?>
                                <?php foreach (array_slice($prochainesSeances, 0, 3) as $seance): 
                                    $estAujourdhui = $seance['date_seance'] == date('Y-m-d');
                                    $estDemain = $seance['date_seance'] == date('Y-m-d', strtotime('+1 day'));
                                    $label = $estAujourdhui ? 'Aujourd\'hui' : ($estDemain ? 'Demain' : date('d/m', strtotime($seance['date_seance'])));
                                    $labelClass = $estAujourdhui ? 'background:rgba(198,242,2,.1);color:var(--lime)' : 'background:var(--anthra2);color:var(--muted)';
                                ?>
                                <div class="row-item">
                                    <div class="av" style="background:linear-gradient(135deg,#<?php echo substr(md5($seance['client_nom'] ?? ''), 0, 6); ?>,#<?php echo substr(md5($seance['client_nom'] ?? ''), 6, 6); ?>)"></div>
                                    <div>
                                        <b><?php echo htmlspecialchars($seance['client_nom'] ?? $seance['titre']); ?></b>
                                        <span><?php echo htmlspecialchars($seance['titre']); ?> · <?php echo substr($seance['heure_debut'], 0, 5); ?></span>
                                    </div>
                                    <span class="st" style="<?php echo $labelClass; ?>;padding:3px 10px;border-radius:50px;font-size:11px"><?php echo $label; ?></span>
                                </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                        
                        <div class="card">
                            <h4 style="font-size:14px;text-transform:none;font-family:var(--body);font-weight:700;margin-bottom:12px">Objectifs du mois</h4>
                            <p class="muted" style="font-size:13px;margin-bottom:18px">Progression vers vos cibles de <?php echo date('F Y'); ?>.</p>
                            
                            <div style="margin-bottom:18px">
                                <div style="display:flex;justify-content:space-between;font-size:12px;color:var(--muted);margin-bottom:6px">
                                    <span>Nouveaux clients</span>
                                    <span style="color:var(--lime);font-weight:700">6/8</span>
                                </div>
                                <div class="wz-bar"><div class="wz-fill" style="width:75%"></div></div>
                            </div>
                            
                            <div style="margin-bottom:18px">
                                <div style="display:flex;justify-content:space-between;font-size:12px;color:var(--muted);margin-bottom:6px">
                                    <span>Satisfaction client</span>
                                    <span style="color:var(--lime);font-weight:700">96/90</span>
                                </div>
                                <div class="wz-bar"><div class="wz-fill" style="width:100%;background:linear-gradient(90deg,var(--lime),#8bc34a)"></div></div>
                            </div>
                            
                            <div>
                                <div style="display:flex;justify-content:space-between;font-size:12px;color:var(--muted);margin-bottom:6px">
                                    <span>Séances réalisées</span>
                                    <span style="color:var(--lime);font-weight:700">22/25</span>
                                </div>
                                <div class="wz-bar"><div class="wz-fill" style="width:88%"></div></div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card" style="margin-top:18px">
                        <h4 style="font-size:14px;text-transform:none;font-family:var(--body);font-weight:700;margin-bottom:14px">📈 Performance mensuelle</h4>
                        <div class="bars" style="height:140px">
                            <?php 
                            $sv = [12, 18, 22, 20, 26, 24, 30]; 
                            $months = ['Jan','Fév','Mar','Avr','Mai','Juin','Juil']; 
                            foreach ($sv as $i => $v): 
                            ?>
                            <div class="bar" style="height:<?php echo ($v/35*100); ?>%">
                                <span><?php echo $v; ?></span>
                                <i><?php echo $months[$i]; ?></i>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                
                <!-- ===== CALENDRIER ===== -->
                <div class="dash-pane" data-d="cal">
                    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:14px">
                        <h3 style="font-size:20px">Calendrier — <?php echo date('F Y'); ?></h3>
                        <div style="display:flex;gap:6px">
                            <button class="btn btn-sm btn-dark" style="padding:8px 14px;font-size:12px">◀</button>
                            <button class="btn btn-sm btn-dark" style="padding:8px 14px;font-size:12px">▶</button>
                        </div>
                    </div>
                    <div class="cal">
                        <?php 
                        $jours = ['Lu','Ma','Me','Je','Ve','Sa','Di'];
                        foreach ($jours as $j): 
                        ?>
                        <div style="text-align:center;font-size:11px;color:var(--muted2);font-weight:700;padding:8px"><?php echo $j; ?></div>
                        <?php endforeach; ?>
                        
                        <?php 
                        $premierJour = date('N', strtotime(date('Y-m-01')));
                        for ($i = 1; $i < $premierJour; $i++): 
                        ?>
                        <div></div>
                        <?php endfor; ?>
                        
                        <?php for ($i = 1; $i <= date('t'); $i++): ?>
                            <?php 
                            $has = isset($joursActifs[$i]); 
                            $today = ($i == date('j'));
                            ?>
                            <div class="d <?php echo $has ? 'has' : ''; ?> <?php echo $today ? 'today' : ''; ?>">
                                <?php echo $i; ?>
                                <?php if ($has): ?><span class="ev"><?php echo $joursActifs[$i]; ?> séance<?php echo $joursActifs[$i] > 1 ? 's' : ''; ?></span><?php endif; ?>
                            </div>
                        <?php endfor; ?>
                    </div>
                </div>
                
                <!-- ===== CLIENTS ===== -->
                <div class="dash-pane" data-d="cli">
                    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:14px">
                        <h3 style="font-size:20px">Mes clients</h3>
                        <span class="pill" style="background:rgba(198,242,2,.1);color:var(--lime)"><?php echo $nbClients; ?> actifs</span>
                    </div>
                    <?php foreach ($clients as $client): 
                        $statutColors = [
                            'actif' => ['color' => 'var(--lime)', 'bg' => 'rgba(198,242,2,.1)'],
                            'nouveau' => ['color' => 'var(--lime)', 'bg' => 'rgba(198,242,2,.1)'],
                            'contrat' => ['color' => '#667eea', 'bg' => 'rgba(102,126,234,.1)'],
                            'inactif' => ['color' => 'var(--muted2)', 'bg' => 'var(--anthra2)']
                        ];
                        $colors = $statutColors[$client['statut']] ?? $statutColors['actif'];
                    ?>
                    <div class="row-item">
                        <div class="av" style="background:linear-gradient(135deg,#<?php echo substr(md5($client['nom']), 0, 6); ?>,#<?php echo substr(md5($client['nom']), 6, 6); ?>)"></div>
                        <div>
                            <b><?php echo htmlspecialchars($client['nom']); ?></b>
                            <span><?php echo htmlspecialchars($client['objectif']); ?> · <?php echo htmlspecialchars($client['frequence'] ?? 'Fréquence non définie'); ?></span>
                        </div>
                        <span class="st" style="color:<?php echo $colors['color']; ?>;background:<?php echo $colors['bg']; ?>;padding:3px 10px;border-radius:50px;text-transform:uppercase;font-size:10px">
                            <?php echo ucfirst($client['statut']); ?>
                        </span>
                    </div>
                    <?php endforeach; ?>
                </div>
                
                <!-- ===== PLANNING ===== -->
                <div class="dash-pane" data-d="plan">
                    <h3 style="font-size:20px;margin-bottom:14px">Planning du jour — <?php echo date('l d F'); ?></h3>
                    <?php 
                    $seancesAujourdhui = array_filter($prochainesSeances, fn($s) => $s['date_seance'] == date('Y-m-d'));
                    if (empty($seancesAujourdhui)): 
                    ?>
                        <p class="muted" style="font-size:13px">Aucune séance aujourd'hui.</p>
                    <?php else: ?>
                    <div style="background:var(--anthra);border-radius:var(--rs);padding:20px;border:1px solid var(--line)">
                        <?php foreach ($seancesAujourdhui as $seance): ?>
                        <div class="row-item">
                            <b style="color:var(--lime);min-width:54px;font-family:var(--disp);font-size:16px"><?php echo substr($seance['heure_debut'], 0, 5); ?></b>
                            <div>
                                <b><?php echo htmlspecialchars($seance['client_nom'] ?? $seance['titre']); ?></b>
                                <span><?php echo htmlspecialchars($seance['titre']); ?> · <?php echo htmlspecialchars($seance['lieu'] ?? 'Lieu non défini'); ?></span>
                            </div>
                            <span class="pill" style="font-size:10px;background:rgba(198,242,2,.1);color:var(--lime)"><?php echo ucfirst($seance['statut']); ?></span>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                    
                    <h4 style="font-size:14px;text-transform:none;font-family:var(--body);font-weight:700;margin:24px 0 14px">Demain — <?php echo date('l d F', strtotime('+1 day')); ?></h4>
                    <?php 
                    $seancesDemain = array_filter($prochainesSeances, fn($s) => $s['date_seance'] == date('Y-m-d', strtotime('+1 day')));
                    if (empty($seancesDemain)): 
                    ?>
                        <p class="muted" style="font-size:13px">Aucune séance demain.</p>
                    <?php else: ?>
                    <div style="background:var(--anthra);border-radius:var(--rs);padding:16px 20px;border:1px solid var(--line);opacity:.7">
                        <?php foreach ($seancesDemain as $seance): ?>
                        <div class="row-item" style="border:0;padding:8px 0">
                            <b style="color:var(--muted);min-width:54px;font-family:var(--disp);font-size:16px"><?php echo substr($seance['heure_debut'], 0, 5); ?></b>
                            <div>
                                <b><?php echo htmlspecialchars($seance['client_nom'] ?? $seance['titre']); ?></b>
                                <span><?php echo htmlspecialchars($seance['lieu'] ?? 'Lieu non défini'); ?></span>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                </div>
                
                <!-- ===== STATISTIQUES ===== -->
                <div class="dash-pane" data-d="stat">
                    <h3 style="font-size:20px;margin-bottom:4px">Statistiques — 6 derniers mois</h3>
                    <p class="muted" style="font-size:13px;margin-bottom:20px">Séances réalisées et revenus générés</p>
                    
                    <div class="bars">
                        <?php 
                        $sv = [18, 22, 20, 26, 24, 30]; 
                        $months = ['Jan','Fév','Mar','Avr','Mai','Juin']; 
                        $revenus = [280, 340, 310, 395, 365, 412];
                        foreach ($sv as $i => $v): 
                        ?>
                        <div class="bar" style="height:<?php echo ($v/35*100); ?>%">
                            <span><?php echo $v; ?></span>
                            <i><?php echo $months[$i]; ?><br><small style="color:var(--lime)"><?php echo $revenus[$i]; ?>k</small></i>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <div class="grid g3" style="margin-top:30px">
                        <div class="kpi" style="text-align:center">
                            <span>Total séances</span>
                            <b>140</b>
                        </div>
                        <div class="kpi" style="text-align:center">
                            <span>Revenus totaux</span>
                            <b style="color:var(--lime)">2.1M <small>FCFA</small></b>
                        </div>
                        <div class="kpi" style="text-align:center">
                            <span>Commission moy.</span>
                            <b style="color:var(--lime)">75%</b>
                        </div>
                    </div>
                </div>
                
                <!-- ===== NOTIFICATIONS ===== -->
                <div class="dash-pane" data-d="notif">
                    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:14px">
                        <h3 style="font-size:20px">Notifications</h3>
                        <?php if ($nbNotifications > 0): ?>
                        <form action="mark_all_read.php" method="POST" style="display:inline">
                            <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                            <button type="submit" style="background:none;border:none;color:var(--lime);font-size:12px;cursor:pointer;font-family:var(--body);font-weight:600">Tout marquer comme lu</button>
                        </form>
                        <?php endif; ?>
                    </div>
                    
                    <?php foreach ($notifications as $notif): 
                        $icones = [
                            'nouveau_client' => '👤',
                            'avis' => '⭐',
                            'rappel' => '📅',
                            'paiement' => '💰',
                            'certification' => '🏆',
                            'systeme' => '🔔'
                        ];
                        $icone = $icones[$notif['type']] ?? '🔔';
                    ?>
                    <div class="row-item" style="<?php echo !$notif['est_lue'] ? 'background:rgba(198,242,2,.05);border-radius:var(--rs);padding:14px 16px;border:1px solid rgba(198,242,2,.2)' : ''; ?>;margin-top:<?php echo $notif === $notifications[0] ? '0' : '8px'; ?>">
                        <div class="av" style="background:<?php echo !$notif['est_lue'] ? 'rgba(198,242,2,.2)' : 'var(--anthra)'; ?>;display:flex;align-items:center;justify-content:center;font-size:16px">
                            <?php echo $icone; ?>
                        </div>
                        <div>
                            <b><?php echo htmlspecialchars($notif['titre']); ?></b>
                            <span><?php echo htmlspecialchars($notif['message']); ?></span>
                        </div>
                        <?php if (!$notif['est_lue']): ?>
                        <span class="pill" style="background:var(--lime);color:var(--noir);font-size:10px">NEW</span>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; ?>
                </div>
                
            </div>
        </div>
    </div>
</section>

<script>
function switchDash(dashName) {
    document.querySelectorAll('#dnav button').forEach(btn => {
        btn.classList.toggle('active', btn.dataset.d === dashName);
    });
    document.querySelectorAll('.dash-pane').forEach(pane => {
        pane.classList.toggle('active', pane.dataset.d === dashName);
    });
}

// Auto-fermeture messages flash
document.querySelectorAll('.flash-message').forEach(msg => {
    setTimeout(() => msg.style.opacity = '0', 4000);
    setTimeout(() => msg.remove(), 4500);
});
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>