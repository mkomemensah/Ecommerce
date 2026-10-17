<?php

require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../controllers/CartController.php';

require_login('Please log in to complete your payment.');

function payment_failed($message)
{
    $_SESSION['error'] = $message;
    redirect(url('views/payment_failed.php'));
}

/* ---- 1. Basic checks on what the browser sent ---- */

$reference = $_GET['ref'] ?? '';

if (!preg_match('/^[A-Za-z0-9._=-]{5,100}$/', $reference)) {
    payment_failed('The payment reference was missing or invalid.');
}

if (!is_file(__DIR__ . '/../core/paystack_config.php')) {
    payment_failed('Payments are not set up yet. The site owner needs to add the Paystack keys.');
}
require_once __DIR__ . '/../core/paystack_config.php';

$cart        = new CartController();
$ip          = get_ip();
$customer_id = (int) $_SESSION['customer_id'];

$total = $cart->getCartTotal($ip);

if ($total <= 0) {
    payment_failed('Your cart is empty, so there is nothing to pay for.');
}

if ($cart->referenceAlreadyUsed($reference)) {
    payment_failed('That payment has already been used for an order.');
}

/* ---- 2. Ask Paystack (server to server) whether this payment really happened ---- */

$ch = curl_init('https://api.paystack.co/transaction/verify/' . rawurlencode($reference));
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER     => [
        'Authorization: Bearer ' . PAYSTACK_SECRET_KEY,
        'Cache-Control: no-cache',
    ],
    CURLOPT_TIMEOUT        => 30,
]);

$body  = curl_exec($ch);
$error = curl_error($ch);
curl_close($ch);

if ($body === false) {
    error_log('Paystack verify cURL error: ' . $error);
    payment_failed('We could not reach Paystack to confirm your payment. Please wait a moment and try again. If you were charged, do not pay twice: contact support with reference ' . $reference . '.');
}

$result = json_decode($body, true);

if (!is_array($result) || empty($result['status']) || empty($result['data'])) {
    payment_failed('Paystack could not confirm this payment.');
}

$data = $result['data'];

/* ---- 3. The payment must be successful, in cedis, and for the right amount ---- */

$expectedPesewas = (int) round($total * 100);

if (($data['status'] ?? '') !== 'success') {
    payment_failed('Your payment was not successful.');
}
if (($data['currency'] ?? '') !== 'GHS') {
    payment_failed('The payment currency did not match.');
}
if ((int) ($data['amount'] ?? 0) !== $expectedPesewas) {
    payment_failed('The amount paid did not match your cart total, so the order was not created. Please contact support.');
}

/* ---- 4. Only now do we record the order ---- */

$order_id = $cart->processPayment($customer_id, $ip, $reference, $total);

if (!$order_id) {
    payment_failed('Your payment went through, but we could not save the order. Please contact support with reference ' . $reference . '.');
}

/* ---- 5. Confirmation email (best effort: many servers do not allow mail()) ---- */

$order = $cart->getOrderById($order_id, $customer_id);
if ($order) {
    $lines = '';
    foreach ($order['items'] as $it) {
        $lines .= sprintf("- %s x %d  (GHS %s)\n", $it['product_title'], $it['qty'], number_format($it['product_price'] * $it['qty'], 2));
    }
    $message = "Hi " . $order['order']['customer_name'] . ",\n\n"
        . "Thank you for your order at Shoppn.\n\n"
        . "Invoice number: #" . $order['order']['invoice_no'] . "\n"
        . $lines
        . "\nTotal paid: GHS " . number_format($order['total'], 2) . "\n\n"
        . "Shoppn";
    @mail($order['order']['customer_email'], 'Your Shoppn order #' . $order['order']['invoice_no'], $message,
          "From: no-reply@shoppn.local\r\nContent-Type: text/plain; charset=UTF-8");
}

redirect(url('views/payment_success.php?order_id=' . (int) $order_id));
