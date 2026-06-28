<?php
/**
 * DASHBOARD ADMIN — Gestion Generale (Super Admin)
 * Suivi clients, progression coachs, messagerie
 */

session_start();

$siteRoot = '/gbo_africa_group';
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: {$siteRoot}/pages/login.php?role=admin");
    exit;
}

$pageTitle = 'Dashboard Administrateur — GBO AFRICA GROUP';
$adminName = $_SESSION['prenom'] ?? 'Admin';
$adminRole = 'Super Administrateur';

$stats = [
    'total_clients' => 248,
    'total_coachs' => 18,
    'sessions_mois' => 156,
    'revenus_mois' => 4850000,
    'nouveaux_clients' => 12,
    'taux_conversion' => 68
];

$coachs = [
    ['id' => 1, 'nom' => 'Awa Kone', 'specialite' => 'Fitness feminin', 'clients' => 24, 'sessions' => 89, 'revenus' => 1250000, 'satisfaction' => 96, 'statut' => 'active'],
    ['id' => 2, 'nom' => 'Yao Kouassi', 'specialite' => 'Performance', 'clients' => 18, 'sessions' => 67, 'revenus' => 980000, 'satisfaction' => 94, 'statut' => 'active'],
    ['id' => 3, 'nom' => 'Marc Bamba', 'specialite' => 'Renforcement', 'clients' => 21, 'sessions' => 78, 'revenus' => 1100000, 'satisfaction' => 92, 'statut' => 'active'],
    ['id' => 4, 'nom' => 'Fatou Diallo', 'specialite' => 'Prenatal/Postnatal', 'clients' => 15, 'sessions' => 45, 'revenus' => 720000, 'satisfaction' => 98, 'statut' => 'active'],
    ['id' => 5, 'nom' => 'Kofi Mensah', 'specialite' => 'Remise en forme', 'clients' => 12, 'sessions' => 38, 'revenus' => 540000, 'satisfaction' => 90, 'statut' => 'inactive'],
];

$clients = [
    ['id' => 1, 'nom' => 'Aicha B.', 'coach' => 'Awa Kone', 'objectif' => 'Perte de poids', 'seances' => 12, 'progression' => 75, 'statut' => 'active', 'abonnement' => 'Premium', 'fin_abo' => '2026-08-15'],
    ['id' => 2, 'nom' => 'Marc D.', 'coach' => 'Yao Kouassi', 'objectif' => 'Prise de masse', 'seances' => 8, 'progression' => 45, 'statut' => 'active', 'abonnement' => 'Standard', 'fin_abo' => '2026-07-20'],
    ['id' => 3, 'nom' => 'Sarah L.', 'coach' => 'Fatou Diallo', 'objectif' => 'Postnatal', 'seances' => 6, 'progression' => 60, 'statut' => 'active', 'abonnement' => 'Premium', 'fin_abo' => '2026-09-01'],
    ['id' => 4, 'nom' => 'Jean K.', 'coach' => 'Marc Bamba', 'objectif' => 'Renforcement', 'seances' => 15, 'progression' => 80, 'statut' => 'active', 'abonnement' => 'VIP', 'fin_abo' => '2026-12-31'],
    ['id' => 5, 'nom' => 'Marie T.', 'coach' => 'Awa Kone', 'objectif' => 'Fitness senior', 'seances' => 4, 'progression' => 30, 'statut' => 'pending', 'abonnement' => 'Decouverte', 'fin_abo' => '2026-07-01'],
];

$messages = [
    ['id' => 1, 'from' => 'Awa Kone', 'role' => 'coach', 'message' => 'Demande de conges du 15 au 20 juillet', 'time' => '10:30', 'unread' => true],
    ['id' => 2, 'from' => 'Aicha B.', 'role' => 'client', 'message' => 'Probleme de paiement pour mon renouvellement', 'time' => '09:15', 'unread' => true],
    ['id' => 3, 'from' => 'Yao Kouassi', 'role' => 'coach', 'message' => 'Nouveau client a integrer dans mon planning', 'time' => 'Hier', 'unread' => false],
    ['id' => 4, 'from' => 'Fatou Diallo', 'role' => 'coach', 'message' => 'Mise a jour du programme prenatal', 'time' => 'Hier', 'unread' => false],
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
        <span class="brand-text">GBO Admin</span>
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
            <a href="<?= $siteRoot ?>/pages/admin/dashboard_admin.php" class="nav-link active">
              <svg viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
              <span class="nav-text">Tableau de bord</span>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?= $siteRoot ?>/pages/admin/admin_clients.php" class="nav-link">
              <svg viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
              <span class="nav-text">Clients</span>
              <span class="nav-badge">248</span>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?= $siteRoot ?>/pages/admin/admin_coachs.php" class="nav-link">
              <svg viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
              <span class="nav-text">Coachs</span>
              <span class="nav-badge">18</span>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?= $siteRoot ?>/pages/admin/admin_agenda.php" class="nav-link">
              <svg viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
              <span class="nav-text">Agenda global</span>
            </a>
          </li>
        </ul>
      </div>
      <div class="nav-section">
        <div class="nav-section-title">Communication</div>
        <ul class="sidebar-nav">
          <li class="nav-item">
            <a href="<?= $siteRoot ?>/pages/admin/admin_messages.php" class="nav-link">
              <svg viewBox="0 0 24 24"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7A8.38 8.38 0 0 1 4 11.5a8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>
              <span class="nav-text">Messagerie</span>
              <span class="nav-badge" style="background:var(--danger)">2</span>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?= $siteRoot ?>/pages/admin/admin_notifications.php" class="nav-link">
              <svg viewBox="0 0 24 24"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
              <span class="nav-text">Notifications</span>
            </a>
          </li>
        </ul>
      </div>
      <div class="nav-section">
        <div class="nav-section-title">Rapports</div>
        <ul class="sidebar-nav">
          <li class="nav-item">
            <a href="<?= $siteRoot ?>/pages/admin/admin_rapports.php" class="nav-link">
              <svg viewBox="0 0 24 24"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
              <span class="nav-text">Statistiques</span>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?= $siteRoot ?>/pages/admin/admin_finances.php" class="nav-link">
              <svg viewBox="0 0 24 24"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
              <span class="nav-text">Finances</span>
            </a>
          </li>
        </ul>
      </div>
    </nav>

    <div class="sidebar-footer">
      <div class="user-card">
        <div class="user-avatar"><?= substr($adminName, 0, 1) ?></div>
        <div class="user-info">
          <div class="user-name"><?= htmlspecialchars($adminName) ?></div>
          <div class="user-role"><?= $adminRole ?></div>
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
          <input type="text" placeholder="Rechercher un client, coach..." id="globalSearch">
        </div>
      </div>
      <div class="header-actions">
        <button class="header-btn" onclick="showToast('Notifications rafraichies')">
          <svg viewBox="0 0 24 24"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
          <span class="notif-badge">3</span>
        </button>
        <a href="<?= $siteRoot ?>/includes/logout.php" class="header-btn" title="Deconnexion">
          <svg viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
        </a>
      </div>
    </header>

    <div class="page-content">
      <div class="breadcrumb">
        <a href="<?= $siteRoot ?>/pages/admin/dashboard_admin.php">Admin</a>
        <span class="sep">/</span>
        <span>Tableau de bord</span>
      </div>

      <div class="page-header">
        <h1>Bonjour, <?= htmlspecialchars($adminName) ?> 👋</h1>
        <p>Vue d'ensemble de l'activite GBO AFRICA GROUP</p>
      </div>

      <div class="card-grid cols-4">
        <div class="card">
          <div class="card-header">
            <div>
              <div class="card-title">Clients actifs</div>
              <div class="card-subtitle">Total inscrits</div>
            </div>
            <div class="card-icon lime">
              <svg viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            </div>
          </div>
          <div class="card-value" data-count="<?= $stats['total_clients'] ?>">0</div>
          <div class="card-change up">
            <svg viewBox="0 0 24 24"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/></svg>
            +<?= $stats['nouveaux_clients'] ?> ce mois
          </div>
        </div>

        <div class="card">
          <div class="card-header">
            <div>
              <div class="card-title">Coachs actifs</div>
              <div class="card-subtitle">Reseau GBO</div>
            </div>
            <div class="card-icon success">
              <svg viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            </div>
          </div>
          <div class="card-value" data-count="<?= $stats['total_coachs'] ?>">0</div>
          <div class="card-change up">
            <svg viewBox="0 0 24 24"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/></svg>
            2 nouveaux ce mois
          </div>
        </div>

        <div class="card">
          <div class="card-header">
            <div>
              <div class="card-title">Seances ce mois</div>
              <div class="card-subtitle">Tous poles confondus</div>
            </div>
            <div class="card-icon warning">
              <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
            </div>
          </div>
          <div class="card-value" data-count="<?= $stats['sessions_mois'] ?>">0</div>
          <div class="card-change up">
            <svg viewBox="0 0 24 24"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/></svg>
            +12% vs mois dernier
          </div>
        </div>

        <div class="card">
          <div class="card-header">
            <div>
              <div class="card-title">Revenus du mois</div>
              <div class="card-subtitle">Chiffre d'affaires</div>
            </div>
            <div class="card-icon info">
              <svg viewBox="0 0 24 24"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
            </div>
          </div>
          <div class="card-value"><?= formatPrice($stats['revenus_mois']) ?></div>
          <div class="card-change up">
            <svg viewBox="0 0 24 24"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/></svg>
            +8.5% vs mois dernier
          </div>
        </div>
      </div>

      <div class="card-grid cols-2">
        <div class="table-container">
          <div class="table-header">
            <h3>Progression des coachs</h3>
            <a href="<?= $siteRoot ?>/pages/admin/admin_coachs.php" class="btn btn-sm btn-ghost">Voir tout</a>
          </div>
          <table class="data-table" id="coachTable">
            <thead>
              <tr>
                <th>Coach</th>
                <th>Clients</th>
                <th>Seances</th>
                <th>Satisfaction</th>
                <th>Revenus</th>
                <th>Statut</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($coachs as $coach): ?>
              <tr>
                <td><strong><?= htmlspecialchars($coach['nom']) ?></strong><br><small style="color:var(--muted)"><?= $coach['specialite'] ?></small></td>
                <td><?= $coach['clients'] ?></td>
                <td><?= $coach['sessions'] ?></td>
                <td>
                  <div style="display:flex;align-items:center;gap:8px">
                    <div class="progress-bar" style="width:80px">
                      <div class="progress-bar-fill" style="width:<?= $coach['satisfaction'] ?>%"></div>
                    </div>
                    <span style="font-size:12px;font-weight:700"><?= $coach['satisfaction'] ?>%</span>
                  </div>
                </td>
                <td><?= formatPrice($coach['revenus']) ?></td>
                <td><span class="status <?= $coach['statut'] ?>"><?= $coach['statut'] === 'active' ? 'Actif' : 'Inactif' ?></span></td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>

        <div class="table-container">
          <div class="table-header">
            <h3>Suivi des clients</h3>
            <a href="<?= $siteRoot ?>/pages/admin/admin_clients.php" class="btn btn-sm btn-ghost">Voir tout</a>
          </div>
          <table class="data-table" id="clientTable">
            <thead>
              <tr>
                <th>Client</th>
                <th>Coach</th>
                <th>Progression</th>
                <th>Abonnement</th>
                <th>Fin abo</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($clients as $client): ?>
              <tr>
                <td><strong><?= htmlspecialchars($client['nom']) ?></strong><br><small style="color:var(--muted)"><?= $client['objectif'] ?></small></td>
                <td><?= htmlspecialchars($client['coach']) ?></td>
                <td>
                  <div style="display:flex;align-items:center;gap:8px">
                    <div class="progress-bar" style="width:80px">
                      <div class="progress-bar-fill" style="width:<?= $client['progression'] ?>%"></div>
                    </div>
                    <span style="font-size:12px;font-weight:700"><?= $client['progression'] ?>%</span>
                  </div>
                </td>
                <td><span class="status <?= $client['abonnement'] === 'VIP' ? 'active' : ($client['abonnement'] === 'Premium' ? 'completed' : 'pending') ?>"><?= $client['abonnement'] ?></span></td>
                <td><?= date('d/m/Y', strtotime($client['fin_abo'])) ?></td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>

      <div class="card">
        <div class="card-header">
          <div>
            <div class="card-title">Messages recents</div>
            <div class="card-subtitle">Communication avec coachs et clients</div>
          </div>
          <a href="<?= $siteRoot ?>/pages/admin/admin_messages.php" class="btn btn-sm btn-primary">Nouveau message</a>
        </div>
        <div class="chat-container" style="height:400px;margin-top:16px">
          <div class="chat-sidebar">
            <div class="chat-search">
              <input type="text" placeholder="Rechercher...">
            </div>
            <?php foreach ($messages as $msg): ?>
            <div class="chat-contact <?= $msg['unread'] ? 'active' : '' ?>">
              <div class="chat-contact-avatar"><?= substr($msg['from'], 0, 1) ?></div>
              <div class="chat-contact-info">
                <div class="chat-contact-name">
                  <?= htmlspecialchars($msg['from']) ?>
                  <span class="chat-contact-time"><?= $msg['time'] ?></span>
                </div>
                <div class="chat-contact-preview"><?= htmlspecialchars($msg['message']) ?></div>
              </div>
            </div>
            <?php endforeach; ?>
          </div>
          <div class="chat-main">
            <div class="chat-header">
              <div style="display:flex;align-items:center;gap:12px">
                <div class="chat-contact-avatar">A</div>
                <div>
                  <div style="font-weight:600">Awa Kone</div>
                  <div style="font-size:12px;color:var(--muted)">Coach · En ligne</div>
                </div>
              </div>
            </div>
            <div class="chat-messages">
              <div class="message received">
                Bonjour, j'aimerais demander des conges du 15 au 20 juillet pour raisons personnelles.
                <div class="message-time">10:30</div>
              </div>
              <div class="message sent">
                Bonjour Awa, c'est note. Je vais verifier la couverture de tes clients et te confirmer d'ici demain.
                <div class="message-time">10:35</div>
              </div>
            </div>
            <div class="chat-input-area">
              <input type="text" placeholder="Ecrire un message...">
              <button class="btn btn-primary btn-sm">Envoyer</button>
            </div>
          </div>
        </div>
      </div>

      <div class="card-grid cols-3">
        <div class="card" style="border-left:3px solid var(--warning)">
          <div class="card-title">Abonnements a renouveler</div>
          <p style="color:var(--muted);font-size:14px;margin:12px 0">3 clients ont leur abonnement qui expire dans moins de 7 jours.</p>
          <a href="<?= $siteRoot ?>/pages/admin/admin_clients.php?filter=expiring" class="btn btn-sm btn-ghost">Voir la liste</a>
        </div>
        <div class="card" style="border-left:3px solid var(--danger)">
          <div class="card-title">Paiements en attente</div>
          <p style="color:var(--muted);font-size:14px;margin:12px 0">2 paiements sont en attente de validation pour un total de 450 000 FCFA.</p>
          <a href="<?= $siteRoot ?>/pages/admin/admin_finances.php?filter=pending" class="btn btn-sm btn-ghost">Gerer</a>
        </div>
        <div class="card" style="border-left:3px solid var(--success)">
          <div class="card-title">Nouvelles inscriptions</div>
          <p style="color:var(--muted);font-size:14px;margin:12px 0">5 nouveaux clients cette semaine. 3 en attente d'attribution coach.</p>
          <a href="<?= $siteRoot ?>/pages/admin/admin_clients.php?filter=new" class="btn btn-sm btn-ghost">Attribuer</a>
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
