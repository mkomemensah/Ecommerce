<?php

require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../controllers/CartController.php';

// Must be logged in to check out; they come straight back here afterwards.
require_login('Please log in to check out. Your cart will be waiting for you.');

$cart = new CartController();

if ($cart->getCartCount(get_ip()) < 1) {
    $_SESSION['error'] = 'Your cart is empty. Add something before checking out.';
    redirect(url('views/cart.php'));
}

redirect(url('views/payment.php'));
