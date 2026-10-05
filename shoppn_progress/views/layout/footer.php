</main>

<footer class="site-footer">
    <div class="container footer-inner">
        <div>
            <a class="logo logo-footer" href="<?php echo url('index.php'); ?>">Shoppn<span class="logo-dot"></span></a>
            <p class="footer-note">Your online shopping platform.</p>
        </div>
        <nav class="footer-links" aria-label="Footer">
            <a href="<?php echo url('index.php'); ?>">Home</a>
            <a href="<?php echo url('views/brands.php'); ?>">Brands</a>
            <?php if (!is_logged_in()): ?>
                <a href="<?php echo url('views/login.php'); ?>">Log in</a>
                <a href="<?php echo url('views/register.php'); ?>">Register</a>
            <?php else: ?>
                <a href="<?php echo url('views/account/my_account.php'); ?>">My account</a>
            <?php endif; ?>
        </nav>
    </div>
    <div class="footer-bottom">
        <div class="container">&copy; <?php echo date('Y'); ?> Shoppn. All rights reserved.</div>
    </div>
</footer>

</body>
</html>
