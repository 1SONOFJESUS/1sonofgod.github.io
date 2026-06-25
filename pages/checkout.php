<?php
/**
 * PAGE CHECKOUT GBÔ SHOP
 * Finalisation de commande avec infos client
 */
$pageTitle = 'Finaliser ma commande — GBÔ Shop';
require_once __DIR__ . '/../includes/head.php';
require_once __DIR__ . '/../includes/header.php';

function formatPrice($prix) {
    return number_format($prix, 0, ',', ' ') . ' FCFA';
}
?>

<section class="checkout-page">
    <div class="wrap">
        <span class="eyebrow">GBÔ Shop</span>
        <h1>Finaliser ma <span class="lime">commande</span></h1>

        <div class="checkout-grid">
            <!-- Formulaire -->
            <div>
                <form action="process_order.php" method="POST" class="checkout-form" id="checkoutForm" onsubmit="return prepareOrder(event)">
                    <input type="hidden" name="order_data" id="orderData">

                    <h2>Informations de livraison</h2>

                    <div class="field-row">
                        <div class="field">
                            <label for="prenom">Prenom *</label>
                            <input type="text" id="prenom" name="prenom" placeholder="Votre prenom" required>
                        </div>
                        <div class="field">
                            <label for="nom">Nom *</label>
                            <input type="text" id="nom" name="nom" placeholder="Votre nom" required>
                        </div>
                    </div>

                    <div class="field">
                        <label for="telephone">Numero de telephone *</label>
                        <input type="tel" id="telephone" name="telephone" placeholder="+225 XX XX XX XX" required>
                    </div>

                    <div class="field">
                        <label for="email">Adresse e-mail</label>
                        <input type="email" id="email" name="email" placeholder="vous@email.com">
                    </div>

                    <div class="field">
                        <label for="adresse">Adresse de livraison *</label>
                        <textarea id="adresse" name="adresse" rows="3" placeholder="Quartier, rue, point de repere..." required></textarea>
                    </div>

                    <div class="field">
                        <label for="commune">Commune / Ville *</label>
                        <select id="commune" name="commune" required>
                            <option value="">Selectionnez votre commune</option>
                            <option value="abobo">Abobo</option>
                            <option value="adjame">Adjame</option>
                            <option value="attecoube">Attecoube</option>
                            <option value="cocody">Cocody</option>
                            <option value="koumassi">Koumassi</option>
                            <option value="marcory">Marcory</option>
                            <option value="plateau">Plateau</option>
                            <option value="port-bouet">Port-Bouet</option>
                            <option value="treichville">Treichville</option>
                            <option value="yopougon">Yopougon</option>
                            <option value="bingerville">Bingerville</option>
                            <option value="autre">Autre (precisez dans les notes)</option>
                        </select>
                    </div>

                    <div class="field">
                        <label for="notes">Notes complementaires (optionnel)</label>
                        <textarea id="notes" name="notes" rows="2" placeholder="Instructions de livraison, batiment, etage..."></textarea>
                    </div>

                    <div class="shipping-info">
                        <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>
                        <div>
                            <p><strong>Livraison</strong></p>
                            <p>Le cout de la livraison vous sera communique par appel par notre service client apres reception de votre commande. Nous vous contacterons sous 24h ouvrees.</p>
                        </div>
                    </div>

                    <button type="submit" class="btn-checkout" style="margin-top:24px;">
                        <svg viewBox="0 0 24 24" width="18" height="18" stroke="currentColor" fill="none" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                        Confirmer ma commande
                    </button>
                </form>
            </div>

            <!-- Recapitulatif commande -->
            <div>
                <div class="cart-summary">
                    <h3>Votre commande</h3>
                    <div id="orderItems" class="order-items"></div>
                    <div class="summary-row">
                        <span>Sous-total</span>
                        <span id="orderSubtotal">0 FCFA</span>
                    </div>
                    <div class="summary-row">
                        <span>Livraison</span>
                        <span style="color:var(--muted2);font-size:12px;">A confirmer par appel</span>
                    </div>
                    <div class="summary-row total">
                        <span>Total</span>
                        <span class="price-total" id="orderTotal">0 FCFA</span>
                    </div>
                    <div class="shipping-note">
                        <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>
                        <p>Notre equipe vous appellera sous 24h pour confirmer les details et le cout de la livraison.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
function renderOrderSummary() {
    const cart = JSON.parse(localStorage.getItem('gbo_cart') || '[]');
    const itemsContainer = document.getElementById('orderItems');
    const subtotalEl = document.getElementById('orderSubtotal');
    const totalEl = document.getElementById('orderTotal');

    if (cart.length === 0) {
        itemsContainer.innerHTML = '<p style="color:var(--muted);font-size:14px;padding:12px 0;">Votre panier est vide. <a href="shop.php" style="color:var(--lime);">Retourner au shop</a></p>';
        subtotalEl.textContent = '0 FCFA';
        totalEl.textContent = '0 FCFA';
        return;
    }

    let subtotal = 0;
    let html = '';

    cart.forEach(item => {
        const itemTotal = item.price * item.qty;
        subtotal += itemTotal;
        html += `
            <div class="order-item">
                <img src="assets/images/${item.image || 'placeholder.webp'}" alt="${item.name}" onerror="this.src='assets/images/placeholder.webp'">
                <div class="order-item-info">
                    <h4>${item.name}</h4>
                    <span>Quantite: ${item.qty}</span>
                </div>
                <span class="order-item-price">${itemTotal.toLocaleString()} FCFA</span>
            </div>
        `;
    });

    itemsContainer.innerHTML = html;
    subtotalEl.textContent = subtotal.toLocaleString() + ' FCFA';
    totalEl.textContent = subtotal.toLocaleString() + ' FCFA';
}

function prepareOrder(e) {
    const cart = JSON.parse(localStorage.getItem('gbo_cart') || '[]');
    if (cart.length === 0) {
        alert('Votre panier est vide.');
        return false;
    }

    // Injecte les donnees du panier dans le formulaire
    document.getElementById('orderData').value = JSON.stringify(cart);

    // Vider le panier apres soumission
    localStorage.removeItem('gbo_cart');
    return true;
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
    renderOrderSummary();
    updateCartCount();
});
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>