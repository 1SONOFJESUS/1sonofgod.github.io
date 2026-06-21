<?php
require_once __DIR__ . '/config_session.php';
require_once __DIR__ . '/../config/database.php';

$role = $_GET['role'] ?? $_POST['role'] ?? 'client';
$allowedRoles = ['client', 'coach', 'admin'];

if (!in_array($role, $allowedRoles)) {
    $role = 'client';
}

// Si déjà connecté avec le bon rôle, rediriger
if (isset($_SESSION['user_id']) && $_SESSION['role'] === $role) {
    $redirects = [
        'admin' => '/gbo_africa_group/pages/dashboard_admin.php',
        'coach' => '/gbo_africa_group/pages/dashboard_coach.php',
        'client' => '/gbo_africa_group/pages/dashboard_client.php'
    ];
    header('Location: ' . $redirects[$role]);
    exit;
}

$error = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'] ?? '';
    $remember = isset($_POST['remember']);
    
    $table = match($role) {
        'admin' => 'administrateurs',
        'coach' => 'coachs',
        default => 'clients'
    };
    
    try {
        $stmt = $pdo->prepare("SELECT * FROM {$table} WHERE email = ? AND actif = 1 LIMIT 1");
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        
        if ($user && password_verify($password, $user['mot_de_passe'])) {
            // Régénération de l'ID de session (sécurité)
            session_regenerate_id(true);
            
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $role;
            $_SESSION['email'] = $user['email'];
            $_SESSION['prenom'] = $user['prenom'] ?? $user['nom'] ?? '';
            $_SESSION['nom'] = $user['nom'] ?? '';
            
            // === "SE SOUVENIR DE MOI" ===
            if ($remember) {
                $token = bin2hex(random_bytes(32));
                $tokenHash = hash('sha256', $token);
                $expires = date('Y-m-d H:i:s', strtotime('+30 days'));
                
                // Supprimer anciens tokens de cet utilisateur
                $pdo->prepare("DELETE FROM remember_tokens WHERE user_id = ?")
                    ->execute([$user['id']]);
                
                // Insérer nouveau token
                $pdo->prepare("
                    INSERT INTO remember_tokens (user_id, token_hash, expires_at, created_at)
                    VALUES (?, ?, ?, NOW())
                ")->execute([$user['id'], $tokenHash, $expires]);
                
                setcookie('gbo_remember', $token, [
                    'expires' => time() + (30 * 24 * 60 * 60),
                    'path' => '/gbo_africa_group/',
                    'secure' => isset($_SERVER['HTTPS']),
                    'httponly' => true,
                    'samesite' => 'Lax'
                ]);
            }
            
            // Redirection post-login
            $redirect = $_SESSION['redirect_after_login'] ?? null;
            unset($_SESSION['redirect_after_login']);
            
            if ($redirect) {
                header("Location: $redirect");
            } else {
                $dashboards = [
                    'admin' => '/gbo_africa_group/pages/dashboard_admin.php',
                    'coach' => '/gbo_africa_group/pages/dashboard_coach.php',
                    'client' => '/gbo_africa_group/pages/dashboard_client.php'
                ];
                header('Location: ' . $dashboards[$role]);
            }
            exit;
            
        } else {
            $error = 'Email ou mot de passe incorrect.';
        }
    } catch (PDOException $e) {
        $error = 'Erreur de connexion. Veuillez réessayer.';
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion — <?= ucfirst($role) ?> | GBÔ AFRICA GROUP</title>
    <link rel="stylesheet" href="../assets/css/style_index.css">
</head>
<body class="auth-page">
    <div class="auth-container">
        <div class="auth-card">
            <a href="../index.php" class="brand">
                <div class="logo-text">GBÔ <span>AFRICA</span></div>
                <div class="logo-sub">GROUP</div>
            </a>
            
            <h1>Connexion <?= ucfirst($role) ?></h1>
            
            <?php if ($error): ?>
                <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            
            <form method="POST" action="">
                <input type="hidden" name="role" value="<?= $role ?>">
                
                <div class="form-group">
                    <label for="email">Adresse email</label>
                    <input type="email" id="email" name="email" required 
                           value="<?= htmlspecialchars($email) ?>"
                           placeholder="votre@email.com" autofocus>
                </div>
                
                <div class="form-group">
                    <label for="password">Mot de passe</label>
                    <input type="password" id="password" name="password" required 
                           placeholder="••••••••">
                </div>
                
                <!-- CASE "SE SOUVENIR DE MOI" -->
                <div class="form-group checkbox-group">
                    <label class="checkbox-label">
                        <input type="checkbox" name="remember" id="remember">
                        <span class="checkmark"></span>
                        Se souvenir de moi (30 jours)
                    </label>
                </div>
                
                <button type="submit" class="btn btn-primary btn-block">
                    Se connecter
                </button>
            </form>
            
            <div class="auth-links">
                <a href="../pages/reset_password.php?role=<?= urlencode($role) ?>">Mot de passe oublié ?</a>
                <?php if ($role === 'client'): ?>
                    <a href="../pages/register.php">Créer un compte client</a>
                <?php endif; ?>
            </div>
            
            <div class="role-switch">
                <p>Autre espace ?</p>
                <div class="role-buttons">
                    <?php foreach ($allowedRoles as $r): ?>
                        <?php if ($r !== $role): ?>
                            <a href="?role=<?= $r ?>" class="btn btn-ghost btn-sm">
                                <?= ucfirst($r) ?>
                            </a>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>