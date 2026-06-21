<?php
/**
 * PAGE À PROPOS
 * Notre histoire et mission
 */
$pageTitle = 'À Propos de GBÔ — Notre histoire';
require_once __DIR__ . '/../includes/head.php';
require_once __DIR__ . '/../includes/header.php';
?>

<!-- HERO PAGE -->
<section class="page-hero">
    <div class="wrap">
        <span class="eyebrow">À Propos</span>
        <h1>Notre<br><span class="lime">histoire.</span></h1>
        <p>GBÔ est née d'une vision simple : réunir tous les ingrédients du mouvement africain au sein d'une marque exigeante et accessible.</p>
    </div>
</section>

<!-- CONTENU PRINCIPAL -->
<section>
    <div class="wrap split">
        <div class="media lg"><span class="tag">Fondateurs GBÔ</span></div>
        <div>
            <span class="eyebrow">Notre Mission</span>
            <h2>Transformer la<br>relation aux<br>mouvement en Afrique.</h2>
            <p>GBÔ AFRICA GROUP réinvente le secteur du sport et du bien-être en Côte d'Ivoire, en proposant une approche holistique et professionnelle.</p>
            <ul class="ticks">
                <li>Démocratiser l'accès au coaching de qualité.</li>
                <li>Former une nouvelle génération de professionnels du fitness.</li>
                <li>Créer une communauté d'athlètes engagés et bienveillants.</li>
                <li>Innover dans les offres de bien-être en entreprise.</li>
            </ul>
        </div>
    </div>
</section>

<!-- VALEURS -->
<section class="sec-sm">
    <div class="wrap">
        <div class="sec-head"><span class="eyebrow">Nos Valeurs</span><h2>Ce qui nous guide</h2></div>
        <div class="grid g3">
            <div class="card">
                <div class="ic">
                    <svg class="i" viewBox="0 0 24 24">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm3.5-9c.83 0 1.5-.67 1.5-1.5S16.33 8 15.5 8 14 8.67 14 9.5s.67 1.5 1.5 1.5zm-7 0c.83 0 1.5-.67 1.5-1.5S9.33 8 8.5 8 7 8.67 7 9.5 7.67 11 8.5 11zm3.5 6.5c2.33 0 4.31-1.46 5.11-3.5H6.89c.8 2.04 2.78 3.5 5.11 3.5z"/>
                    </svg>
                </div>
                <h3>Accessibilité</h3>
                <p>Rendre le coaching et le bien-être accessibles à tous, quelque soit le budget ou le niveau.</p>
            </div>
            <div class="card">
                <div class="ic">
                    <svg class="i" viewBox="0 0 24 24">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                    </svg>
                </div>
                <h3>Excellence</h3>
                <p>Maintenir les plus hauts standards de qualité dans tous nos services.</p>
            </div>
            <div class="card">
                <div class="ic">
                    <svg class="i" viewBox="0 0 24 24">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2M16 11a4 4 0 1 1 0-8 4 4 0 0 1 0 8zM8 11a4 4 0 1 1 0-8 4 4 0 0 1 0 8z"/>
                    </svg>
                </div>
                <h3>Communauté</h3>
                <p>Bâtir une communauté solidaire de passionnés du mouvement.</p>
            </div>
        </div>
    </div>
</section>

<!-- STATISTIQUES -->
<div class="strip">
    <div class="wrap">
        <div class="stat"><b>6</b><span>pôles d'activité</span></div>
        <div class="stat"><b>20+</b><span>coachs certifiés</span></div>
        <div class="stat"><b>1000+</b><span>clients satisfaits</span></div>
        <div class="stat"><b>2024</b><span>année de création</span></div>
    </div>
</div>

<!-- CTA FINAL -->
<section class="cta-section">
    <div class="wrap">
        <div class="cta-card">
            <h2>Rejoignez le mouvement</h2>
            <p>Découvrez comment GBÔ peut vous accompagner dans votre parcours fitness et bien-être.</p>
            <a class="btn btn-primary" href="<?= $siteRoot ?>/index.php?page=fitness">Réserver mon bilan gratuit</a>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>

</body>
</html>
