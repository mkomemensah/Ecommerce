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

if (mb_strlen($name) > 100) {
    $_SESSION['error'] = 'Brand name must be 100 characters or fewer.';
    redirect('../views/admin/brand.php');
}

$controller = new ProductController();

if ($controller->brandNameExists($name)) {
    $_SESSION['error'] = 'A brand named "' . $name . '" already exists.';
    redirect('../views/admin/brand.php');
}

if ($controller->addBrand($name)) {
    $_SESSION['success'] = 'Brand added. Customers can see it now.';
} else {
    $_SESSION['error'] = 'Failed to add brand.';
}

redirect('../views/admin/brand.php');
