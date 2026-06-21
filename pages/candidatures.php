<?php
/**
 * ADMIN/CANDIDATURES.PHP
 * Gestion des candidatures coachs
 * Permission requise : gestion_candidatures
 */

require_once __DIR__ . '/../../includes/auth_check.php';
$user = checkAuth('gestion_candidatures');

// Traitement actions
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'], $_POST['candidature_id'])) {
    if (!isset($_POST['csrf_token']) || !verifyCSRFToken($_POST['csrf_token'])) {
        redirect('candidatures.php', 'error', 'Token invalide.');
    }
    
    $candId = (int)$_POST['candidature_id'];
    $action = $_POST['action'];
    $commentaire = sanitize($_POST['commentaire'] ?? '');
    
    $nouveauStatut = match($action) {
        'accepter' => 'acceptee',
        'refuser' => 'refusee',
        'entretien' => 'entretien',
        'formation' => 'formation',
        default => null
    };
    
    if ($nouveauStatut) {
        $stmt = $pdo->prepare("
            UPDATE candidatures 
            SET statut = ?, commentaire_admin = ?, date_traitement = NOW() 
            WHERE id = ?
        ");
        $stmt->execute([$nouveauStatut, $commentaire, $candId]);
        
        // Log action
        logAdminAction($user['id'], 'candidature_' . $action, 'candidatures', $candId, [
            'nouveau_statut' => $nouveauStatut,
            'commentaire' => $commentaire
        ]);
        
        redirect('candidatures.php', 'success', 'Candidature ' . $nouveauStatut . ' avec succès.');
    }
}

// Filtres
$statutFiltre = $_GET['statut'] ?? 'nouvelle';
$stmt = $pdo->prepare("SELECT * FROM candidatures WHERE statut = ? ORDER BY date_creation DESC");
$stmt->execute([$statutFiltre]);
$candidatures = $stmt->fetchAll();

// Compteurs
$counts = $pdo->query("
    SELECT statut, COUNT(*) as nb FROM candidatures GROUP BY statut
")->fetchAll(PDO::FETCH_KEY_PAIR);

$pageTitle = 'Gestion Candidatures — Admin GBÔ';
require_once __DIR__ . '/../../includes/head.php';
?>

<header class="site-header">
    <div class="wrap">
        <nav class="nav">
            <a href="../dashboard_admin.php" class="brand">
                <div>
                    <div class="logo-text">GBÔ <span>ADMIN</span></div>
                    <div class="logo-sub">Gestion candidatures</div>
                </div>
            </a>
            <div class="nav-cta">
                <a href="../dashboard_admin.php" class="btn btn-ghost btn-sm">← Dashboard</a>
            </div>
        </nav>
    </div>
</header>

<section style="padding-top:100px;padding-bottom:40px">
    <div class="wrap">
        <?php showFlash(); ?>
        
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:24px">
            <h1 style="font-size:28px">Gestion des <span class="lime">candidatures</span></h1>
            <div style="display:flex;gap:8px">
                <?php 
                $statuts = ['nouvelle' => 'Nouvelles', 'en_etude' => 'En étude', 'entretien' => 'Entretien', 'formation' => 'Formation', 'acceptee' => 'Acceptées', 'refusee' => 'Refusées'];
                foreach ($statuts as $key => $label): 
                    $count = $counts[$key] ?? 0;
                ?>
                <a href="?statut=<?php echo $key; ?>" 
                   class="chip <?php echo $statutFiltre === $key ? 'active' : ''; ?>"
                   style="text-decoration:none">
                    <?php echo $label; ?> <?php if ($count > 0): ?><span style="margin-left:4px;font-weight:800">(<?php echo $count; ?>)</span><?php endif; ?>
                </a>
                <?php endforeach; ?>
            </div>
        </div>
        
        <div class="card">
            <?php if (empty($candidatures)): ?>
                <p class="muted" style="text-align:center;padding:40px">Aucune candidature dans cette catégorie.</p>
            <?php else: ?>
                <div style="overflow-x:auto">
                    <table style="width:100%;border-collapse:collapse;font-size:13px">
                        <thead>
                            <tr style="border-bottom:2px solid var(--line);text-align:left">
                                <th style="padding:12px">Nom</th>
                                <th style="padding:12px">Contact</th>
                                <th style="padding:12px">Profil</th>
                                <th style="padding:12px">Spécialité</th>
                                <th style="padding:12px">Date</th>
                                <th style="padding:12px">CV</th>
                                <th style="padding:12px">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($candidatures as $c): ?>
                            <tr style="border-bottom:1px solid var(--line)">
                                <td style="padding:12px">
                                    <b><?php echo htmlspecialchars($c['nom']); ?></b>
                                    <?php if ($c['motivation']): ?>
                                    <p style="font-size:11px;color:var(--muted);margin-top:4px;max-width:200px;overflow:hidden;text-overflow:ellipsis">
                                        "<?php echo htmlspecialchars(substr($c['motivation'], 0, 60)); ?>..."
                                    </p>
                                    <?php endif; ?>
                                </td>
                                <td style="padding:12px">
                                    <div><?php echo htmlspecialchars($c['email']); ?></div>
                                    <div style="font-size:11px;color:var(--muted)"><?php echo htmlspecialchars($c['telephone']); ?></div>
                                </td>
                                <td style="padding:12px"><?php echo htmlspecialchars($c['profil']); ?></td>
                                <td style="padding:12px"><?php echo htmlspecialchars($c['specialite']); ?></td>
                                <td style="padding:12px;font-size:12px;color:var(--muted)">
                                    <?php echo date('d/m/Y', strtotime($c['date_creation'])); ?>
                                </td>
                                <td style="padding:12px">
                                    <?php if ($c['cv_fichier']): ?>
                                    <a href="<?php echo htmlspecialchars($c['cv_fichier']); ?>" target="_blank" class="btn btn-sm btn-dark" style="padding:6px 12px;font-size:11px">📄 CV</a>
                                    <?php else: ?>
                                    <span style="font-size:11px;color:var(--muted2)">Aucun</span>
                                    <?php endif; ?>
                                </td>
                                <td style="padding:12px">
                                    <form method="POST" style="display:flex;gap:6px;flex-wrap:wrap">
                                        <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                                        <input type="hidden" name="candidature_id" value="<?php echo $c['id']; ?>">
                                        
                                        <?php if ($c['statut'] === 'nouvelle' || $c['statut'] === 'en_etude'): ?>
                                        <button type="submit" name="action" value="entretien" class="btn btn-sm btn-dark" style="padding:6px 12px;font-size:11px;background:rgba(33,150,243,.1);color:#2196f3;border-color:#2196f3">Entretien</button>
                                        <?php endif; ?>
                                        
                                        <?php if (in_array($c['statut'], ['nouvelle', 'en_etude', 'entretien'])): ?>
                                        <button type="submit" name="action" value="accepter" class="btn btn-sm btn-primary" style="padding:6px 12px;font-size:11px">✓ Accepter</button>
                                        <button type="submit" name="action" value="refuser" class="btn btn-sm btn-dark" style="padding:6px 12px;font-size:11px;background:rgba(244,67,54,.1);color:#f44336;border-color:#f44336">✕ Refuser</button>
                                        <?php endif; ?>
                                    </form>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>