<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopn</title>
</head>
<body>

<header>
    <h1>Shoppn</h1>

 <nav>
    <a href="/~maame.kome-mensah/e-commerce-labs/shoppn-login-register/index.php">Home</a>

  <?php if (is_logged_in()): ?>

    <span>Welcome <?php echo htmlspecialchars($_SESSION['customer_name']); ?></span>
    <a href="/~maame.kome-mensah/e-commerce-labs/shoppn-login-register/views/account/my_account.php">My Account</a>

   <?php if (is_admin()): ?>
    <a href="/~maame.kome-mensah/e-commerce-labs/shoppn-login-register/views/admin/brand.php">Brands</a>
    <a href="/~maame.kome-mensah/e-commerce-labs/shoppn-login-register/views/admin/category.php">Categories</a>
<?php endif; ?>

    <a href="/~maame.kome-mensah/e-commerce-labs/shoppn-login-register/logout.php">Logout</a>
    <?php else: ?>

        <a href="/~maame.kome-mensah/e-commerce-labs/shoppn-login-register/views/register.php">Register</a>
        <a href="/~maame.kome-mensah/e-commerce-labs/shoppn-login-register/views/login.php">Login</a>

    <?php endif; ?>
</nav>

    <form action="#" method="GET">
        <input type="text" name="search" placeholder="Search products">
        <button type="submit">Search</button>
    </form>
</header>

<main>