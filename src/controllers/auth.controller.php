<?php 
require_once '../src/services/auth.service.php';
class AuthController {
    private $authService;
    public function __construct($conn) {
        $this->authService = new AuthService($conn);
    }
    function showRegisterForm() {
         require_once '../src/views/register.php';
    }
    function register(){
        $fullname=$_POST['fullname'];
        $email=$_POST['email'];
        $password=$_POST['password'];
        $result=$this->authService->register($fullname, $email, $password);
        if ($result) {
            // Đăng ký thành công, chuyển hướng đến trang đăng nhập
            header('Location: /shoe-shop/public/login'); // Sẽ tạo trang login sau
            exit();
        } else {
            // Đăng ký thất bại (ví dụ: email tồn tại)
            $message = 'Email đã tồn tại hoặc có lỗi xảy ra!';
            // Nạp lại view và truyền ra biến $message
            require_once '../src/views/register.php';
        }
    }
}

?>