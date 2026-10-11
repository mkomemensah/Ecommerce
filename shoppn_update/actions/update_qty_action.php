<?php

require_once '../core/core.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    exit('Invalid request.');
}

require_once '../controllers/CartController.php';

$cart = new CartController();
$ip = get_ip();

$ids  = array_values((array) ($_POST['update'] ?? []));
$qtys = array_values((array) ($_POST['qty'] ?? []));
$bad = false;

foreach ($ids as $i => $pid) {
    $q = isset($qtys[$i]) ? trim((string) $qtys[$i]) : '';

    if (!is_string($pid) || !ctype_digit($pid) || (int) $pid <= 0
        || $q === '' || !ctype_digit($q) || (int) $q < 1) {
        $bad = true;
        continue;
    }

    $cart->updateQty((int) $pid, (int) $q, $ip);
}

if ($bad) {
    $_SESSION['error'] = 'Quantities must be whole numbers of 1 or more. Valid rows were updated.';
} else {
    $_SESSION['success'] = 'Quantities updated.';
}

redirect('../views/cart.php');
