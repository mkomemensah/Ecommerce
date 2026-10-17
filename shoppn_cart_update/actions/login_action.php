<?php

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    exit('Invalid request.');
}

require_once '../core/core.php';
require_once '../controllers/CustomerController.php';

$email = filter_var(
    trim($_POST['email'] ?? ''),
    FILTER_SANITIZE_EMAIL
);

$pass = $_POST['pass'] ?? '';

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['error'] = 'Please enter a valid email address.';
    redirect('../views/login.php');
}

$controller = new CustomerController();

$customer = $controller->login($email, $pass);

if ($customer) {

    $_SESSION['customer_id'] = $customer['customer_id'];
    $_SESSION['customer_name'] = $customer['customer_name'];
    $_SESSION['customer_email'] = $customer['customer_email'];
    $_SESSION['user_role'] = $customer['user_role'];

    $returnTo = safe_return_url($_SESSION['after_login'] ?? '');
    unset($_SESSION['after_login']);

    // The visitor clicked "Add to Cart" before logging in: add it now.
    if (!empty($_SESSION['pending_add'])) {
        require_once '../controllers/CartController.php';
        $cart = new CartController();
        $pid  = (int) $_SESSION['pending_add'];
        unset($_SESSION['pending_add']);

        if (!$cart->isInCart($pid, get_ip()) && $cart->addToCart($pid, 1, get_ip(), $customer['customer_id'])) {
            $_SESSION['success'] = 'Welcome back! The item has been added to your cart.';
        } else {
            $_SESSION['success'] = 'Welcome back! That item is already in your cart.';
        }
    }

    redirect($returnTo);
}

$_SESSION['error'] = 'Invalid email or password.';

redirect('../views/login.php');

?>