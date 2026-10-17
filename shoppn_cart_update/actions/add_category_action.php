<?php

require_once '../core/core.php';

require_admin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    exit('Invalid request.');
}

require_once '../controllers/ProductController.php';

$controller = new ProductController();

$name = trim(strip_tags($_POST['name'] ?? ''));

if ($name === '') {
    $_SESSION['error'] = 'Category name is required.';
    redirect('../views/admin/category.php');
}

if (mb_strlen($name) > 100) {
    $_SESSION['error'] = 'Category name must be 100 characters or fewer.';
    redirect('../views/admin/category.php');
}

if ($controller->categoryNameExists($name)) {
    $_SESSION['error'] = 'A category named "' . $name . '" already exists.';
    redirect('../views/admin/category.php');
}

if ($controller->addCategory($name)) {
    $_SESSION['success'] = 'Category added.';
} else {
    $_SESSION['error'] = 'Failed to add category.';
}

redirect('../views/admin/category.php');

?>