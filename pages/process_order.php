<?php
/**
 * TRAITEMENT COMMANDE GBÔ SHOP
 * Recoit les donnees du formulaire et du panier
 */

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: shop.php');
    exit;
}

// Recuperation des donnees
$prenom = htmlspecialchars($_POST['prenom'] ?? '');
$nom = htmlspecialchars($_POST['nom'] ?? '');
$telephone = htmlspecialchars($_POST['telephone'] ?? '');
$email = htmlspecialchars($_POST['email'] ?? '');
$adresse = htmlspecialchars($_POST['adresse'] ?? '');
$commune = htmlspecialchars($_POST['commune'] ?? '');
$notes = htmlspecialchars($_POST['notes'] ?? '');
$orderData = json_decode($_POST['order_data'] ?? '[]', true);

// Validation minimale
if (empty($prenom) || empty($nom) || empty($telephone) || empty($adresse) || empty($commune)) {
    header('Location: checkout.php?error=missing_fields');
    exit;
}

if (empty($orderData)) {
    header('Location: shop.php');
    exit;
}

// Ici vous pouvez:
// 1. Enregistrer la commande en base de donnees
// 2. Envoyer un email de confirmation au client
// 3. Envoyer une notification a l'administration
// 4. Integrer avec un service de paiement (future V2)

// Exemple d'enregistrement en base (a adapter selon votre structure):
/*
require_once __DIR__ . '/../includes/db.php';

$stmt = $pdo->prepare("INSERT INTO orders (prenom, nom, telephone, email, adresse, commune, notes, items, total, status, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending', NOW())");
$total = array_sum(array_map(fn($i) => $i['price'] * $i['qty'], $orderData));
$stmt->execute([$prenom, $nom, $telephone, $email, $adresse, $commune, $notes, json_encode($orderData), $total]);
*/

// Redirection vers la page de confirmation
header('Location: order_success.php');
exit;