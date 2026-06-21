<?php
/**
 * HEAD COMMUN À TOUTES LES PAGES
 * Inclure : require_once 'includes/head.php'; (depuis la racine)
 *           require_once '../includes/head.php'; (depuis un sous-dossier)
 */

// Détection du chemin de base
$basePath = (strpos($_SERVER['PHP_SELF'], '/pages/') !== false || 
             strpos($_SERVER['PHP_SELF'], '/admin/') !== false) ? '../' : '';

// Titre de page par défaut
$pageTitle = $pageTitle ?? 'GBÔ AFRICA GROUP — Sport · Fitness · Bien-être';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo htmlspecialchars($pageTitle); ?></title>
<meta name="description" content="GBÔ AFRICA GROUP — L'écosystème africain du mouvement. Coaching, formation, événementiel et bien-être en Côte d'Ivoire.">
<meta name="theme-color" content="#080A08">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Archivo:wght@500;600;700;800;900&family=Manrope:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?php echo $basePath; ?>assets/css/base.css">
<link rel="stylesheet" href="<?php echo $basePath; ?>assets/css/layout.css">
<link rel="stylesheet" href="<?php echo $basePath; ?>assets/css/sections.css">
<link rel="stylesheet" href="<?php echo $basePath; ?>assets/css/responsive.css">
<link rel="stylesheet" href="<?php echo $basePath; ?>assets/css/style_index.css">
</head>
<body>