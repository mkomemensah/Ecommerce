<?php

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    exit('Invalid request.');
}

require_once '../core/core.php';
require_once '../controllers/CustomerController.php';

$name = trim(strip_tags($_POST['name'] ?? ''));
$email = filter_var(
    trim($_POST['email'] ?? ''),
    FILTER_SANITIZE_EMAIL
);
$pass = $_POST['pass'] ?? '';
$country = trim(strip_tags($_POST['country'] ?? ''));
$city = trim(strip_tags($_POST['city'] ?? ''));
$contact = trim(strip_tags($_POST['contact'] ?? ''));

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['error'] = 'Please enter a valid email address.';
    redirect('../views/register.php');
}

$data = [
    'name' => $name,
    'email' => $email,
    'pass' => $pass,
    'country' => $country,
    'city' => $city,
    'contact' => $contact
];

$controller = new CustomerController();
$result = $controller->register($data);

if ($result['success']) {
    $_SESSION['customer_id'] = $result['customer_id'];
    $_SESSION['customer_name'] = $name;
    $_SESSION['customer_email'] = $email;
    $_SESSION['user_role'] = 2;

    // If they were trying to add something to the cart, finish that and send them back.
    if (!empty($_SESSION['pending_add'])) {
        require_once '../controllers/CartController.php';
        $cart = new CartController();
        $pid  = (int) $_SESSION['pending_add'];
        unset($_SESSION['pending_add']);
        if (!$cart->isInCart($pid, get_ip())) {
            $cart->addToCart($pid, 1, get_ip(), $result['customer_id']);
        }
        $_SESSION['success'] = 'Account created. The item has been added to your cart.';
        $returnTo = safe_return_url($_SESSION['after_login'] ?? '');
        unset($_SESSION['after_login']);
        redirect($returnTo);
    }

    unset($_SESSION['after_login']);
    redirect('../views/account/my_account.php');
}

$_SESSION['error'] = $result['error'];
redirect('../views/register.php');