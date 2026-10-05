<?php
require_once __DIR__ . '/../../controllers/ProductController.php';

$sidebarController = new ProductController();
$sidebarCategories = $sidebarController->getAllCategories();
$sidebarBrands     = $sidebarController->getAllBrands();
$activeBrand       = isset($_GET['brand_id']) ? (int) $_GET['brand_id'] : 0;
?>
<aside class="sidebar" aria-label="Shop filters">
    <section class="side-block">
        <h2>Brands</h2>
        <?php if ($sidebarBrands): ?>
            <ul class="side-list">
                <?php foreach ($sidebarBrands as $b): ?>
                    <li>
                        <a href="<?php echo url('views/brands.php?brand_id=' . (int) $b['brand_id']); ?>"
                           class="<?php echo $activeBrand === (int) $b['brand_id'] ? 'is-active' : ''; ?>">
                            <?php echo e($b['brand_name']); ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p class="muted">No brands yet.</p>
        <?php endif; ?>
    </section>

    <section class="side-block">
        <h2>Categories</h2>
        <?php if ($sidebarCategories): ?>
            <ul class="side-list side-list-plain">
                <?php foreach ($sidebarCategories as $c): ?>
                    <li><span><?php echo e($c['cat_name']); ?></span></li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p class="muted">No categories yet.</p>
        <?php endif; ?>
    </section>
</aside>
