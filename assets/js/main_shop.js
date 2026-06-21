/**
 * GBÔ SHOP - JavaScript Principal
 * Gestion des filtres, modal précommande et interactions
 */

// ===== FILTRAGE DES PRODUITS =====
document.addEventListener('DOMContentLoaded', function() {
  const shopFilters = document.getElementById('shopFilters');
  
  if (shopFilters) {
    shopFilters.addEventListener('click', function(e) {
      const btn = e.target.closest('.chip');
      if (!btn) return;

      // Mise à jour visuelle des boutons
      document.querySelectorAll('#shopFilters .chip').forEach(c => c.classList.remove('active'));
      btn.classList.add('active');

      // Filtrage des produits
      const cat = btn.dataset.cat;
      document.querySelectorAll('#productGrid .product').forEach(p => {
        p.style.display = (cat === 'Tous' || p.dataset.category === cat) ? 'block' : 'none';
      });
    });
  }

  // Animation au scroll des produits
  revealInit();
});

// ===== MODAL PRÉCOMMANDE =====
function preorderProduct(id) {
  // Récupérer les données du produit depuis le HTML
  const productElement = document.querySelector(`[data-product-id="${id}"]`) || 
                         document.querySelector(`[onclick*="preorderProduct(${id})"]`).closest('.product');
  
  if (!productElement) return;

  const productName = productElement.querySelector('h4')?.textContent || 'Produit';
  const productPrice = productElement.querySelector('.pr')?.textContent || '0 FCFA';

  const content = `
    <span class="eyebrow">GBÔ Shop</span>
    <h2 style="font-size:24px;margin:12px 0">Précommande : ${productName}</h2>
    <p class="muted" style="margin-bottom:22px">Soyez informé(e) dès que ce produit est disponible. Prix indicatif : <span class="lime" style="font-weight:700">${productPrice}</span></p>
    <form action="submit_preorder.php" method="POST" onsubmit="return validatePreorder(this)">
      <input type="hidden" name="product_id" value="${id}">
      <input type="hidden" name="product_name" value="${productName}">

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
  const modal = document.getElementById('modal');
  if (modal) {
    modal.classList.remove('open');
    document.body.style.overflow = '';
  }
}

// Fermer modal au clic sur l'overlay
const modal = document.getElementById('modal');
if (modal) {
  modal.addEventListener('click', function(e) {
    if (e.target === this) closeModal();
  });
}

// ===== VALIDATION FORMULAIRE PRÉCOMMANDE =====
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

// ===== VALIDATION NEWSLETTER =====
function validateNewsletter(form) {
  const email = form.email.value;
  if (!email.match(/^[^\s@]+@[^\s@]+\.[^\s@]+$/)) {
    alert('Veuillez entrer un email valide.');
    return false;
  }
  return true;
}

// ===== ANIMATION AU SCROLL =====
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
