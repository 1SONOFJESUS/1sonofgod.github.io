<?php
/**
 * PAGE GBÔ SHOP
 * Boutique avec panier, pagination, réductions, rupture de stock
 */

$pageTitle = 'GBÔ Shop — Équipement sport & bien-être';
require_once __DIR__ . '/../includes/head.php';
require_once __DIR__ . '/../includes/header.php';

// ============================================
// DONNÉES PRODUITS (12 articles pour démo)
// ============================================
$products = [
    [
        'id' => 1,
        'nom' => 'Haltères GBÔ',
        'categorie' => 'Équipements',
        'prix' => 35000,
        'prix_old' => null,
        'discount' => null,
        'badge' => null,
        'image' => 'halteres.webp',
        'description' => 'Haltères ajustables premium avec revêtement néoprène',
        'stock' => 15
    ],
    [
        'id' => 2,
        'nom' => 'T-shirt Performance',
        'categorie' => 'Textile',
        'prix' => 15000,
        'prix_old' => 20000,
        'discount' => 25,
        'badge' => 'Nouveau',
        'image' => 'tshirt.webp',
        'description' => 'T-shirt technique anti-transpiration, coupe ajustée',
        'stock' => 8
    ],
    [
        'id' => 3,
        'nom' => 'Gourde Isotherme',
        'categorie' => 'Accessoires',
        'prix' => 8000,
        'prix_old' => null,
        'discount' => null,
        'badge' => null,
        'image' => 'gourde.webp',
        'description' => 'Gourde 750ml, maintien froid 12h',
        'stock' => 0
    ],
    [
        'id' => 4,
        'nom' => 'Protéine Whey',
        'categorie' => 'Nutrition',
        'prix' => 18000,
        'prix_old' => null,
        'discount' => null,
        'badge' => null,
        'image' => 'proteine.webp',
        'description' => 'Whey isolate 100%, saveur vanille',
        'stock' => 20
    ],
    [
        'id' => 5,
        'nom' => 'Tapis Premium',
        'categorie' => 'Équipements',
        'prix' => 22000,
        'prix_old' => 28000,
        'discount' => 21,
        'badge' => null,
        'image' => 'tapis.webp',
        'description' => 'Tapis yoga/fitness, antidérapant, 6mm',
        'stock' => 12
    ],
    [
        'id' => 6,
        'nom' => 'Legging GBÔ',
        'categorie' => 'Textile',
        'prix' => 16000,
        'prix_old' => null,
        'discount' => null,
        'badge' => 'Best-seller',
        'image' => 'legging.webp',
        'description' => 'Legging compression, taille haute',
        'stock' => 5
    ],
    [
        'id' => 7,
        'nom' => 'Bandes Élastiques',
        'categorie' => 'Accessoires',
        'prix' => 9000,
        'prix_old' => null,
        'discount' => null,
        'badge' => null,
        'image' => 'elastique.webp',
        'description' => 'Set de 5 bandes, résistances variées',
        'stock' => 30
    ],
    [
        'id' => 8,
        'nom' => 'Barre Énergétique',
        'categorie' => 'Nutrition',
        'prix' => 2500,
        'prix_old' => null,
        'discount' => null,
        'badge' => null,
        'image' => 'barre.webp',
        'description' => 'Barre protéinée, fruits secs et chocolat',
        'stock' => 50
    ],
    [
        'id' => 9,
        'nom' => 'Gants de Boxe GBÔ',
        'categorie' => 'Équipements',
        'prix' => 28000,
        'prix_old' => 35000,
        'discount' => 20,
        'badge' => 'Nouveau',
        'image' => 'gants.webp',
        'description' => 'Gants cuir synthétique, rembourrage mousse haute densité',
        'stock' => 7
    ],
    [
        'id' => 10,
        'nom' => 'Short Running',
        'categorie' => 'Textile',
        'prix' => 12000,
        'prix_old' => null,
        'discount' => null,
        'badge' => null,
        'image' => 'short.webp',
        'description' => 'Short léger avec doublure intégrée, poches zippées',
        'stock' => 0
    ],
    [
        'id' => 11,
        'nom' => 'Shaker Pro',
        'categorie' => 'Accessoires',
        'prix' => 5500,
        'prix_old' => null,
        'discount' => null,
        'badge' => null,
        'image' => 'shaker.webp',
        'description' => 'Shaker 700ml avec grille anti-grumeaux',
        'stock' => 25
    ],
    [
        'id' => 12,
        'nom' => 'BCAA 2:1:1',
        'categorie' => 'Nutrition',
        'prix' => 22000,
        'prix_old' => 28000,
        'discount' => 21,
        'badge' => 'Best-seller',
        'image' => 'bcaa.webp',
        'description' => 'Acides aminés essentiels, saveur fruit du dragon',
        'stock' => 18
    ]
];

$categories = ['Équipements', 'Textile', 'Accessoires', 'Nutrition'];

// Pagination
$itemsPerPage = 8;
$currentPage = isset($_GET['p']) ? max(1, intval($_GET['p'])) : 1;
$totalItems = count($products);
$totalPages = ceil($totalItems / $itemsPerPage);
$offset = ($currentPage - 1) * $itemsPerPage;
$paginatedProducts = array_slice($products, $offset, $itemsPerPage);

function clean($str) {
    return htmlspecialchars($str ?? '', ENT_QUOTES, 'UTF-8');
}

function formatPrice($prix) {
    return number_format($prix, 0, ',', ' ') . ' FCFA';
}

function generateToken() {
    return bin2hex(random_bytes(32));
}
?>

<!-- HERO SECTION -->
<section class="page-hero">
    <div class="wrap">
        <span class="eyebrow">GBÔ Shop</span>
        <h1>L'équipement du<br><span class="lime">mouvement.</span></h1>
        <p>Nutrition, textile, accessoires et équipements aux couleurs de la marque.</p>
        <div class="device-note" style="display:inline-flex;gap:8px;align-items:center;background:var(--anthra);border:1px solid var(--line);border-radius:50px;padding:8px 16px;font-size:12.5px;color:var(--muted);margin-top:18px">
            🟢 Livraison disponible à Abidjan — Commandez dès maintenant.
        </div>
    </div>
</section>

<!-- SECTION PRODUITS -->
<section style="padding-top:10px">
    <div class="wrap">
        <!-- Filtres par catégorie -->
        <div class="shop-filters" id="shopFilters">
            <button class="chip active" data-cat="Tous">Tous</button>
            <?php foreach ($categories as $cat): ?>
                <button class="chip" data-cat="<?php echo clean($cat); ?>"><?php echo clean($cat); ?></button>
            <?php endforeach; ?>
        </div>

        <!-- Grille produits -->
        <div class="grid g4" id="productGrid">
            <?php foreach ($paginatedProducts as $p): 
                $isOutOfStock = ($p['stock'] <= 0);
                $hasDiscount = !empty($p['discount']) && $p['discount'] > 0;
            ?>
                <div class="card product" data-category="<?php echo clean($p['categorie']); ?>">
                    <div style="position:relative">
                        <div class="media" style="min-height:280px">
                            <?php if ($p['image']): ?>
                                <img src="<?php echo 'assets/images/' . clean($p['image']); ?>" 
                                     alt="<?php echo clean($p['nom']); ?>" 
                                     loading="lazy" 
                                     style="width:100%;height:100%;object-fit:cover"
                                     onerror="this.style.display='none'; this.parentElement.innerHTML='<span class=\'tag\'>' + this.alt + '</span>';">
                            <?php else: ?>
                                <span class="tag"><?php echo clean($p['categorie']); ?></span>
                            <?php endif; ?>
                        </div>

                        <!-- Badges -->
                        <?php if ($isOutOfStock): ?>
                            <span class="product-badge badge-outofstock">Rupture de stock</span>
                        <?php elseif ($hasDiscount): ?>
                            <span class="product-badge badge-sale">-<?php echo $p['discount']; ?>%</span>
                        <?php elseif ($p['badge'] === 'Nouveau'): ?>
                            <span class="product-badge badge-new"><?php echo clean($p['badge']); ?></span>
                        <?php elseif ($p['badge'] === 'Best-seller'): ?>
                            <span class="product-badge badge-bestseller"><?php echo clean($p['badge']); ?></span>
                        <?php endif; ?>
                    </div>

                    <span class="cat"><?php echo clean($p['categorie']); ?></span>
                    <h4><?php echo clean($p['nom']); ?></h4>
                    <p class="muted" style="font-size:13px;margin-top:4px"><?php echo clean($p['description']); ?></p>

                    <!-- Prix -->
                    <div class="price-container">
                        <span class="price-current"><?php echo formatPrice($p['prix']); ?></span>
                        <?php if ($p['prix_old']): ?>
                            <span class="price-old"><?php echo formatPrice($p['prix_old']); ?></span>
                        <?php endif; ?>
                        <?php if ($hasDiscount): ?>
                            <span class="price-discount">-<?php echo $p['discount']; ?>%</span>
                        <?php endif; ?>
                    </div>

                    <!-- Bouton Ajouter au panier -->
                    <button class="btn-add-cart" 
                            onclick="addToCart(<?php echo $p['id']; ?>, '<?php echo clean($p['nom']); ?>', <?php echo $p['prix']; ?>, '<?php echo clean($p['image']); ?>', '<?php echo clean($p['categorie']); ?>')"
                            <?php if ($isOutOfStock): ?>disabled<?php endif; ?>>
                        <?php if ($isOutOfStock): ?>
                            <svg viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                            Rupture de stock
                        <?php else: ?>
                            <svg viewBox="0 0 24 24"><path d="M9 20a1 1 0 1 0 0 2 1 1 0 1 0 0-2z"></path><path d="M20 20a1 1 0 1 0 0 2 1 1 0 1 0 0-2z"></path><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>
                            Ajouter au panier
                        <?php endif; ?>
                    </button>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Pagination -->
        <?php if ($totalPages > 1): 
            // Construire l'URL de base en preservant les parametres existants
            $baseUrl = strtok($_SERVER['REQUEST_URI'], '?');
            $queryParams = $_GET;
        ?>
        <div class="pagination">
            <?php if ($currentPage > 1): 
                $queryParams['p'] = $currentPage - 1;
            ?>
                <a href="<?php echo $baseUrl . '?' . http_build_query($queryParams); ?>">←</a>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $totalPages; $i++): 
                $queryParams['p'] = $i;
                if ($i == $currentPage): ?>
                    <span class="current"><?php echo $i; ?></span>
                <?php else: ?>
                    <a href="<?php echo $baseUrl . '?' . http_build_query($queryParams); ?>"><?php echo $i; ?></a>
                <?php endif; ?>
            <?php endfor; ?>

            <?php if ($currentPage < $totalPages): 
                $queryParams['p'] = $currentPage + 1;
            ?>
                <a href="<?php echo $baseUrl . '?' . http_build_query($queryParams); ?>">→</a>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </div>
</section>

<!-- SECTION NEWSLETTER -->
<section class="sec-sm" style="background:linear-gradient(135deg,rgba(198,242,2,.08),transparent)">
    <div class="wrap" style="text-align:center">
        <span class="eyebrow" style="justify-content:center">Restez informé(e)</span>
        <h2 style="font-size:clamp(24px,4vw,36px);margin:16px 0 14px">Soyez le premier au lancement</h2>
        <p class="muted" style="max-width:480px;margin:0 auto 24px">Inscrivez-vous pour recevoir en exclusivité les nouveautés, promotions et dates de lancement.</p>
        <form action="subscribe_newsletter.php" method="POST" style="display:flex;gap:12px;justify-content:center;flex-wrap:wrap;max-width:560px;margin:0 auto" onsubmit="return validateNewsletter(this)">
            <input type="hidden" name="csrf_token" value="<?php echo generateToken(); ?>">
            <input type="email" name="email" placeholder="votre@email.com" required 
                   style="flex:1;min-width:240px;background:var(--anthra);border:1px solid var(--line2);border-radius:50px;padding:12px 20px;color:var(--white);font-family:var(--body);font-size:14px">
            <button type="submit" class="btn btn-primary">M'inscrire</button>
        </form>
    </div>
</section>

<!-- TOAST NOTIFICATION -->
<div class="toast" id="toast">
    <svg viewBox="0 0 24 24"><path d="M20 6L9 17l-5-5"/></svg>
    <span id="toastMessage">Article ajouté au panier</span>
</div>

<script>
// Données produits pour le JS
const productsData = <?php echo json_encode($products); ?>;

// Filtrage des produits par catégorie
document.getElementById('shopFilters').addEventListener('click', function(e) {
    const btn = e.target.closest('.chip');
    if (!btn) return;

    document.querySelectorAll('#shopFilters .chip').forEach(c => c.classList.remove('active'));
    btn.classList.add('active');

    const cat = btn.dataset.cat;
    document.querySelectorAll('#productGrid .product').forEach(p => {
        p.style.display = (cat === 'Tous' || p.dataset.category === cat) ? 'block' : 'none';
    });
});

// Ajouter au panier
function addToCart(id, name, price, image, category) {
    let cart = JSON.parse(localStorage.getItem('gbo_cart') || '[]');
    const existing = cart.find(item => item.id === id);

    if (existing) {
        existing.qty += 1;
    } else {
        cart.push({ id, name, price, image, category, qty: 1 });
    }

    localStorage.setItem('gbo_cart', JSON.stringify(cart));
    updateCartCount();
    showToast('« ' + name + ' » ajouté au panier');
}

// Mise à jour compteur panier
function updateCartCount() {
    const cart = JSON.parse(localStorage.getItem('gbo_cart') || '[]');
    const count = cart.reduce((sum, item) => sum + item.qty, 0);
    const badge = document.getElementById('cartCount');
    if (badge) {
        badge.textContent = count;
        badge.style.display = count > 0 ? 'flex' : 'none';
    }
}

// Toast notification
function showToast(message) {
    const toast = document.getElementById('toast');
    const msg = document.getElementById('toastMessage');
    msg.textContent = message;
    toast.classList.add('show');
    setTimeout(() => toast.classList.remove('show'), 3000);
}

// Validation newsletter
function validateNewsletter(form) {
    const email = form.email.value;
    if (!email.match(/^[^\s@]+@[^\s@]+\.[^\s@]+$/)) {
        alert('Veuillez entrer un email valide.');
        return false;
    }
    return true;
}

// Animation au scroll
function revealInit() {
    const els = document.querySelectorAll('.product');
    if (!('IntersectionObserver' in window)) {
        els.forEach(e => e.style.opacity = 1);
        return;
    }

    const io = new IntersectionObserver(entries => {
        entries.forEach(en => {
            if (en.isIntersecting) {
                en.target.style.opacity = '1';
                en.target.style.transform = 'translateY(0)';
                io.unobserve(en.target);
            }
        });
    }, { threshold: 0.1 });

    els.forEach((e, i) => {
        e.style.opacity = '0';
        e.style.transform = 'translateY(20px)';
        e.style.transition = `opacity 0.5s ease ${i * 0.1}s, transform 0.5s ease ${i * 0.1}s`;
        io.observe(e);
    });
}

document.addEventListener('DOMContentLoaded', function() {
    revealInit();
    updateCartCount();
});
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>