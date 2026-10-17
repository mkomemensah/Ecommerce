<?php

require_once '../core/core.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    exit('Invalid request.');
}

require_once '../controllers/CartController.php';

$cart = new CartController();
$ip = get_ip();
$removed = 0;

foreach ((array) ($_POST['remove'] ?? []) as $pid) {
    if (is_string($pid) && ctype_digit($pid) && (int) $pid > 0) {
        if ($cart->removeFromCart((int) $pid, $ip)) {
            $removed++;
        }
    }
}

if ($removed > 0) {
    $_SESSION['success'] = 'Cart updated.';
} else {
    $_SESSION['error'] = 'Tick the items you want to remove first.';
}

redirect('../views/cart.php');
