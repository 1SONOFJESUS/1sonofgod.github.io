<?php
/**
 * DASHBOARD ADMIN — Gestion Contenu (Content Manager)
 * Articles, avis, commandes, stock, pages web
 */

session_start();

// === CONFIGURATION RACINE ===
$siteRoot = '/gbo_africa_group';
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin_content') {
    header("Location: {$siteRoot}/pages/login.php?role=admin");
    exit;
}

$pageTitle = 'Gestion Contenu — GBO AFRICA GROUP';
$adminName = $_SESSION['prenom'] ?? 'Manager';
$adminRole = 'Content Manager';

$articles = [
    ['id' => 1, 'titre' => 'Les bienfaits du fitness feminin', 'categorie' => 'Fitness', 'auteur' => 'Awa Kone', 'date' => '2026-06-20', 'statut' => 'publie', 'vues' => 1245],
    ['id' => 2, 'titre' => 'Nutrition pre-workout : guide complet', 'categorie' => 'Nutrition', 'auteur' => 'GBO Team', 'date' => '2026-06-18', 'statut' => 'publie', 'vues' => 892],
    ['id' => 3, 'titre' => 'Comment devenir coach certifie', 'categorie' => 'Entrepreneuriat', 'auteur' => 'Marc Bamba', 'date' => '2026-06-15', 'statut' => 'brouillon', 'vues' => 0],
    ['id' => 4, 'titre' => 'Programme postnatal : semaine 1', 'categorie' => 'Bien-etre', 'auteur' => 'Fatou Diallo', 'date' => '2026-06-10', 'statut' => 'publie', 'vues' => 2103],
    ['id' => 5, 'titre' => 'Nouveaux equipements GBO Shop', 'categorie' => 'Actualites', 'auteur' => 'GBO Team', 'date' => '2026-06-22', 'statut' => 'publie', 'vues' => 567],
];

$avis = [
    ['id' => 1, 'client' => 'Aicha B.', 'note' => 5, 'commentaire' => 'Un accompagnement qui change tout : securite, methode et resultats.', 'date' => '2026-06-20', 'statut' => 'approuve', 'page' => 'Accueil'],
    ['id' => 2, 'client' => 'Yao K.', 'note' => 5, 'commentaire' => 'Grace a GBO, j ai professionnalise mon activite.', 'date' => '2026-06-18', 'statut' => 'approuve', 'page' => 'Coach'],
    ['id' => 3, 'client' => 'M. Kone', 'note' => 4, 'commentaire' => 'Nos equipes sont plus engagees depuis le programme bien-etre.', 'date' => '2026-06-15', 'statut' => 'en_attente', 'page' => 'Corporate'],
    ['id' => 4, 'client' => 'Sarah L.', 'note' => 5, 'commentaire' => 'Excellent suivi postnatal, je me sens en pleine forme.', 'date' => '2026-06-12', 'statut' => 'approuve', 'page' => 'Fitness'],
];

$commandes = [
    ['id' => 'CMD-001', 'client' => 'Jean K.', 'produits' => 'Halteres GBO, T-shirt Performance', 'total' => 50000, 'date' => '2026-06-24', 'statut' => 'validee'],
    ['id' => 'CMD-002', 'client' => 'Marie T.', 'produits' => 'Proteine Whey, Shaker Pro', 'total' => 23500, 'date' => '2026-06-23', 'statut' => 'en_cours'],
    ['id' => 'CMD-003', 'client' => 'Aicha B.', 'produits' => 'Tapis Premium, Bandes Elastiques', 'total' => 31000, 'date' => '2026-06-22', 'statut' => 'expediee'],
    ['id' => 'CMD-004', 'client' => 'Marc D.', 'produits' => 'Gants de Boxe GBO, BCAA 2:1:1', 'total' => 50000, 'date' => '2026-06-21', 'statut' => 'en_cours'],
    ['id' => 'CMD-005', 'client' => 'Sarah L.', 'produits' => 'Legging GBO, Gourde Isotherme', 'total' => 24000, 'date' => '2026-06-20', 'statut' => 'livree'],
];

$stock = [
    ['id' => 1, 'produit' => 'Halteres GBO', 'categorie' => 'Equipements', 'quantite' => 15, 'seuil' => 5, 'statut' => 'ok'],
    ['id' => 2, 'produit' => 'T-shirt Performance', 'categorie' => 'Textile', 'quantite' => 8, 'seuil' => 10, 'statut' => 'critique'],
    ['id' => 3, 'produit' => 'Gourde Isotherme', 'categorie' => 'Accessoires', 'quantite' => 0, 'seuil' => 5, 'statut' => 'rupture'],
    ['id' => 4, 'produit' => 'Proteine Whey', 'categorie' => 'Nutrition', 'quantite' => 20, 'seuil' => 8, 'statut' => 'ok'],
    ['id' => 5, 'produit' => 'Tapis Premium', 'categorie' => 'Equipements', 'quantite' => 12, 'seuil' => 5, 'statut' => 'ok'],
    ['id' => 6, 'produit' => 'Legging GBO', 'categorie' => 'Textile', 'quantite' => 5, 'seuil' => 5, 'statut' => 'critique'],
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
        <span class="brand-text">GBO Content</span>
      </a>
    </div>
    <button class="sidebar-toggle" onclick="toggleSidebar()">
      <svg viewBox="0 0 24 24" width="14" height="14" stroke="currentColor" fill="none" stroke-width="2.5"><polyline points="15 18 9 12 15 6"/></svg>
    </button>

    <nav>
      <div class="nav-section">
        <div class="nav-section-title">Contenu</div>
        <ul class="sidebar-nav">
          <li class="nav-item">
            <a href="<?= $siteRoot ?>/pages/admin/dashboard_content.php" class="nav-link active">
              <svg viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
              <span class="nav-text">Tableau de bord</span>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?= $siteRoot ?>/pages/admin/content_articles.php" class="nav-link">
              <svg viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
              <span class="nav-text">Articles</span>
              <span class="nav-badge">5</span>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?= $siteRoot ?>/pages/admin/content_avis.php" class="nav-link">
              <svg viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
              <span class="nav-text">Avis clients</span>
              <span class="nav-badge" style="background:var(--warning)">1</span>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?= $siteRoot ?>/pages/admin/content_pages.php" class="nav-link">
              <svg viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><line x1="3" y1="9" x2="21" y2="9"/><line x1="9" y1="21" x2="9" y2="9"/></svg>
              <span class="nav-text">Pages web</span>
            </a>
          </li>
        </ul>
      </div>
      <div class="nav-section">
        <div class="nav-section-title">Boutique</div>
        <ul class="sidebar-nav">
          <li class="nav-item">
            <a href="<?= $siteRoot ?>/pages/admin/content_commandes.php" class="nav-link">
              <svg viewBox="0 0 24 24"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
              <span class="nav-text">Commandes</span>
              <span class="nav-badge" style="background:var(--warning)">2</span>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?= $siteRoot ?>/pages/admin/content_stock.php" class="nav-link">
              <svg viewBox="0 0 24 24"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/></svg>
              <span class="nav-text">Stock</span>
              <span class="nav-badge" style="background:var(--danger)">2</span>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?= $siteRoot ?>/pages/admin/content_produits.php" class="nav-link">
              <svg viewBox="0 0 24 24"><path d="M12 2l9 4.9V17L12 22l-9-4.9V7z"/></svg>
              <span class="nav-text">Produits</span>
            </a>
          </li>
        </ul>
      </div>
      <div class="nav-section">
        <div class="nav-section-title">Parametres</div>
        <ul class="sidebar-nav">
          <li class="nav-item">
            <a href="<?= $siteRoot ?>/pages/admin/content_seo.php" class="nav-link">
              <svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/><line x1="11" y1="8" x2="11" y2="14"/><line x1="8" y1="11" x2="14" y2="11"/></svg>
              <span class="nav-text">SEO & Meta</span>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?= $siteRoot ?>/pages/admin/content_newsletter.php" class="nav-link">
              <svg viewBox="0 0 24 24"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
              <span class="nav-text">Newsletter</span>
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
          <input type="text" placeholder="Rechercher..." id="globalSearch">
        </div>
      </div>
      <div class="header-actions">
        <button class="header-btn" onclick="showToast('Notifications rafraichies')">
          <svg viewBox="0 0 24 24"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
          <span class="notif-badge">4</span>
        </button>
        <a href="<?= $siteRoot ?>/includes/logout.php" class="header-btn" title="Deconnexion">
          <svg viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
        </a>
      </div>
    </header>

    <div class="page-content">
      <div class="breadcrumb">
        <a href="<?= $siteRoot ?>/pages/admin/dashboard_content.php">Content Manager</a>
        <span class="sep">/</span>
        <span>Tableau de bord</span>
      </div>

      <div class="page-header">
        <h1>Gestion du contenu GBO</h1>
        <p>Articles, avis, commandes et stock</p>
      </div>

      <div class="card-grid cols-4">
        <div class="card">
          <div class="card-header">
            <div>
              <div class="card-title">Articles publies</div>
              <div class="card-subtitle">Blog & pages</div>
            </div>
            <div class="card-icon lime">
              <svg viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
            </div>
          </div>
          <div class="card-value" data-count="24">0</div>
          <div class="card-change up">
            <svg viewBox="0 0 24 24"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/></svg>
            +3 cette semaine
          </div>
        </div>

        <div class="card">
          <div class="card-header">
            <div>
              <div class="card-title">Avis clients</div>
              <div class="card-subtitle">Total collectes</div>
            </div>
            <div class="card-icon success">
              <svg viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
            </div>
          </div>
          <div class="card-value" data-count="47">0</div>
          <div class="card-change up">
            <svg viewBox="0 0 24 24"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/></svg>
            1 en attente
          </div>
        </div>

        <div class="card">
          <div class="card-header">
            <div>
              <div class="card-title">Commandes</div>
              <div class="card-subtitle">Ce mois</div>
            </div>
            <div class="card-icon warning">
              <svg viewBox="0 0 24 24"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/></svg>
            </div>
          </div>
          <div class="card-value" data-count="18">0</div>
          <div class="card-change up">
            <svg viewBox="0 0 24 24"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/></svg>
            2 en cours
          </div>
        </div>

        <div class="card">
          <div class="card-header">
            <div>
              <div class="card-title">Alertes stock</div>
              <div class="card-subtitle">Produits concernes</div>
            </div>
            <div class="card-icon danger">
              <svg viewBox="0 0 24 24"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
            </div>
          </div>
          <div class="card-value" data-count="2">0</div>
          <div class="card-change down">
            <svg viewBox="0 0 24 24"><polyline points="23 18 13.5 8.5 8.5 13.5 1 6"/><polyline points="17 18 23 18 23 12"/></svg>
            1 rupture
          </div>
        </div>
      </div>

      <div class="tabs" id="contentTabs">
        <button class="tab-btn active" data-tab="articles">Articles</button>
        <button class="tab-btn" data-tab="avis">Avis</button>
        <button class="tab-btn" data-tab="commandes">Commandes</button>
        <button class="tab-btn" data-tab="stock">Stock</button>
      </div>

      <div class="tab-panel active" data-panel="articles">
        <div class="table-container">
          <div class="table-header">
            <h3>Gestion des articles</h3>
            <button class="btn btn-sm btn-primary" onclick="openModal('modalArticle')">
              <svg viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
              Nouvel article
            </button>
          </div>
          <table class="data-table" id="articlesTable">
            <thead>
              <tr>
                <th>Titre</th>
                <th>Categorie</th>
                <th>Auteur</th>
                <th>Date</th>
                <th>Vues</th>
                <th>Statut</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($articles as $art): ?>
              <tr>
                <td><strong><?= htmlspecialchars($art['titre']) ?></strong></td>
                <td><?= $art['categorie'] ?></td>
                <td><?= htmlspecialchars($art['auteur']) ?></td>
                <td><?= date('d/m/Y', strtotime($art['date'])) ?></td>
                <td><?= number_format($art['vues'], 0, ',', ' ') ?></td>
                <td><span class="status <?= $art['statut'] === 'publie' ? 'active' : 'pending' ?>"><?= $art['statut'] === 'publie' ? 'Publie' : 'Brouillon' ?></span></td>
                <td>
                  <button class="btn btn-xs btn-ghost" onclick="showToast('Article modifie')">Modifier</button>
                  <button class="btn btn-xs btn-danger" onclick="confirmAction('Supprimer cet article ?', () => showToast('Article supprime'))">Supprimer</button>
                </td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>

      <div class="tab-panel" data-panel="avis" style="display:none">
        <div class="table-container">
          <div class="table-header">
            <h3>Gestion des avis clients</h3>
            <button class="btn btn-sm btn-primary" onclick="openModal('modalAvis')">
              <svg viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
              Ajouter un avis
            </button>
          </div>
          <table class="data-table" id="avisTable">
            <thead>
              <tr>
                <th>Client</th>
                <th>Note</th>
                <th>Commentaire</th>
                <th>Page</th>
                <th>Date</th>
                <th>Statut</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($avis as $av): ?>
              <tr>
                <td><strong><?= htmlspecialchars($av['client']) ?></strong></td>
                <td><?= str_repeat('★', $av['note']) . str_repeat('☆', 5 - $av['note']) ?></td>
                <td><?= htmlspecialchars($av['commentaire']) ?></td>
                <td><?= $av['page'] ?></td>
                <td><?= date('d/m/Y', strtotime($av['date'])) ?></td>
                <td><span class="status <?= $av['statut'] === 'approuve' ? 'active' : 'pending' ?>"><?= $av['statut'] === 'approuve' ? 'Approuve' : 'En attente' ?></span></td>
                <td>
                  <?php if ($av['statut'] === 'en_attente'): ?>
                  <button class="btn btn-xs btn-primary" onclick="showToast('Avis approuve')">Approuver</button>
                  <?php endif; ?>
                  <button class="btn btn-xs btn-danger" onclick="confirmAction('Retirer cet avis ?', () => showToast('Avis retire'))">Retirer</button>
                </td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>

      <div class="tab-panel" data-panel="commandes" style="display:none">
        <div class="table-container">
          <div class="table-header">
            <h3>Suivi des commandes</h3>
            <div style="display:flex;gap:8px">
              <select class="form-select" style="width:auto;min-width:140px">
                <option>Tous les statuts</option>
                <option>En cours</option>
                <option>Validee</option>
                <option>Expediee</option>
                <option>Livree</option>
              </select>
            </div>
          </div>
          <table class="data-table" id="commandesTable">
            <thead>
              <tr>
                <th>N Commande</th>
                <th>Client</th>
                <th>Produits</th>
                <th>Total</th>
                <th>Date</th>
                <th>Statut</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($commandes as $cmd): ?>
              <tr>
                <td><strong><?= $cmd['id'] ?></strong></td>
                <td><?= htmlspecialchars($cmd['client']) ?></td>
                <td><?= htmlspecialchars($cmd['produits']) ?></td>
                <td><?= formatPrice($cmd['total']) ?></td>
                <td><?= date('d/m/Y', strtotime($cmd['date'])) ?></td>
                <td>
                  <span class="status <?= $cmd['statut'] === 'livree' ? 'completed' : ($cmd['statut'] === 'expediee' ? 'active' : ($cmd['statut'] === 'validee' ? 'active' : 'pending')) ?>">
                    <?= ucfirst($cmd['statut']) ?>
                  </span>
                </td>
                <td>
                  <select class="form-select" style="width:auto;min-width:120px;font-size:12px" onchange="showToast('Statut mis a jour')">
                    <option value="en_cours" <?= $cmd['statut'] === 'en_cours' ? 'selected' : '' ?>>En cours</option>
                    <option value="validee" <?= $cmd['statut'] === 'validee' ? 'selected' : '' ?>>Validee</option>
                    <option value="expediee" <?= $cmd['statut'] === 'expediee' ? 'selected' : '' ?>>Expediee</option>
                    <option value="livree" <?= $cmd['statut'] === 'livree' ? 'selected' : '' ?>>Livree</option>
                  </select>
                </td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>

      <div class="tab-panel" data-panel="stock" style="display:none">
        <div class="table-container">
          <div class="table-header">
            <h3>Gestion des stocks</h3>
            <button class="btn btn-sm btn-primary" onclick="openModal('modalStock')">
              <svg viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
              Ajouter produit
            </button>
          </div>
          <table class="data-table" id="stockTable">
            <thead>
              <tr>
                <th>Produit</th>
                <th>Categorie</th>
                <th>Quantite</th>
                <th>Seuil alerte</th>
                <th>Statut</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($stock as $prod): ?>
              <tr>
                <td><strong><?= htmlspecialchars($prod['produit']) ?></strong></td>
                <td><?= $prod['categorie'] ?></td>
                <td><?= $prod['quantite'] ?></td>
                <td><?= $prod['seuil'] ?></td>
                <td>
                  <span class="status <?= $prod['statut'] === 'ok' ? 'active' : ($prod['statut'] === 'critique' ? 'warning' : 'cancelled') ?>">
                    <?= $prod['statut'] === 'ok' ? 'OK' : ($prod['statut'] === 'critique' ? 'Critique' : 'Rupture') ?>
                  </span>
                </td>
                <td>
                  <button class="btn btn-xs btn-ghost" onclick="showToast('Stock modifie')">Modifier</button>
                  <button class="btn btn-xs btn-primary" onclick="showToast('Reapprovisionnement lance')">Reappro</button>
                </td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </main>
</div>

<div class="modal-overlay" id="modalArticle">
  <div class="modal">
    <div class="modal-header">
      <h3>Nouvel article</h3>
      <button class="modal-close" onclick="closeModal('modalArticle')">
        <svg viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
      </button>
    </div>
    <div class="modal-body">
      <div class="form-group">
        <label class="form-label">Titre</label>
        <input type="text" class="form-input" placeholder="Titre de l'article">
      </div>
      <div class="form-row">
        <div class="form-group">
          <label class="form-label">Categorie</label>
          <select class="form-select">
            <option>Fitness</option>
            <option>Nutrition</option>
            <option>Bien-etre</option>
            <option>Entrepreneuriat sportif</option>
            <option>Actualites GBO</option>
          </select>
        </div>
        <div class="form-group">
          <label class="form-label">Auteur</label>
          <select class="form-select">
            <option>GBO Team</option>
            <option>Awa Kone</option>
            <option>Marc Bamba</option>
            <option>Fatou Diallo</option>
          </select>
        </div>
      </div>
      <div class="form-group">
        <label class="form-label">Contenu</label>
        <textarea class="form-textarea" placeholder="Contenu de l'article..."></textarea>
      </div>
    </div>
    <div class="modal-footer">
      <button class="btn btn-ghost" onclick="closeModal('modalArticle')">Annuler</button>
      <button class="btn btn-primary" onclick="closeModal('modalArticle'); showToast('Article publie avec succes')">Publier</button>
    </div>
  </div>
</div>

<div class="modal-overlay" id="modalAvis">
  <div class="modal">
    <div class="modal-header">
      <h3>Ajouter un avis</h3>
      <button class="modal-close" onclick="closeModal('modalAvis')">
        <svg viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
      </button>
    </div>
    <div class="modal-body">
      <div class="form-row">
        <div class="form-group">
          <label class="form-label">Client</label>
          <input type="text" class="form-input" placeholder="Nom du client">
        </div>
        <div class="form-group">
          <label class="form-label">Note</label>
          <select class="form-select">
            <option>5 etoiles</option>
            <option>4 etoiles</option>
            <option>3 etoiles</option>
            <option>2 etoiles</option>
            <option>1 etoile</option>
          </select>
        </div>
      </div>
      <div class="form-group">
        <label class="form-label">Commentaire</label>
        <textarea class="form-textarea" placeholder="Commentaire..."></textarea>
      </div>
      <div class="form-group">
        <label class="form-label">Page d'affichage</label>
        <select class="form-select">
          <option>Accueil</option>
          <option>Fitness</option>
          <option>Coach</option>
          <option>Corporate</option>
        </select>
      </div>
    </div>
    <div class="modal-footer">
      <button class="btn btn-ghost" onclick="closeModal('modalAvis')">Annuler</button>
      <button class="btn btn-primary" onclick="closeModal('modalAvis'); showToast('Avis ajoute')">Ajouter</button>
    </div>
  </div>
</div>

<div class="modal-overlay" id="modalStock">
  <div class="modal">
    <div class="modal-header">
      <h3>Ajouter un produit</h3>
      <button class="modal-close" onclick="closeModal('modalStock')">
        <svg viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
      </button>
    </div>
    <div class="modal-body">
      <div class="form-row">
        <div class="form-group">
          <label class="form-label">Nom du produit</label>
          <input type="text" class="form-input" placeholder="Nom">
        </div>
        <div class="form-group">
          <label class="form-label">Categorie</label>
          <select class="form-select">
            <option>Equipements</option>
            <option>Textile</option>
            <option>Accessoires</option>
            <option>Nutrition</option>
          </select>
        </div>
      </div>
      <div class="form-row">
        <div class="form-group">
          <label class="form-label">Quantite</label>
          <input type="number" class="form-input" placeholder="0">
        </div>
        <div class="form-group">
          <label class="form-label">Seuil d'alerte</label>
          <input type="number" class="form-input" placeholder="5">
        </div>
      </div>
    </div>
    <div class="modal-footer">
      <button class="btn btn-ghost" onclick="closeModal('modalStock')">Annuler</button>
      <button class="btn btn-primary" onclick="closeModal('modalStock'); showToast('Produit ajoute au stock')">Ajouter</button>
    </div>
  </div>
</div>

<script src="<?= $siteRoot ?>/assets/js/dashboard.js"></script>
<script>
document.querySelectorAll('#contentTabs .tab-btn').forEach(btn => {
  btn.addEventListener('click', () => {
    const tab = btn.dataset.tab;
    document.querySelectorAll('#contentTabs .tab-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    document.querySelectorAll('.tab-panel').forEach(p => {
      p.style.display = p.dataset.panel === tab ? 'block' : 'none';
      p.classList.toggle('active', p.dataset.panel === tab);
    });
  });
});

document.getElementById('globalSearch').addEventListener('input', function() {
  const term = this.value.toLowerCase();
  const activePanel = document.querySelector('.tab-panel.active');
  if (activePanel) {
    activePanel.querySelectorAll('.data-table tbody tr').forEach(row => {
      row.style.display = row.textContent.toLowerCase().includes(term) ? '' : 'none';
    });
  }
});

if (window.innerWidth <= 1024) {
  document.getElementById('mobileMenuBtn').style.display = 'flex';
}
</script>

</body>
</html>
