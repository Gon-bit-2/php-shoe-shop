<?php
class AuthMiddleware
{
    function validateRegisterBody($data)
    {
        $fullname = trim($data['fullname'] ?? '');
        $email = trim($data['email'] ?? '');
        $password = trim($data['password']);

        if (empty($fullname) || empty($email) || empty($password)) {
            return 'Vui lòng nhập đầy đủ thông tin!';
        }
        if (strlen($fullname) < 2) {
            return 'Họ và tên phải có ít nhất 2 ký tự!';
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return 'Email không đúng định dạng!';
        }

        if (strlen(str_replace(' ', '', $password)) < 6) {
            return 'Mật khẩu phải có ít nhất 6 ký tự!';
        }

        return false;
    }
    function validateLoginBody($data)
    {
        $email = trim($data['email'] ?? '');
        $password = trim($data['password']);


        if (empty($email) || empty($password)) {
            return 'Vui lòng nhập đầy đủ thông tin!';
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return 'Email không đúng định dạng!';
        }

        if (strlen(str_replace(' ', '', $password)) < 6) {
            return 'Mật khẩu phải có ít nhất 6 ký tự!';
        }

        return false;
    }
    public function requireAdmin()
    {
        // 1. Start session nếu chưa có
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // 2. Yêu cầu đăng nhập trước
        $this->requireAuth();

        // 3. Kiểm tra vai trò trong session
        if (!isset($_SESSION['user']['role_id']) || $_SESSION['user']['role_id'] != 1) {
            http_response_code(403);
            require_once __DIR__ . '/../views/errors/403.php';
            exit();
        }
    }

    function requireAuth()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user'])) {
            header('Location: /shoe-shop/public/login');
            exit();
        }
        return true;
    }
    function redirectIfAuthenticated()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (isset($_SESSION['user']) && !empty($_SESSION['user'])) {
            header('Location: /shoe-shop/public/');
            exit();
        }
    }
    function applyGlobalMiddleware($path)
    {
        $routes = require_once '../src/config/routes.php';
        // Kiểm tra nếu là route admin
        // strpos($path, '/admin') === 0 có nghĩa là "$path bắt đầu bằng '/admin'"
        if (strpos($path, '/admin') === 0) {
            $this->requireAdmin(); // Áp dụng chốt bảo vệ admin
        }
        //  cần authentication
        if (in_array($path, $routes['protected'])) {
            $this->requireAuth();
        }
        //
        if (in_array($path, ['/login', '/register'])) {
            $this->redirectIfAuthenticated();
        }
    }
}
