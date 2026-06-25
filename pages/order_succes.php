<?php
/**
 * PAGE CONFIRMATION COMMANDE GBÔ SHOP
 */
$pageTitle = 'Commande confirmee — GBÔ Shop';
require_once __DIR__ . '/../includes/head.php';
require_once __DIR__ . '/../includes/header.php';
?>

<section class="cart-page" style="display:flex;align-items:center;justify-content:center;min-height:80vh;">
    <div class="wrap" style="text-align:center;max-width:560px;">
        <div style="width:80px;height:80px;border-radius:50%;background:rgba(198,242,2,.15);border:2px solid var(--lime);display:flex;align-items:center;justify-content:center;margin:0 auto 24px;">
            <svg viewBox="0 0 24 24" width="36" height="36" stroke="var(--lime)" fill="none" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                <polyline points="22 4 12 14.01 9 11.01"></polyline>
            </svg>
        </div>
        <h1 style="font-size:clamp(28px,4vw,42px);margin-bottom:16px;">Commande <span class="lime">confirmee</span></h1>
        <p style="color:var(--muted);font-size:16px;line-height:1.7;margin-bottom:32px;">
            Merci pour votre commande ! Notre equipe vous contactera sous <strong style="color:var(--white);">24h ouvrees</strong> par telephone pour confirmer les details et vous communiquer le cout de la livraison.
        </p>
        <div style="background:var(--anthra);border:1px solid var(--line);border-radius:var(--r);padding:24px;margin-bottom:32px;text-align:left;">
            <h3 style="font-size:14px;font-family:var(--body);text-transform:none;margin-bottom:16px;color:var(--muted2);font-weight:700;letter-spacing:.08em;text-transform:uppercase;">Prochaines etapes</h3>
            <div style="display:flex;flex-direction:column;gap:14px;">
                <div style="display:flex;align-items:center;gap:12px;">
                    <span style="width:28px;height:28px;border-radius:50%;background:var(--lime);color:var(--noir);display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:800;flex-shrink:0;">1</span>
                    <span style="font-size:14px;color:var(--white);">Appel de confirmation sous 24h</span>
                </div>
                <div style="display:flex;align-items:center;gap:12px;">
                    <span style="width:28px;height:28px;border-radius:50%;background:var(--lime);color:var(--noir);display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:800;flex-shrink:0;">2</span>
                    <span style="font-size:14px;color:var(--white);">Confirmation du cout de livraison</span>
                </div>
                <div style="display:flex;align-items:center;gap:12px;">
                    <span style="width:28px;height:28px;border-radius:50%;background:var(--lime);color:var(--noir);display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:800;flex-shrink:0;">3</span>
                    <span style="font-size:14px;color:var(--white);">Livraison a votre adresse</span>
                </div>
            </div>
        </div>
        <a href="shop.php" class="btn btn-primary">Continuer mes achats</a>
    </div>
</section>

<script>
// Vider le panier au chargement de la page de confirmation
localStorage.removeItem('gbo_cart');

function updateCartCount() {
    const badge = document.getElementById('cartCount');
    if (badge) {
        badge.textContent = '0';
        badge.style.display = 'none';
    }
}

document.addEventListener('DOMContentLoaded', updateCartCount);
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>