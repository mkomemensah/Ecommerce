<?php
require_once '../../core/core.php';
require_login();

$page_title = 'My account';
require_once '../layout/header.php';
?>

<div class="card account-card">
    <h1>Hello, <?php echo e($_SESSION['customer_name']); ?></h1>
    <p class="muted">You are logged in as <?php echo e($_SESSION['customer_email']); ?>.</p>

    <div class="account-actions">
        <a class="btn btn-primary" href="<?php echo url('views/brands.php'); ?>">Browse brands</a>
        <?php if (is_admin()): ?>
            <a class="btn btn-ghost" href="<?php echo url('views/admin/brand.php'); ?>">Manage brands</a>
            <a class="btn btn-ghost" href="<?php echo url('views/admin/category.php'); ?>">Manage categories</a>
        <?php endif; ?>
    </div>
</div>

<?php require_once '../layout/footer.php'; ?>
