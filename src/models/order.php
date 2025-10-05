<?php

class Order
{
    public $id;
    public $user_id;
    public $customer_name;
    public $customer_phone;
    public $customer_address;
    public $total_amount;
    public $status;
    public $created_at;
    public $updated_at;
    public $voucher_code;
    public $discount_amount;
    // Từ JOIN
    public $fullname; // khi SELECT u.fullname
    public $email;    // khi SELECT u.email
}
