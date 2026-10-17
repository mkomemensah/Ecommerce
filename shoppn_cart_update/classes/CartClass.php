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


    /* ------------------------------------------------------------------
     * Payments & orders (Task 13)
     * ---------------------------------------------------------------- */

    /*
     * Makes sure the payment table can remember Paystack references, so one
     * successful payment can never be used to create two orders.
     * (Done once; harmless if the column already exists.)
     */
    private function ensureReferenceColumn()
    {
        try {
            $res = $this->conn->query("SHOW COLUMNS FROM payment LIKE 'reference'");
            if ($res && $res->num_rows > 0) {
                return true;
            }
            $this->conn->query("ALTER TABLE payment ADD COLUMN reference VARCHAR(100) NULL, ADD UNIQUE KEY uq_payment_reference (reference)");
            return true;
        } catch (Throwable $e) {
            error_log('payment.reference column: ' . $e->getMessage());
            return false;
        }
    }

    public function referenceAlreadyUsed($reference)
    {
        if (!$this->ensureReferenceColumn()) {
            return false;
        }
        $stmt = $this->conn->prepare("SELECT pay_id FROM payment WHERE reference = ? LIMIT 1");
        $stmt->bind_param("s", $reference);
        $stmt->execute();
        return $stmt->get_result()->num_rows > 0;
    }

    /*
     * Turns the customer's cart into a paid order, all-or-nothing.
     * Returns the new order_id, or false if anything went wrong.
     */
    public function processPayment($customer_id, $ip, $reference, $amount)
    {
        $hasRef = $this->ensureReferenceColumn();   // runs before the transaction (ALTER commits)

        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);   // make failures throw so we can roll back

        try {
            $this->conn->begin_transaction();

            // 1. Invoice number (6 digits). Retry in the rare case it is already taken.
            $check = $this->conn->prepare("SELECT order_id FROM orders WHERE invoice_no = ? LIMIT 1");
            do {
                $invoice = mt_rand(100000, 999999);
                $check->bind_param("i", $invoice);
                $check->execute();
            } while ($check->get_result()->num_rows > 0);

            // 2. The order itself.
            $status = 'paid';
            $stmt = $this->conn->prepare(
                "INSERT INTO orders (customer_id, invoice_no, order_date, order_status)
                 VALUES (?, ?, CURDATE(), ?)"
            );
            $stmt->bind_param("iis", $customer_id, $invoice, $status);
            $stmt->execute();
            $order_id = (int) $this->conn->insert_id;

            // 3. Copy the cart rows into orderdetails.
            $stmt = $this->conn->prepare(
                "INSERT INTO orderdetails (order_id, product_id, qty)
                 SELECT ?, p_id, qty FROM cart WHERE ip_add = ?"
            );
            $stmt->bind_param("is", $order_id, $ip);
            $stmt->execute();

            if ($stmt->affected_rows < 1) {
                throw new Exception('Cart was empty.');
            }

            // Record the payment.
            $currency = 'GHS';
            if ($hasRef) {
                $stmt = $this->conn->prepare(
                    "INSERT INTO payment (amt, customer_id, order_id, currency, payment_date, reference)
                     VALUES (?, ?, ?, ?, CURDATE(), ?)"
                );
                $stmt->bind_param("diiss", $amount, $customer_id, $order_id, $currency, $reference);
            } else {
                $stmt = $this->conn->prepare(
                    "INSERT INTO payment (amt, customer_id, order_id, currency, payment_date)
                     VALUES (?, ?, ?, ?, CURDATE())"
                );
                $stmt->bind_param("diis", $amount, $customer_id, $order_id, $currency);
            }
            $stmt->execute();

            // 4. Empty the cart.
            $stmt = $this->conn->prepare("DELETE FROM cart WHERE ip_add = ?");
            $stmt->bind_param("s", $ip);
            $stmt->execute();

            $this->conn->commit();

            // 5.
            return $order_id;
        } catch (Throwable $e) {
            $this->conn->rollback();
            error_log('processPayment failed: ' . $e->getMessage());
            return false;
        }
    }

    /*
     * One order with its line items and product names, for the invoice.
     * Only returns the order if it belongs to $customer_id.
     */
    public function getOrderById($order_id, $customer_id)
    {
        $stmt = $this->conn->prepare(
            "SELECT o.order_id, o.invoice_no, o.order_date, o.order_status, o.customer_id,
                    c.customer_name, c.customer_email
             FROM orders o
             INNER JOIN customer c ON c.customer_id = o.customer_id
             WHERE o.order_id = ? AND o.customer_id = ?"
        );
        $stmt->bind_param("ii", $order_id, $customer_id);
        $stmt->execute();
        $order = $stmt->get_result()->fetch_assoc();

        if (!$order) {
            return false;
        }

        $stmt = $this->conn->prepare(
            "SELECT od.product_id, od.qty,
                    p.product_title, p.product_price, p.product_image
             FROM orderdetails od
             INNER JOIN products p ON p.product_id = od.product_id
             WHERE od.order_id = ?
             ORDER BY p.product_title ASC"
        );
        $stmt->bind_param("i", $order_id);
        $stmt->execute();
        $items = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

        $total = 0.0;
        foreach ($items as $it) {
            $total += (int) $it['qty'] * (float) $it['product_price'];
        }

        // What was actually paid, if recorded.
        $stmt = $this->conn->prepare("SELECT amt, currency FROM payment WHERE order_id = ? LIMIT 1");
        $stmt->bind_param("i", $order_id);
        $stmt->execute();
        $pay = $stmt->get_result()->fetch_assoc();

        return [
            'order'    => $order,
            'items'    => $items,
            'total'    => $pay ? (float) $pay['amt'] : $total,
            'currency' => $pay ? $pay['currency'] : 'GHS',
        ];
    }
}
