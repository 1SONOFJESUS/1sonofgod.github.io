<?php
/**
 * PAGE D'ACCUEIL
 */
$pageTitle = 'GBÔ AFRICA GROUP — Sport · Fitness · Bien-être';
require_once __DIR__ . '/../includes/head.php';
require_once __DIR__ . '/../includes/header.php';
?>

<!-- ============ HERO ============ -->
<section class="hero">
  <div class="wrap hero-grid">
    <div>
      <span class="eyebrow">Sport · Fitness · Bien-être — Côte d'Ivoire</span>
      <h1>L'écosystème<br>africain du<br><em>mouvement.</em></h1>
      <p>GBÔ réunit coaching, formation, événementiel et bien-être au sein d'une même marque exigeante et accessible. Plus qu'une pratique, un style de vie.</p>
      <div class="hero-cta">
        <a class="btn btn-primary" href="<?= $siteRoot ?>/index.php?page=fitness" >Découvrir nos services</a>
        <a class="btn btn-ghost" href="<?= $siteRoot ?>/index.php?page=fitness" >Réserver un coaching</a>
      </div>
      <div class="hero-badges">
        <div><b>6</b><span>pôles intégrés</span></div>
        <div><b>20+</b><span>coachs certifiés</span></div>
        <div><b>100%</b><span>Méthode GBÔ</span></div>
      </div>
    </div>
    <div class="hero-collage">
      <div class="media lg">
        <img src="<?= $siteRoot ?>/assets/images/hommealtere.webp" alt="Athlète · Abidjan">
        <span class="tag">Athlète · Abidjan</span>
      </div>
      <div class="media">
        <img src="<?= $siteRoot ?>/assets/images/coachh.webp" alt="Coaching">
        <span class="tag">Coaching</span>
      </div>
      <div class="media">
        <img src="<?= $siteRoot ?>/assets/images/imagesportcorde.webp" alt="Communauté">
        <span class="tag">Communauté</span>
      </div>
    </div>
  </div>
  <div class="scroll-hint">↓ défiler</div>
</section>

<!-- ============ VIDÉO DE PRÉSENTATION ============ -->
<section class="video-section">
  <div class="wrap">
    <div class="video-container" id="videoContainer">
  <span class="video-label">Présentation GBÔ</span>
  <video id="presentationVideo" preload="metadata" playsinline>
    <source src="assets/video/video.mp4" type="video/mp4">
    Votre navigateur ne supporte pas la lecture de vidéos.
  </video>
  <div class="video-overlay" id="videoOverlay" onclick="toggleVideo()">
    <div class="play-btn">
      <svg viewBox="0 0 24 24"><polygon points="5 3 19 12 5 21 5 3"/></svg>
    </div>
  </div>
</div>
    
  </div>
</section>

<script>
/* ---------- VIDÉO PLAY / PAUSE ---------- */
function toggleVideo(){
  const video = document.getElementById('presentationVideo');
  const overlay = document.getElementById('videoOverlay');
  const playBtn = document.getElementById('playBtn');
  const pauseBtn = document.getElementById('pauseBtn');

  if(video.paused){
    video.play();
    overlay.style.opacity = '0';
    overlay.style.pointerEvents = 'none';
    playBtn.style.display = 'none';
    pauseBtn.style.display = 'none';
  } else {
    video.pause();
    overlay.style.opacity = '1';
    overlay.style.pointerEvents = 'auto';
    playBtn.style.display = 'block';
    pauseBtn.style.display = 'none';
  }
}

// Pause automatique quand on clique sur la vidéo en cours de lecture
document.getElementById('presentationVideo').addEventListener('click', function(){
  const video = document.getElementById('presentationVideo');
  const overlay = document.getElementById('videoOverlay');
  const playBtn = document.getElementById('playBtn');

  if(!video.paused){
    video.pause();
    overlay.style.opacity = '1';
    overlay.style.pointerEvents = 'auto';
    playBtn.style.display = 'block';
  }
});

// Quand la vidéo se termine, réafficher le bouton play
document.getElementById('presentationVideo').addEventListener('ended', function(){
  const overlay = document.getElementById('videoOverlay');
  const playBtn = document.getElementById('playBtn');
  overlay.style.opacity = '1';
  overlay.style.pointerEvents = 'auto';
  playBtn.style.display = 'block';
});
</script>

<!-- ============ STATS ============ -->
<div class="strip"><div class="wrap">
  <div class="stat"><b><i>+30%</i></b><span>de croissance visée / an</span></div>
  <div class="stat"><b>6</b><span>pôles complémentaires</span></div>
  <div class="stat"><b>≥90%</b><span>satisfaction visée</span></div>
  <div class="stat"><b>24/7</b><span>coaching en ligne</span></div>
</div></div>

<!-- ============ PRÉSENTATION ============ -->
<section><div class="wrap split">
  <div class="media lg">
    <img src="<?= $siteRoot ?>/assets/images/legbo.webp" alt="Coach professionnel GBÔ">
    <span class="tag">Coach professionnel GBÔ</span>
  </div>
  <div>
    <span class="eyebrow">Qui sommes-nous</span>
    <h2>Une marque<br>professionnelle,<br>accessible & premium.</h2>
    <p>Là où le marché propose des offres éparses, GBÔ relie le coaching, la formation, l'événementiel, l'entreprise et le commerce au sein d'un écosystème cohérent, porté par une exigence de qualité unique.</p>
    <ul class="ticks">
      <li>Coachs certifiés selon la Méthode GBÔ (cycle B.O.U.G.E.).</li>
      <li>Accompagnement de tous les publics, en toute sécurité.</li>
      <li>Présence à domicile, en salle, en ligne et en entreprise.</li>
    </ul>
    <div style="margin-top:28px"><a class="btn btn-ghost" href="<?= $siteRoot ?>/index.php?page=apropos" >Notre histoire</a></div>
  </div>
</div></section>

<!-- ============ PÔLES ============ -->
<section class="sec-sm"><div class="wrap">
  <div class="sec-head"><span class="eyebrow">L'écosystème</span><h2>Explorez nos pôles</h2><p>Quatre portes d'entrée vers le mouvement GBÔ. Chaque pôle nourrit les autres.</p></div>
  <div class="grid g4">
    <a href="<?= $siteRoot ?>/index.php?page=fitness" target="_blank" class="card pole-card">
      <div class="ic"><svg class="i" viewBox="0 0 24 24"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg></div>
      <h3>GBÔ Fitness</h3>
      <p>Remise en forme et coaching pour tous les publics.</p>
      <span class="more">En savoir plus →</span>
    </a>
    <a href="<?= $siteRoot ?>/index.php?page=academy" target="_blank" class="card pole-card">
      <div class="ic"><svg class="i" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg></div>
      <h3>GBÔ Academy</h3>
      <p>Formations et certifications aux métiers du sport.</p>
      <span class="more">En savoir plus →</span>
    </a>
    <a href="<?= $siteRoot ?>/index.php?page=coach" target="_blank" class="card pole-card">
      <div class="ic"><svg class="i" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg></div>
      <h3>GBÔ Coach</h3>
      <p>Le réseau de coachs certifiés, à domicile et en ligne.</p>
      <span class="more">En savoir plus →</span>
    </a>
    <a href="<?= $siteRoot ?>/index.php?page=shop" target="_blank" class="card pole-card">
      <div class="ic"><svg class="i" viewBox="0 0 24 24"><path d="M6.5 6.5l11 11"/><path d="M21 21l-1-1"/><path d="M3 3l1 1"/><path d="M18 22l4-4"/><path d="M2 6l4-4"/><path d="M3 10l7-7"/><path d="M14 21l7-7"/></svg></div>
      <h3>GBÔ Shop</h3>
      <p>Produits sport & bien-être — ouverture prochaine.</p>
      <span class="more">En savoir plus →</span>
    </a>
  </div>
</div></section>

<!-- ============ COACHS ============ -->
<section><div class="wrap">
  <div class="sec-head"><span class="eyebrow">Le réseau</span><h2>Nos coachs</h2><p>Des professionnels certifiés, encadrés par un Coach Mentor Senior de plus de 25 ans d'expérience.</p></div>
  <div class="grid g4">
    <div class="card" style="padding:0;overflow:hidden">
      <div class="media md">
        <img src="<?= $siteRoot ?>/assets/images/coachf.webp" alt="Coach Awa">
        <span class="tag">Coach Awa</span>
      </div>
      <div style="padding:18px"><h3 style="font-size:18px">Awa</h3><p>Fitness féminin</p></div>
    </div>
    <div class="card" style="padding:0;overflow:hidden">
      <div class="media md">
        <img src="<?= $siteRoot ?>/assets/images/coachh.webp" alt="Coach Yao">
        <span class="tag">Coach Yao</span>
      </div>
      <div style="padding:18px"><h3 style="font-size:18px">Yao</h3><p>Performance</p></div>
    </div>
    <div class="card" style="padding:0;overflow:hidden">
      <div class="media md">
        <img src="<?= $siteRoot ?>/assets/images/coach.webp" alt="Coach Marc">
        <span class="tag">Coach Marc</span>
      </div>
      <div style="padding:18px"><h3 style="font-size:18px">Marc</h3><p>Renforcement</p></div>
    </div>
    <div class="card" style="padding:0;overflow:hidden">
      <div class="media md">
        <img src="<?= $siteRoot ?>/assets/images/coachff.webp" alt="Coach Fatou">
        <span class="tag">Coach Fatou</span>
      </div>
      <div style="padding:18px"><h3 style="font-size:18px">Fatou</h3><p>Prénatal/Postnatal</p></div>
    </div>
  </div>
</div></section>

<!-- ============ TÉMOIGNAGES ============ -->
<section class="sec-sm"><div class="wrap">
  <div class="sec-head"><span class="eyebrow">Ils nous font confiance</span><h2>Témoignages</h2></div>
  <div class="grid g3">
    <div class="quote">
      <div class="q">« Un accompagnement qui change tout : sécurité, méthode et résultats. Je ne lâche plus. »</div>
      <div class="who">
        <div class="av"><img src="<?= $siteRoot ?>/assets/images/coachf.webp" alt="Aïcha B."></div>
        <div><b>Aïcha B.</b><span>Cliente Fitness</span></div>
      </div>
    </div>
    <div class="quote">
      <div class="q">« Grâce à GBÔ, j'ai professionnalisé mon activité et trouvé mes premiers clients. »</div>
      <div class="who">
        <div class="av"><img src="<?= $siteRoot ?>/assets/images/coachh.webp" alt="Yao K."></div>
        <div><b>Yao K.</b><span>Coach GBÔ</span></div>
      </div>
    </div>
    <div class="quote">
      <div class="q">« Nos équipes sont plus engagées depuis le programme bien-être GBÔ Corporate. »</div>
      <div class="who">
        <div class="av"><img src="<?= $siteRoot ?>/assets/images/client.webp" alt="M. Koné"></div>
        <div><b>M. Koné</b><span>DRH</span></div>
      </div>
    </div>
  </div>
  <p class="muted" style="font-size:12.5px;margin-top:18px">* Les témoignages sont modifiables depuis le <a href="<?= $siteRoot ?>/pages/admin/login.php" target="_blank" style="color:var(--lime)">dashboard administrateur</a>.</p>
</div></section>

<!-- ============ CTA ============ -->
<section class="cta-section"><div class="wrap">
  <div class="cta-card">
    <h2>Prêt à bouger ?</h2>
    <p>Réservez votre bilan offert et recevez une offre adaptée à vos objectifs en moins d'une minute.</p>
    <a class="btn btn-primary" href="<?= $siteRoot ?>/index.php?page=fitness">Réserver mon coaching</a>
  </div>
</div></section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>