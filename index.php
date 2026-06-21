<?php
// Récupère le paramètre "page" dans l'URL
$page = $_GET['page'] ?? 'home';

switch ($page) {
    case 'home':
        include __DIR__ . '/pages/home.php';
        break;
    case 'shop':
        include __DIR__ . '/pages/shop.php';
        break;
    case 'fitness':
        include __DIR__ . '/pages/fitness.php';
        break;
    case 'coach':
        include __DIR__ . '/pages/coach.php';
        break;
    case 'academy':
        include __DIR__ . '/pages/academy.php';
        break;
    case 'apropos':
        include __DIR__ . '/pages/apropos.php';
        break;
    case 'blog':
        include __DIR__ . '/pages/blog.php';
        break;
    case 'contact':
        include __DIR__ . '/pages/contact.php';
        break;
    default:
        include __DIR__ . '/pages/404.php';
        break;
}
?>
