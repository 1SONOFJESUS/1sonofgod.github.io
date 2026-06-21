<?php
/**
 * PAGE GBÔ FITNESS
 * Coaching particuliers & entreprises avec questionnaire intelligent (7 étapes)
 * Conforme au Cahier des Charges GBÔ AFRICA GROUP - Édition 2026
 * CORRIGÉ : cohérent avec le CSS global (styles.css)
 */
$pageTitle = 'GBÔ Fitness — Coaching particuliers & entreprises';
require_once __DIR__ . '/../includes/head.php';
require_once __DIR__ . '/../includes/header.php';

// Données des services particuliers (conforme CDC §7.2.1)
$servicesParticuliers = [
    ['icon' => 'ic-perte', 'titre' => 'Perte de poids', 'desc' => 'Parcours structuré et durable pour atteindre votre poids idéal.'],
    ['icon' => 'ic-masse', 'titre' => 'Prise de masse', 'desc' => 'Développement musculaire encadré et progressif.'],
    ['icon' => 'ic-renfo', 'titre' => 'Renforcement musculaire', 'desc' => 'Gain de force et de tonicité globale.'],
    ['icon' => 'ic-forme', 'titre' => 'Remise en forme', 'desc' => 'Retrouvez énergie et condition physique.'],
    ['icon' => 'ic-femme', 'titre' => 'Fitness féminin', 'desc' => 'Programmes adaptés aux spécificités féminines.'],
    ['icon' => 'ic-prenatal', 'titre' => 'Fitness prénatal', 'desc' => 'Encadrement adapté — validation médicale recommandée.', 'warning' => true],
    ['icon' => 'ic-postnatal', 'titre' => 'Fitness postnatal', 'desc' => 'Récupération progressive et sécurisée.', 'warning' => true],
    ['icon' => 'ic-senior', 'titre' => 'Fitness senior', 'desc' => 'Activité physique douce et sécurisée.', 'warning' => true],
    ['icon' => 'ic-ado', 'titre' => 'Fitness adolescent', 'desc' => 'Accompagnement du développement sportif des jeunes.'],
];

// Données des services entreprises (conforme CDC §7.2.2)
$servicesEntreprises = [
    ['icon' => 'ic-entreprise', 'titre' => 'Coaching en entreprise', 'desc' => 'Programmes sportifs sur mesure pour vos équipes.'],
    ['icon' => 'ic-bienetre', 'titre' => 'Bien-être au travail', 'desc' => 'Prévention santé et qualité de vie professionnelle.'],
    ['icon' => 'ic-team', 'titre' => 'Team Building', 'desc' => "Cohésion et énergie d'équipe par le sport."],
    ['icon' => 'ic-prevention', 'titre' => 'Prévention santé', 'desc' => 'Prévention TMS, gestion du stress, ergonomie.'],
];

// Grille tarifaire de référence (moteur de règles paramétrable - CDC §8.3)
$grilleTarifs = [
    'collectif' => ['nom' => 'Découverte', 'prix' => '15 000', 'seances' => '4', 'incl' => ['Cours collectif', 'Programme général', "Accès groupe WhatsApp"]],
    'individuel' => ['nom' => 'Standard', 'prix' => '35 000', 'seances' => '8', 'incl' => ['Coaching individuel', 'Programme personnalisé', 'Suivi nutritionnel']],
    'domicile' => ['nom' => 'Premium', 'prix' => '65 000', 'seances' => '8', 'incl' => ["Coaching à domicile", 'Programme sur mesure', 'Suivi complet', 'Matériel fourni']],
    'intensif' => ['nom' => 'VIP', 'prix' => '90 000', 'seances' => '12', 'incl' => ['Coaching VIP', 'Programme exclusif', 'Suivi 24/7', 'Nutrition + Sport', "Accès événements"]],
    'prenatal' => ['nom' => "Parcours Prénatal", 'prix' => '25 000', 'seances' => '6', 'incl' => ['Encadrement spécialisé', 'Validation médicale', 'Programme adapté']],
    'postnatal' => ['nom' => "Parcours Postnatal", 'prix' => '30 000', 'seances' => '8', 'incl' => ['Récupération progressive', 'Validation médicale', 'Rééducation']],
];
?>

<!-- ==================== HERO ==================== -->
<section class="page-hero" id="fitnessHero">
    <div class="wrap">
        <span class="eyebrow">GBÔ Fitness</span>
        <h1>Votre remise en forme,<br><span class="lime">encadrée.</span></h1>
        <p>Un accompagnement pour les particuliers comme pour les entreprises — du premier pas à la performance.</p>
        <div class="hero-cta" style="margin-top:26px">
            <div class="tabs" id="fitTabs" role="tablist" aria-label="Navigation Fitness">
                <button class="active" data-tab="part" role="tab" aria-selected="true" aria-controls="pane-part" id="tab-part">
                    <span class="tab-icon">👤</span> Particuliers
                </button>
                <button data-tab="ent" role="tab" aria-selected="false" aria-controls="pane-ent" id="tab-ent">
                    <span class="tab-icon">🏢</span> Entreprises
                </button>
            </div>
        </div>
    </div>
</section>

<!-- ==================== CONTENU PRINCIPAL ==================== -->
<section style="padding-top:10px"><div class="wrap">

    <!-- ═══════════════════════════════════════════ -->
    <!-- ║           ONGLET PARTICULIERS            ║ -->
    <!-- ═══════════════════════════════════════════ -->
    <div class="tabpane active" data-pane="part" id="pane-part" role="tabpanel" aria-labelledby="tab-part">

        <!-- Intro -->
        <div class="split" style="margin-bottom:60px">
            <div>
                <span class="eyebrow">Particuliers</span>
                <h2 style="font-size:36px;margin:14px 0 16px">Atteignez vos objectifs, à votre rythme.</h2>
                <p class="muted">Perte de poids, prise de masse, remise en forme, bien-être : un coach certifié conçoit un programme sur mesure et vous accompagne jusqu'au résultat.</p>
                <ul class="ticks" style="margin-top:18px">
                    <li>Bilan initial offert et objectifs co-construits.</li>
                    <li>Programme individualisé, sûr et progressif.</li>
                    <li>Suivi régulier et ajustements en temps réel.</li>
                </ul>
                <div style="margin-top:26px">
                    <button class="btn btn-primary" onclick="openWizard()">
                        <span>Je réserve ma séance</span>
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                    </button>
                </div>
            </div>
            <div class="media lg" style="position:relative; overflow:hidden;">
                <img src="<?= $siteRoot ?>/assets/images/objectif.webp" alt="Séance de coaching">
                <span class="tag">Séance de coaching</span>
                <div class="media-overlay">
                    <div class="media-stat">
                        <span class="stat-num">500+</span>
                        <span class="stat-label">Coachs certifiés</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Services Grid -->
        <div class="sec-head" style="text-align:center;margin-bottom:30px">
            <span class="eyebrow" style="justify-content:center">Nos programmes</span>
            <h2>Un programme pour chaque objectif</h2>
        </div>

        <div class="grid g3" id="fitServices">
            <?php foreach ($servicesParticuliers as $svc): ?>
            <div class="card pole-card <?= isset($svc['warning']) ? 'service-sensitive' : '' ?>" data-service="<?= htmlspecialchars($svc['titre']) ?>">
                <div class="ic"><svg class="i" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg></div>
                <h3 style="font-size:18px"><?= htmlspecialchars($svc['titre']) ?></h3>
                <p><?= htmlspecialchars($svc['desc']) ?></p>
                <?php if (isset($svc['warning'])): ?>
                <div class="pill" style="margin-top:12px; border-color: var(--lime); color: var(--lime);">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align:middle;margin-right:4px"><path d="M12 9v2m0 4h.01M12 2a10 10 0 100 20 10 10 0 000-20z"/></svg>
                    Validation médicale recommandée
                </div>
                <?php endif; ?>
                <span class="more" onclick="preselectService('<?= htmlspecialchars($svc['titre']) ?>')">En savoir plus <svg class="i" viewBox="0 0 24 24"><path d="M5 12h14M12 5l7 7-7 7"/></svg></span>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- QUESTIONNAIRE INTELLIGENT (7 étapes - CDC §8) -->
        <div style="margin-top:80px" id="wizardWrap">
            <div class="sec-head" style="text-align:center;margin:0 auto 40px; max-width:600px">
                <span class="eyebrow" style="justify-content:center">Questionnaire intelligent</span>
                <h2>Trouvez votre offre idéale</h2>
                <p>7 questions — votre recommandation personnalisée et un tarif estimatif en moins d'une minute.</p>
            </div>

            <div class="wizard" id="fitnessWizard">
                <!-- Barre de progression 7 étapes -->
                <div class="wz-progress-wrap">
                    <div class="wz-steps-labels">
                        <span class="wz-label active" data-step="0">Sexe</span>
                        <span class="wz-label" data-step="1">Âge</span>
                        <span class="wz-label" data-step="2">Niveau</span>
                        <span class="wz-label" data-step="3">Objectif</span>
                        <span class="wz-label" data-step="4">Fréquence</span>
                        <span class="wz-label" data-step="5">Format</span>
                        <span class="wz-label" data-step="6">Localisation</span>
                    </div>
                    <div class="wz-bar"><div class="wz-fill" id="wzFill"></div></div>
                    <div class="wz-counter"><span id="wzCurrent">1</span> / 7</div>
                </div>

                <!-- Étape 0: Sexe -->
                <div class="wz-step active" data-step="0">
                    <h3>Votre sexe ?</h3>
                    <div class="sub">Pour personnaliser votre programme.</div>
                    <div class="opts opts-cards" data-q="sexe">
                        <div class="opt" data-v="Femme"><span class="dot"></span>Femme</div>
                        <div class="opt" data-v="Homme"><span class="dot"></span>Homme</div>
                        <div class="opt" data-v="Non précisé"><span class="dot"></span>Préfère ne pas préciser</div>
                    </div>
                </div>

                <!-- Étape 1: Âge -->
                <div class="wz-step" data-step="1">
                    <h3>Votre tranche d'âge ?</h3>
                    <div class="sub">Pour adapter l'intensité et la progressivité.</div>
                    <div class="opts" data-q="age">
                        <div class="opt" data-v="<18"><span class="dot"></span>Moins de 18 ans</div>
                        <div class="opt" data-v="18-34"><span class="dot"></span>18 – 34 ans</div>
                        <div class="opt" data-v="35-54"><span class="dot"></span>35 – 54 ans</div>
                        <div class="opt" data-v="55+"><span class="dot"></span>55 ans et plus</div>
                    </div>
                </div>

                <!-- Étape 2: Niveau sportif -->
                <div class="wz-step" data-step="2">
                    <h3>Votre niveau actuel ?</h3>
                    <div class="sub">Pour calibrer la progressivité de votre programme.</div>
                    <div class="opts" data-q="niveau">
                        <div class="opt" data-v="Débutant"><span class="dot"></span>Débutant — Je reprends ou je commence</div>
                        <div class="opt" data-v="Intermédiaire"><span class="dot"></span>Intermédiaire — Je pratique régulièrement</div>
                        <div class="opt" data-v="Avancé"><span class="dot"></span>Avancé — Je cherche la performance</div>
                        <div class="opt" data-v="Reprise"><span class="dot"></span>Reprise après pause</div>
                    </div>
                </div>

                <!-- Étape 3: Objectif -->
                <div class="wz-step" data-step="3">
                    <h3>Votre objectif principal ?</h3>
                    <div class="sub">Ce que vous voulez accomplir avant tout.</div>
                    <div class="opts" data-q="objectif">
                        <div class="opt" data-v="Perte de poids"><span class="dot"></span>Perte de poids</div>
                        <div class="opt" data-v="Prise de masse"><span class="dot"></span>Prise de masse</div>
                        <div class="opt" data-v="Renforcement"><span class="dot"></span>Renforcement musculaire</div>
                        <div class="opt" data-v="Remise en forme"><span class="dot"></span>Remise en forme</div>
                        <div class="opt" data-v="Bien-être"><span class="dot"></span>Bien-être & santé</div>
                        <div class="opt" data-v="Prénatal"><span class="dot"></span>Prénatal <span class="pill" style="margin-left:8px; background: var(--lime); color: var(--noir); font-size: 10px; padding: 2px 8px;">Spécialisé</span></div>
                        <div class="opt" data-v="Postnatal"><span class="dot"></span>Postnatal <span class="pill" style="margin-left:8px; background: var(--lime); color: var(--noir); font-size: 10px; padding: 2px 8px;">Spécialisé</span></div>
                    </div>
                    <div class="pill" id="medicalBanner" style="display:none; margin-top: 16px; border-color: var(--lime); color: var(--lime); text-align: left; align-items: center; gap: 8px;">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="flex-shrink:0"><path d="M12 9v2m0 4h.01M12 2a10 10 0 100 20 10 10 0 000-20z"/></svg>
                        Cet objectif nécessite une validation médicale. Notre coach spécialisé vous accompagnera en toute sécurité.
                    </div>
                </div>

                <!-- Étape 4: Fréquence -->
                <div class="wz-step" data-step="4">
                    <h3>Fréquence souhaitée ?</h3>
                    <div class="sub">Séances par semaine.</div>
                    <div class="opts" data-q="frequence">
                        <div class="opt" data-v="1-2"><span class="dot"></span>1 à 2 / semaine</div>
                        <div class="opt" data-v="3"><span class="dot"></span>3 / semaine</div>
                        <div class="opt" data-v="4+"><span class="dot"></span>4+ / semaine</div>
                        <div class="opt" data-v="Indécis"><span class="dot"></span>Je ne sais pas encore</div>
                    </div>
                </div>

                <!-- Étape 5: Format -->
                <div class="wz-step" data-step="5">
                    <h3>Format d'accompagnement ?</h3>
                    <div class="sub">Où et comment vous entraîner.</div>
                    <div class="opts" data-q="format">
                        <div class="opt" data-v="Individuel"><span class="dot"></span>Individuel dédié — À partir de 35 000 FCFA</div>
                        <div class="opt" data-v="Collectif"><span class="dot"></span>Cours collectifs — À partir de 15 000 FCFA</div>
                        <div class="opt" data-v="Domicile"><span class="dot"></span>À domicile — À partir de 65 000 FCFA</div>
                        <div class="opt" data-v="En ligne"><span class="dot"></span>En ligne — À partir de 25 000 FCFA</div>
                    </div>
                </div>

                <!-- Étape 6: Localisation -->
                <div class="wz-step" data-step="6">
                    <h3>Où vous entraînez-vous ?</h3>
                    <div class="sub">Votre commune ou quartier à Abidjan, ou en ligne.</div>
                    <div class="opts" data-q="localisation">
                        <div class="opt" data-v="Cocody"><span class="dot"></span>Cocody</div>
                        <div class="opt" data-v="Marcory"><span class="dot"></span>Marcory</div>
                        <div class="opt" data-v="Plateau"><span class="dot"></span>Plateau</div>
                        <div class="opt" data-v="Yopougon"><span class="dot"></span>Yopougon</div>
                        <div class="opt" data-v="Treichville"><span class="dot"></span>Treichville</div>
                        <div class="opt" data-v="Autre"><span class="dot"></span>Autre commune</div>
                        <div class="opt" data-v="En ligne"><span class="dot"></span>En ligne (partout)</div>
                    </div>
                </div>

                <!-- Étape 7: Résultat -->
                <div class="wz-step" data-step="7">
                    <div class="result">
                        <div class="badge">Votre offre recommandée</div>
                        <h3 id="rName" style="font-size:30px">—</h3>
                        <div class="price" id="rPrice">—</div>
                        <div class="pill" id="rSeances" style="display:inline-block; margin-top:8px; border-color: var(--lime); color: var(--lime);">—</div>
                        <p class="muted" id="rDesc" style="max-width:440px;margin:12px auto 0"></p>
                        <div id="rIncl" style="display:flex;gap:8px;flex-wrap:wrap;justify-content:center;margin-top:18px"></div>

                        <!-- RGPD Consentement explicite (CDC §8.1) -->
                        <div style="margin-top:24px; max-width:480px; margin-left:auto; margin-right:auto; text-align:left">
                            <label style="display:flex;align-items:flex-start;gap:10px;cursor:pointer">
                                <input type="checkbox" id="rgpdConsent" required style="margin-top:3px;flex-shrink:0">
                                <span style="font-size:12px;color:var(--muted);line-height:1.5">J'accepte que mes données personnelles soient collectées pour établir une recommandation personnalisée. Je comprends que cette estimation ne remplace pas un avis médical. <a href="<?= $siteRoot ?>/index.php?page=confidentialite" target="_blank" style="color:var(--lime)">Politique de confidentialité</a>.</span>
                            </label>
                        </div>

                        <p class="muted" style="font-size:12px;margin-top:14px">Tarif estimatif indicatif — confirmé après votre bilan offert.</p>
                        <div style="margin-top:22px;display:flex;gap:12px;justify-content:center;flex-wrap:wrap">
                            <a href="<?= $siteRoot ?>/index.php?page=contact" class="btn btn-primary" id="btnValidate" onclick="return checkConsent()">
                                <span>Valider ma demande</span>
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                            </a>
                            <button class="btn btn-ghost" onclick="resetWizard()">Recommencer</button>
                        </div>
                    </div>
                </div>

                <!-- Navigation -->
                <div class="wz-nav" id="wzNav">
                    <button class="btn btn-ghost btn-sm" id="wzPrev" onclick="wzStep(-1)" style="visibility:hidden">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
                        Retour
                    </button>
                    <button class="btn btn-primary btn-sm" id="wzNext" onclick="wzStep(1)">
                        Continuer
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Réassurance -->
        <div class="grid g3" style="margin-top:60px">
            <div class="card" style="text-align:center">
                <div class="ic" style="margin-left:auto;margin-right:auto"><svg class="i" viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg></div>
                <h4 style="font-size:16px;margin-bottom:8px">Coachs certifiés</h4>
                <p class="muted" style="font-size:13px">Tous nos coachs sont diplômés et formés à la Méthode GBÔ.</p>
            </div>
            <div class="card" style="text-align:center">
                <div class="ic" style="margin-left:auto;margin-right:auto"><svg class="i" viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg></div>
                <h4 style="font-size:16px;margin-bottom:8px">Sécurité avant tout</h4>
                <p class="muted" style="font-size:13px">Encadrement adapté, mentions médicales pour les publics sensibles.</p>
            </div>
            <div class="card" style="text-align:center">
                <div class="ic" style="margin-left:auto;margin-right:auto"><svg class="i" viewBox="0 0 24 24"><path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/></svg></div>
                <h4 style="font-size:16px;margin-bottom:8px">Bilan initial offert</h4>
                <p class="muted" style="font-size:13px">Évaluation complète et définition des objectifs sans engagement.</p>
            </div>
        </div>
    </div>

    <!-- ═══════════════════════════════════════════ -->
    <!-- ║           ONGLET ENTREPRISES             ║ -->
    <!-- ═══════════════════════════════════════════ -->
    <div class="tabpane" data-pane="ent" id="pane-ent" role="tabpanel" aria-labelledby="tab-ent">
        <div class="split" style="margin-bottom:50px">
            <div>
                <span class="eyebrow">Entreprises</span>
                <h2 style="font-size:36px;margin:14px 0 16px">Le bien-être, levier de performance.</h2>
                <p class="muted">Programmes de sport et de prévention santé pour vos équipes — sur site, en ligne ou en team building. Une approche fondée sur la prévention et des résultats mesurables.</p>
                <ul class="ticks" style="margin-top:18px">
                    <li>Diagnostic bien-être et programme sur mesure.</li>
                    <li>Prévention TMS, gestion du stress, cohésion d'équipe.</li>
                    <li>Reporting et suivi des indicateurs de performance.</li>
                </ul>
                <div style="margin-top:24px; display:flex; gap:24px; flex-wrap:wrap">
                    <div style="text-align:center">
                        <span style="display:block;font-size:28px;font-weight:700;color:var(--lime)">30%</span>
                        <span style="font-size:12px;color:var(--muted)">Réduction du turnover</span>
                    </div>
                    <div style="text-align:center">
                        <span style="display:block;font-size:28px;font-weight:700;color:var(--lime)">-25%</span>
                        <span style="font-size:12px;color:var(--muted)">Arrêts maladie</span>
                    </div>
                </div>
            </div>
            <div class="form-card">
                <h3 style="font-size:20px;margin-bottom:6px;text-transform:none;font-family:var(--body);font-weight:700">Prendre rendez-vous</h3>
                <p class="muted" style="font-size:13px;margin-bottom:18px">Un conseiller vous recontacte sous 48 h.</p>
                <form action="submit_entreprise.php" method="POST" onsubmit="return validateForm(this)" id="formEntreprise">
                    <div class="field"><label>Entreprise <span style="color:var(--lime)">*</span></label><input name="entreprise" placeholder="Nom de l'entreprise" required></div>
                    <div class="field"><label>Contact <span style="color:var(--lime)">*</span></label><input name="contact" placeholder="Nom & fonction" required></div>
                    <div class="grid g2" style="gap:14px">
                        <div class="field"><label>E-mail pro <span style="color:var(--lime)">*</span></label><input type="email" name="email" placeholder="vous@entreprise.com" required></div>
                        <div class="field"><label>Téléphone <span style="color:var(--lime)">*</span></label><input name="telephone" placeholder="+225 ..." required></div>
                    </div>
                    <div class="grid g2" style="gap:14px">
                        <div class="field"><label>Effectif <span style="color:var(--lime)">*</span></label>
                            <select name="effectif" required>
                                <option value="">Choisir</option>
                                <option value="1-30">1 – 30 collaborateurs</option>
                                <option value="30-100">30 – 100 collaborateurs</option>
                                <option value="100+">100+ collaborateurs</option>
                            </select>
                        </div>
                        <div class="field"><label>Secteur d'activité <span style="color:var(--lime)">*</span></label>
                            <input name="secteur" placeholder="Ex: Banque, Tech, Industrie..." required>
                        </div>
                    </div>
                    <div class="field"><label>Pack envisagé</label>
                        <select name="pack">
                            <option value="">Choisir un pack</option>
                            <option value="Start">Start — Découverte</option>
                            <option value="Performance">Performance — Standard</option>
                            <option value="Excellence">Excellence — Premium</option>
                            <option value="Sur mesure">Sur mesure</option>
                        </select>
                    </div>
                    <div class="field"><label>Besoin identifié <span style="color:var(--lime)">*</span></label><textarea name="besoin" rows="3" placeholder="Décrivez votre projet bien-être (objectifs, contraintes, effectif concerné...)" required></textarea></div>

                    <!-- RGPD Entreprise -->
                    <div class="field" style="margin-bottom:16px">
                        <label style="display:flex;gap:10px;align-items:flex-start;cursor:pointer">
                            <input type="checkbox" name="rgpd_consent" required style="margin-top:3px;flex-shrink:0">
                            <span style="font-size:12px;color:var(--muted)">J'accepte que mes données professionnelles soient traitées par GBÔ AFRICA GROUP dans le cadre de cette demande de rendez-vous. <a href="<?= $siteRoot ?>/index.php?page=confidentialite" target="_blank" style="color:var(--lime)">En savoir plus</a>.</span>
                        </label>
                    </div>

                    <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center">
                        <span>Demander un rendez-vous</span>
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 2L11 13M22 2l-7 20-4-9-9-4 20-7z"/></svg>
                    </button>
                </form>
            </div>
        </div>

        <!-- Services Entreprises -->
        <div class="sec-head" style="text-align:center;margin-bottom:30px">
            <span class="eyebrow" style="justify-content:center">Nos solutions</span>
            <h2>Des programmes adaptés à votre structure</h2>
        </div>

        <div class="grid g2" style="margin-bottom:40px">
            <?php foreach ($servicesEntreprises as $svc): ?>
            <div class="card pole-card">
                <div class="ic"><svg class="i" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg></div>
                <h3 style="font-size:18px"><?= htmlspecialchars($svc['titre']) ?></h3>
                <p><?= htmlspecialchars($svc['desc']) ?></p>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Réassurance Entreprises -->
        <div class="grid g3">
            <div class="card" style="text-align:center">
                <div class="ic" style="margin-left:auto;margin-right:auto"><svg class="i" viewBox="0 0 24 24"><path d="M3 3v18h18"/><path d="M18.7 8l-5.1 5.2-2.8-2.7L7 14.3"/></svg></div>
                <h4 style="font-size:16px;margin-bottom:8px">Reporting complet</h4>
                <p class="muted" style="font-size:13px">Suivi des indicateurs bien-être et ROI mesurable.</p>
            </div>
            <div class="card" style="text-align:center">
                <div class="ic" style="margin-left:auto;margin-right:auto"><svg class="i" viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg></div>
                <h4 style="font-size:16px;margin-bottom:8px">Prévention certifiée</h4>
                <p class="muted" style="font-size:13px">Programmes conformes aux normes de prévention TMS.</p>
            </div>
            <div class="card" style="text-align:center">
                <div class="ic" style="margin-left:auto;margin-right:auto"><svg class="i" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg></div>
                <h4 style="font-size:16px;margin-bottom:8px">Références</h4>
                <p class="muted" style="font-size:13px">Entreprises de renom nous font confiance à Abidjan.</p>
            </div>
        </div>
    </div>
</div></section>

<!-- ==================== MODAL SERVICE DÉTAIL ==================== -->
<div class="modal" id="serviceModal">
    <div class="modal-box" style="max-width:600px">
        <button class="modal-close" onclick="closeServiceModal()">×</button>
        <div id="serviceModalContent"></div>
    </div>
</div>

<!-- ==================== MODAL QUESTIONNAIRE ==================== -->
<div class="modal" id="wizardModal">
    <div class="modal-box" style="max-width:700px">
        <button class="modal-close" onclick="closeWizard()">×</button>
        <div id="wizardModalContent"></div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>

<!-- ═══════════════════════════════════════════════════════════════ -->
<!-- ║                    JAVASCRIPT DYNAMIQUE                       ║ -->
<!-- ═══════════════════════════════════════════════════════════════ -->
<script>
// ═══════════════════════════════════════════════
// DONNÉES & CONFIGURATION (Moteur de règles paramétrable - CDC §8.3)
// ═══════════════════════════════════════════════
const TARIFS = <?= json_encode($grilleTarifs) ?>;

const REGLES = {
    objectifMap: {
        'Prénatal': 'prenatal',
        'Postnatal': 'postnatal',
        'Perte de poids': 'standard',
        'Prise de masse': 'standard',
        'Renforcement': 'standard',
        'Remise en forme': 'standard',
        'Bien-être': 'standard'
    },
    formatMap: {
        'Collectif': 'collectif',
        'Individuel': 'individuel',
        'Domicile': 'domicile',
        'En ligne': 'individuel'
    },
    frequenceIntensite: {
        '1-2': 'léger',
        '3': 'modéré',
        '4+': 'intensif',
        'Indécis': 'modéré'
    }
};

// ═══════════════════════════════════════════════
// TABS NAVIGATION
// ═══════════════════════════════════════════════
const tabs = document.querySelectorAll('#fitTabs button');
const panes = document.querySelectorAll('.tabpane');

tabs.forEach(tab => {
    tab.addEventListener('click', () => {
        const target = tab.dataset.tab;
        tabs.forEach(t => {
            t.classList.remove('active');
            t.setAttribute('aria-selected', 'false');
        });
        tab.classList.add('active');
        tab.setAttribute('aria-selected', 'true');
        panes.forEach(pane => {
            if (pane.dataset.pane === target) {
                pane.classList.add('active');
            } else {
                pane.classList.remove('active');
            }
        });
    });
});

// ═══════════════════════════════════════════════
// QUESTIONNAIRE INTELLIGENT (7 étapes)
// ═══════════════════════════════════════════════
let currentStep = 0;
const totalSteps = 7;
const answers = {};

function openWizard() {
    const modal = document.getElementById('wizardModal');
    const content = document.getElementById('wizardModalContent');
    content.innerHTML = document.getElementById('fitnessWizard').outerHTML;
    modal.classList.add('open');
    document.body.style.overflow = 'hidden';
    resetWizardState();
}

function closeWizard() {
    document.getElementById('wizardModal').classList.remove('open');
    document.body.style.overflow = '';
}

function resetWizard() {
    resetWizardState();
    updateUI();
}

function resetWizardState() {
    currentStep = 0;
    Object.keys(answers).forEach(k => delete answers[k]);
}

function wzStep(dir) {
    if (dir === 1 && !validateCurrentStep()) {
        shakeStep();
        return;
    }
    const newStep = currentStep + dir;
    if (newStep < 0 || newStep > totalSteps) return;
    currentStep = newStep;
    updateUI();
    if (currentStep === totalSteps) {
        calculateRecommendation();
    }
}

function validateCurrentStep() {
    if (currentStep === totalSteps) return true;
    const stepEl = document.querySelector('.wz-step.active');
    if (!stepEl) return false;
    const selected = stepEl.querySelector('.opt.sel');
    return selected !== null;
}

function shakeStep() {
    const stepEl = document.querySelector('.wz-step.active');
    if (stepEl) {
        stepEl.style.animation = 'shake 0.4s ease';
        setTimeout(() => stepEl.style.animation = '', 400);
    }
}

function updateUI() {
    document.querySelectorAll('.wz-step').forEach(s => s.classList.remove('active'));
    const activeStep = document.querySelector(`.wz-step[data-step="${currentStep}"]`);
    if (activeStep) activeStep.classList.add('active');

    const pct = (currentStep / totalSteps) * 100;
    const fill = document.getElementById('wzFill');
    if (fill) fill.style.width = pct + '%';

    document.querySelectorAll('.wz-label').forEach((l, i) => {
        l.classList.toggle('active', i <= currentStep);
        l.style.opacity = i < currentStep ? '0.6' : '1';
    });

    const counter = document.getElementById('wzCurrent');
    if (counter) counter.textContent = Math.min(currentStep + 1, totalSteps);

    const prevBtn = document.getElementById('wzPrev');
    const nextBtn = document.getElementById('wzNext');
    if (prevBtn) prevBtn.style.visibility = currentStep === 0 ? 'hidden' : 'visible';
    if (nextBtn) {
        if (currentStep === totalSteps) {
            nextBtn.style.display = 'none';
        } else if (currentStep === totalSteps - 1) {
            nextBtn.innerHTML = 'Voir mon offre <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg>';
        } else {
            nextBtn.innerHTML = 'Continuer <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg>';
        }
    }
}

// Gestion des options — CORRECTION: utilise .sel comme dans le CSS global
document.addEventListener('click', function(e) {
    const opt = e.target.closest('.opt');
    if (!opt) return;
    const stepEl = opt.closest('.wz-step');
    if (!stepEl) return;
    const stepNum = parseInt(stepEl.dataset.step);
    if (stepNum !== currentStep) return;

    stepEl.querySelectorAll('.opt').forEach(o => o.classList.remove('sel'));
    opt.classList.add('sel');

    const q = stepEl.querySelector('.opts').dataset.q;
    answers[q] = opt.dataset.v;

    if (q === 'objectif') {
        const medicalBanner = document.getElementById('medicalBanner');
        if (medicalBanner) {
            const sensibles = ['Prénatal', 'Postnatal'];
            medicalBanner.style.display = sensibles.includes(answers.objectif) ? 'flex' : 'none';
        }
    }

    if (currentStep < totalSteps - 1) {
        setTimeout(() => wzStep(1), 400);
    }
});

// ═══════════════════════════════════════════════
// MOTEUR DE RECOMMANDATION
// ═══════════════════════════════════════════════
function calculateRecommendation() {
    const { objectif, format, frequence, age, niveau, localisation } = answers;
    let formuleKey = 'individuel';
    if (objectif === 'Prénatal') formuleKey = 'prenatal';
    else if (objectif === 'Postnatal') formuleKey = 'postnatal';
    else if (format === 'Collectif') formuleKey = 'collectif';
    else if (format === 'Domicile') formuleKey = 'domicile';
    else if (frequence === '4+') formuleKey = 'intensif';

    const offre = TARIFS[formuleKey] || TARIFS['individuel'];
    let nbSeances = parseInt(offre.seances);
    if (frequence === '1-2') nbSeances = Math.max(4, nbSeances - 2);
    if (frequence === '4+') nbSeances = nbSeances + 4;
    if (age === '55+' || age === '<18') nbSeances = Math.max(4, nbSeances - 2);

    document.getElementById('rName').textContent = offre.nom;
    document.getElementById('rPrice').textContent = offre.prix + ' FCFA/mois';
    document.getElementById('rSeances').textContent = nbSeances + ' séances/mois recommandées';

    let desc = `Programme ${offre.nom.toLowerCase()} adapté à votre profil`;
    if (niveau === 'Débutant') desc += ' avec une progression douce';
    if (niveau === 'Avancé') desc += ' avec des objectifs ambitieux';
    if (localisation === 'En ligne') desc += ', entièrement en ligne';
    desc += '.';
    document.getElementById('rDesc').textContent = desc;

    const inclContainer = document.getElementById('rIncl');
    inclContainer.innerHTML = '';
    offre.incl.forEach(item => {
        const tag = document.createElement('span');
        tag.className = 'pill';
        tag.style.cssText = 'border-color: var(--line2); color: var(--white); font-size: 12px; padding: 6px 14px;';
        tag.textContent = '✓ ' + item;
        inclContainer.appendChild(tag);
    });

    window.wizardResult = {
        offre: offre.nom,
        prix: offre.prix,
        seances: nbSeances,
        reponses: answers
    };
}

// ═══════════════════════════════════════════════
// RGPD CONSENTEMENT
// ═══════════════════════════════════════════════
function checkConsent() {
    const consent = document.getElementById('rgpdConsent');
    if (!consent || !consent.checked) {
        alert('Veuillez accepter la politique de confidentialité pour continuer.');
        consent?.focus();
        return false;
    }
    if (window.wizardResult) {
        sessionStorage.setItem('gbofitness_wizard', JSON.stringify(window.wizardResult));
    }
    return true;
}

// ═══════════════════════════════════════════════
// PRESELECT SERVICE
// ═══════════════════════════════════════════════
function preselectService(serviceName) {
    openWizard();
    setTimeout(() => {
        const objectifMap = {
            'Perte de poids': 'Perte de poids',
            'Prise de masse': 'Prise de masse',
            'Renforcement musculaire': 'Renforcement',
            'Remise en forme': 'Remise en forme',
            'Fitness féminin': 'Bien-être',
            'Fitness prénatal': 'Prénatal',
            'Fitness postnatal': 'Postnatal',
            'Fitness senior': 'Bien-être',
            'Fitness adolescent': 'Remise en forme'
        };
        answers.objectif = objectifMap[serviceName];
    }, 500);
}

// ═══════════════════════════════════════════════
// MODAL SERVICE DÉTAIL
// ═══════════════════════════════════════════════
function openServiceModal(serviceName, description) {
    const modal = document.getElementById('serviceModal');
    const content = document.getElementById('serviceModalContent');
    content.innerHTML = `<h2>${serviceName}</h2><p>${description}</p><div style="margin-top:24px"><button class="btn btn-primary" onclick="closeServiceModal(); preselectService('${serviceName}');">Démarrer le questionnaire</button></div>`;
    modal.classList.add('open');
}

function closeServiceModal() {
    document.getElementById('serviceModal').classList.remove('open');
}

// ═══════════════════════════════════════════════
// VALIDATION FORMULAIRE ENTREPRISE
// ═══════════════════════════════════════════════
function validateForm(form) {
    const consent = form.querySelector('input[name="rgpd_consent"]');
    if (!consent.checked) {
        alert('Veuillez accepter la politique de confidentialité.');
        consent.focus();
        return false;
    }
    const btn = form.querySelector('button[type="submit"]');
    btn.innerHTML = '<span style="display:inline-block;width:16px;height:16px;border:2px solid rgba(255,255,255,0.3);border-top-color:#fff;border-radius:50%;animation:spin 0.8s linear infinite;vertical-align:middle;margin-right:8px"></span> Envoi en cours...';
    btn.disabled = true;
    return true;
}

// ═══════════════════════════════════════════════
// ANIMATIONS AU DÉFILEMENT
// ═══════════════════════════════════════════════
const observerOptions = { threshold: 0.1, rootMargin: '0px 0px -50px 0px' };
const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.classList.add('in');
            observer.unobserve(entry.target);
        }
    });
}, observerOptions);

document.querySelectorAll('.card, .split').forEach(el => {
    el.classList.add('reveal');
    observer.observe(el);
});

// ═══════════════════════════════════════════════
// STYLES DYNAMIQUES MINIMALES (uniquement ce qui ne peut pas être dans le CSS global)
// ═══════════════════════════════════════════════
const dynamicStyles = document.createElement('style');
dynamicStyles.textContent = `
    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(-8px); }
        75% { transform: translateX(8px); }
    }
    @keyframes spin { to { transform: rotate(360deg); } }
    .wz-step { display: none; }
    .wz-step.active { display: block; animation: fade .35s; }
    .wz-label { font-size: 11px; color: #666; transition: all 0.3s; }
    .wz-label.active { color: var(--lime); font-weight: 600; }
    .wz-counter { text-align: center; font-size: 13px; color: #888; margin-top: 8px; }
    .wz-progress-wrap { margin-bottom: 32px; }
    .wz-steps-labels { display: flex; justify-content: space-between; margin-bottom: 12px; }
    .service-sensitive { border-color: rgba(198, 242, 2, 0.3); }
    #medicalBanner { align-items: center; gap: 8px; }
    @media (max-width: 680px) {
        .wz-steps-labels { display: none; }
    }
`;
document.head.appendChild(dynamicStyles);

// Fermer modals avec Escape
document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
        closeWizard();
        closeServiceModal();
    }
});

// Fermer modal au clic extérieur
document.querySelectorAll('.modal').forEach(modal => {
    modal.addEventListener('click', (e) => {
        if (e.target === modal) {
            modal.classList.remove('open');
            document.body.style.overflow = '';
        }
    });
});
</script>