<?php

class CartClass extends Database
{
    public function isInCart($product_id, $ip)
    {
        $stmt = $this->conn->prepare(
            "SELECT p_id
             FROM cart
             WHERE p_id = ?
             AND ip_add = ?
             LIMIT 1"
        );

        $stmt->bind_param(
            "is",
            $product_id,
            $ip
        );

        $stmt->execute();

        return $stmt->get_result()->num_rows > 0;
    }


    public function addToCart(
        $product_id,
        $qty,
        $ip,
        $customer_id = null
    ) {
        $stmt = $this->conn->prepare(
            "INSERT INTO cart
            (p_id, ip_add, qty)
            VALUES (?, ?, ?)"
        );

        $stmt->bind_param(
            "isi",
            $product_id,
            $ip,
            $qty
        );

        return $stmt->execute();
    }


    public function getCartItems($ip)
    {
        $stmt = $this->conn->prepare(
            "SELECT
                cart.*,
                products.product_title,
                products.product_price,
                products.product_image
             FROM cart
             INNER JOIN products
                ON cart.p_id = products.product_id
             WHERE cart.ip_add = ?
             ORDER BY products.product_title ASC"
        );

        $stmt->bind_param(
            "s",
            $ip
        );

        $stmt->execute();

        return $stmt
            ->get_result()
            ->fetch_all(MYSQLI_ASSOC);
    }


    public function getCartCount($ip)
    {
        $stmt = $this->conn->prepare(
            "SELECT COUNT(*) AS total
             FROM cart
             WHERE ip_add = ?"
        );

        $stmt->bind_param(
            "s",
            $ip
        );

        $stmt->execute();

        return (int)
            $stmt
            ->get_result()
            ->fetch_assoc()['total'];
    }


    public function getCartTotal($ip)
    {
        $items = $this->getCartItems($ip);

        $total = 0.0;

        foreach ($items as $item) {
            $total +=
                (int) $item['qty'] *
                (float) $item['product_price'];
        }

        return $total;
    }


    public function removeFromCart(
        $product_id,
        $ip
    ) {
        $stmt = $this->conn->prepare(
            "DELETE FROM cart
             WHERE p_id = ?
             AND ip_add = ?"
        );

        $stmt->bind_param(
            "is",
            $product_id,
            $ip
        );

        return $stmt->execute();
    }


    public function updateQty(
        $product_id,
        $new_qty,
        $ip
    ) {
        if ((int) $new_qty < 1) {
            return false;
        }

        $stmt = $this->conn->prepare(
            "UPDATE cart
             SET qty = ?
             WHERE p_id = ?
             AND ip_add = ?"
        );

        $stmt->bind_param(
            "iis",
            $new_qty,
            $product_id,
            $ip
        );

        return $stmt->execute();
    }
}