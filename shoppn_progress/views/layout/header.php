<?php $page_title = isset($page_title) ? $page_title . ' | Shoppn' : 'Shoppn'; ?>
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

        <form class="search" action="<?php echo url('views/brands.php'); ?>" method="GET" role="search">
            <label for="search" class="sr-only">Search brands</label>
            <input type="search" name="search" id="search" placeholder="Search brands"
                   value="<?php echo e($_GET['search'] ?? ''); ?>">
            <button type="submit">Search</button>
        </form>

        <nav class="nav" aria-label="Main">
            <a href="<?php echo url('index.php'); ?>">Home</a>
            <a href="<?php echo url('views/brands.php'); ?>">Brands</a>

            <?php if (is_logged_in()): ?>
                <span class="welcome">Welcome, <?php echo e(strtok($_SESSION['customer_name'], ' ')); ?></span>
                <?php if (is_admin()): ?>
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
    </div>
</header>

<main id="main" class="container main">
