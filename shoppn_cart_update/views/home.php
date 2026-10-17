<?php

require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../controllers/ProductController.php';

$controller = new ProductController();

$heading = 'Featured products';

if (isset($_GET['cat']) && ctype_digit($_GET['cat']) && (int) $_GET['cat'] > 0) {
    $products = $controller->getProductsByCategory((int) $_GET['cat']);
    $heading = 'Products in this category';
} elseif (isset($_GET['brand']) && ctype_digit($_GET['brand']) && (int) $_GET['brand'] > 0) {
    $products = $controller->getProductsByBrand((int) $_GET['brand']);
    $heading = 'Products from this brand';
} else {
    $products = $controller->getFeaturedProducts(6);
}

$page_title = 'Home';
require_once __DIR__ . '/layout/header.php';

?>

<div class="layout">
    <?php require_once __DIR__ . '/layout/sidebar.php'; ?>

    <div class="content">
        <?php flash(); ?>

        <div class="section-head">
            <h2><?php echo e($heading); ?></h2>
            <?php if (isset($_GET['cat']) || isset($_GET['brand'])): ?>
                <a href="<?php echo url('index.php'); ?>">Clear filter</a>
            <?php endif; ?>
        </div>

        <?php if ($products): ?>
            <div class="product-grid">
                <?php foreach ($products as $p): ?>
                    <?php include __DIR__ . '/layout/product_card.php'; ?>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="empty">
                <h3>No products found</h3>
                <p>Nothing here yet. Check back soon.</p>
                <?php if (is_admin()): ?>
                    <a class="btn btn-primary" href="<?php echo url('views/admin/product.php'); ?>">Add a product</a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/layout/footer.php'; ?>
