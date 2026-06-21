<?php
/**
 * RESET_PASSWORD.PHP
 * Demande de réinitialisation de mot de passe
 */
session_start();
require_once __DIR__ . '/../config/database.php';

$step = $_GET['step'] ?? 'request'; // request | confirm | success

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($step === 'request' && isset($_POST['email'])) {
        $email = sanitize($_POST['email']);
        
        // Vérifier si l'email existe
        $stmt = $pdo->prepare("SELECT id, nom, prenom FROM coachs WHERE email = ? AND statut = 'actif'");
        $stmt->execute([$email]);
        $coach = $stmt->fetch();
        
        if ($coach) {
            // Générer token
            $token = bin2hex(random_bytes(32));
            $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));
            
            $stmt = $pdo->prepare("UPDATE coachs SET reset_token = ?, reset_expires = ? WHERE id = ?");
            $stmt->execute([$token, $expires, $coach['id']]);
            
            // TODO: Envoyer email avec lien de réinitialisation
            // mail($email, 'Réinitialisation mot de passe GBÔ', "Lien: https://gbo-africa.com/reset_password.php?step=confirm&token=$token");
            
            $step = 'success';
        } else {
            $error = "Aucun compte trouvé avec cet email.";
        }
    }
    
    if ($step === 'confirm' && isset($_POST['password'], $_POST['token'])) {
        $token = $_POST['token'];
        $password = $_POST['password'];
        
        if (strlen($password) < 8) {
            $error = "Le mot de passe doit contenir au moins 8 caractères.";
        } else {
            $stmt = $pdo->prepare("SELECT id FROM coachs WHERE reset_token = ? AND reset_expires > NOW()");
            $stmt->execute([$token]);
            $coach = $stmt->fetch();
            
            if ($coach) {
                $hash = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
                $stmt = $pdo->prepare("UPDATE coachs SET password_hash = ?, reset_token = NULL, reset_expires = NULL WHERE id = ?");
                $stmt->execute([$hash, $coach['id']]);
                
                $step = 'success_reset';
            } else {
                $error = "Lien de réinitialisation invalide ou expiré.";
            }
        }
    }
}

$pageTitle = 'Mot de passe oublié — GBÔ Coach';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle); ?></title>
    <style>
        /* ============================================
           RESET & BASE
           ============================================ */
        *, *::before, *::after {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            /* Couleurs officielles GBÔ */
            --bg-primary: #080A08;
            --bg-secondary: #0D0F0D;
            --bg-card: #15181A;
            --accent: #C6F202;
            --accent-hover: #D4FF1A;
            --text-primary: #FFFFFF;
            --text-secondary: #A0A5AA;
            --text-muted: #6B7280;
            --border: #2A2E33;
            --border-focus: #C6F202;
            --error: #EF4444;
            --error-bg: rgba(239, 68, 68, 0.1);
            --success: #22C55E;
            --success-bg: rgba(34, 197, 94, 0.1);
        }

        html {
            font-size: 16px;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        body {
            font-family: 'Manrope', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background-color: var(--bg-primary);
            color: var(--text-primary);
            min-height: 100vh;
            line-height: 1.6;
            overflow-x: hidden;
        }

        /* ============================================
           TYPOGRAPHIE
           ============================================ */
        .logo-text {
            font-family: 'Archivo', 'Impact', sans-serif;
            font-weight: 900;
            font-size: 24px;
            letter-spacing: -0.5px;
            text-transform: uppercase;
            color: var(--text-primary);
        }

        .logo-text span {
            color: var(--accent);
        }

        .logo-sub {
            font-size: 11px;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: var(--text-muted);
            margin-top: 2px;
        }

        /* ============================================
           HEADER
           ============================================ */
        .site-header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 100;
            background: rgba(8, 10, 8, 0.95);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--border);
        }

        .wrap {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 24px;
        }

        .nav {
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 72px;
        }

        .brand {
            display: flex;
            align-items: center;
            text-decoration: none;
            transition: opacity 0.3s ease;
        }

        .brand:hover {
            opacity: 0.8;
        }

        .nav-cta {
            display: flex;
            gap: 12px;
            align-items: center;
        }

        /* ============================================
           BOUTONS
           ============================================ */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 14px 28px;
            font-family: inherit;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            border: none;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .btn-primary {
            background: var(--accent);
            color: var(--bg-primary);
            font-weight: 700;
        }

        .btn-primary:hover {
            background: var(--accent-hover);
            transform: translateY(-2px);
            box-shadow: 0 8px 32px rgba(198, 242, 2, 0.3);
        }

        .btn-primary:active {
            transform: translateY(0);
        }

        .btn-ghost {
            background: transparent;
            color: var(--text-secondary);
            border: 1px solid var(--border);
            padding: 10px 20px;
            font-size: 13px;
        }

        .btn-ghost:hover {
            border-color: var(--accent);
            color: var(--accent);
            background: rgba(198, 242, 2, 0.05);
        }

        .btn-sm {
            padding: 10px 20px;
            font-size: 13px;
        }

        /* ============================================
           PAGE LOGIN / RESET
           ============================================ */
        .login-page {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 100px 24px 60px;
            background: 
                radial-gradient(ellipse at 20% 50%, rgba(198, 242, 2, 0.03) 0%, transparent 50%),
                radial-gradient(ellipse at 80% 20%, rgba(198, 242, 2, 0.02) 0%, transparent 50%),
                var(--bg-primary);
        }

        .login-container {
            width: 100%;
            max-width: 440px;
            animation: fadeInUp 0.6s ease-out;
        }

        .login-header {
            text-align: center;
            margin-bottom: 32px;
        }

        .login-header .logo-text {
            font-size: 32px;
            margin-bottom: 8px;
        }

        .login-header p {
            color: var(--text-secondary);
            font-size: 15px;
        }

        /* ============================================
           CARTE FORMULAIRE
           ============================================ */
        .form-card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 20px;
            padding: 40px;
            position: relative;
            overflow: hidden;
        }

        .form-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--accent), transparent);
            opacity: 0.6;
        }

        /* ============================================
           FORMULAIRE
           ============================================ */
        .login-form {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .field {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .field label {
            font-size: 13px;
            font-weight: 600;
            color: var(--text-secondary);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .field input {
            width: 100%;
            padding: 14px 16px;
            background: var(--bg-primary);
            border: 1px solid var(--border);
            border-radius: 12px;
            color: var(--text-primary);
            font-family: inherit;
            font-size: 15px;
            transition: all 0.3s ease;
        }

        .field input::placeholder {
            color: var(--text-muted);
        }

        .field input:focus {
            outline: none;
            border-color: var(--border-focus);
            box-shadow: 0 0 0 3px rgba(198, 242, 2, 0.1);
        }

        .field input:hover {
            border-color: #3A3E44;
        }

        .field .hint {
            font-size: 12px;
            color: var(--text-muted);
            margin-top: 4px;
        }

        /* ============================================
           MESSAGES FLASH
           ============================================ */
        .flash-message {
            position: static;
            margin-bottom: 20px;
            padding: 14px 16px;
            border-radius: 12px;
            font-size: 14px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            animation: slideIn 0.4s ease-out;
        }

        .flash-error {
            background: var(--error-bg);
            border: 1px solid rgba(239, 68, 68, 0.2);
            color: #FCA5A5;
        }

        .flash-success {
            background: var(--success-bg);
            border: 1px solid rgba(34, 197, 94, 0.2);
            color: #86EFAC;
        }

        .flash-close {
            background: none;
            border: none;
            color: inherit;
            font-size: 20px;
            cursor: pointer;
            padding: 0;
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0.6;
            transition: opacity 0.2s;
            line-height: 1;
        }

        .flash-close:hover {
            opacity: 1;
        }

        /* ============================================
           ÉTAT SUCCESS
           ============================================ */
        .success-state {
            text-align: center;
            padding: 20px;
            animation: fadeInUp 0.5s ease-out;
        }

        .success-icon {
            font-size: 56px;
            margin-bottom: 20px;
            display: block;
            animation: bounceIn 0.6s ease-out;
        }

        .success-state h3 {
            font-size: 22px;
            font-weight: 700;
            margin-bottom: 10px;
            color: var(--text-primary);
        }

        .success-state p {
            font-size: 14px;
            color: var(--text-secondary);
            margin-bottom: 24px;
            line-height: 1.6;
        }

        .muted {
            color: var(--text-secondary);
        }

        /* ============================================
           ANIMATIONS
           ============================================ */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(-10px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes bounceIn {
            0% {
                transform: scale(0.3);
                opacity: 0;
            }
            50% {
                transform: scale(1.05);
            }
            70% {
                transform: scale(0.9);
            }
            100% {
                transform: scale(1);
                opacity: 1;
            }
        }

        /* ============================================
           RESPONSIVE
           ============================================ */
        @media (max-width: 480px) {
            .login-page {
                padding: 90px 16px 40px;
            }

            .form-card {
                padding: 28px 20px;
                border-radius: 16px;
            }

            .login-header .logo-text {
                font-size: 26px;
            }

            .nav {
                height: 64px;
            }

            .btn {
                padding: 12px 20px;
                font-size: 13px;
            }
        }

        /* ============================================
           ACCESSIBILITÉ
           ============================================ */
        @media (prefers-reduced-motion: reduce) {
            *,
            *::before,
            *::after {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.01ms !important;
            }
        }

        /* Focus visible pour navigation clavier */
        .btn:focus-visible,
        .field input:focus-visible,
        .brand:focus-visible {
            outline: 2px solid var(--accent);
            outline-offset: 2px;
        }

        /* ============================================
           LOADER (optionnel pour le bouton)
           ============================================ */
        .btn-loading {
            position: relative;
            color: transparent !important;
        }

        .btn-loading::after {
            content: '';
            position: absolute;
            width: 18px;
            height: 18px;
            border: 2px solid transparent;
            border-top-color: var(--bg-primary);
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }
    </style>
</head>
<body>

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
                    <a href="/gbo_africa_group/pages/login.php?role=coach" class="btn btn-ghost btn-sm">← Retour connexion</a>
                </div>
            </nav>
        </div>
    </header>

    <section class="login-page">
        <div class="login-container">
            <div class="login-header">
                <div class="logo-text" style="font-size:32px;margin-bottom:8px">GBÔ <span>COACH</span></div>
                <p>Réinitialisation de votre mot de passe</p>
            </div>
            
            <div class="form-card">
                <?php if (isset($error)): ?>
                    <div class="flash-message flash-error">
                        <?php echo htmlspecialchars($error); ?>
                        <button class="flash-close" onclick="this.parentElement.remove()">×</button>
                    </div>
                <?php endif; ?>
                
                <?php if ($step === 'request'): ?>
                    <form method="POST" class="login-form">
                        <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                        
                        <div class="field">
                            <label>Adresse email</label>
                            <input type="email" name="email" placeholder="vous@email.com" required autofocus>
                            <span class="hint">
                                Un lien de réinitialisation vous sera envoyé.
                            </span>
                        </div>
                        
                        <button type="submit" class="btn btn-primary" style="width:100%">
                            Envoyer le lien
                        </button>
                    </form>
                    
                <?php elseif ($step === 'confirm' && isset($_GET['token'])): ?>
                    <form method="POST" class="login-form">
                        <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                        <input type="hidden" name="token" value="<?php echo htmlspecialchars($_GET['token']); ?>">
                        
                        <div class="field">
                            <label>Nouveau mot de passe</label>
                            <input type="password" name="password" placeholder="••••••••" required minlength="8">
                            <span class="hint">
                                Minimum 8 caractères.
                            </span>
                        </div>
                        
                        <button type="submit" class="btn btn-primary" style="width:100%">
                            Réinitialiser le mot de passe
                        </button>
                    </form>
                    
                <?php elseif ($step === 'success'): ?>
                    <div class="success-state">
                        <span class="success-icon">📧</span>
                        <h3>Email envoyé !</h3>
                        <p class="muted">Vérifiez votre boîte de réception et suivez les instructions pour réinitialiser votre mot de passe.</p>
                        <a href="/gbo_africa_group/pages/login.php?role=coach" class="btn btn-primary" style="margin-top:8px">Retour à la connexion</a>
                    </div>
                    
                <?php elseif ($step === 'success_reset'): ?>
                    <div class="success-state">
                        <span class="success-icon">✅</span>
                        <h3>Mot de passe mis à jour !</h3>
                        <p class="muted">Vous pouvez maintenant vous connecter avec votre nouveau mot de passe.</p>
                        <a href="/gbo_africa_group/pages/login.php?role=coach" class="btn btn-primary" style="margin-top:8px">Se connecter</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

</body>
</html>