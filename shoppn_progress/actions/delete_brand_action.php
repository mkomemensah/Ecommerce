<?php

require_once '../core/core.php';

require_admin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    exit('Invalid request.');
}

require_once '../controllers/ProductController.php';

$id = $_POST['brand_id'] ?? '';

if (!ctype_digit($id) || (int) $id <= 0) {
    $_SESSION['error'] = 'Invalid brand.';
    redirect('../views/admin/brand.php');
}

$id = (int) $id;
$controller = new ProductController();
$inUse = $controller->countProductsByBrand($id);

if ($inUse > 0) {
    $_SESSION['error'] = 'This brand still has ' . $inUse . ' product' . ($inUse === 1 ? '' : 's')
        . '. Move or remove ' . ($inUse === 1 ? 'it' : 'them') . ' first.';
    redirect('../views/admin/brand.php');
}

if ($controller->deleteBrand($id)) {
    $_SESSION['success'] = 'Brand deleted.';
} else {
    $_SESSION['error'] = 'Could not delete that brand. It may already be gone.';
}

redirect('../views/admin/brand.php');
