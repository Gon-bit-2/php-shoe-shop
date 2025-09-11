<?php

class User {
    // Chỉ chứa các thuộc tính public, không có logic, không có kết nối DB
    public $id;
    public $fullname;
    public $email;
    public $password;
    public $role;
    public $created_at;
}