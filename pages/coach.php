<?php
/**
 * PAGE GBÔ COACH — ESPACE PUBLIC
 * Devenir Coach GBÔ : présentation + formulaire de candidature
 * Cahier des charges : MVP — UC-05
 */
$pageTitle = 'GBÔ Coach — Devenir coach certifié';
require_once __DIR__ . '/../includes/head.php';
require_once __DIR__ . '/../includes/header.php';
?>

<!-- ==================== HERO ==================== -->
<section class="page-hero">
    <div class="wrap">
        <span class="eyebrow">GBÔ Coach</span>
        <h1>Le réseau des<br><span class="lime">coachs d'excellence.</span></h1>
        <p>Rejoignez un réseau certifié : la marque vous apporte des clients, une méthode, des outils et une communauté. Vous vous concentrez sur ce que vous aimez — <strong style="color:var(--white)">coacher</strong>.</p>
        
        <div class="hero-cta" style="margin-top:30px">
            <a href="#devenir-coach" class="btn btn-primary">Candidater maintenant</a>
            <a href="<?= $siteRoot ?>/includes/login.php?role=coach" class="btn btn-ghost">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/></svg>
                Espace Coach (connexion)
            </a>
        </div>
    </div>
</section>

<!-- ==================== POURQUOI GBÔ COACH ==================== -->
<section class="sec-sm">
    <div class="wrap">
        <div class="sec-head reveal">
            <span class="eyebrow">Pourquoi nous rejoindre</span>
            <h2>La marque qui propulse<br><span class="lime">votre carrière.</span></h2>
        </div>
        
        <div class="grid g3">
            <div class="card reveal">
                <div class="ic">
                    <svg class="i" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                </div>
                <h3>Apport de clients</h3>
                <p>GBÔ vous fournit un flux continu de clients qualifiés. Plus besoin de chercher — concentrez-vous sur le coaching.</p>
                <div class="more">Commissions 70% → 80%</div>
            </div>
            
            <div class="card reveal">
                <div class="ic">
                    <svg class="i" viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                </div>
                <h3>Certification GBÔ</h3>
                <p>Formation complète à la Méthode GBÔ. Un label reconnu qui vous différencie et rassure vos clients.</p>
                <div class="more">Formation incluse</div>
            </div>
            
            <div class="card reveal">
                <div class="ic">
                    <svg class="i" viewBox="0 0 24 24"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg>
                </div>
                <h3>Carrière évolutive</h3>
                <p>Trajectoire claire : Junior → Confirmé → Senior → Mentor. Chaque niveau débloque de nouveaux avantages.</p>
                <div class="more">4 niveaux de progression</div>
            </div>
        </div>
    </div>
</section>

<!-- ==================== PARCOURS DE CERTIFICATION ==================== -->
<section class="sec-sm" style="background:var(--noir2)">
    <div class="wrap">
        <div class="sec-head reveal">
            <span class="eyebrow">Votre parcours</span>
            <h2>De la candidature<br><span class="lime">à vos premiers clients.</span></h2>
        </div>
        
        <div style="max-width:900px;margin:0 auto">
            <div class="parcours-track reveal" style="justify-content:center;margin-bottom:40px">
                <div class="parcours-step active">
                    <div class="parcours-dot"></div>
                    <span>Candidature</span>
                </div>
                <div class="parcours-arrow">→</div>
                <div class="parcours-step">
                    <div class="parcours-dot"></div>
                    <span>Entretien</span>
                </div>
                <div class="parcours-arrow">→</div>
                <div class="parcours-step">
                    <div class="parcours-dot"></div>
                    <span>Formation</span>
                </div>
                <div class="parcours-arrow">→</div>
                <div class="parcours-step">
                    <div class="parcours-dot"></div>
                    <span>Certification</span>
                </div>
                <div class="parcours-arrow">→</div>
                <div class="parcours-step">
                    <div class="parcours-dot"></div>
                    <span>Intégration</span>
                </div>
                <div class="parcours-arrow">→</div>
                <div class="parcours-step highlight">
                    <div class="parcours-dot"></div>
                    <span>Premiers clients</span>
                </div>
            </div>
            
            <div class="grid g3">
                <div class="card reveal" style="text-align:center">
                    <b style="font-family:var(--disp);font-size:48px;color:var(--lime);line-height:1">72h</b>
                    <p style="margin-top:8px">Réponse à votre candidature</p>
                </div>
                <div class="card reveal" style="text-align:center">
                    <b style="font-family:var(--disp);font-size:48px;color:var(--lime);line-height:1">4</b>
                    <p style="margin-top:8px">Semaines de formation intensive</p>
                </div>
                <div class="card reveal" style="text-align:center">
                    <b style="font-family:var(--disp);font-size:48px;color:var(--lime);line-height:1">100%</b>
                    <p style="margin-top:8px">Taux d'intégration réussie</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ==================== FORMULAIRE CANDIDATURE ==================== -->
<section id="devenir-coach">
    <div class="wrap">
        <div class="split">
            <div class="reveal">
                <span class="eyebrow">Candidature</span>
                <h2 style="font-size:36px;margin:14px 0 16px">Rejoignez la<br><span class="lime">prochaine promotion.</span></h2>
                <p class="muted">Remplissez le formulaire ci-contre. Notre équipe étudie chaque candidature avec soin. Réponse sous 72h ouvrées.</p>
                
                <ul class="ticks" style="margin-top:24px">
                    <li>Formation & certification à la Méthode GBÔ</li>
                    <li>Apport de clients et commissions évolutives (70% → 80%)</li>
                    <li>Trajectoire de carrière : Junior → Confirmé → Senior → Mentor</li>
                    <li>Outils digitaux, planning intelligent et messagerie intégrée</li>
                    <li>Communauté de 120+ coachs certifiés</li>
                </ul>
                
                <div style="margin-top:32px;display:flex;gap:24px;flex-wrap:wrap">
                    <div>
                        <b style="font-family:var(--disp);font-size:32px;color:var(--lime);line-height:1">120+</b>
                        <span style="display:block;font-size:12px;color:var(--muted);margin-top:4px">Coachs certifiés</span>
                    </div>
                    <div>
                        <b style="font-family:var(--disp);font-size:32px;color:var(--lime);line-height:1">4.8★</b>
                        <span style="display:block;font-size:12px;color:var(--muted);margin-top:4px">Satisfaction clients</span>
                    </div>
                    <div>
                        <b style="font-family:var(--disp);font-size:32px;color:var(--lime);line-height:1">85%</b>
                        <span style="display:block;font-size:12px;color:var(--muted);margin-top:4px">Taux de rétention</span>
                    </div>
                </div>
            </div>
            
            <div class="form-card reveal">
                <div style="display:flex;align-items:center;gap:12px;margin-bottom:6px">
                    <div style="width:40px;height:40px;border-radius:50%;background:rgba(198,242,2,.15);display:flex;align-items:center;justify-content:center;font-size:20px">📝</div>
                    <div>
                        <h3 style="font-size:20px;margin-bottom:2px;text-transform:none;font-family:var(--body);font-weight:700">Candidater</h3>
                        <p class="muted" style="font-size:13px">Tous les champs * sont obligatoires</p>
                    </div>
                </div>
                
                <form action="submit_candidature.php" method="POST" enctype="multipart/form-data" onsubmit="return validateForm(this)">
                    <div class="grid g2" style="gap:14px">
                        <div class="field">
                            <label>Nom complet *</label>
                            <input name="nom" placeholder="Nom & prénoms" required>
                        </div>
                        <div class="field">
                            <label>Téléphone *</label>
                            <input name="telephone" type="tel" placeholder="+225 XX XX XX XX" required>
                        </div>
                    </div>
                    
                    <div class="field">
                        <label>E-mail *</label>
                        <input type="email" name="email" placeholder="vous@email.com" required>
                    </div>
                    
                    <div class="grid g2" style="gap:14px">
                        <div class="field">
                            <label>Profil *</label>
                            <select name="profil" required>
                                <option value="">Choisir votre profil</option>
                                <option value="debutant">Débutant passionné</option>
                                <option value="experimente">Coach expérimenté</option>
                                <option value="etudiant">Étudiant</option>
                                <option value="pro">Professionnel du sport</option>
                            </select>
                        </div>
                        <div class="field">
                            <label>Spécialité souhaitée</label>
                            <select name="specialite">
                                <option value="fitness">Fitness général</option>
                                <option value="perte-poids">Perte de poids</option>
                                <option value="prenatal">Prénatal / Postnatal</option>
                                <option value="senior">Senior</option>
                                <option value="performance">Performance</option>
                                <option value="nutrition">Nutrition</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="field">
                        <label>Commune / Ville *</label>
                        <input name="commune" placeholder="Votre commune à Abidjan" required>
                    </div>
                    
                    <div class="field">
                        <label>CV / Documents</label>
                        <input type="file" name="cv" accept=".pdf,.doc,.docx" style="padding:10px">
                        <span style="font-size:11px;color:var(--muted2)">PDF, DOC ou DOCX — max 5 Mo</span>
                    </div>
                    
                    <div class="field">
                        <label>Lettre de motivation *</label>
                        <textarea name="motivation" rows="3" placeholder="Pourquoi souhaitez-vous rejoindre GBÔ ? Décrivez votre parcours et vos motivations..." required></textarea>
                    </div>
                    
                    <div class="field" style="margin-bottom:20px">
                        <label style="display:flex;align-items:flex-start;gap:10px;cursor:pointer;font-weight:400;color:var(--muted)">
                            <input type="checkbox" name="consentement" required style="width:auto;margin-top:3px">
                            <span style="font-size:12px">J'accepte que mes données soient traitées dans le cadre de ma candidature, conformément à la <a href="/pages/confidentialite.php" style="color:var(--lime)">politique de confidentialité</a> GBÔ. *</span>
                        </label>
                    </div>
                    
                    <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center">
                        Envoyer ma candidature
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
                    </button>
                    
                    <p style="text-align:center;font-size:12px;color:var(--muted2);margin-top:14px">
                        ⏱️ Réponse sous 72h ouvrées • 🔒 Données sécurisées
                    </p>
                </form>
            </div>
        </div>
    </div>
</section>

<!-- ==================== COACHS CERTIFIÉS ==================== -->
<section class="sec-sm">
    <div class="wrap">
        <div class="sec-head reveal">
            <span class="eyebrow">Le réseau</span>
            <h2>Nos coachs certifiés</h2>
            <p class="muted">Des professionnels sélectionnés et formés à la Méthode GBÔ.</p>
        </div>
        
        <div class="grid g4">
            <div class="card coach-card reveal" style="padding:0;overflow:hidden">
                <div class="media md" style="position:relative">
                    <span class="tag">⭐ Senior</span>
                    <div style="position:absolute;bottom:0;left:0;right:0;padding:16px;background:linear-gradient(transparent,rgba(8,10,8,.9))">
                        <div style="display:flex;gap:4px">
                            <span class="pill" style="background:rgba(198,242,2,.2);color:var(--lime);border-color:var(--lime);font-size:10px">Fitness féminin</span>
                            <span class="pill" style="background:rgba(198,242,2,.2);color:var(--lime);border-color:var(--lime);font-size:10px">Perte de poids</span>
                        </div>
                    </div>
                </div>
                <div style="padding:18px">
                    <h3 style="font-size:18px;margin-bottom:4px">Awa K.</h3>
                    <p style="font-size:13px;color:var(--muted);margin-bottom:8px">Cocody, Abidjan</p>
                    <div style="display:flex;align-items:center;gap:8px">
                        <span class="pill" style="background:rgba(198,242,2,.1);color:var(--lime);border-color:var(--lime)">Senior</span>
                        <span style="font-size:12px;color:var(--muted2)">98% satisfaction</span>
                    </div>
                </div>
            </div>
            
            <div class="card coach-card reveal" style="padding:0;overflow:hidden">
                <div class="media md" style="position:relative">
                    <span class="tag">🏆 Confirmé</span>
                    <div style="position:absolute;bottom:0;left:0;right:0;padding:16px;background:linear-gradient(transparent,rgba(8,10,8,.9))">
                        <div style="display:flex;gap:4px">
                            <span class="pill" style="background:rgba(198,242,2,.2);color:var(--lime);border-color:var(--lime);font-size:10px">Performance</span>
                            <span class="pill" style="background:rgba(198,242,2,.2);color:var(--lime);border-color:var(--lime);font-size:10px">Prépa physique</span>
                        </div>
                    </div>
                </div>
                <div style="padding:18px">
                    <h3 style="font-size:18px;margin-bottom:4px">Yao D.</h3>
                    <p style="font-size:13px;color:var(--muted);margin-bottom:8px">Marcory, Abidjan</p>
                    <div style="display:flex;align-items:center;gap:8px">
                        <span class="pill">Confirmé</span>
                        <span style="font-size:12px;color:var(--muted2)">45 clients actifs</span>
                    </div>
                </div>
            </div>
            
            <div class="card coach-card reveal" style="padding:0;overflow:hidden">
                <div class="media md" style="position:relative">
                    <span class="tag">🌱 Junior</span>
                    <div style="position:absolute;bottom:0;left:0;right:0;padding:16px;background:linear-gradient(transparent,rgba(8,10,8,.9))">
                        <div style="display:flex;gap:4px">
                            <span class="pill" style="background:rgba(198,242,2,.2);color:var(--lime);border-color:var(--lime);font-size:10px">Renforcement</span>
                        </div>
                    </div>
                </div>
                <div style="padding:18px">
                    <h3 style="font-size:18px;margin-bottom:4px">Marc T.</h3>
                    <p style="font-size:13px;color:var(--muted);margin-bottom:8px">Plateau, Abidjan</p>
                    <div style="display:flex;align-items:center;gap:8px">
                        <span class="pill">Junior</span>
                        <span style="font-size:12px;color:var(--muted2)">Nouveau réseau</span>
                    </div>
                </div>
            </div>
            
            <div class="card coach-card reveal" style="padding:0;overflow:hidden">
                <div class="media md" style="position:relative">
                    <span class="tag">⭐ Senior</span>
                    <div style="position:absolute;bottom:0;left:0;right:0;padding:16px;background:linear-gradient(transparent,rgba(8,10,8,.9))">
                        <div style="display:flex;gap:4px">
                            <span class="pill" style="background:rgba(198,242,2,.2);color:var(--lime);border-color:var(--lime);font-size:10px">Prénatal</span>
                            <span class="pill" style="background:rgba(198,242,2,.2);color:var(--lime);border-color:var(--lime);font-size:10px">Postnatal</span>
                        </div>
                    </div>
                </div>
                <div style="padding:18px">
                    <h3 style="font-size:18px;margin-bottom:4px">Fatou S.</h3>
                    <p style="font-size:13px;color:var(--muted);margin-bottom:8px">Riviera, Abidjan</p>
                    <div style="display:flex;align-items:center;gap:8px">
                        <span class="pill" style="background:rgba(198,242,2,.1);color:var(--lime);border-color:var(--lime)">Senior</span>
                        <span style="font-size:12px;color:var(--muted2)">Mentor junior</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ==================== TÉMOIGNAGES ==================== -->
<section class="sec-sm" style="background:var(--noir2)">
    <div class="wrap">
        <div class="sec-head reveal">
            <span class="eyebrow">Témoignages</span>
            <h2>Ils ont rejoint le réseau</h2>
        </div>
        
        <div class="grid g3">
            <div class="quote reveal">
                <p class="q">"GBÔ m'a donné la structure qu'il me manquait. En 6 mois, je suis passé de 5 à 28 clients actifs. La méthode et la communauté font toute la différence."</p>
                <div class="who">
                    <div class="av"></div>
                    <div>
                        <b>Kouamé B.</b>
                        <span>Coach Confirmé · Fitness</span>
                    </div>
                </div>
            </div>
            <div class="quote reveal">
                <p class="q">"La formation prénatal/postnatal GBÔ m'a ouvert un marché que je ne connaissais pas. Aujourd'hui, c'est 40% de mon chiffre d'affaires."</p>
                <div class="who">
                    <div class="av" style="background:linear-gradient(135deg,#ff6b9d,#c44569)"></div>
                    <div>
                        <b>Aminata D.</b>
                        <span>Coach Senior · Prénatal</span>
                    </div>
                </div>
            </div>
            <div class="quote reveal">
                <p class="q">"Passer de Junior à Mentor en 18 mois, c'est possible. GBÔ investit vraiment dans la montée en compétence de ses coachs."</p>
                <div class="who">
                    <div class="av" style="background:linear-gradient(135deg,#00d2ff,#3a7bd5)"></div>
                    <div>
                        <b>Serge K.</b>
                        <span>Coach Mentor · Performance</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ==================== CTA FINAL ==================== -->
<section class="cta-section">
    <div class="wrap">
        <div class="cta-card reveal">
            <h2>Prêt à transformer<br><span class="lime">votre passion en carrière ?</span></h2>
            <p>Rejoignez 120+ coachs certifiés GBÔ et accédez à une méthode éprouvée, des clients qualifiés et une communauté qui vous pousse au sommet.</p>
            <div style="display:flex;gap:14px;justify-content:center;flex-wrap:wrap;margin-top:8px">
                <a href="#devenir-coach" class="btn btn-primary">Candidater maintenant</a>
                <a href="<?= $siteRoot ?>/includes/login.php?role=coach" class="btn btn-ghost">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/></svg>
                    Déjà coach ? Se connecter
                </a>
            </div>
        </div>
    </div>
</section>

<!-- ==================== SCRIPTS ==================== -->
<script>
function validateForm(form) {
    const tel = form.telephone.value.trim();
    const email = form.email.value.trim();
    const consent = form.consentement?.checked;
    
    const telRegex = /^(\+225|00225)?[0-9\s]{8,12}$/;
    const telClean = tel.replace(/\s/g, '');
    if (!telRegex.test(tel) && !telRegex.test('+225' + telClean)) {
        alert('Veuillez entrer un numéro de téléphone valide (ex: +225 07 XX XX XX XX)');
        form.telephone.focus();
        return false;
    }
    
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
        alert('Veuillez entrer une adresse email valide.');
        form.email.focus();
        return false;
    }
    
    if (!consent) {
        alert('Vous devez accepter la politique de confidentialité pour soumettre votre candidature.');
        return false;
    }
    
    const btn = form.querySelector('button[type="submit"]');
    btn.innerHTML = 'Envoi en cours...';
    btn.disabled = true;
    
    return true;
}

document.addEventListener('DOMContentLoaded', function() {
    const reveals = document.querySelectorAll('.reveal');
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('in');
            }
        });
    }, { threshold: 0.1 });
    reveals.forEach(el => observer.observe(el));
});
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>