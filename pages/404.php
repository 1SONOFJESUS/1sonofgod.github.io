<?php
/**
 * PAGE 404
 */
$pageTitle = 'Page non trouvée — GBÔ AFRICA GROUP';
require_once __DIR__ . '/../includes/head.php';
require_once __DIR__ . '/../includes/header.php';
?>
<section class="page-hero">
    <div class="wrap">
        <span class="eyebrow">Erreur 404</span>
        <h1>Page non trouvée.</h1>
        <p class="muted">La page que vous recherchez n'existe pas ou a été déplacée.</p>
        <div style="margin-top:28px"><a class="btn btn-primary" href="<?= $siteRoot ?>/index.php?page=home">Retour à l'accueil</a></div>
    </div>
</section>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
