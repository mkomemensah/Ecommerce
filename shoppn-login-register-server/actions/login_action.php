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

    redirect('/~maame.kome-mensah/e-commerce-labs/shoppn-login-register/index.php');
}

$_SESSION['error'] = 'Invalid email or password.';

redirect('../views/login.php');

?>