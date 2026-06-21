<?php
/**
 * LOGIN_COACH.PHP
 * Formulaire de connexion espace coach sécurisé
 */
session_start();
require_once __DIR__ . '/../config/database.php';

// Si déjà connecté, rediriger vers dashboard
if (isset($_SESSION['coach_session_token'])) {
    header("Location: dashboard_coach.php");
    exit;
}

$pageTitle = 'Connexion — Espace Coach GBÔ';
require_once __DIR__ . '/../includes/head.php';
?>

<header class="site-header">
    <div class="wrap">
        <nav class="nav">
            <a href="/" class="brand">
                <div>
                    <div class="logo-text">GBÔ <span>COACH</span></div>
                    <div class="logo-sub">Espace sécurisé</div>
                </div>
            </a>
            <div class="nav-cta">
                <a href="coach.php" class="btn btn-ghost btn-sm">← Retour espace public</a>
            </div>
        </nav>
    </div>
</header>

<section class="login-page">
    <div class="login-container">
        
        <!-- Messages flash -->
        <?php showFlash(); ?>
        
        <div class="login-header reveal">
            <div class="logo-text" style="font-size:36px;margin-bottom:8px">GBÔ <span>COACH</span></div>
            <p>Espace réservé aux coachs certifiés du réseau GBÔ.</p>
        </div>
        
        <div class="form-card reveal" style="padding:40px">
            <form action="auth_coach.php" method="POST" class="login-form" onsubmit="return validateLogin(this)">
                
                <!-- CSRF Token -->
                <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                
                <div class="field">
                    <label>Identifiant Coach GBÔ</label>
                    <input type="text" name="coach_id" placeholder="GBO-XXXX" required autofocus 
                           pattern="GBO-\d{4,}" title="Format: GBO-XXXX">
                    <span style="font-size:11px;color:var(--muted2);margin-top:4px;display:block">
                        Ex: GBO-0001 (fourni lors de votre certification)
                    </span>
                </div>
                
                <div class="field" style="position:relative">
                    <label>Mot de passe</label>
                    <input type="password" name="password" id="pwd" placeholder="••••••••" required minlength="8">
                    <button type="button" onclick="togglePwd()" style="position:absolute;right:12px;top:38px;background:none;border:none;color:var(--muted);cursor:pointer;font-size:14px">
                        👁️
                    </button>
                </div>
                
                <div class="login-options">
                    <label>
                        <input type="checkbox" name="remember">
                        <span>Rester connecté (30 jours)</span>
                    </label>
                    <a href="reset_password.php">Mot de passe oublié ?</a>
                </div>
                
                <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/></svg>
                    Se connecter
                </button>
            </form>
            
            <div class="login-separator">ou</div>
            
            <div style="text-align:center">
                <p style="font-size:13px;color:var(--muted);margin-bottom:12px">Vous n'êtes pas encore coach GBÔ ?</p>
                <a href="coach.php#devenir-coach" class="btn btn-ghost" style="width:100%;justify-content:center">
                    Devenir Coach GBÔ
                </a>
            </div>
        </div>
        
        <div class="login-footer reveal">
            <p>🔒 Connexion sécurisée — HTTPS — Sessions chiffrées</p>
            <p style="margin-top:8px">Problème de connexion ? <a href="mailto:support@gbo-africa.com">Contactez le support</a></p>
        </div>
    </div>
</section>

<script>
function togglePwd() {
    const pwd = document.getElementById('pwd');
    pwd.type = pwd.type === 'password' ? 'text' : 'password';
}

function validateLogin(form) {
    const coachId = form.coach_id.value.trim().toUpperCase();
    const pwd = form.password.value.trim();
    
    if (!coachId || !pwd) {
        alert('Veuillez remplir tous les champs.');
        return false;
    }
    
    if (!coachId.match(/^GBO-\d{4,}$/)) {
        alert('Format d\'identifiant invalide. Utilisez GBO-XXXX.');
        return false;
    }
    
    const btn = form.querySelector('button[type="submit"]');
    btn.innerHTML = 'Connexion...';
    btn.disabled = true;
    
    return true;
}

// Auto-fermeture des messages flash
document.querySelectorAll('.flash-message').forEach(msg => {
    setTimeout(() => msg.remove(), 5000);
});
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>