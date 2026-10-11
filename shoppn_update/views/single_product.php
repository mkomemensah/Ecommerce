<?php

require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../controllers/ProductController.php';

$id = $_GET['pro_id'] ?? '';

if (!ctype_digit($id) || (int) $id <= 0) {
    redirect(url('index.php'));
}

$controller = new ProductController();
$p = $controller->getProductById((int) $id);

if (!$p) {
    $_SESSION['error'] = 'That product could not be found.';
    redirect(url('index.php'));
}

$page_title = $p['product_title'];
require_once __DIR__ . '/layout/header.php';

?>

<div class="layout">
    <?php require_once __DIR__ . '/layout/sidebar.php'; ?>

    <div class="content">
        <?php flash(); ?>

        <nav class="breadcrumb" aria-label="Breadcrumb">
            <a href="<?php echo url('index.php'); ?>">Home</a>
            <span aria-hidden="true">/</span>
            <span><?php echo e($p['product_title']); ?></span>
        </nav>

        <article class="card" style="display:flex;gap:24px;flex-wrap:wrap;">
            <div style="flex:1 1 280px;">
                <?php if (!empty($p['product_image'])): ?>
                    <img src="<?php echo url('images/products/' . rawurlencode($p['product_image'])); ?>"
                         alt="<?php echo e($p['product_title']); ?>" style="max-width:100%;border-radius:12px;">
                <?php endif; ?>
            </div>
            <div style="flex:1 1 280px;">
                <h1><?php echo e($p['product_title']); ?></h1>
                <p class="muted">
                    Category: <a href="<?php echo url('index.php?cat=' . (int) $p['product_cat']); ?>"><?php echo e($p['cat_name']); ?></a>
                    &middot;
                    Brand: <a href="<?php echo url('index.php?brand=' . (int) $p['product_brand']); ?>"><?php echo e($p['brand_name']); ?></a>
                </p>
                <p class="price">GH&#8373; <?php echo number_format((float) $p['product_price'], 2); ?></p>
                <?php if (!empty($p['product_desc'])): ?>
                    <p><?php echo nl2br(e($p['product_desc'])); ?></p>
                <?php endif; ?>
                <?php if (!empty($p['product_keywords'])): ?>
                    <p class="muted">Keywords: <?php echo e($p['product_keywords']); ?></p>
                <?php endif; ?>
                <a class="btn btn-primary" href="<?php echo url('index.php?add_cart=' . (int) $p['product_id']); ?>">Add to Cart</a>
            </div>
        </article>
    </div>
</div>

<?php require_once __DIR__ . '/layout/footer.php'; ?>
