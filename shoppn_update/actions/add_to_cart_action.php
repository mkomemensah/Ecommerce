<?php

require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../controllers/CartController.php';
require_once __DIR__ . '/../controllers/ProductController.php';

$back = $_SERVER['HTTP_REFERER'] ?? url('index.php');

$id = $_GET['add_cart'] ?? '';

if (!ctype_digit($id) || (int) $id <= 0) {
    $_SESSION['error'] = 'Invalid product.';
    redirect($back);
}

$id = (int) $id;

$products = new ProductController();
if (!$products->getProductById($id)) {
    $_SESSION['error'] = 'That product no longer exists.';
    redirect($back);
}

$cart = new CartController();
$ip = get_ip();

if ($cart->isInCart($id, $ip)) {
    $_SESSION['error'] = 'This item is already in your cart. Go to cart to update quantity.';
} elseif ($cart->addToCart($id, 1, $ip, $_SESSION['customer_id'] ?? null)) {
    $_SESSION['success'] = 'Added to your cart.';
} else {
    $_SESSION['error'] = 'Could not add that item to your cart.';
}

redirect($back);
