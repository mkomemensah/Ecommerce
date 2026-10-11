<?php

require_once '../core/core.php';

require_admin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    exit('Invalid request.');
}

require_once '../controllers/ProductController.php';

$id = $_POST['product_id'] ?? '';

if (!ctype_digit($id) || (int) $id <= 0) {
    $_SESSION['error'] = 'Invalid product.';
    redirect('../views/admin/product.php');
}

$id = (int) $id;
$controller = new ProductController();
$product = $controller->getProductById($id);

if ($product && $controller->deleteProduct($id)) {
    $img = basename((string) $product['product_image']);
    $path = __DIR__ . '/../images/products/' . $img;
    if ($img !== '' && is_file($path)) {
        @unlink($path);
    }
    $_SESSION['success'] = 'Product deleted.';
} else {
    $_SESSION['error'] = 'Could not delete that product. It may already be gone or be part of an order.';
}

redirect('../views/admin/product.php');
