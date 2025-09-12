<?php
class AuthMiddleware
{
    function validateRegisterBody($data)
    {
        $fullname = trim($data['fullname'] ?? '');
        $email = trim($data['email'] ?? '');
        $password = $data['password'] ?? '';

        if (empty($fullname) || empty($email) || empty($password)) {
            return 'Vui lòng nhập đầy đủ thông tin!';
        }
        if (strlen($fullname) < 2) {
            return 'Họ và tên phải có ít nhất 2 ký tự!';
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return 'Email không đúng định dạng!';
        }

        if (strlen($password) < 6) {
            return 'Mật khẩu phải có ít nhất 6 ký tự!';
        }

        return false;
    }
}
