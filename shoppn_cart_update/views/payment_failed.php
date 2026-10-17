<?php

require_once __DIR__ . '/../core/core.php';

$page_title = 'Payment failed';
require_once __DIR__ . '/layout/header.php';

?>

<div class="empty">
    <h3>Payment didn&rsquo;t go through</h3>
    <?php if (isset($_SESSION['error'])): ?>
        <p><?php echo e($_SESSION['error']); unset($_SESSION['error']); ?></p>
    <?php else: ?>
        <p>Something went wrong with your payment. Your cart is still saved.</p>
    <?php endif; ?>
    <div class="form-actions" style="justify-content:center">
        <a class="btn btn-primary" href="<?php echo url('views/payment.php'); ?>">Try again</a>
        <a class="btn btn-ghost" href="<?php echo url('views/cart.php'); ?>">Back to cart</a>
    </div>
</div>

<?php require_once __DIR__ . '/layout/footer.php'; ?>
