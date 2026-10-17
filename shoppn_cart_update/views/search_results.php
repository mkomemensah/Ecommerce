<?php

require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../controllers/ProductController.php';

$query = trim($_GET['user_query'] ?? '');

$controller = new ProductController();
$products = $query !== '' ? $controller->searchProducts($query) : [];

$page_title = 'Search';
require_once __DIR__ . '/layout/header.php';

?>

<div class="layout">
    <?php require_once __DIR__ . '/layout/sidebar.php'; ?>

    <div class="content">
        <div class="section-head">
            <h2><?php echo $query !== '' ? 'Results for "' . e($query) . '"' : 'Search products'; ?></h2>
            <a href="<?php echo url('index.php'); ?>">Back to shop</a>
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
                <p><?php echo $query !== '' ? 'Try a different search term.' : 'Type something in the search box above.'; ?></p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/layout/footer.php'; ?>
