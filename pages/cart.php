<?php
/**
 * PAGE PANIER GBÔ SHOP
 */
$pageTitle = 'Mon Panier — GBÔ Shop';
require_once __DIR__ . '/../includes/head.php';
require_once __DIR__ . '/../includes/header.php';

function formatPrice($prix) {
    return number_format($prix, 0, ',', ' ') . ' FCFA';
}
?>

<section class="cart-page">
    <div class="wrap">
        <span class="eyebrow">GBÔ Shop</span>
        <h1>Mon <span class="lime">Panier</span></h1>

        <div id="cartContent">
            <!-- Le contenu sera injecte par JavaScript -->
        </div>
    </div>
</section>

<script>
function renderCart() {
    const cart = JSON.parse(localStorage.getItem('gbo_cart') || '[]');
    const container = document.getElementById('cartContent');

    if (cart.length === 0) {
        container.innerHTML = `
            <div class="cart-empty">
                <svg viewBox="0 0 24 24"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>
                <h3>Votre panier est vide</h3>
                <p>Decouvrez nos produits et commencez vos achats.</p>
                <a href="shop.php" class="btn btn-primary" style="margin-top:8px">Continuer les achats</a>
            </div>
        `;
        return;
    }

    let subtotal = 0;
    let html = '<div style="display:grid;grid-template-columns:1fr 360px;gap:32px;align-items:start;">';

    // Tableau des articles
    html += '<div>';
    html += '<table class="cart-table">';
    html += '<thead><tr><th>Produit</th><th>Prix</th><th>Quantite</th><th>Total</th><th></th></tr></thead><tbody>';

    cart.forEach((item, index) => {
        const itemTotal = item.price * item.qty;
        subtotal += itemTotal;
        html += `
            <tr>
                <td data-label="Produit">
                    <div style="display:flex;align-items:center;gap:14px;">
                        <img src="assets/images/${item.image || 'placeholder.webp'}" alt="${item.name}" class="cart-item-img" onerror="this.src='assets/images/placeholder.webp'">
                        <div class="cart-item-info">
                            <h4>${item.name}</h4>
                            <span>${item.category}</span>
                        </div>
                    </div>
                </td>
                <td data-label="Prix" style="font-weight:700;color:var(--lime);">${item.price.toLocaleString()} FCFA</td>
                <td data-label="Quantite">
                    <div class="qty-control">
                        <button onclick="updateQty(${index}, -1)">−</button>
                        <input type="text" value="${item.qty}" readonly>
                        <button onclick="updateQty(${index}, 1)">+</button>
                    </div>
                </td>
                <td data-label="Total" style="font-weight:700;">${itemTotal.toLocaleString()} FCFA</td>
                <td data-label="">
                    <button class="btn-remove" onclick="removeItem(${index})" title="Retirer">
                        <svg viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                    </button>
                </td>
            </tr>
        `;
    });

    html += '</tbody></table>';
    html += '<div style="margin-top:20px;"><a href="shop.php" class="btn btn-ghost btn-sm">← Continuer les achats</a></div>';
    html += '</div>';

    // Recapitulatif
    html += `
        <div class="cart-summary">
            <h3>Recapitulatif</h3>
            <div class="summary-row">
                <span>Sous-total</span>
                <span>${subtotal.toLocaleString()} FCFA</span>
            </div>
            <div class="summary-row">
                <span>Livraison</span>
                <span style="color:var(--muted2);font-size:12px;">A confirmer</span>
            </div>
            <div class="summary-row total">
                <span>Total</span>
                <span class="price-total">${subtotal.toLocaleString()} FCFA</span>
            </div>
            <div class="shipping-note">
                <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>
                <p>Le cout de la livraison vous sera communique par appel par notre service client apres reception de votre commande.</p>
            </div>
            <button class="btn-checkout" onclick="window.location.href='checkout.php'">
                <svg viewBox="0 0 24 24" width="18" height="18" stroke="currentColor" fill="none" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect><line x1="1" y1="10" x2="23" y2="10"></line></svg>
                Finaliser la commande
            </button>
        </div>
    `;

    html += '</div>';
    container.innerHTML = html;
}

function updateQty(index, delta) {
    let cart = JSON.parse(localStorage.getItem('gbo_cart') || '[]');
    cart[index].qty += delta;
    if (cart[index].qty < 1) cart[index].qty = 1;
    if (cart[index].qty > 10) cart[index].qty = 10;
    localStorage.setItem('gbo_cart', JSON.stringify(cart));
    renderCart();
    updateCartCount();
}

function removeItem(index) {
    let cart = JSON.parse(localStorage.getItem('gbo_cart') || '[]');
    cart.splice(index, 1);
    localStorage.setItem('gbo_cart', JSON.stringify(cart));
    renderCart();
    updateCartCount();
}

function updateCartCount() {
    const cart = JSON.parse(localStorage.getItem('gbo_cart') || '[]');
    const count = cart.reduce((sum, item) => sum + item.qty, 0);
    const badge = document.getElementById('cartCount');
    if (badge) {
        badge.textContent = count;
        badge.style.display = count > 0 ? 'flex' : 'none';
    }
}

document.addEventListener('DOMContentLoaded', function() {
    renderCart();
    updateCartCount();
});
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>