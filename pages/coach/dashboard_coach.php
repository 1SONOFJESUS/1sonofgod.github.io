<?php
/**
 * DASHBOARD COACH
 * Clients, agenda, progression, revenus, messagerie
 */

$siteRoot = '/gbo_africa_group';

session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'coach') {
    header("Location: {$siteRoot}/pages/login.php?role=coach");
    exit;
}

$pageTitle = 'Espace Coach — GBO AFRICA GROUP';
$coachName = $_SESSION['prenom'] ?? 'Coach';
$coachNom = $_SESSION['nom'] ?? 'GBO';
$coachFullName = $coachName . ' ' . $coachNom;

$stats = [
    'clients_actifs' => 24,
    'seances_mois' => 32,
    'revenus_mois' => 1250000,
    'taux_satisfaction' => 96,
    'nouveaux_clients' => 3,
    'seances_a_venir' => 8
];

$clients = [
    ['id' => 1, 'nom' => 'Aicha B.', 'objectif' => 'Perte de poids', 'seances_total' => 12, 'seances_restantes' => 8, 'progression' => 75, 'derniere_seance' => '2026-06-23', 'prochaine' => '2026-06-26', 'statut' => 'active', 'programme' => 'Fitness feminin'],
    ['id' => 2, 'nom' => 'Marie T.', 'objectif' => 'Fitness senior', 'seances_total' => 8, 'seances_restantes' => 6, 'progression' => 30, 'derniere_seance' => '2026-06-22', 'prochaine' => '2026-06-25', 'statut' => 'active', 'programme' => 'Remise en forme'],
    ['id' => 3, 'nom' => 'Fatou K.', 'objectif' => 'Postnatal', 'seances_total' => 10, 'seances_restantes' => 7, 'progression' => 45, 'derniere_seance' => '2026-06-21', 'prochaine' => '2026-06-27', 'statut' => 'active', 'programme' => 'Postnatal'],
    ['id' => 4, 'nom' => 'Aminata D.', 'objectif' => 'Renforcement', 'seances_total' => 6, 'seances_restantes' => 4, 'progression' => 60, 'derniere_seance' => '2026-06-20', 'prochaine' => '2026-06-24', 'statut' => 'active', 'programme' => 'Renforcement'],
    ['id' => 5, 'nom' => 'Sophie L.', 'objectif' => 'Perte de poids', 'seances_total' => 15, 'seances_restantes' => 10, 'progression' => 50, 'derniere_seance' => '2026-06-19', 'prochaine' => '2026-06-28', 'statut' => 'active', 'programme' => 'Fitness feminin'],
];

$agenda = [
    ['date' => '2026-06-25', 'heure' => '08:00', 'client' => 'Aicha B.', 'type' => 'coaching', 'duree' => '60min', 'lieu' => 'Salle GBO Cocody'],
    ['date' => '2026-06-25', 'heure' => '10:00', 'client' => 'Marie T.', 'type' => 'coaching', 'duree' => '45min', 'lieu' => 'Domicile'],
    ['date' => '2026-06-25', 'heure' => '14:00', 'client' => 'Fatou K.', 'type' => 'coaching', 'duree' => '60min', 'lieu' => 'Salle GBO Marcory'],
    ['date' => '2026-06-25', 'heure' => '16:00', 'client' => 'Reunion equipe', 'type' => 'perso', 'duree' => '30min', 'lieu' => 'En ligne'],
    ['date' => '2026-06-26', 'heure' => '09:00', 'client' => 'Aminata D.', 'type' => 'coaching', 'duree' => '60min', 'lieu' => 'Salle GBO Cocody'],
    ['date' => '2026-06-26', 'heure' => '11:00', 'client' => 'Sophie L.', 'type' => 'coaching', 'duree' => '60min', 'lieu' => 'En ligne'],
    ['date' => '2026-06-26', 'heure' => '15:00', 'client' => 'Formation continue', 'type' => 'perso', 'duree' => '120min', 'lieu' => 'Academy GBO'],
];

$historique = [
    ['date' => '2026-06-23', 'client' => 'Aicha B.', 'type' => 'Fitness feminin', 'duree' => '60min', 'note' => 'Bonne progression, +2kg perdus', 'evaluation' => 5],
    ['date' => '2026-06-22', 'client' => 'Marie T.', 'type' => 'Remise en forme', 'duree' => '45min', 'note' => 'Mobilite amelioree', 'evaluation' => 4],
    ['date' => '2026-06-21', 'client' => 'Fatou K.', 'type' => 'Postnatal', 'duree' => '60min', 'note' => 'Renforcement pelvien OK', 'evaluation' => 5],
    ['date' => '2026-06-20', 'client' => 'Aminata D.', 'type' => 'Renforcement', 'duree' => '60min', 'note' => 'Nouveau PR squat', 'evaluation' => 5],
    ['date' => '2026-06-19', 'client' => 'Sophie L.', 'type' => 'Fitness feminin', 'duree' => '60min', 'note' => 'Cardio en progres', 'evaluation' => 4],
];

$messages = [
    ['id' => 1, 'from' => 'Aicha B.', 'message' => 'Bonjour coach, je ne pourrai pas venir demain, famille urgente', 'time' => '14:30', 'unread' => true],
    ['id' => 2, 'from' => 'Admin GBO', 'message' => 'Votre planning de juillet est disponible', 'time' => '11:00', 'unread' => true],
    ['id' => 3, 'from' => 'Fatou K.', 'message' => 'Merci pour la seance d hier, je me sens mieux', 'time' => 'Hier', 'unread' => false],
];

function formatPrice($prix) {
    return number_format($prix, 0, ',', ' ') . ' FCFA';
}
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
        <span class="brand-text">GBO Coach</span>
      </a>
    </div>
    <button class="sidebar-toggle" onclick="toggleSidebar()">
      <svg viewBox="0 0 24 24" width="14" height="14" stroke="currentColor" fill="none" stroke-width="2.5"><polyline points="15 18 9 12 15 6"/></svg>
    </button>

    <nav>
      <div class="nav-section">
        <div class="nav-section-title">Principal</div>
        <ul class="sidebar-nav">
          <li class="nav-item">
            <a href="<?= $siteRoot ?>/pages/coach/dashboard_coach.php" class="nav-link active">
              <svg viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
              <span class="nav-text">Tableau de bord</span>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?= $siteRoot ?>/pages/coach/coach_clients.php" class="nav-link">
              <svg viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
              <span class="nav-text">Mes clients</span>
              <span class="nav-badge">24</span>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?= $siteRoot ?>/pages/coach/coach_agenda.php" class="nav-link">
              <svg viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
              <span class="nav-text">Mon agenda</span>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?= $siteRoot ?>/pages/coach/coach_historique.php" class="nav-link">
              <svg viewBox="0 0 24 24"><polyline points="1 4 1 10 7 10"/><path d="M3.51 15a9 9 0 1 0 2.13-9.36L1 10"/></svg>
              <span class="nav-text">Historique</span>
            </a>
          </li>
        </ul>
      </div>
      <div class="nav-section">
        <div class="nav-section-title">Performance</div>
        <ul class="sidebar-nav">
          <li class="nav-item">
            <a href="<?= $siteRoot ?>/pages/coach/coach_progression.php" class="nav-link">
              <svg viewBox="0 0 24 24"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
              <span class="nav-text">Progressions</span>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?= $siteRoot ?>/pages/coach/coach_revenus.php" class="nav-link">
              <svg viewBox="0 0 24 24"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
              <span class="nav-text">Mes revenus</span>
            </a>
          </li>
        </ul>
      </div>
      <div class="nav-section">
        <div class="nav-section-title">Communication</div>
        <ul class="sidebar-nav">
          <li class="nav-item">
            <a href="<?= $siteRoot ?>/pages/coach/coach_messages.php" class="nav-link">
              <svg viewBox="0 0 24 24"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7A8.38 8.38 0 0 1 4 11.5a8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>
              <span class="nav-text">Messagerie</span>
              <span class="nav-badge" style="background:var(--danger)">2</span>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?= $siteRoot ?>/pages/coach/coach_documents.php" class="nav-link">
              <svg viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
              <span class="nav-text">Documents</span>
            </a>
          </li>
        </ul>
      </div>
    </nav>

    <div class="sidebar-footer">
      <div class="user-card">
        <div class="user-avatar"><?= substr($coachName, 0, 1) ?></div>
        <div class="user-info">
          <div class="user-name"><?= htmlspecialchars($coachFullName) ?></div>
          <div class="user-role">Coach GBO Certifie</div>
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
          <input type="text" placeholder="Rechercher un client..." id="globalSearch">
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
        <a href="<?= $siteRoot ?>/pages/coach/dashboard_coach.php">Coach</a>
        <span class="sep">/</span>
        <span>Tableau de bord</span>
      </div>

      <div class="page-header">
        <h1>Bonjour, Coach <?= htmlspecialchars($coachName) ?></h1>
        <p>Voici votre activite du jour</p>
      </div>

      <div class="card-grid cols-4">
        <div class="card">
          <div class="card-header">
            <div>
              <div class="card-title">Clients actifs</div>
              <div class="card-subtitle">Sous votre responsabilite</div>
            </div>
            <div class="card-icon lime">
              <svg viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            </div>
          </div>
          <div class="card-value" data-count="<?= $stats['clients_actifs'] ?>">0</div>
          <div class="card-change up">
            <svg viewBox="0 0 24 24"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/></svg>
            +<?= $stats['nouveaux_clients'] ?> ce mois
          </div>
        </div>

        <div class="card">
          <div class="card-header">
            <div>
              <div class="card-title">Seances ce mois</div>
              <div class="card-subtitle">Realisees</div>
            </div>
            <div class="card-icon success">
              <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
            </div>
          </div>
          <div class="card-value" data-count="<?= $stats['seances_mois'] ?>">0</div>
          <div class="card-change up">
            <svg viewBox="0 0 24 24"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/></svg>
            Objectif: 40
          </div>
        </div>

        <div class="card">
          <div class="card-header">
            <div>
              <div class="card-title">Revenus du mois</div>
              <div class="card-subtitle">Commissions & seances</div>
            </div>
            <div class="card-icon warning">
              <svg viewBox="0 0 24 24"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
            </div>
          </div>
          <div class="card-value"><?= formatPrice($stats['revenus_mois']) ?></div>
          <div class="card-change up">
            <svg viewBox="0 0 24 24"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/></svg>
            +15% vs mois dernier
          </div>
        </div>

        <div class="card">
          <div class="card-header">
            <div>
              <div class="card-title">Satisfaction</div>
              <div class="card-subtitle">Moyenne clients</div>
            </div>
            <div class="card-icon info">
              <svg viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
            </div>
          </div>
          <div class="card-value"><?= $stats['taux_satisfaction'] ?>%</div>
          <div class="card-change up">
            <svg viewBox="0 0 24 24"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/></svg>
            Excellent
          </div>
        </div>
      </div>

      <div class="card-grid cols-2">
        <div class="table-container">
          <div class="table-header">
            <h3>Mes clients</h3>
            <a href="<?= $siteRoot ?>/pages/coach/coach_clients.php" class="btn btn-sm btn-ghost">Voir tout</a>
          </div>
          <table class="data-table" id="clientsTable">
            <thead>
              <tr>
                <th>Client</th>
                <th>Progression</th>
                <th>Seances</th>
                <th>Prochaine</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($clients as $cl): ?>
              <tr>
                <td>
                  <strong><?= htmlspecialchars($cl['nom']) ?></strong><br>
                  <small style="color:var(--muted)"><?= $cl['programme'] ?></small>
                </td>
                <td>
                  <div style="display:flex;align-items:center;gap:8px">
                    <div class="progress-bar" style="width:80px">
                      <div class="progress-bar-fill" style="width:<?= $cl['progression'] ?>%"></div>
                    </div>
                    <span style="font-size:12px;font-weight:700"><?= $cl['progression'] ?>%</span>
                  </div>
                </td>
                <td><?= $cl['seances_total'] - $cl['seances_restantes'] ?>/<?= $cl['seances_total'] ?></td>
                <td><?= date('d/m', strtotime($cl['prochaine'])) ?></td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>

        <div class="card">
          <div class="card-header">
            <div>
              <div class="card-title">Agenda du jour</div>
              <div class="card-subtitle"><?= date('l d F Y') ?></div>
            </div>
            <a href="<?= $siteRoot ?>/pages/coach/coach_agenda.php" class="btn btn-sm btn-ghost">Calendrier complet</a>
          </div>

          <div style="display:flex;flex-direction:column;gap:12px;margin-top:16px">
            <?php 
            $today = date('Y-m-d');
            $hasToday = false;
            foreach ($agenda as $evt): 
              if ($evt['date'] === $today): 
                $hasToday = true;
            ?>
            <div style="display:flex;align-items:center;gap:16px;padding:14px;background:var(--bg-light);border-radius:var(--radius-sm);border-left:3px solid var(--lime)">
              <div style="font-weight:700;font-size:14px;color:var(--lime);min-width:50px"><?= $evt['heure'] ?></div>
              <div style="flex:1">
                <div style="font-weight:600"><?= htmlspecialchars($evt['client']) ?></div>
                <div style="font-size:12px;color:var(--muted)"><?= $evt['duree'] ?> · <?= $evt['lieu'] ?></div>
              </div>
              <span class="status <?= $evt['type'] === 'coaching' ? 'active' : 'completed' ?>"><?= $evt['type'] === 'coaching' ? 'Coaching' : 'Perso' ?></span>
            </div>
            <?php endif; endforeach; ?>

            <?php if (!$hasToday): ?>
            <div style="text-align:center;padding:40px;color:var(--muted)">
              <svg viewBox="0 0 24 24" width="48" height="48" stroke="currentColor" fill="none" stroke-width="1.5" style="margin-bottom:12px;opacity:0.5"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
              <p>Aucune seance prevue aujourd'hui</p>
            </div>
            <?php endif; ?>
          </div>

          <div style="margin-top:20px;padding-top:20px;border-top:1px solid var(--line)">
            <div style="font-weight:600;margin-bottom:12px">Demain</div>
            <?php 
            $tomorrow = date('Y-m-d', strtotime('+1 day'));
            foreach ($agenda as $evt): 
              if ($evt['date'] === $tomorrow): 
            ?>
            <div style="display:flex;align-items:center;gap:16px;padding:10px 0;border-bottom:1px solid var(--line)">
              <div style="font-weight:600;font-size:13px;color:var(--muted);min-width:50px"><?= $evt['heure'] ?></div>
              <div style="flex:1;font-size:14px"><?= htmlspecialchars($evt['client']) ?></div>
              <span style="font-size:12px;color:var(--muted)"><?= $evt['duree'] ?></span>
            </div>
            <?php endif; endforeach; ?>
          </div>
        </div>
      </div>

      <div class="card-grid cols-2">
        <div class="table-container">
          <div class="table-header">
            <h3>Historique des seances</h3>
            <a href="<?= $siteRoot ?>/pages/coach/coach_historique.php" class="btn btn-sm btn-ghost">Voir tout</a>
          </div>
          <table class="data-table">
            <thead>
              <tr>
                <th>Date</th>
                <th>Client</th>
                <th>Type</th>
                <th>Note</th>
                <th>Eval</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($historique as $hist): ?>
              <tr>
                <td><?= date('d/m', strtotime($hist['date'])) ?></td>
                <td><strong><?= htmlspecialchars($hist['client']) ?></strong></td>
                <td><?= $hist['type'] ?></td>
                <td style="max-width:200px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis"><?= htmlspecialchars($hist['note']) ?></td>
                <td><?= str_repeat('★', $hist['evaluation']) ?></td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>

        <div class="card">
          <div class="card-header">
            <div>
              <div class="card-title">Messages</div>
              <div class="card-subtitle">Clients & Administration</div>
            </div>
            <a href="<?= $siteRoot ?>/pages/coach/coach_messages.php" class="btn btn-sm btn-primary">Nouveau</a>
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
      </div>

      <div class="card" style="border-left:3px solid var(--lime)">
        <div class="card-title">Mes objectifs du mois</div>
        <div class="card-grid cols-3" style="margin-top:16px;margin-bottom:0">
          <div>
            <div style="display:flex;justify-content:space-between;margin-bottom:8px">
              <span style="font-size:14px">Seances realisees</span>
              <span style="font-weight:700">32/40</span>
            </div>
            <div class="progress-bar">
              <div class="progress-bar-fill" style="width:80%"></div>
            </div>
          </div>
          <div>
            <div style="display:flex;justify-content:space-between;margin-bottom:8px">
              <span style="font-size:14px">Nouveaux clients</span>
              <span style="font-weight:700">3/5</span>
            </div>
            <div class="progress-bar">
              <div class="progress-bar-fill" style="width:60%"></div>
            </div>
          </div>
          <div>
            <div style="display:flex;justify-content:space-between;margin-bottom:8px">
              <span style="font-size:14px">Satisfaction client</span>
              <span style="font-weight:700">96%</span>
            </div>
            <div class="progress-bar">
              <div class="progress-bar-fill" style="width:96%"></div>
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
