<?php
/**
 * DASHBOARD CLIENT
 * Parcours, seances, abonnement, paiements, rappels
 */

$siteRoot = '/gbo_africa_group';

session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'client') {
    header("Location: {$siteRoot}/pages/login.php?role=client");
    exit;
}

$pageTitle = 'Espace Client — GBO AFRICA GROUP';
$clientName = $_SESSION['prenom'] ?? 'Client';
$clientNom = $_SESSION['nom'] ?? 'GBO';

$abonnement = [
    'type' => 'Premium',
    'debut' => '2026-01-15',
    'fin' => '2026-07-15',
    'statut' => 'actif',
    'seances_incluses' => 12,
    'seances_utilisees' => 8,
    'prix' => 450000,
    'coach' => 'Awa Kone',
    'programme' => 'Fitness feminin'
];

$parcours = [
    ['date' => '2026-06-23', 'seance' => 'Fitness feminin - Seance 8', 'coach' => 'Awa Kone', 'duree' => '60min', 'type' => 'coaching', 'note' => 'Excellent travail sur les squats', 'evaluation' => 5],
    ['date' => '2026-06-20', 'seance' => 'Fitness feminin - Seance 7', 'coach' => 'Awa Kone', 'duree' => '60min', 'type' => 'coaching', 'note' => 'Progression cardio notable', 'evaluation' => 5],
    ['date' => '2026-06-17', 'seance' => 'Fitness feminin - Seance 6', 'coach' => 'Awa Kone', 'duree' => '60min', 'type' => 'coaching', 'note' => 'Renforcement core renforce', 'evaluation' => 4],
    ['date' => '2026-06-14', 'seance' => 'Bilan mensuel + Coaching', 'coach' => 'Awa Kone', 'duree' => '90min', 'type' => 'bilan', 'note' => '-2kg, objectifs atteints a 75%', 'evaluation' => 5],
    ['date' => '2026-06-10', 'seance' => 'Fitness feminin - Seance 5', 'coach' => 'Awa Kone', 'duree' => '60min', 'type' => 'coaching', 'note' => 'Nouveaux exercices integres', 'evaluation' => 5],
];

$prochaines = [
    ['date' => '2026-06-26', 'heure' => '09:00', 'seance' => 'Fitness feminin - Seance 9', 'coach' => 'Awa Kone', 'lieu' => 'Salle GBO Cocody', 'duree' => '60min'],
    ['date' => '2026-06-29', 'heure' => '09:00', 'seance' => 'Fitness feminin - Seance 10', 'coach' => 'Awa Kone', 'lieu' => 'Salle GBO Cocody', 'duree' => '60min'],
    ['date' => '2026-07-02', 'heure' => '09:00', 'seance' => 'Bilan trimestriel', 'coach' => 'Awa Kone', 'lieu' => 'Salle GBO Cocody', 'duree' => '90min'],
];

$progression = [
    'objectif' => 'Perte de poids',
    'poids_depart' => 78,
    'poids_actuel' => 73,
    'poids_objectif' => 68,
    'seances_total' => 12,
    'seances_faites' => 8,
    'taux_completion' => 67,
];

$paiements = [
    ['date' => '2026-01-15', 'montant' => 450000, 'mode' => 'Mobile Money', 'statut' => 'paye', 'description' => 'Abonnement Premium - Janvier/Juillet 2026'],
    ['date' => '2025-07-15', 'montant' => 350000, 'mode' => 'Carte bancaire', 'statut' => 'paye', 'description' => 'Abonnement Standard - Juillet/Decembre 2025'],
];

$messages = [
    ['id' => 1, 'from' => 'Coach Awa', 'message' => 'Bravo pour votre progression ! Continuez ainsi', 'time' => '14:30', 'unread' => true],
    ['id' => 2, 'from' => 'GBO Admin', 'message' => 'Votre abonnement expire dans 20 jours. Pensez au renouvellement', 'time' => '10:00', 'unread' => true],
];

function formatPrice($prix) {
    return number_format($prix, 0, ',', ' ') . ' FCFA';
}

$jours_restant = max(0, (strtotime($abonnement['fin']) - time()) / 86400);
$alerte_abo = $jours_restant <= 7 ? 'danger' : ($jours_restant <= 30 ? 'warning' : 'success');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= htmlspecialchars($pageTitle) ?></title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Archivo:wght@500;600;700;800;900&family=Manrope:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?= $siteRoot ?>/assets/css/dashboard.css">
</head>
<body>

<div class="dashboard-layout">
  <aside class="sidebar" id="sidebar">
    <div class="sidebar-header">
      <a href="<?= $siteRoot ?>/index.php" class="sidebar-brand">
        <div class="logo-icon">G</div>
        <span class="brand-text">GBO Client</span>
      </a>
    </div>
    <button class="sidebar-toggle" onclick="toggleSidebar()">
      <svg viewBox="0 0 24 24" width="14" height="14" stroke="currentColor" fill="none" stroke-width="2.5"><polyline points="15 18 9 12 15 6"/></svg>
    </button>

    <nav>
      <div class="nav-section">
        <div class="nav-section-title">Mon compte</div>
        <ul class="sidebar-nav">
          <li class="nav-item">
            <a href="<?= $siteRoot ?>/pages/client/dashboard_client.php" class="nav-link active">
              <svg viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
              <span class="nav-text">Tableau de bord</span>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?= $siteRoot ?>/pages/client/client_parcours.php" class="nav-link">
              <svg viewBox="0 0 24 24"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
              <span class="nav-text">Mon parcours</span>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?= $siteRoot ?>/pages/client/client_seances.php" class="nav-link">
              <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
              <span class="nav-text">Mes seances</span>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?= $siteRoot ?>/pages/client/client_abonnement.php" class="nav-link">
              <svg viewBox="0 0 24 24"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
              <span class="nav-text">Mon abonnement</span>
            </a>
          </li>
        </ul>
      </div>
      <div class="nav-section">
        <div class="nav-section-title">Services</div>
        <ul class="sidebar-nav">
          <li class="nav-item">
            <a href="<?= $siteRoot ?>/pages/client/client_reserver.php" class="nav-link">
              <svg viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
              <span class="nav-text">Reserver</span>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?= $siteRoot ?>/pages/client/client_paiements.php" class="nav-link">
              <svg viewBox="0 0 24 24"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
              <span class="nav-text">Mes paiements</span>
            </a>
          </li>
        </ul>
      </div>
      <div class="nav-section">
        <div class="nav-section-title">Communication</div>
        <ul class="sidebar-nav">
          <li class="nav-item">
            <a href="<?= $siteRoot ?>/pages/client/client_messages.php" class="nav-link">
              <svg viewBox="0 0 24 24"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7A8.38 8.38 0 0 1 4 11.5a8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>
              <span class="nav-text">Messagerie</span>
              <span class="nav-badge" style="background:var(--danger)">2</span>
            </a>
          </li>
        </ul>
      </div>
    </nav>

    <div class="sidebar-footer">
      <div class="user-card">
        <div class="user-avatar"><?= substr($clientName, 0, 1) ?></div>
        <div class="user-info">
          <div class="user-name"><?= htmlspecialchars($clientName . ' ' . $clientNom) ?></div>
          <div class="user-role">Client Premium</div>
        </div>
      </div>
    </div>
  </aside>

  <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleMobileMenu()"></div>

  <main class="main-content" id="mainContent">
    <header class="top-header">
      <div style="display:flex;align-items:center;gap:16px">
        <button class="header-btn" style="display:none" id="mobileMenuBtn" onclick="toggleMobileMenu()">
          <svg viewBox="0 0 24 24"><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
        </button>
        <div class="header-search">
          <svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
          <input type="text" placeholder="Rechercher..." id="globalSearch">
        </div>
      </div>
      <div class="header-actions">
        <button class="header-btn" onclick="showToast('Notifications rafraichies')">
          <svg viewBox="0 0 24 24"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
          <span class="notif-badge">2</span>
        </button>
        <a href="<?= $siteRoot ?>/includes/logout.php" class="header-btn" title="Deconnexion">
          <svg viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
        </a>
      </div>
    </header>

    <div class="page-content">
      <div class="breadcrumb">
        <a href="<?= $siteRoot ?>/pages/client/dashboard_client.php">Client</a>
        <span class="sep">/</span>
        <span>Tableau de bord</span>
      </div>

      <div class="page-header">
        <h1>Bonjour, <?= htmlspecialchars($clientName) ?></h1>
        <p>Voici votre progression et vos prochains rendez-vous</p>
      </div>

      <?php if ($alerte_abo !== 'success'): ?>
      <div class="alert alert-<?= $alerte_abo === 'danger' ? 'danger' : 'warning' ?>">
        <svg viewBox="0 0 24 24"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
        <div>
          <strong>Votre abonnement <?= $alerte_abo === 'danger' ? 'expire dans ' . ceil($jours_restant) . ' jours !' : 'expire bientot' ?></strong>
          <p style="margin-top:4px">Pensez a le renouveler pour ne pas interrompre votre parcours.</p>
          <a href="<?= $siteRoot ?>/pages/client/client_abonnement.php" class="btn btn-sm btn-primary" style="margin-top:8px">Renouveler maintenant</a>
        </div>
      </div>
      <?php endif; ?>

      <div class="card-grid cols-4">
        <div class="card">
          <div class="card-header">
            <div>
              <div class="card-title">Seances realisees</div>
              <div class="card-subtitle">Sur votre abonnement</div>
            </div>
            <div class="card-icon lime">
              <svg viewBox="0 0 24 24"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
            </div>
          </div>
          <div class="card-value"><?= $progression['seances_faites'] ?>/<?= $progression['seances_total'] ?></div>
          <div class="card-change up">
            <svg viewBox="0 0 24 24"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/></svg>
            <?= round(($progression['seances_faites'] / $progression['seances_total']) * 100) ?>% complete
          </div>
        </div>

        <div class="card">
          <div class="card-header">
            <div>
              <div class="card-title">Poids actuel</div>
              <div class="card-subtitle">Objectif : <?= $progression['poids_objectif'] ?> kg</div>
            </div>
            <div class="card-icon success">
              <svg viewBox="0 0 24 24"><path d="M12 2v20M2 12h20"/></svg>
            </div>
          </div>
          <div class="card-value"><?= $progression['poids_actuel'] ?> <span style="font-size:16px;color:var(--muted)">kg</span></div>
          <div class="card-change up">
            <svg viewBox="0 0 24 24"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/></svg>
            -<?= $progression['poids_depart'] - $progression['poids_actuel'] ?> kg depuis le debut
          </div>
        </div>

        <div class="card">
          <div class="card-header">
            <div>
              <div class="card-title">Coach assigne</div>
              <div class="card-subtitle">Votre referent</div>
            </div>
            <div class="card-icon info">
              <svg viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            </div>
          </div>
          <div class="card-value" style="font-size:20px"><?= htmlspecialchars($abonnement['coach']) ?></div>
          <div class="card-change up">
            <svg viewBox="0 0 24 24"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/></svg>
            <?= $abonnement['programme'] ?>
          </div>
        </div>

        <div class="card">
          <div class="card-header">
            <div>
              <div class="card-title">Abonnement</div>
              <div class="card-subtitle"><?= $abonnement['type'] ?></div>
            </div>
            <div class="card-icon <?= $alerte_abo ?>">
              <svg viewBox="0 0 24 24"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
            </div>
          </div>
          <div class="card-value"><?= ceil($jours_restant) ?></div>
          <div class="card-change <?= $alerte_abo === 'success' ? 'up' : 'down' ?>">
            <svg viewBox="0 0 24 24"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/></svg>
            jours restants
          </div>
        </div>
      </div>

      <div class="card">
        <div class="card-header">
          <div>
            <div class="card-title">Votre progression</div>
            <div class="card-subtitle">Objectif : <?= $progression['objectif'] ?></div>
          </div>
        </div>
        <div style="display:flex;align-items:center;gap:40px;margin-top:20px;flex-wrap:wrap">
          <div class="progress-ring">
            <svg width="120" height="120" viewBox="0 0 120 120">
              <circle class="progress-ring-bg" cx="60" cy="60" r="52"/>
              <circle class="progress-ring-fill" cx="60" cy="60" r="52" 
                stroke-dasharray="326.73" 
                stroke-dashoffset="<?= 326.73 - (326.73 * $progression['taux_completion'] / 100) ?>"/>
            </svg>
            <div class="progress-ring-text"><?= $progression['taux_completion'] ?>%</div>
          </div>

          <div style="flex:1;min-width:280px">
            <div class="stat-row">
              <span class="stat-row-label">Poids de depart</span>
              <span class="stat-row-value"><?= $progression['poids_depart'] ?> kg</span>
            </div>
            <div class="stat-row">
              <span class="stat-row-label">Poids actuel</span>
              <span class="stat-row-value" style="color:var(--lime)"><?= $progression['poids_actuel'] ?> kg</span>
            </div>
            <div class="stat-row">
              <span class="stat-row-label">Objectif</span>
              <span class="stat-row-value"><?= $progression['poids_objectif'] ?> kg</span>
            </div>
            <div class="stat-row">
              <span class="stat-row-label">Seances completees</span>
              <span class="stat-row-value"><?= $progression['seances_faites'] ?> / <?= $progression['seances_total'] ?></span>
            </div>
            <div class="stat-row">
              <span class="stat-row-label">Reste a perdre</span>
              <span class="stat-row-value"><?= $progression['poids_actuel'] - $progression['poids_objectif'] ?> kg</span>
            </div>
          </div>
        </div>
      </div>

      <div class="card-grid cols-2">
        <div class="card">
          <div class="card-header">
            <div>
              <div class="card-title">Prochaines seances</div>
            </div>
            <a href="<?= $siteRoot ?>/pages/client/client_reserver.php" class="btn btn-sm btn-primary">Reserver</a>
          </div>
          <div style="display:flex;flex-direction:column;gap:12px;margin-top:16px">
            <?php foreach ($prochaines as $seance): ?>
            <div style="display:flex;align-items:center;gap:16px;padding:16px;background:var(--bg-light);border-radius:var(--radius-sm);border-left:3px solid var(--lime)">
              <div style="text-align:center;min-width:60px">
                <div style="font-size:20px;font-weight:800;color:var(--lime)"><?= date('d', strtotime($seance['date'])) ?></div>
                <div style="font-size:12px;color:var(--muted);text-transform:uppercase"><?= date('M', strtotime($seance['date'])) ?></div>
              </div>
              <div style="flex:1">
                <div style="font-weight:600"><?= htmlspecialchars($seance['seance']) ?></div>
                <div style="font-size:13px;color:var(--muted);margin-top:4px">
                  <?= $seance['heure'] ?> · <?= htmlspecialchars($seance['lieu']) ?> · <?= $seance['duree'] ?>
                </div>
              </div>
            </div>
            <?php endforeach; ?>
          </div>
        </div>

        <div class="table-container">
          <div class="table-header">
            <h3>Seances effectuees</h3>
            <a href="<?= $siteRoot ?>/pages/client/client_seances.php" class="btn btn-sm btn-ghost">Voir tout</a>
          </div>
          <table class="data-table">
            <thead>
              <tr>
                <th>Date</th>
                <th>Seance</th>
                <th>Note coach</th>
                <th>Eval</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($parcours as $seance): ?>
              <tr>
                <td><?= date('d/m', strtotime($seance['date'])) ?></td>
                <td>
                  <strong><?= htmlspecialchars($seance['seance']) ?></strong><br>
                  <small style="color:var(--muted)"><?= $seance['type'] === 'bilan' ? 'Bilan' : 'Coaching' ?></small>
                </td>
                <td style="max-width:200px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis"><?= htmlspecialchars($seance['note']) ?></td>
                <td><?= str_repeat('★', $seance['evaluation']) ?></td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>

      <div class="card-grid cols-2">
        <div class="card">
          <div class="card-header">
            <div>
              <div class="card-title">Messages</div>
              <div class="card-subtitle">De votre coach et l'administration</div>
            </div>
            <a href="<?= $siteRoot ?>/pages/client/client_messages.php" class="btn btn-sm btn-primary">Contacter</a>
          </div>
          <div style="display:flex;flex-direction:column;gap:8px;margin-top:16px">
            <?php foreach ($messages as $msg): ?>
            <div style="display:flex;align-items:center;gap:12px;padding:12px;background:var(--bg-light);border-radius:var(--radius-sm);cursor:pointer;transition:all 0.2s" onmouseover="this.style.background='var(--bg-hover)'" onmouseout="this.style.background='var(--bg-light)'">
              <div class="chat-contact-avatar"><?= substr($msg['from'], 0, 1) ?></div>
              <div style="flex:1;min-width:0">
                <div style="display:flex;align-items:center;justify-content:space-between">
                  <span style="font-weight:600;font-size:14px"><?= htmlspecialchars($msg['from']) ?></span>
                  <span style="font-size:11px;color:var(--muted)"><?= $msg['time'] ?></span>
                </div>
                <div style="font-size:13px;color:var(--muted);white-space:nowrap;overflow:hidden;text-overflow:ellipsis"><?= htmlspecialchars($msg['message']) ?></div>
              </div>
              <?php if ($msg['unread']): ?>
              <div style="width:8px;height:8px;background:var(--lime);border-radius:50%;flex-shrink:0"></div>
              <?php endif; ?>
            </div>
            <?php endforeach; ?>
          </div>
        </div>

        <div class="card">
          <div class="card-header">
            <div>
              <div class="card-title">Mes paiements</div>
              <div class="card-subtitle">Historique et factures</div>
            </div>
            <a href="<?= $siteRoot ?>/pages/client/client_paiements.php" class="btn btn-sm btn-ghost">Voir tout</a>
          </div>
          <div style="margin-top:16px">
            <?php foreach ($paiements as $pay): ?>
            <div style="display:flex;align-items:center;justify-content:space-between;padding:14px 0;border-bottom:1px solid var(--line)">
              <div>
                <div style="font-weight:600"><?= htmlspecialchars($pay['description']) ?></div>
                <div style="font-size:12px;color:var(--muted);margin-top:2px"><?= date('d/m/Y', strtotime($pay['date'])) ?> · <?= $pay['mode'] ?></div>
              </div>
              <div style="text-align:right">
                <div style="font-weight:700;color:var(--lime)"><?= formatPrice($pay['montant']) ?></div>
                <span class="status active" style="margin-top:4px">Paye</span>
              </div>
            </div>
            <?php endforeach; ?>

            <div style="margin-top:20px;padding:16px;background:var(--bg-light);border-radius:var(--radius-sm);text-align:center">
              <p style="color:var(--muted);font-size:14px;margin-bottom:12px">Prochain paiement : renouvellement abonnement</p>
              <a href="<?= $siteRoot ?>/pages/client/client_abonnement.php" class="btn btn-primary">Payer maintenant</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>
</div>

<script src="<?= $siteRoot ?>/assets/js/dashboard.js"></script>
<script>
document.getElementById('globalSearch').addEventListener('input', function() {
  const term = this.value.toLowerCase();
  document.querySelectorAll('.data-table tbody tr').forEach(row => {
    row.style.display = row.textContent.toLowerCase().includes(term) ? '' : 'none';
  });
});

if (window.innerWidth <= 1024) {
  document.getElementById('mobileMenuBtn').style.display = 'flex';
}
</script>

</body>
</html>
