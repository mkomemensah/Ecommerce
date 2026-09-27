<?php

require_once '../core/core.php';

require_admin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    exit('Invalid request.');
}

require_once '../controllers/ProductController.php';

$name = trim(strip_tags($_POST['name'] ?? ''));

if ($name === '') {
    $_SESSION['error'] = 'Brand name is required.';
    redirect('../views/admin/brand.php');
}

$controller = new ProductController();

if ($controller->addBrand($name)) {
    $_SESSION['success'] = 'Brand added.';
} else {
    $_SESSION['error'] = 'Failed to add brand.';
}

redirect('../views/admin/brand.php');

?>