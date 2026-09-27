<?php

require_once '../core/core.php';

require_admin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    exit('Invalid request.');
}

require_once '../controllers/ProductController.php';

$name = trim(strip_tags($_POST['name'] ?? ''));

if ($name === '') {
    $_SESSION['error'] = 'Category name is required.';
    redirect('../views/admin/category.php');
}

$controller = new ProductController();

if ($controller->addCategory($name)) {
    $_SESSION['success'] = 'Category added.';
} else {
    $_SESSION['error'] = 'Failed to add category.';
}

redirect('../views/admin/category.php');

?>