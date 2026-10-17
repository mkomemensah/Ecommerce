<?php

require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../classes/CartClass.php';

class CartController
{
    private $cart;

    public function __construct()
    {
        $this->cart = new CartClass();
    }

    public function isInCart($product_id, $ip)
    {
        return $this->cart->isInCart(
            $product_id,
            $ip
        );
    }

    public function addToCart(
        $product_id,
        $qty,
        $ip,
        $customer_id = null
    ) {
        return $this->cart->addToCart(
            $product_id,
            $qty,
            $ip,
            $customer_id
        );
    }

    public function getCartItems($ip)
    {
        return $this->cart->getCartItems($ip);
    }

    public function getCartCount($ip)
    {
        return $this->cart->getCartCount($ip);
    }

    public function getCartTotal($ip)
    {
        return $this->cart->getCartTotal($ip);
    }

    public function removeFromCart(
        $product_id,
        $ip
    ) {
        return $this->cart->removeFromCart(
            $product_id,
            $ip
        );
    }

    public function updateQty(
        $product_id,
        $new_qty,
        $ip
    ) {
        return $this->cart->updateQty(
            $product_id,
            $new_qty,
            $ip
        );
    }

    public function processPayment($customer_id, $ip, $reference, $amount)
    {
        return $this->cart->processPayment($customer_id, $ip, $reference, $amount);
    }

    public function referenceAlreadyUsed($reference)
    {
        return $this->cart->referenceAlreadyUsed($reference);
    }

    public function getOrderById($order_id, $customer_id)
    {
        return $this->cart->getOrderById($order_id, $customer_id);
    }
}
