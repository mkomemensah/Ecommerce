<?php

require_once '../core/core.php';

require_admin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    exit('Invalid request.');
}

require_once '../controllers/ProductController.php';

$id = $_POST['id'] ?? '';

if (!ctype_digit($id) || (int) $id <= 0) {
    $_SESSION['error'] = 'Invalid category.';
    redirect('../views/admin/category.php');
}

$id = (int) $id;
$controller = new ProductController();
$inUse = $controller->countProductsByCategory($id);

if ($inUse > 0) {
    $_SESSION['error'] = 'This category still has ' . $inUse . ' product' . ($inUse === 1 ? '' : 's')
        . '. Move or remove ' . ($inUse === 1 ? 'it' : 'them') . ' first.';
    redirect('../views/admin/category.php');
}

if ($controller->deleteCategory($id)) {
    $_SESSION['success'] = 'Category deleted.';
} else {
    $_SESSION['error'] = 'Could not delete that category. It may already be gone.';
}

redirect('../views/admin/category.php');
