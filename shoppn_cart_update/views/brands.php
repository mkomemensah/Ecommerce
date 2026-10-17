<?php

require_once '../core/core.php';
require_once '../controllers/ProductController.php';

$controller = new ProductController();

$brand = false;
$products = [];
$brands = [];
$search = trim($_GET['search'] ?? '');
$notFound = false;

if (isset($_GET['brand_id'])) {
    $id = $_GET['brand_id'];
    if (ctype_digit($id) && (int) $id > 0) {
        $brand = $controller->getBrandById((int) $id);
    }
    if ($brand) {
        $products = $controller->getProductsByBrand((int) $brand['brand_id']);
    } else {
        $notFound = true;
    }
} else {
    $brands = $controller->getBrandsWithCount($search);
}

$page_title = $brand ? $brand['brand_name'] : 'Brands';
require_once 'layout/header.php';

?>

<div class="layout">
    <?php require_once 'layout/sidebar.php'; ?>

    <div class="content">

    <?php if ($notFound): ?>

        <div class="empty">
            <h3>We couldn't find that brand</h3>
            <p>It may have been renamed or removed.</p>
            <a class="btn btn-primary" href="<?php echo url('views/brands.php'); ?>">See all brands</a>
        </div>

    <?php elseif ($brand): ?>

        <nav class="breadcrumb" aria-label="Breadcrumb">
            <a href="<?php echo url('views/brands.php'); ?>">Brands</a>
            <span aria-hidden="true">/</span>
            <span><?php echo e($brand['brand_name']); ?></span>
        </nav>

        <div class="brand-head">
            <span class="monogram" style="--h: <?php echo brand_hue($brand['brand_name']); ?>">
                <?php echo e(brand_initials($brand['brand_name'])); ?>
            </span>
            <div>
                <h1><?php echo e($brand['brand_name']); ?></h1>
                <p class="muted"><?php echo count($products); ?> product<?php echo count($products) === 1 ? '' : 's'; ?></p>
            </div>
        </div>

        <?php if ($products): ?>
            <div class="product-grid">
                <?php foreach ($products as $p): ?>
                    <article class="product-card">
                        <div class="product-image">
                            <?php if (!empty($p['product_image'])): ?>
                                <img src="<?php echo url('images/products/' . rawurlencode($p['product_image'])); ?>"
                                     alt="<?php echo e($p['product_title']); ?>" loading="lazy">
                            <?php else: ?>
                                <span><?php echo e(brand_initials($p['product_title'])); ?></span>
                            <?php endif; ?>
                        </div>
                        <div class="product-body">
                            <h3><?php echo e($p['product_title']); ?></h3>
                            <?php if (!empty($p['product_desc'])): ?>
                                <p class="muted"><?php echo e($p['product_desc']); ?></p>
                            <?php endif; ?>
                            <p class="price">GH&#8373; <?php echo number_format((float) $p['product_price'], 2); ?></p>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="empty">
                <h3>No products from <?php echo e($brand['brand_name']); ?> yet</h3>
                <p>Check back soon, or browse another brand.</p>
                <a class="btn btn-primary" href="<?php echo url('views/brands.php'); ?>">See all brands</a>
            </div>
        <?php endif; ?>

    <?php else: ?>

        <div class="section-head">
            <h1 class="page-title">
                <?php echo $search !== '' ? 'Brands matching "' . e($search) . '"' : 'All brands'; ?>
            </h1>
            <?php if ($search !== ''): ?>
                <a href="<?php echo url('views/brands.php'); ?>">Clear search</a>
            <?php endif; ?>
        </div>

        <?php if ($brands): ?>
            <div class="brand-grid">
                <?php foreach ($brands as $b): ?>
                    <a class="brand-card" href="<?php echo url('views/brands.php?brand_id=' . (int) $b['brand_id']); ?>">
                        <span class="monogram monogram-sm" style="--h: <?php echo brand_hue($b['brand_name']); ?>">
                            <?php echo e(brand_initials($b['brand_name'])); ?>
                        </span>
                        <span class="brand-card-name"><?php echo e($b['brand_name']); ?></span>
                        <span class="brand-card-count">
                            <?php echo (int) $b['product_count']; ?> product<?php echo (int) $b['product_count'] === 1 ? '' : 's'; ?>
                        </span>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="empty">
                <h3><?php echo $search !== '' ? 'No brands match your search' : 'No brands yet'; ?></h3>
                <p><?php echo $search !== '' ? 'Try a different name.' : 'Brands will show up here as soon as they are added.'; ?></p>
            </div>
        <?php endif; ?>

    <?php endif; ?>

    </div>
</div>

<?php require_once 'layout/footer.php'; ?>
