<?php

require_once '../core/core.php';
require_admin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    exit('Invalid request.');
}

require_once '../controllers/ProductController.php';

$controller = new ProductController();

$id = intval($_POST['id'] ?? 0);
$name = trim(strip_tags($_POST['name'] ?? ''));

if ($id <= 0 || $name === '') {
    $_SESSION['error'] = 'Please provide a valid category name.';
    redirect('../views/admin/category.php');
}

if (mb_strlen($name) > 100) {
    $_SESSION['error'] = 'Category name must be 100 characters or fewer.';
    redirect('../views/admin/category.php?edit_id=' . $id);
}

if ($controller->categoryNameExists($name, $id)) {
    $_SESSION['error'] = 'Another category is already named "' . $name . '".';
    redirect('../views/admin/category.php?edit_id=' . $id);
}

if ($controller->updateCategory($id, $name)) {
    $_SESSION['success'] = 'Category updated successfully.';
} else {
    $_SESSION['error'] = 'Unable to update category.';
}

redirect('../views/admin/category.php');