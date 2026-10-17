<?php

require_once '../core/core.php';

require_admin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    exit('Invalid request.');
}

require_once '../controllers/ProductController.php';

$controller = new ProductController();

$cat = $_POST['product_cat'] ?? '';
$brand = $_POST['product_brand'] ?? '';
$title = trim(strip_tags($_POST['product_title'] ?? ''));
$price = $_POST['product_price'] ?? '';
$desc = trim(strip_tags($_POST['product_desc'] ?? ''));
$keywords = trim(strip_tags($_POST['product_keywords'] ?? ''));

if (!ctype_digit($cat) || (int) $cat <= 0) {
    $_SESSION['error'] = 'Please select a valid category.';
    redirect('../views/admin/product.php');
}

if (!ctype_digit($brand) || (int) $brand <= 0) {
    $_SESSION['error'] = 'Please select a valid brand.';
    redirect('../views/admin/product.php');
}

if ($title === '' || mb_strlen($title) > 200) {
    $_SESSION['error'] =
        'Product title is required and must be 200 characters or fewer.';

    redirect('../views/admin/product.php');
}

if (!is_numeric($price) || (float) $price < 0) {
    $_SESSION['error'] = 'Please enter a valid product price.';
    redirect('../views/admin/product.php');
}

$price = (float) $price;

if (mb_strlen($desc) > 500) {
    $_SESSION['error'] =
        'Description must be 500 characters or fewer.';

    redirect('../views/admin/product.php');
}

if (mb_strlen($keywords) > 100) {
    $_SESSION['error'] =
        'Keywords must be 100 characters or fewer.';

    redirect('../views/admin/product.php');
}


/*
 * Maximum image size: 2 MB.
 */
$maxSize = 2 * 1024 * 1024;


/*
 * MIME types allowed by the application.
 *
 *
 */
$allowedMime = [
    'image/jpeg' => 'jpg',
    'image/png'  => 'png',
    'image/gif'  => 'gif',
    'image/webp' => 'webp'
];


if (
    !isset($_FILES['product_image']) ||
    $_FILES['product_image']['error'] !== UPLOAD_ERR_OK
) {
    $_SESSION['error'] = 'Please select a product image.';
    redirect('../views/admin/product.php');
}


$file = $_FILES['product_image'];


if ($file['size'] > $maxSize) {
    $_SESSION['error'] =
        'Product image must be 2 MB or smaller.';

    redirect('../views/admin/product.php');
}


/*
 * Check the actual contents of the uploaded file.
 * 
 * can be manipulated by the client.
 */
$finfo = finfo_open(FILEINFO_MIME_TYPE);

$mime = finfo_file(
    $finfo,
    $file['tmp_name']
);

finfo_close($finfo);


if (!isset($allowedMime[$mime])) {
    $_SESSION['error'] =
        'Invalid image type. Please upload a JPG, PNG, GIF, or WebP image.';

    redirect('../views/admin/product.php');
}


$extension = $allowedMime[$mime];


/*
 * Create a unique filename.
 */
$filename =
    uniqid('', true) .
    '_' .
    bin2hex(random_bytes(4)) .
    '.' .
    $extension;


$uploadDir = __DIR__ . '/../images/products/';


if (
    !is_dir($uploadDir) &&
    !mkdir($uploadDir, 0755, true)
) {
    $_SESSION['error'] =
        'The product image folder could not be created.';

    redirect('../views/admin/product.php');
}


if (!is_writable($uploadDir)) {
    $_SESSION['error'] =
        'The product image folder is not writable on the school server.';

    redirect('../views/admin/product.php');
}


if (!move_uploaded_file(
    $file['tmp_name'],
    $uploadDir . $filename
)) {
    $_SESSION['error'] =
        'The product image could not be uploaded.';

    redirect('../views/admin/product.php');
}


/*
 * Save the generated filename in the database.
 */
$success = $controller->addProduct(
    (int) $cat,
    (int) $brand,
    $title,
    $price,
    $desc,
    $filename,
    $keywords
);


if ($success) {

    $_SESSION['success'] =
        'Product added successfully.';

} else {

    /*
     * If database insertion fails, remove the image we
     * just uploaded 
     */
    @unlink($uploadDir . $filename);

    $_SESSION['error'] =
        'Failed to add product. Please try again.';
}


redirect('../views/admin/product.php');

?>