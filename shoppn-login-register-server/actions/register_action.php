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
    redirect('../views/account/my_account.php');
}

$_SESSION['error'] = $result['error'];
redirect('../views/register.php');