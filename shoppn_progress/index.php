<?php

require_once 'core/core.php';
require_once 'controllers/ProductController.php';

$controller = new ProductController();
$brands = $controller->getBrandsWithCount();
$featured = array_slice($brands, 0, 6);

$page_title = 'Home';
require_once 'views/layout/header.php';

?>

<?php flash(); ?>

<section class="hero">
    <div class="hero-copy">
        <?php if (is_logged_in()): ?>
            <p class="hero-welcome">Welcome back, <?php echo e($_SESSION['customer_name']); ?></p>
        <?php endif; ?>
        <h1>Find the brands you love, all in one place.</h1>
        <p>Browse every brand on Shoppn and see what each one has in store.</p>
        <div class="hero-actions">
            <a class="btn btn-primary" href="<?php echo url('views/brands.php'); ?>">Browse brands</a>
            <?php if (!is_logged_in()): ?>
                <a class="btn btn-ghost" href="<?php echo url('views/register.php'); ?>">Create an account</a>
            <?php endif; ?>
        </div>
    </div>

    <?php if ($featured): ?>
        <ul class="hero-tiles" aria-label="Featured brands">
            <?php foreach ($featured as $b): ?>
                <li>
                    <a class="monogram" style="--h: <?php echo brand_hue($b['brand_name']); ?>"
                       href="<?php echo url('views/brands.php?brand_id=' . (int) $b['brand_id']); ?>">
                        <?php echo e(brand_initials($b['brand_name'])); ?>
                        <span><?php echo e($b['brand_name']); ?></span>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</section>

<div class="layout">
    <?php require_once 'views/layout/sidebar.php'; ?>

    <div class="content">
        <div class="section-head">
            <h2>Shop by brand</h2>
            <?php if (count($brands) > 0): ?>
                <a href="<?php echo url('views/brands.php'); ?>">View all brands</a>
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
                <h3>No brands yet</h3>
                <p>Brands will show up here as soon as they are added.</p>
                <?php if (is_admin()): ?>
                    <a class="btn btn-primary" href="<?php echo url('views/admin/brand.php'); ?>">Add the first brand</a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once 'views/layout/footer.php'; ?>