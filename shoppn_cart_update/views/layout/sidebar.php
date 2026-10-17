<?php
require_once __DIR__ . '/../../controllers/ProductController.php';

$sidebarController = new ProductController();
$sidebarCategories = $sidebarController->getCategoriesWithCount();
$sidebarBrands     = $sidebarController->getBrandsWithCount();

$activeCat   = isset($_GET['cat']) ? (int) $_GET['cat'] : 0;
$activeBrand = isset($_GET['brand_id']) ? (int) $_GET['brand_id'] : (isset($_GET['brand']) ? (int) $_GET['brand'] : 0);
$noFilter    = ($activeCat === 0 && $activeBrand === 0);
?>
<aside class="sidebar" aria-label="Shop filters">

    <a class="side-all <?php echo $noFilter ? 'is-active' : ''; ?>" href="<?php echo url('index.php'); ?>">
        <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/>
            <rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/>
        </svg>
        All products
    </a>

    <details class="side-block" open>
        <summary>Shop by category</summary>
        <?php if ($sidebarCategories): ?>
            <ul class="side-nav">
                <?php foreach ($sidebarCategories as $c): ?>
                    <li>
                        <a href="<?php echo url('index.php?cat=' . (int) $c['cat_id']); ?>"
                           class="<?php echo $activeCat === (int) $c['cat_id'] ? 'is-active' : ''; ?>"
                           <?php echo $activeCat === (int) $c['cat_id'] ? 'aria-current="page"' : ''; ?>>
                            <span class="side-name"><?php echo e($c['cat_name']); ?></span>
                            <span class="side-count"><?php echo (int) $c['product_count']; ?></span>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p class="muted side-empty">No categories yet.</p>
        <?php endif; ?>
    </details>

    <details class="side-block" open>
        <summary>Shop by brand</summary>
        <?php if ($sidebarBrands): ?>
            <ul class="side-nav">
                <?php foreach ($sidebarBrands as $b): ?>
                    <li>
                        <a href="<?php echo url('index.php?brand=' . (int) $b['brand_id']); ?>"
                           class="<?php echo $activeBrand === (int) $b['brand_id'] ? 'is-active' : ''; ?>"
                           <?php echo $activeBrand === (int) $b['brand_id'] ? 'aria-current="page"' : ''; ?>>
                            <span class="side-avatar" style="--h: <?php echo brand_hue($b['brand_name']); ?>" aria-hidden="true">
                                <?php echo e(brand_initials($b['brand_name'])); ?>
                            </span>
                            <span class="side-name"><?php echo e($b['brand_name']); ?></span>
                            <span class="side-count"><?php echo (int) $b['product_count']; ?></span>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p class="muted side-empty">No brands yet.</p>
        <?php endif; ?>
    </details>
</aside>
