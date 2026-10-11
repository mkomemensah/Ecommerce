<?php

require_once '../core/core.php';

require_admin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    exit('Invalid request.');
}

require_once '../controllers/ProductController.php';

$id = $_POST['brand_id'] ?? '';
$name = trim(strip_tags($_POST['name'] ?? ''));

if (!ctype_digit($id) || (int)$id <= 0) {
    $_SESSION['error'] = 'Invalid brand ID.';
    redirect('../views/admin/brand.php');
}

if ($name === '') {
    $_SESSION['error'] = 'Brand name is required.';
    redirect('../views/admin/brand.php?edit_id=' . (int)$id);
}

$id = (int)$id;

if (mb_strlen($name) > 100) {
    $_SESSION['error'] = 'Brand name must be 100 characters or fewer.';
    redirect('../views/admin/brand.php?edit_id=' . $id);
}

$controller = new ProductController();

if ($controller->brandNameExists($name, $id)) {
    $_SESSION['error'] = 'Another brand is already named "' . $name . '".';
    redirect('../views/admin/brand.php?edit_id=' . $id);
}

$result = $controller->updateBrand($id, $name);

if ($result) {
    $_SESSION['success'] = 'Brand updated. Customers see the new name now.';
} else {
    $_SESSION['error'] = 'Failed to update brand.';
}

redirect('../views/admin/brand.php');

?>