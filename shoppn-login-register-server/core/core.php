<?php

session_start();

date_default_timezone_set('Africa/Accra');

require_once 'db_class.php';

function get_ip() {
    return $_SERVER['REMOTE_ADDR'] ?? 'UNKNOWN';
}

function redirect($url) {
    header("Location: $url");
    exit();
}

function is_logged_in() {
    return isset($_SESSION['customer_id']);
}

function is_admin() {
    return isset($_SESSION['user_role']) && $_SESSION['user_role'] == 1;
}

function require_login() {
    if (!is_logged_in()) {
        redirect('views/login.php');
    }
}

function require_admin() {
    if (!is_admin()) {
        redirect('index.php');
    }
}



?>