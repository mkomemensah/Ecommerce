<?php

require_once '../core/core.php';

$page_title = 'Log in';
require_once 'layout/header.php';

?>

<div class="auth-wrap">
    <div class="card auth-card">
        <h1>Log in</h1>
        <p class="muted">Welcome back. Enter your details to continue.</p>

        <?php flash(); ?>

        <form action="../actions/login_action.php" method="POST" class="form">
            <div class="field">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" autocomplete="email" required>
            </div>

            <div class="field">
                <label for="pass">Password</label>
                <input type="password" name="pass" id="pass" autocomplete="current-password" required>
            </div>

            <button type="submit" class="btn btn-primary btn-block">Log in</button>
        </form>

        <p class="auth-switch">New to Shoppn? <a href="register.php">Create an account</a></p>
    </div>
</div>

<?php require_once 'layout/footer.php'; ?>
