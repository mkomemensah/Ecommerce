<?php

require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../controllers/CartController.php';

$cart  = new CartController();
$items = $cart->getCartItems(get_ip());
$total = $cart->getCartTotal(get_ip());

$page_title = 'Your cart';
require_once __DIR__ . '/layout/header.php';

?>

<div class="layout">
    <?php require_once __DIR__ . '/layout/sidebar.php'; ?>

    <div class="content">
        <?php flash(); ?>
        <h1 class="page-title">Your cart</h1>

        <?php if ($items): ?>
            <form id="cartForm" method="POST" class="form">
                <div class="table-wrap">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Remove</th>
                                <th>Product</th>
                                <th>Qty</th>
                                <th class="num">Unit price</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($items as $it): ?>
                            <tr>
                                <td><input type="checkbox" name="remove[]" value="<?php echo (int) $it['p_id']; ?>"></td>
                                <td>
                                    <?php if (!empty($it['product_image'])): ?>
                                        <img src="<?php echo url('images/products/' . rawurlencode($it['product_image'])); ?>" alt=""
                                             style="width:48px;height:48px;object-fit:cover;border-radius:6px;vertical-align:middle;margin-right:8px;">
                                    <?php endif; ?>
                                    <?php echo e($it['product_title']); ?>
                                </td>
                                <td>
                                    <input type="text" name="qty[]" value="<?php echo (int) $it['qty']; ?>" size="3" inputmode="numeric">
                                    <input type="hidden" name="update[]" value="<?php echo (int) $it['p_id']; ?>">
                                </td>
                                <td class="num">GH&#8373; <?php echo number_format((float) $it['product_price'], 2); ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="3">Subtotal</th>
                                <th class="num">GH&#8373; <?php echo number_format($total, 2); ?></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-ghost" formaction="<?php echo url('actions/remove_from_cart_action.php'); ?>">Remove from Cart</button>
                    <button type="submit" class="btn btn-ghost" formaction="<?php echo url('actions/update_qty_action.php'); ?>">Update Quantity</button>
                    <a class="btn btn-ghost" href="<?php echo url('index.php'); ?>">Continue Shopping</a>
                    <a class="btn btn-primary" href="<?php echo url('views/checkout.php'); ?>">Checkout</a>
                </div>
            </form>
        <?php else: ?>
            <div class="empty">
                <h3>Your cart is empty</h3>
                <p>Add something you like and it will show up here.</p>
                <a class="btn btn-primary" href="<?php echo url('index.php'); ?>">Continue Shopping</a>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/layout/footer.php'; ?>
