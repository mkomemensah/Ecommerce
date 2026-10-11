<?php

class CustomerClass extends Database
{
    public function emailExists($email)
    {
        $stmt = $this->conn->prepare(
            "SELECT customer_email FROM customer WHERE customer_email = ?"
        );
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            return true;
        }

        return false;
    }

    public function addCustomer($name, $email, $pass, $country, $city, $contact)
    {
        $hashedPassword = password_hash($pass, PASSWORD_BCRYPT);

        $stmt = $this->conn->prepare(
            "INSERT INTO customer (customer_name, customer_email, customer_pass, customer_country, customer_city, customer_contact) 
             VALUES (?, ?, ?, ?, ?, ?)"
        );
        $stmt->bind_param(
            "ssssss",
            $name,
            $email,
            $hashedPassword,
            $country,
            $city,
            $contact
        );

        if ($stmt->execute()) {
            return $this->conn->insert_id;
        }

        return false;
    }

    public function getCustomerByEmail($email)
    {
        $stmt = $this->conn->prepare(
            "SELECT * FROM customer WHERE customer_email = ?"
        );
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            return $result->fetch_assoc();
        }

        return false;
    }

    public function login($email, $pass)
    {
        $customer = $this->getCustomerByEmail($email);

        if ($customer && password_verify($pass, $customer['customer_pass'])) {
            return $customer;
        }

        return false;
    }
}