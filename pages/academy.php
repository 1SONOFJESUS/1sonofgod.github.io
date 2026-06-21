<?php
/**
 * PAGE GBÔ ACADEMY
 * Formations et certifications
 */
$pageTitle = 'GBÔ Academy — Formations aux métiers du sport';
require_once __DIR__ . '/../includes/head.php';
require_once __DIR__ . '/../includes/header.php';
?>

<section class="page-hero">
    <div class="wrap">
        <span class="eyebrow">GBÔ Academy</span>
        <h1>Formez-vous aux<br><span class="lime">métiers du sport.</span></h1>
        <p>Certifications reconnues, formations continues et parcours professionnels pour devenir coach GBÔ.</p>
    </div>
</section>

<section><div class="wrap">
    <div class="sec-head" style="text-align:center;margin:0 auto 52px">
        <span class="eyebrow" style="justify-content:center">Nos formations</span>
        <h2>Parcours certifiants</h2>
        <p>Des programmes conçus par des experts pour vous donner toutes les clés de la réussite.</p>
    </div>
    
    <div class="grid g3">
        <div class="card">
            <div class="ic"><svg class="i" viewBox="0 0 24 24"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c0 2 2 3 6 3s6-1 6-3v-5"/></svg></div>
            <h3>Certification Coach GBÔ</h3>
            <p>120h de formation pratique et théorique. Méthode B.O.U.G.E., sécurité, pédagogie.</p>
            <span class="more">En savoir plus →</span>
        </div>
        <div class="card">
            <div class="ic"><svg class="i" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg></div>
            <h3>Formation continue</h3>
            <p>Ateliers spécialisés : nutrition, performance, coaching senior, prénatal.</p>
            <span class="more">En savoir plus →</span>
        </div>
        <div class="card">
            <div class="ic"><svg class="i" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg></div>
            <h3>Bootcamp intensif</h3>
            <p>2 semaines intensives pour accélérer votre lancement professionnel.</p>
            <span class="more">En savoir plus →</span>
        </div>
    </div>
    
    <div class="cta-card" style="margin-top:60px">
        <h2>Prêt à vous former ?</h2>
        <p>Rejoignez la prochaine promotion et lancez votre carrière de coach certifié.</p>
        <a href="<?= $siteRoot ?>/index.php?page=coach" class="btn btn-primary">Candidater maintenant</a>
    </div>
</div></section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>