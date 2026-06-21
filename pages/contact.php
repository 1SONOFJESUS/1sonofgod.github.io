<?php
/**
 * PAGE CONTACT
 */
$pageTitle = 'Contact — GBÔ Africa Group';
require_once __DIR__ . '/../includes/head.php';
require_once __DIR__ . '/../includes/header.php';
?>

<section class="page-hero">
    <div class="wrap">
        <span class="eyebrow">Contact</span>
        <h1>Discutons de votre<br><span class="lime">projet.</span></h1>
        <p>Une question, un projet, une collaboration ? Nous sommes à votre écoute.</p>
    </div>
</section>

<section><div class="wrap">
    <div class="split">
        <div>
            <h2 style="font-size:28px;margin-bottom:18px">Nos coordonnées</h2>
            <div style="display:grid;gap:20px">
                <div style="display:flex;gap:14px;align-items:flex-start">
                    <div class="ic" style="margin:0;flex-shrink:0"><svg class="i" viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg></div>
                    <div>
                        <h4 style="font-size:14px;text-transform:uppercase;letter-spacing:.1em;color:var(--lime);margin-bottom:4px">Adresse</h4>
                        <p class="muted">Plateau, Abidjan, Côte d'Ivoire</p>
                    </div>
                </div>
                <div style="display:flex;gap:14px;align-items:flex-start">
                    <div class="ic" style="margin:0;flex-shrink:0"><svg class="i" viewBox="0 0 24 24"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg></div>
                    <div>
                        <h4 style="font-size:14px;text-transform:uppercase;letter-spacing:.1em;color:var(--lime);margin-bottom:4px">Téléphone</h4>
                        <p class="muted">+225 01 03 16 14 15</p>
                    </div>
                </div>
                <div style="display:flex;gap:14px;align-items:flex-start">
                    <div class="ic" style="margin:0;flex-shrink:0"><svg class="i" viewBox="0 0 24 24"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg></div>
                    <div>
                        <h4 style="font-size:14px;text-transform:uppercase;letter-spacing:.1em;color:var(--lime);margin-bottom:4px">E-mail</h4>
                        <p class="muted">contact@gbo-officiel.com</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="form-card">
            <h3 style="font-size:20px;margin-bottom:6px;text-transform:none;font-family:var(--body);font-weight:700">Envoyer un message</h3>
            <p class="muted" style="font-size:13px;margin-bottom:18px">Nous vous répondons sous 24h.</p>
            <form action="submit_contact.php" method="POST" onsubmit="return validateForm(this)">
                <div class="grid g2" style="gap:14px">
                    <div class="field"><label>Nom</label><input name="nom" placeholder="Votre nom" required></div>
                    <div class="field"><label>Téléphone</label><input name="telephone" placeholder="+225 ..."></div>
                </div>
                <div class="field"><label>E-mail</label><input type="email" name="email" placeholder="vous@email.com" required></div>
                <div class="field"><label>Sujet</label>
                    <select name="sujet" required>
                        <option value="">Choisir un sujet</option>
                        <option>Demande de coaching</option>
                        <option>Devenir coach</option>
                        <option>Partenariat entreprise</option>
                        <option>Question produit</option>
                        <option>Autre</option>
                    </select>
                </div>
                <div class="field"><label>Message</label><textarea name="message" rows="4" placeholder="Votre message..." required></textarea></div>
                <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center">Envoyer mon message</button>
            </form>
        </div>
    </div>
</div></section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>