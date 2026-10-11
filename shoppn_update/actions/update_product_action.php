<?php

require_once '../core/core.php';

require_admin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    exit('Invalid request.');
}

require_once '../controllers/ProductController.php';

$controller = new ProductController();

$id = $_POST['product_id'] ?? '';
$cat = $_POST['product_cat'] ?? '';
$brand = $_POST['product_brand'] ?? '';
$title = trim(strip_tags($_POST['product_title'] ?? ''));
$price = $_POST['product_price'] ?? '';
$desc = trim(strip_tags($_POST['product_desc'] ?? ''));
$keywords = trim(strip_tags($_POST['product_keywords'] ?? ''));

$existingImage = basename(
    $_POST['existing_image'] ?? ''
);


if (!ctype_digit($id) || (int) $id <= 0) {
    $_SESSION['error'] = 'Invalid product ID.';
    redirect('../views/admin/product.php');
}

$id = (int) $id;


/*
 * Get the real current product from the database.
 */
$currentProduct = $controller->getProductById($id);

if (!$currentProduct) {
    $_SESSION['error'] = 'Product not found.';
    redirect('../views/admin/product.php');
}


/*
 * The database value is authoritative.
 * We don't trust the hidden field for the actual filename.
 */
$existingImage = basename(
    (string) $currentProduct['product_image']
);


if (!ctype_digit($cat) || (int) $cat <= 0) {
    $_SESSION['error'] =
        'Please select a valid category.';

    redirect(
        '../views/admin/product.php?edit_id=' . $id
    );
}


if (!ctype_digit($brand) || (int) $brand <= 0) {
    $_SESSION['error'] =
        'Please select a valid brand.';

    redirect(
        '../views/admin/product.php?edit_id=' . $id
    );
}


if ($title === '' || mb_strlen($title) > 200) {
    $_SESSION['error'] =
        'Product title is required and must be 200 characters or fewer.';

    redirect(
        '../views/admin/product.php?edit_id=' . $id
    );
}


if (!is_numeric($price) || (float) $price < 0) {
    $_SESSION['error'] =
        'Please enter a valid product price.';

    redirect(
        '../views/admin/product.php?edit_id=' . $id
    );
}


$price = (float) $price;


if (mb_strlen($desc) > 500) {
    $_SESSION['error'] =
        'Description must be 500 characters or fewer.';

    redirect(
        '../views/admin/product.php?edit_id=' . $id
    );
}


if (mb_strlen($keywords) > 100) {
    $_SESSION['error'] =
        'Keywords must be 100 characters or fewer.';

    redirect(
        '../views/admin/product.php?edit_id=' . $id
    );
}


/*
 * By default, keep the current image.
 */
$imageFilename = $existingImage;

$newImagePath = null;


/*
 * If the admin selected a new image, validate and upload it.
 */
if (
    isset($_FILES['product_image']) &&
    $_FILES['product_image']['error'] !== UPLOAD_ERR_NO_FILE
) {

    if (
        $_FILES['product_image']['error'] !== UPLOAD_ERR_OK
    ) {
        $_SESSION['error'] =
            'There was a problem uploading the new image.';

        redirect(
            '../views/admin/product.php?edit_id=' . $id
        );
    }


    $maxSize = 2 * 1024 * 1024;


    $allowedMime = [
        'image/jpeg' => 'jpg',
        'image/png'  => 'png',
        'image/gif'  => 'gif',
        'image/webp' => 'webp'
    ];


    $file = $_FILES['product_image'];


    if ($file['size'] > $maxSize) {
        $_SESSION['error'] =
            'Product image must be 2 MB or smaller.';

        redirect(
            '../views/admin/product.php?edit_id=' . $id
        );
    }


    $finfo = finfo_open(
        FILEINFO_MIME_TYPE
    );

    $mime = finfo_file(
        $finfo,
        $file['tmp_name']
    );

    finfo_close($finfo);


    if (!isset($allowedMime[$mime])) {
        $_SESSION['error'] =
            'Invalid image type. Please upload a JPG, PNG, GIF, or WebP image.';

        redirect(
            '../views/admin/product.php?edit_id=' . $id
        );
    }


    $extension = $allowedMime[$mime];


    $imageFilename =
        uniqid('', true) .
        '_' .
        bin2hex(random_bytes(4)) .
        '.' .
        $extension;


    $uploadDir =
        __DIR__ . '/../images/products/';


    if (
        !is_dir($uploadDir) &&
        !mkdir($uploadDir, 0755, true)
    ) {
        $_SESSION['error'] =
            'The product image folder could not be created.';

        redirect(
            '../views/admin/product.php?edit_id=' . $id
        );
    }


    if (!is_writable($uploadDir)) {
        $_SESSION['error'] =
            'The product image folder is not writable on the school server.';

        redirect(
            '../views/admin/product.php?edit_id=' . $id
        );
    }


    $newImagePath =
        $uploadDir . $imageFilename;


    if (!move_uploaded_file(
        $file['tmp_name'],
        $newImagePath
    )) {
        $_SESSION['error'] =
            'The new product image could not be uploaded.';

        redirect(
            '../views/admin/product.php?edit_id=' . $id
        );
    }
}


/*
 * Update the database.
 */
$success = $controller->updateProduct(
    $id,
    (int) $cat,
    (int) $brand,
    $title,
    $price,
    $desc,
    $imageFilename,
    $keywords
);


if ($success) {

    /*
     * Only delete the old image AFTER the database update succeeds.
     */
    if (
        $newImagePath !== null &&
        $existingImage !== ''
    ) {

        $oldImagePath =
            __DIR__ .
            '/../images/products/' .
            $existingImage;

        if (is_file($oldImagePath)) {
            @unlink($oldImagePath);
        }
    }

    $_SESSION['success'] =
        'Product updated successfully.';

} else {

    /*
     * Database update failed, so remove the newly uploaded
     * image rather than leaving an unused file.
     */
    if (
        $newImagePath !== null &&
        is_file($newImagePath)
    ) {
        @unlink($newImagePath);
    }

    $_SESSION['error'] =
        'Failed to update product. Please try again.';
}


redirect('../views/admin/product.php');

?>