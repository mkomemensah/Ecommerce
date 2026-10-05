<?php

require_once '../core/core.php';
require_once '../classes/CustomerClass.php';

class CustomerController
{
    private $customer;

    public function __construct()
    {
        $this->customer = new CustomerClass();
    }

    public function register($data)
    {
        $passwordError = validate_password($data['pass']);
        if ($passwordError !== null) {
            return [
                'success' => false,
                'error' => $passwordError
            ];
        }

        if ($this->customer->emailExists($data['email'])) {
            return [
                'success' => false,
                'error' => 'Email already registered'
            ];
        }

        $customerId = $this->customer->addCustomer(
            $data['name'],
            $data['email'],
            $data['pass'],
            $data['country'],
            $data['city'],
            $data['contact']
        );

        if ($customerId) {
            return [
                'success' => true,
                'customer_id' => $customerId
            ];
        }

        return [
            'success' => false,
            'error' => 'Registration failed'
        ];
    }

    public function login($email, $pass)
    {
        return $this->customer->login($email, $pass);
    }
}