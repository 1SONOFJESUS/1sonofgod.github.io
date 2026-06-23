<?php
/**
 * Footer commun GBÔ AFRICA GROUP
 */
$siteRoot = $siteRoot ?? '/gbo_africa_group';
?>

<!-- ============ FOOTER ============ -->
<footer><div class="wrap">
  <div class="foot-grid">
    <div>
      <img src="<?= $siteRoot ?>/assets/images/logo.png" alt="GBÔ AFRICA GROUP" class="logo-img-footer">
      <p class="muted" style="font-size:13.5px;max-width:260px;margin-top:12px">Plus qu'une pratique, un style de vie. L'écosystème africain du sport, du fitness et du bien-être.</p>
    </div>
    <div><h5>Services</h5><a href="<?= $siteRoot ?>/index.php?page=fitness" target="_blank">Fitness</a><a href="<?= $siteRoot ?>/index.php?page=coach" target="_blank">Coach</a><a href="<?= $siteRoot ?>/index.php?page=academy" target="_blank">Academy</a><a href="<?= $siteRoot ?>/index.php?page=shop" target="_blank">Shop</a></div>
    <div><h5>Groupe</h5><a href="<?= $siteRoot ?>/index.php?page=apropos" target="_blank">À propos</a><a href="<?= $siteRoot ?>/index.php?page=blog" target="_blank">Blog</a><a href="<?= $siteRoot ?>/index.php?page=contact" target="_blank">Contact</a></div>
    <div><h5>Espaces</h5><a href="<?= $siteRoot ?>/pages/login.php?role=coach" target="_blank">Espace Coach</a><a href="<?= $siteRoot ?>/pages/login.php?role=client" target="_blank">Espace Client</a><a href="<?= $siteRoot ?>/pages/login.php?role=admin" target="_blank">Administration</a></div>
  </div>
  <div class="foot-bottom"><span>© 2026 GBÔ AFRICA GROUP</span><span>Abidjan, Côte d'Ivoire · contact@gboafricagroup.com · 07 88 19 95 42</span></div>
</div></footer>

<script>
/* ---------- MENU BURGER ---------- */
function toggleMenu(){
  document.getElementById('menu').classList.toggle('open');
}

/* ---------- LOGIN DROPDOWN ---------- */
function toggleLogin(){
  document.getElementById('loginMenu').classList.toggle('open');
}

// Fermer le menu login en cliquant ailleurs
document.addEventListener('click', function(e){
  const dropdown = document.getElementById('loginDropdown');
  const menu = document.getElementById('loginMenu');
  if (!dropdown.contains(e.target)) {
    menu.classList.remove('open');
  }
});

/* ---------- REVEAL ON SCROLL ---------- */
function revealInit(){
  const els = document.querySelectorAll('section, .card, .quote');
  if(!('IntersectionObserver' in window)){
    els.forEach(e => e.classList.add('in'));
    return;
  }
  const io = new IntersectionObserver(es => es.forEach(en => {
    if(en.isIntersecting){
      en.target.classList.add('in');
      io.unobserve(en.target);
    }
  }), {threshold: .06});
  els.forEach(e => {e.classList.add('reveal'); io.observe(e);});
  setTimeout(() => els.forEach(e => e.classList.add('in')), 700);
}
revealInit();
</script>

</body>
</html>