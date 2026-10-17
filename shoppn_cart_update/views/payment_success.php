<?php

require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../controllers/CartController.php';

require_login('Please log in to see your order.');

$id = $_GET['order_id'] ?? '';
if (!ctype_digit($id) || (int) $id <= 0) {
    redirect(url('index.php'));
}

$cart  = new CartController();
$order = $cart->getOrderById((int) $id, (int) $_SESSION['customer_id']);

if (!$order) {
    $_SESSION['error'] = 'We could not find that order.';
    redirect(url('index.php'));
}

$o = $order['order'];

$page_title = 'Order confirmed';
require_once __DIR__ . '/layout/header.php';

?>

<div class="success-banner">
    <div class="success-tick" aria-hidden="true">
        <svg viewBox="0 0 24 24" width="28" height="28" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
    </div>
    <div>
        <h1>Thank you, <?php echo e(strtok($o['customer_name'], ' ')); ?>!</h1>
        <p class="muted">Your payment was successful and your order is confirmed.</p>
    </div>
</div>

<section class="card invoice" id="invoice">
    <div class="invoice-head">
        <div>
            <h2>Invoice</h2>
            <p class="muted">Shoppn</p>
        </div>
        <dl class="invoice-meta">
            <div><dt>Invoice no.</dt><dd>#<?php echo e($o['invoice_no']); ?></dd></div>
            <div><dt>Date</dt><dd><?php echo e(date('j M Y', strtotime($o['order_date']))); ?></dd></div>
            <div><dt>Status</dt><dd><span class="pill"><?php echo e(ucfirst($o['order_status'])); ?></span></dd></div>
        </dl>
    </div>

    <p class="muted">Billed to <strong><?php echo e($o['customer_name']); ?></strong> (<?php echo e($o['customer_email']); ?>)</p>

    <div class="table-wrap">
        <table class="table">
            <thead>
                <tr><th>Product</th><th class="num">Qty</th><th class="num">Unit price</th><th class="num">Total</th></tr>
            </thead>
            <tbody>
            <?php foreach ($order['items'] as $it): ?>
                <tr>
                    <td><?php echo e($it['product_title']); ?></td>
                    <td class="num"><?php echo (int) $it['qty']; ?></td>
                    <td class="num">GH&#8373; <?php echo number_format((float) $it['product_price'], 2); ?></td>
                    <td class="num">GH&#8373; <?php echo number_format((int) $it['qty'] * (float) $it['product_price'], 2); ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr><th colspan="3">Total paid</th><th class="num">GH&#8373; <?php echo number_format($order['total'], 2); ?></th></tr>
            </tfoot>
        </table>
    </div>
</section>

<div class="form-actions no-print" style="margin-top:1.25rem">
    <a class="btn btn-primary" href="<?php echo url('index.php'); ?>">Continue Shopping</a>
    <button type="button" class="btn btn-ghost" onclick="window.print()">Print invoice</button>
</div>

<?php require_once __DIR__ . '/layout/footer.php'; ?>
