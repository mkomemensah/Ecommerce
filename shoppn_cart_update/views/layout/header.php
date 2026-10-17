<?php
require_once __DIR__ . '/../../controllers/CartController.php';
$headerCart = new CartController();
$cartCount  = $headerCart->getCartCount(get_ip());
$cartTotal  = $headerCart->getCartTotal(get_ip());
$page_title = isset($page_title) ? $page_title . ' | Shoppn' : 'Shoppn';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e($page_title); ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo url('css/style.css'); ?>">
</head>
<body>

<a class="skip-link" href="#main">Skip to content</a>

<header class="site-header">
    <div class="container header-inner">
        <a class="logo" href="<?php echo url('index.php'); ?>">Shoppn<span class="logo-dot"></span></a>

        <form class="search" action="<?php echo url('index.php'); ?>" method="GET" role="search">
            <label for="search" class="sr-only">Search products</label>
            <input type="search" name="user_query" id="search" placeholder="Search products"
                   value="<?php echo e($_GET['user_query'] ?? ''); ?>">
            <button type="submit">Search</button>
        </form>

        <nav class="nav" aria-label="Main">
            <a href="<?php echo url('index.php'); ?>">Home</a>
            <a href="<?php echo url('views/brands.php'); ?>">Brands</a>

            <?php if (is_logged_in()): ?>
                <span class="welcome">Welcome, <?php echo e(strtok($_SESSION['customer_name'], ' ')); ?></span>
                <?php if (is_admin()): ?>
                    <a href="<?php echo url('views/admin/product.php'); ?>">Manage products</a>
                    <a href="<?php echo url('views/admin/brand.php'); ?>">Manage brands</a>
                    <a href="<?php echo url('views/admin/category.php'); ?>">Manage categories</a>
                <?php endif; ?>
                <a href="<?php echo url('views/account/my_account.php'); ?>">My account</a>
                <a class="btn btn-small btn-outline-light" href="<?php echo url('logout.php'); ?>">Log out</a>
            <?php else: ?>
                <a href="<?php echo url('views/login.php'); ?>">Log in</a>
                <a class="btn btn-small btn-accent" href="<?php echo url('views/register.php'); ?>">Register</a>
            <?php endif; ?>
        </nav>

        <a class="cart-link" href="<?php echo url('views/cart.php'); ?>"
           aria-label="Shopping cart: <?php echo (int) $cartCount; ?> items, total GH&#8373; <?php echo number_format($cartTotal, 2); ?>. Go to cart">
            <span class="cart-icon" aria-hidden="true">
                <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/>
                </svg>
                <?php if ($cartCount > 0): ?><span class="cart-badge"><?php echo (int) $cartCount; ?></span><?php endif; ?>
            </span>
            <span class="cart-text">
                <span class="cart-label">Cart</span>
                <span class="cart-total">GH&#8373; <?php echo number_format($cartTotal, 2); ?></span>
            </span>
        </a>
    </div>
</header>

<main id="main" class="container main">
