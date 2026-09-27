<?php

require_once '../core/core.php';
require_admin();

require_once '../controllers/ProductController.php';

$controller = new ProductController();

$id = intval($_POST['id'] ?? 0);
$name = trim($_POST['name'] ?? '');

if ($id <= 0 || $name === '') {
    $_SESSION['error'] = 'Please provide a valid category name.';
    redirect('../views/admin/category.php');
}

if ($controller->updateCategory($id, $name)) {
    $_SESSION['success'] = 'Category updated successfully.';
} else {
    $_SESSION['error'] = 'Unable to update category.';
}

redirect('../views/admin/category.php');
