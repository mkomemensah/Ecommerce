<?php

require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../controllers/CartController.php';

$cart  = new CartController();
$items = $cart->getCartItems(get_ip());
$total = $cart->getCartTotal(get_ip());

$itemUnits = 0;
foreach ($items as $it) {
    $itemUnits += (int) $it['qty'];
}

$page_title = 'Your cart';
require_once __DIR__ . '/layout/header.php';

?>

<div class="page-head">
    <div>
        <h1>Your cart</h1>
        <p class="muted"><?php echo $items ? $itemUnits . ' ' . ($itemUnits === 1 ? 'item' : 'items') . ' ready for checkout' : 'Nothing here yet'; ?></p>
    </div>
</div>

<?php flash(); ?>

<?php if ($items): ?>
<div class="cart-layout">

    <form id="cartForm" method="POST" class="cart-main">
        <div class="cart-list card">
            <div class="cart-head" aria-hidden="true">
                <span>Product</span><span>Quantity</span><span class="num">Price</span><span class="num">Total</span><span class="num">Remove</span>
            </div>

            <?php foreach ($items as $it):
                $line = (int) $it['qty'] * (float) $it['product_price']; ?>
                <div class="cart-item">
                    <div class="cart-product">
                        <div class="cart-thumb">
                            <?php if (!empty($it['product_image'])): ?>
                                <img src="<?php echo url('images/products/' . rawurlencode($it['product_image'])); ?>" alt="">
                            <?php else: ?>
                                <span><?php echo e(brand_initials($it['product_title'])); ?></span>
                            <?php endif; ?>
                        </div>
                        <div>
                            <a class="cart-title" href="<?php echo url('index.php?pro_id=' . (int) $it['p_id']); ?>"><?php echo e($it['product_title']); ?></a>
                            <span class="muted cart-unit-mobile">GH&#8373; <?php echo number_format((float) $it['product_price'], 2); ?> each</span>
                        </div>
                    </div>

                    <div class="qty-stepper">
                        <button type="button" class="qty-btn" data-step="-1" aria-label="Decrease quantity">&minus;</button>
                        <input type="text" name="qty[]" value="<?php echo (int) $it['qty']; ?>" inputmode="numeric"
                               pattern="[0-9]*" aria-label="Quantity for <?php echo e($it['product_title']); ?>">
                        <button type="button" class="qty-btn" data-step="1" aria-label="Increase quantity">+</button>
                        <input type="hidden" name="update[]" value="<?php echo (int) $it['p_id']; ?>">
                    </div>

                    <div class="num cart-price">GH&#8373; <?php echo number_format((float) $it['product_price'], 2); ?></div>
                    <div class="num cart-line">GH&#8373; <?php echo number_format($line, 2); ?></div>

                    <label class="cart-remove">
                        <input type="checkbox" name="remove[]" value="<?php echo (int) $it['p_id']; ?>">
                        <span>Remove</span>
                    </label>
                </div>
            <?php endforeach; ?>

            <div class="cart-subtotal">
                <span>Subtotal</span>
                <strong>GH&#8373; <?php echo number_format($total, 2); ?></strong>
            </div>
        </div>

        <div class="cart-actions">
            <button type="submit" class="btn btn-ghost" formaction="<?php echo url('actions/update_qty_action.php'); ?>">Update Quantity</button>
            <button type="submit" class="btn btn-danger" formaction="<?php echo url('actions/remove_from_cart_action.php'); ?>">Remove from Cart</button>
            <a class="btn btn-ghost cart-continue" href="<?php echo url('index.php'); ?>">Continue Shopping</a>
        </div>
    </form>

    <aside class="summary card" aria-label="Order summary">
        <h2>Order summary</h2>
        <dl class="summary-rows">
            <div><dt>Items</dt><dd><?php echo (int) $itemUnits; ?></dd></div>
            <div><dt>Subtotal</dt><dd>GH&#8373; <?php echo number_format($total, 2); ?></dd></div>
            <div><dt>Delivery</dt><dd class="muted">Arranged after order</dd></div>
        </dl>
        <div class="summary-total">
            <span>Total</span>
            <strong>GH&#8373; <?php echo number_format($total, 2); ?></strong>
        </div>
        <a class="btn btn-primary btn-block" href="<?php echo url('views/checkout.php'); ?>">Checkout</a>
        <?php if (!is_logged_in()): ?>
            <p class="muted summary-note">You&rsquo;ll be asked to log in before paying.</p>
        <?php endif; ?>
        <p class="summary-secure">
            <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
            Secure payment with Paystack
        </p>
    </aside>

</div>

<script>
// + / - buttons next to the quantity boxes
document.querySelectorAll('.qty-stepper').forEach(function (box) {
    var input = box.querySelector('input[type="text"]');
    box.querySelectorAll('.qty-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var n = parseInt(input.value, 10);
            if (isNaN(n) || n < 1) n = 1;
            input.value = Math.max(1, n + parseInt(btn.dataset.step, 10));
        });
    });
});
</script>

<?php else: ?>
    <div class="empty">
        <h3>Your cart is empty</h3>
        <p>Add something you like and it will show up here.</p>
        <a class="btn btn-primary" href="<?php echo url('index.php'); ?>">Continue Shopping</a>
    </div>
<?php endif; ?>

<?php require_once __DIR__ . '/layout/footer.php'; ?>
