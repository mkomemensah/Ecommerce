<?php /* Expects $p (a product row). Used by home.php and search_results.php */ ?>
<article class="product-card">
    <a class="product-image" href="<?php echo url('index.php?pro_id=' . (int) $p['product_id']); ?>">
        <?php if (!empty($p['product_image'])): ?>
            <img src="<?php echo url('images/products/' . rawurlencode($p['product_image'])); ?>"
                 alt="<?php echo e($p['product_title']); ?>" loading="lazy">
        <?php else: ?>
            <span><?php echo e(brand_initials($p['product_title'])); ?></span>
        <?php endif; ?>
    </a>
    <div class="product-body">
        <h3><?php echo e($p['product_title']); ?></h3>
        <p class="price">GH&#8373; <?php echo number_format((float) $p['product_price'], 2); ?></p>
        <div class="row-actions">
            <a class="btn btn-small btn-ghost" href="<?php echo url('index.php?pro_id=' . (int) $p['product_id']); ?>">Details</a>
            <a class="btn btn-small btn-primary" href="<?php echo url('index.php?add_cart=' . (int) $p['product_id']); ?>">Add to Cart</a>
        </div>
    </div>
</article>
