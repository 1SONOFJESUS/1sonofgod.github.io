<?php
/**
 * Header commun GBÔ AFRICA GROUP — Version avec panier
 */
$siteRoot = '/gbo_africa_group';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>GBÔ AFRICA GROUP — Sport · Fitness · Bien-être</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Archivo:wght@500;600;700;800;900&family=Manrope:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?= $siteRoot ?>/assets/css/style.css">
<link rel="stylesheet" href="<?= $siteRoot ?>/assets/css/shop.css">
</head>
<body>

<!-- HEADER -->
<header>
    <div class="wrap nav">
        <a class="brand" href="<?= $siteRoot ?>/index.php">
            <img src="<?= $siteRoot ?>/assets/images/logo.png" alt="GBÔ AFRICA GROUP" class="logo-img" loading="eager" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
            <span class="logo-fallback" style="display:none;">
                <span class="logo-text">GBÔ <span>AFRICA</span></span>
                <span class="logo-sub">GROUP</span>
            </span>
        </a>

        <nav class="menu" id="menu">
            <a href="<?= $siteRoot ?>/index.php?page=home">Accueil</a>
            <a href="<?= $siteRoot ?>/index.php?page=fitness">Fitness</a>
            <a href="<?= $siteRoot ?>/index.php?page=coach">Coach</a>
            <a href="<?= $siteRoot ?>/index.php?page=academy">Academy</a>
            <a href="<?= $siteRoot ?>/index.php?page=shop">Shop</a>
            <a href="<?= $siteRoot ?>/index.php?page=apropos">À propos</a>
            <a href="<?= $siteRoot ?>/index.php?page=blog">Blog</a>
            <a href="<?= $siteRoot ?>/index.php?page=contact">Contact</a>
        </nav>

        <div class="nav-cta">
            <!-- PANIER -->
            <a href="<?= $siteRoot ?>/pages/cart.php" class="cart-btn" id="cartBtn">
                <svg viewBox="0 0 24 24" width="20" height="20" stroke="currentColor" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="9" cy="21" r="1"></circle>
                    <circle cx="20" cy="21" r="1"></circle>
                    <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                </svg>
                <span class="cart-label">Panier</span>
                <span class="cart-count" id="cartCount">0</span>
            </a>

            <a class="btn btn-primary btn-sm" href="<?= $siteRoot ?>/index.php?page=fitness">Réserver un coaching</a>

            <!-- BOUTON CONNEXION MULTI-RÔLES -->
            <?php
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            $isLoggedIn = false;
            $userRole = null;
            $userName = '';
            $dashboardUrl = '';

            if (isset($_SESSION['user_id'])) {
                $isLoggedIn = true;
                $userRole = $_SESSION['role'] ?? null;
                $userName = $_SESSION['prenom'] ?? $_SESSION['nom'] ?? 'Utilisateur';

                switch ($userRole) {
                    case 'admin':
                        $dashboardUrl = $siteRoot . '/pages/admin/dashboard_admin.php';
                        $roleLabel = 'Administrateur';
                        $roleIcon = '<svg viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>';
                        break;
                    case 'coach':
                        $dashboardUrl = $siteRoot . '/pages/coach/dashboard_coach.php';
                        $roleLabel = 'Coach GBÔ';
                        $roleIcon = '<svg viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>';
                        break;
                    case 'client':
                    default:
                        $dashboardUrl = $siteRoot . '/pages/client/dashboard_client.php';
                        $roleLabel = 'Espace Client';
                        $roleIcon = '<svg viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>';
                        break;
                }
            }
            ?>

            <div class="login-dropdown" id="loginDropdown">
                <?php if ($isLoggedIn): ?>
                    <button class="login-btn logged-in" onclick="toggleLogin()">
                        <?php echo $roleIcon; ?>
                        <span class="user-name"><?= htmlspecialchars($userName) ?></span>
                        <span class="role-badge"><?= htmlspecialchars($roleLabel) ?></span>
                    </button>
                    <div class="login-menu" id="loginMenu">
                        <div class="user-header">
                            <?php echo $roleIcon; ?>
                            <div>
                                <strong><?= htmlspecialchars($userName) ?></strong>
                                <span class="role-text"><?= htmlspecialchars($roleLabel) ?></span>
                            </div>
                        </div>
                        <div class="sep"></div>
                        <a href="<?= $dashboardUrl ?>">
                            <svg viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                            Mon tableau de bord
                        </a>
                        <a href="<?= $siteRoot ?>/includes/profil.php">
                            <svg viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                            Mon profil
                        </a>
                        <div class="sep"></div>
                        <a href="<?= $siteRoot ?>/includes/logout.php" class="logout-link">
                            <svg viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                            Déconnexion
                        </a>
                    </div>
                <?php else: ?>
                    <button class="login-btn" onclick="toggleLogin()">
                        <svg viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                        Connexion
                    </button>
                    <div class="login-menu" id="loginMenu">
                        <div class="role-label">Espace Coach</div>
                        <a href="<?= $siteRoot ?>/includes/login.php?role=coach">
                            <svg viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                            Espace Coach GBÔ
                        </a>
                        <div class="sep"></div>
                        <div class="role-label">Espace Client</div>
                        <a href="<?= $siteRoot ?>/includes/login.php?role=client">
                            <svg viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                            Espace Client
                        </a>
                        <div class="sep"></div>
                        <div class="role-label">Administration</div>
                        <a href="<?= $siteRoot ?>/includes/login.php?role=admin">
                            <svg viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                            Espace Administrateur
                        </a>
                    </div>
                <?php endif; ?>
            </div>

            <button class="burger" id="burger" onclick="toggleMenu()"><span></span><span></span><span></span></button>
        </div>
    </div>
</header>

<!-- SCRIPTS -->
<script src="<?= $siteRoot ?>/assets/js/main.js"></script>
<script src="<?= $siteRoot ?>/assets/js/shop.js"></script>
<script>
function toggleMenu() {
    const menu = document.getElementById('menu');
    menu.classList.toggle('open');
}

function toggleLogin() {
    const loginMenu = document.getElementById('loginMenu');
    const loginDropdown = document.getElementById('loginDropdown');
    loginMenu.classList.toggle('open');
    loginDropdown.classList.toggle('active');
}

document.addEventListener('click', function(event) {
    const loginDropdown = document.getElementById('loginDropdown');
    const loginBtn = document.querySelector('.login-btn');
    if (loginDropdown && !loginDropdown.contains(event.target) && event.target !== loginBtn) {
        document.getElementById('loginMenu').classList.remove('open');
        loginDropdown.classList.remove('active');
    }
});

// Mise à jour du compteur panier
function updateCartCount() {
    const cart = JSON.parse(localStorage.getItem('gbo_cart') || '[]');
    const count = cart.reduce((sum, item) => sum + item.qty, 0);
    const badge = document.getElementById('cartCount');
    if (badge) {
        badge.textContent = count;
        badge.style.display = count > 0 ? 'flex' : 'none';
    }
}

document.addEventListener('DOMContentLoaded', updateCartCount);
</script>

</body>
</html>