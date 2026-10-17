<?php

/*
 * Copy this file to core/paystack_config.php and paste in your own TEST keys
 * from https://dashboard.paystack.com  (Settings > API Keys & Webhooks,
 * with "Test mode" switched on).
 *
 * The secret key must NEVER be committed to GitHub or shown in the browser.
 */
define('PAYSTACK_PUBLIC_KEY', 'pk_test_YOUR_PUBLIC_KEY');
define('PAYSTACK_SECRET_KEY', 'sk_test_YOUR_SECRET_KEY');
