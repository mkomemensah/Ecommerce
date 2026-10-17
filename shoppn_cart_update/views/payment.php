<?php

require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../controllers/CartController.php';

require_login('Please log in to pay for your order.');

$cart  = new CartController();
$items = $cart->getCartItems(get_ip());
$total = $cart->getCartTotal(get_ip());

if (!$items) {
    $_SESSION['error'] = 'Your cart is empty. Add something before checking out.';
    redirect(url('views/cart.php'));
}

$configured = is_file(__DIR__ . '/../core/paystack_config.php');
if ($configured) {
    require_once __DIR__ . '/../core/paystack_config.php';
    $configured = defined('PAYSTACK_PUBLIC_KEY') && strpos(PAYSTACK_PUBLIC_KEY, 'YOUR_PUBLIC_KEY') === false;
}
$testMode = $configured && strpos(PAYSTACK_PUBLIC_KEY, 'pk_test_') === 0;

$pesewas = (int) round($total * 100);

$page_title = 'Payment';
require_once __DIR__ . '/layout/header.php';

?>

<div class="page-head">
    <div>
        <h1>Checkout</h1>
        <p class="muted">Review your order, then pay securely.</p>
    </div>
    <a href="<?php echo url('views/cart.php'); ?>">&larr; Back to cart</a>
</div>

<?php flash(); ?>

<div class="cart-layout">

    <section class="card">
        <h2>Your items</h2>
        <div class="table-wrap">
            <table class="table">
                <thead>
                    <tr><th>Product</th><th class="num">Qty</th><th class="num">Unit price</th><th class="num">Total</th></tr>
                </thead>
                <tbody>
                <?php foreach ($items as $it): ?>
                    <tr>
                        <td>
                            <span class="table-brand">
                                <?php if (!empty($it['product_image'])): ?>
                                    <img class="mini-thumb" src="<?php echo url('images/products/' . rawurlencode($it['product_image'])); ?>" alt="">
                                <?php endif; ?>
                                <?php echo e($it['product_title']); ?>
                            </span>
                        </td>
                        <td class="num"><?php echo (int) $it['qty']; ?></td>
                        <td class="num">GH&#8373; <?php echo number_format((float) $it['product_price'], 2); ?></td>
                        <td class="num">GH&#8373; <?php echo number_format((int) $it['qty'] * (float) $it['product_price'], 2); ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <h2 class="pay-sub">Billing to</h2>
        <p class="muted"><?php echo e($_SESSION['customer_name']); ?> &middot; <?php echo e($_SESSION['customer_email']); ?></p>
    </section>

    <aside class="summary card" aria-label="Payment summary">
        <h2>Payment</h2>
        <div class="summary-total">
            <span>Total to pay</span>
            <strong>GH&#8373; <?php echo number_format($total, 2); ?></strong>
        </div>

        <?php if ($configured): ?>
            <button type="button" class="btn btn-primary btn-block" id="payBtn" onclick="payWithPaystack()">Pay GH&#8373; <?php echo number_format($total, 2); ?></button>
            <?php if ($testMode): ?>
                <p class="muted summary-note"><strong>Test mode.</strong> Use card 4084 0840 8408 4081, any future expiry, CVV 408.</p>
            <?php endif; ?>
        <?php else: ?>
            <div class="alert alert-error" style="margin-top:0">Payments aren&rsquo;t set up yet. Add your Paystack test keys to <code>core/paystack_config.php</code>.</div>
        <?php endif; ?>

        <p class="summary-secure">
            <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
            Secure payment with Paystack
        </p>
    </aside>

</div>

<?php if ($configured): ?>
<script src="https://js.paystack.co/v1/inline.js"></script>
<script>
function payWithPaystack() {
    var handler = PaystackPop.setup({
        key: <?php echo json_encode(PAYSTACK_PUBLIC_KEY); ?>,
        email: <?php echo json_encode($_SESSION['customer_email']); ?>,
        amount: <?php echo $pesewas; ?>,
        currency: 'GHS',
        onSuccess: function (response) {
            // The server re-checks this with Paystack before creating any order.
            window.location = <?php echo json_encode(url('actions/process_payment_action.php')); ?> + '?ref=' + encodeURIComponent(response.reference);
        },
        onCancel: function () { alert('Payment cancelled.'); }
    });
    handler.openIframe();
}
</script>
<?php endif; ?>

<?php require_once __DIR__ . '/layout/footer.php'; ?>
