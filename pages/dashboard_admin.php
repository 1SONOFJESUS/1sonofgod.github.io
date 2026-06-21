<?php
/**
 * DASHBOARD_ADMIN.PHP
 * Tableau de bord administration — Supervision globale
 * Permissions : dashboard_admin, gestion_coachs, gestion_candidatures, statistiques
 */

require_once __DIR__ . '/../includes/auth_check.php';
require_once __DIR__ . '/../includes/config_session.php';
$user = checkAuth('dashboard_admin'); // Vérifie permission spécifique

// Stats globales
$stmt = $pdo->query("SELECT COUNT(*) FROM coachs WHERE est_admin = FALSE AND statut = 'actif'");
$totalCoachs = $stmt->fetchColumn();

$stmt = $pdo->query("SELECT COUNT(*) FROM candidatures WHERE statut = 'nouvelle'");
$nouvellesCandidatures = $stmt->fetchColumn();

$stmt = $pdo->query("SELECT COUNT(*) FROM clients WHERE statut = 'actif'");
$totalClients = $stmt->fetchColumn();

$stmt = $pdo->query("SELECT COUNT(*) FROM seances WHERE date_seance = CURDATE() AND statut IN ('planifiee', 'confirmee')");
$seancesAujourdhui = $stmt->fetchColumn();

// Candidatures récentes
$stmt = $pdo->query("
    SELECT * FROM candidatures 
    WHERE statut = 'nouvelle' 
    ORDER BY date_creation DESC 
    LIMIT 5
");
$candidaturesRecentes = $stmt->fetchAll();

// Coachs récents
$stmt = $pdo->query("
    SELECT c.*, r.label as role_label 
    FROM coachs c
    LEFT JOIN roles r ON c.role_id = r.id
    WHERE c.est_admin = FALSE
    ORDER BY c.date_creation DESC 
    LIMIT 5
");
$coachsRecents = $stmt->fetchAll();

// Activité récente (logs)
$stmt = $pdo->query("
    SELECT l.*, c.prenom, c.nom 
    FROM admin_logs l
    JOIN coachs c ON l.utilisateur_id = c.id
    ORDER BY l.date_creation DESC 
    LIMIT 10
");
$activiteRecente = $stmt->fetchAll();

$pageTitle = 'Dashboard — Administration GBÔ';
require_once __DIR__ . '/../includes/head.php';
?>

<header class="site-header">
    <div class="wrap">
        <nav class="nav">
            <a href="dashboard_admin.php" class="brand">
                <div>
                    <div class="logo-text">GBÔ <span>ADMIN</span></div>
                    <div class="logo-sub">Espace administration</div>
                </div>
            </a>
            <div class="nav-cta" style="gap:16px">
                <span style="font-size:13px;color:var(--muted)">🔐 <?php echo htmlspecialchars($user['prenom'] . ' ' . $user['nom']); ?></span>
                <span class="pill" style="background:rgba(198,242,2,.1);color:var(--lime);font-size:11px"><?php echo htmlspecialchars($user['role_label']); ?></span>
                <a href="logout.php" class="btn btn-ghost btn-sm" style="color:#ff4444;border-color:#ff444433">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                </a>
            </div>
        </nav>
    </div>
</header>

<section style="padding-top:100px;padding-bottom:40px">
    <div class="wrap">
        <?php showFlash(); ?>
        
        <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:12px;margin-bottom:24px">
            <div>
                <h1 style="font-size:28px">Dashboard <span class="lime">Administration</span></h1>
                <p class="muted" style="font-size:14px">
                    <?php echo htmlspecialchars($user['role_label']); ?> · 
                    Dernière connexion : <?php echo date('d/m/Y H:i', strtotime($user['derniere_connexion'] ?? 'now')); ?>
                </p>
            </div>
            <div style="display:flex;gap:8px">
                <a href="messages_admin.php" class="btn btn-dark btn-sm">
                    💬 Messages <?php $unread = getUnreadMessagesCount($user['id']); if ($unread > 0): ?><span style="background:var(--lime);color:var(--noir);border-radius:50%;padding:2px 6px;font-size:10px;margin-left:4px"><?php echo $unread; ?></span><?php endif; ?>
                </a>
                <a href="admin/parametres.php" class="btn btn-ghost btn-sm">⚙️ Paramètres</a>
            </div>
        </div>
        
        <!-- KPIs Admin -->
        <div class="kpis" style="grid-template-columns:repeat(4, 1fr)">
            <div class="kpi" style="border-left:3px solid var(--lime)">
                <span>Coachs actifs</span>
                <b><?php echo $totalCoachs; ?></b>
                <span style="font-size:11px;color:var(--muted2)">dans le réseau</span>
            </div>
            <div class="kpi" style="border-left:3px solid #ff9800">
                <span>Nouvelles candidatures</span>
                <b style="color:#ff9800"><?php echo $nouvellesCandidatures; ?></b>
                <span style="font-size:11px;color:var(--muted2)">en attente de traitement</span>
            </div>
            <div class="kpi" style="border-left:3px solid #2196f3">
                <span>Clients actifs</span>
                <b style="color:#2196f3"><?php echo $totalClients; ?></b>
                <span style="font-size:11px;color:var(--muted2)">suivis par le réseau</span>
            </div>
            <div class="kpi" style="border-left:3px solid #9c27b0">
                <span>Séances aujourd'hui</span>
                <b style="color:#9c27b0"><?php echo $seancesAujourdhui; ?></b>
                <span style="font-size:11px;color:var(--muted2)">planifiées</span>
            </div>
        </div>
        
        <div class="grid g2" style="margin-top:24px">
            <!-- Candidatures récentes -->
            <div class="card">
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px">
                    <h4 style="font-size:14px;text-transform:none;font-family:var(--body);font-weight:700">📋 Candidatures récentes</h4>
                    <a href="admin/candidatures.php" class="btn btn-sm btn-dark">Voir tout</a>
                </div>
                
                <?php if (empty($candidaturesRecentes)): ?>
                    <p class="muted" style="font-size:13px">Aucune nouvelle candidature.</p>
                <?php else: ?>
                    <?php foreach ($candidaturesRecentes as $cand): 
                        $statutColors = [
                            'nouvelle' => ['bg' => 'rgba(255,152,0,.1)', 'color' => '#ff9800'],
                            'en_etude' => ['bg' => 'rgba(33,150,243,.1)', 'color' => '#2196f3'],
                            'acceptee' => ['bg' => 'rgba(198,242,2,.1)', 'color' => 'var(--lime)'],
                            'refusee' => ['bg' => 'rgba(244,67,54,.1)', 'color' => '#f44336']
                        ];
                        $colors = $statutColors[$cand['statut']] ?? $statutColors['nouvelle'];
                    ?>
                    <div class="row-item" style="padding:12px 0">
                        <div style="flex:1;min-width:0">
                            <b style="font-size:13px"><?php echo htmlspecialchars($cand['nom']); ?></b>
                            <span style="font-size:12px;color:var(--muted);display:block">
                                <?php echo htmlspecialchars($cand['profil']); ?> · 
                                <?php echo htmlspecialchars($cand['specialite']); ?> ·
                                <?php echo date('d/m', strtotime($cand['date_creation'])); ?>
                            </span>
                        </div>
                        <span class="pill" style="background:<?php echo $colors['bg']; ?>;color:<?php echo $colors['color']; ?>;font-size:10px;text-transform:uppercase">
                            <?php echo str_replace('_', ' ', $cand['statut']); ?>
                        </span>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            
            <!-- Coachs récents -->
            <div class="card">
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px">
                    <h4 style="font-size:14px;text-transform:none;font-family:var(--body);font-weight:700">👥 Coachs récents</h4>
                    <a href="admin/coachs.php" class="btn btn-sm btn-dark">Gérer</a>
                </div>
                
                <?php foreach ($coachsRecents as $c): ?>
                <div class="row-item" style="padding:12px 0">
                    <div class="av" style="width:32px;height:32px;background:linear-gradient(135deg,#<?php echo substr(md5($c['prenom']), 0, 6); ?>,#<?php echo substr(md5($c['nom']), 6, 6); ?>)"></div>
                    <div style="flex:1;min-width:0">
                        <b style="font-size:13px"><?php echo htmlspecialchars($c['prenom'] . ' ' . $c['nom']); ?></b>
                        <span style="font-size:12px;color:var(--muted);display:block">
                            <?php echo htmlspecialchars($c['niveau']); ?> · <?php echo htmlspecialchars($c['specialite'] ?? 'Non spécifié'); ?>
                        </span>
                    </div>
                    <span class="pill" style="font-size:10px"><?php echo htmlspecialchars($c['coach_id']); ?></span>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        
        <!-- Activité récente -->
        <div class="card" style="margin-top:24px">
            <h4 style="font-size:14px;text-transform:none;font-family:var(--body);font-weight:700;margin-bottom:16px">📊 Activité récente</h4>
            <div style="max-height:300px;overflow-y:auto">
                <?php foreach ($activiteRecente as $log): ?>
                <div class="row-item" style="padding:10px 0;border-color:var(--line)">
                    <div style="width:8px;height:8px;border-radius:50%;background:var(--lime);flex-shrink:0;margin-top:6px"></div>
                    <div style="flex:1;min-width:0">
                        <b style="font-size:12px"><?php echo htmlspecialchars($log['prenom'] . ' ' . $log['nom']); ?></b>
                        <span style="font-size:12px;color:var(--muted);display:block">
                            <?php echo htmlspecialchars($log['action']); ?>
                            <?php if ($log['cible_table']): ?> → <?php echo $log['cible_table']; ?> #<?php echo $log['cible_id']; ?><?php endif; ?>
                        </span>
                    </div>
                    <span style="font-size:11px;color:var(--muted2);white-space:nowrap">
                        <?php echo date('H:i', strtotime($log['date_creation'])); ?>
                    </span>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        
        <!-- Menu admin rapide -->
        <div class="grid g4" style="margin-top:24px">
            <a href="admin/coachs.php" class="card" style="text-decoration:none;color:inherit;text-align:center;padding:30px">
                <div style="font-size:32px;margin-bottom:12px">👥</div>
                <h4 style="font-size:14px;text-transform:none">Gestion Coachs</h4>
                <p style="font-size:12px;color:var(--muted);margin-top:4px">Activer, suspendre, modifier</p>
            </a>
            <a href="admin/candidatures.php" class="card" style="text-decoration:none;color:inherit;text-align:center;padding:30px">
                <div style="font-size:32px;margin-bottom:12px">📋</div>
                <h4 style="font-size:14px;text-transform:none">Candidatures</h4>
                <p style="font-size:12px;color:var(--muted);margin-top:4px">Traiter les demandes</p>
            </a>
            <a href="admin/stats.php" class="card" style="text-decoration:none;color:inherit;text-align:center;padding:30px">
                <div style="font-size:32px;margin-bottom:12px">📈</div>
                <h4 style="font-size:14px;text-transform:none">Statistiques</h4>
                <p style="font-size:12px;color:var(--muted);margin-top:4px">Rapports et analyses</p>
            </a>
            <a href="admin/contenu.php" class="card" style="text-decoration:none;color:inherit;text-align:center;padding:30px">
                <div style="font-size:32px;margin-bottom:12px">📝</div>
                <h4 style="font-size:14px;text-transform:none">Contenu Site</h4>
                <p style="font-size:12px;color:var(--muted);margin-top:4px">Blog, pages, SEO</p>
            </a>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>