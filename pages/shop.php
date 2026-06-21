<?php
/**
 * PAGE GBÔ SHOP
 * Boutique vitrine inspirée du style AYBL (www.aybl.com/collections/mens)
 * V1 : catalogue + précommande | V2 : e-commerce complet
 */

/**
 * PAGE GBÔ SHOP
 * Boutique vitrine — V1 : catalogue + précommande
 */
$pageTitle = 'GBÔ Shop — Équipement sport & bien-être';
require_once __DIR__ . '/../includes/head.php';
require_once __DIR__ . '/../includes/header.php';

// Données produits (en attendant la base de données)
$products = [
    [
        'id' => 1,
        'nom' => 'Haltères GBÔ',
        'categorie' => 'Équipements',
        'prix' => 35000,
        'prix_old' => null,
        'badge' => null,
        'image' => 'halteres.webp',
        'description' => 'Haltères ajustables premium avec revêtement néoprène'
    ],
    [
        'id' => 2,
        'nom' => 'T-shirt Performance',
        'categorie' => 'Textile',
        'prix' => 15000,
        'prix_old' => 20000,
        'badge' => 'Nouveau',
        'image' => 'tshirt.webp',
        'description' => 'T-shirt technique anti-transpiration, coupe ajustée'
    ],
    [
        'id' => 3,
        'nom' => 'Gourde Isotherme',
        'categorie' => 'Accessoires',
        'prix' => 8000,
        'prix_old' => null,
        'badge' => null,
        'image' => 'gourde.webp',
        'description' => 'Gourde 750ml, maintien froid 12h'
    ],
    [
        'id' => 4,
        'nom' => 'Protéine Whey',
        'categorie' => 'Nutrition',
        'prix' => 18000,
        'prix_old' => null,
        'badge' => null,
        'image' => 'proteïne.webp',
        'description' => 'Whey isolate 100%, saveur vanille'
    ],
    [
        'id' => 5,
        'nom' => 'Tapis Premium',
        'categorie' => 'Équipements',
        'prix' => 22000,
        'prix_old' => null,
        'badge' => null,
        'image' => 'tapis.webp',
        'description' => 'Tapis yoga/fitness, antidérapant, 6mm'
    ],
    [
        'id' => 6,
        'nom' => 'Legging GBÔ',
        'categorie' => 'Textile',
        'prix' => 16000,
        'prix_old' => null,
        'badge' => 'Best-seller',
        'image' => 'legging.webp',
        'description' => 'Legging compression, taille haute'
    ],
    [
        'id' => 7,
        'nom' => 'Bandes Élastiques',
        'categorie' => 'Accessoires',
        'prix' => 9000,
        'prix_old' => null,
        'badge' => null,
        'image' => 'elastique.webp',
        'description' => 'Set de 5 bandes, résistances variées'
    ],
    [
        'id' => 8,
        'nom' => 'Barre Énergétique',
        'categorie' => 'Nutrition',
        'prix' => 2500,
        'prix_old' => null,
        'badge' => null,
        'image' => 'barre.webp',
        'description' => 'Barre protéinée, fruits secs et chocolat'
    ]
];

$categories = ['Équipements', 'Textile', 'Accessoires', 'Nutrition'];

// Fonctions utilitaires
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
            🟢 Ouverture prochaine — précommandez et soyez informé(e) du lancement.
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
            <?php foreach ($products as $p): ?>
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
                        <span class="soon">Bientôt</span>
                        <?php if ($p['badge']): ?>
                            <span style="position:absolute;top:12px;left:12px;background:var(--lime);color:var(--noir);font-size:10px;font-weight:800;letter-spacing:.1em;text-transform:uppercase;padding:5px 10px;border-radius:50px">
                                <?php echo clean($p['badge']); ?>
                            </span>
                        <?php endif; ?>
                    </div>
                    <span class="cat"><?php echo clean($p['categorie']); ?></span>
                    <h4><?php echo clean($p['nom']); ?></h4>
                    <p class="muted" style="font-size:13px;margin-top:4px"><?php echo clean($p['description']); ?></p>
                    <div style="display:flex;align-items:center;gap:10px;margin-top:6px">
                        <span class="pr"><?php echo formatPrice($p['prix']); ?></span>
                        <?php if ($p['prix_old']): ?>
                            <span style="text-decoration:line-through;color:var(--muted2);font-size:13px">
                                <?php echo formatPrice($p['prix_old']); ?>
                            </span>
                        <?php endif; ?>
                    </div>
                    <button class="btn btn-ghost btn-sm" style="margin-top:12px;width:100%;justify-content:center" onclick="preorderProduct(<?php echo $p['id']; ?>)">
                        Précommander
                    </button>
                </div>
            <?php endforeach; ?>
        </div>
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

<!-- MODAL PRÉCOMMANDE -->
<div class="modal" id="modal">
    <div class="modal-box" id="modalBox">
        <button class="modal-close" onclick="closeModal()">×</button>
        <div id="modalContent"></div>
    </div>
</div>

<script>
// Données produits pour le JS
const productsData = <?php echo json_encode($products); ?>;

// Filtrage des produits par catégorie
document.getElementById('shopFilters').addEventListener('click', function(e) {
    const btn = e.target.closest('.chip');
    if (!btn) return;

    // Mise à jour visuelle des boutons
    document.querySelectorAll('#shopFilters .chip').forEach(c => c.classList.remove('active'));
    btn.classList.add('active');

    // Filtrage
    const cat = btn.dataset.cat;
    document.querySelectorAll('#productGrid .product').forEach(p => {
        p.style.display = (cat === 'Tous' || p.dataset.category === cat) ? 'block' : 'none';
    });
});

// Précommande - Ouvrir le modal
function preorderProduct(id) {
    const product = productsData.find(p => p.id === id);
    if (!product) return;

    const content = `
        <span class="eyebrow">GBÔ Shop</span>
        <h2 style="font-size:24px;margin:12px 0">Précommande : ${product.nom}</h2>
        <p class="muted" style="margin-bottom:22px">Soyez informé(e) dès que ce produit est disponible. Prix indicatif : <span class="lime" style="font-weight:700">${product.prix.toLocaleString()} FCFA</span></p>
        <form action="submit_preorder.php" method="POST" onsubmit="return validatePreorder(this)">
            <input type="hidden" name="csrf_token" value="<?php echo generateToken(); ?>">
            <input type="hidden" name="product_id" value="${id}">
            <input type="hidden" name="product_name" value="${product.nom}">

            <div class="field">
                <label>Nom complet</label>
                <input name="nom" placeholder="Votre nom" required>
            </div>
            <div class="field">
                <label>E-mail</label>
                <input type="email" name="email" placeholder="vous@email.com" required>
            </div>
            <div class="field">
                <label>Téléphone</label>
                <input name="telephone" placeholder="+225 XX XX XX XX" required>
            </div>
            <div class="grid g2" style="gap:14px">
                <div class="field">
                    <label>Quantité souhaitée</label>
                    <input type="number" name="quantite" value="1" min="1" max="10" style="width:100%">
                </div>
                <div class="field">
                    <label>Taille/Couleur (si applicable)</label>
                    <input name="variante" placeholder="Ex: L, Noir">
                </div>
            </div>
            <div class="field">
                <label>Message (optionnel)</label>
                <textarea name="message" rows="2" placeholder="Une précision ?"></textarea>
            </div>
            <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center">
                Être informé(e) du lancement
            </button>
        </form>
    `;

    document.getElementById('modalContent').innerHTML = content;
    document.getElementById('modal').classList.add('open');
    document.body.style.overflow = 'hidden';
}

// Fermer le modal
function closeModal() {
    document.getElementById('modal').classList.remove('open');
    document.body.style.overflow = '';
}

// Fermer modal au clic sur l'overlay
document.getElementById('modal').addEventListener('click', function(e) {
    if (e.target === this) closeModal();
});

// Validation formulaire précommande
function validatePreorder(form) {
    const email = form.email.value;
    const phone = form.telephone.value;

    if (!email.match(/^[^\s@]+@[^\s@]+\.[^\s@]+$/)) {
        alert('Veuillez entrer un email valide.');
        return false;
    }

    if (phone.length < 8) {
        alert('Veuillez entrer un numéro de téléphone valide.');
        return false;
    }

    return true;
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

// Initialisation
document.addEventListener('DOMContentLoaded', revealInit);
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>