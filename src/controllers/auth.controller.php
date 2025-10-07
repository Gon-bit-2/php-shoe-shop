<?php
require_once '../src/services/auth.service.php';
class AuthController
{
    private $authService;
    public function __construct($conn)
    {
        $this->authService = new AuthService($conn);
    }
    function showRegisterForm($message = '', $oldInput = [])
    {
        $errorMessage = $message;
        require_once '../src/views/register.php';
    }
    function register()
    {
        $fullname = trim($_POST['fullname']);
        $email = trim($_POST['email']);
        $password = trim($_POST['password']);
        $result = $this->authService->register($fullname, $email, $password);
        if ($result) {
            // Đăng ký thành công
            header('Location: /shoe-shop/public/login'); // Sẽ tạo trang login sau
            exit();
        } else {
            // exist email
            $message = 'Email đã tồn tại hoặc có lỗi xảy ra!';
            // Nạp lại view và truyền ra biến $message
            require_once '../src/views/register.php';
        }
    }
    function showLoginForm($message = '', $oldInput = [])
    {
        $errorMessage = $message;
        require_once '../src/views/login.php';
    }
    function login()
    {
        $email = trim($_POST['email']);
        $password = trim($_POST['password']);
        $loginResult = $this->authService->login($email, $password);
        if ($loginResult->status == true) {
            // lưu thông tin cần thiết
            $_SESSION['user'] = [
                'id' => $loginResult->user->id,
                'fullname' => $loginResult->user->fullname,
                'email' => $loginResult->user->email,
                'role_id' => $loginResult->user->role_id
            ];
            header('Location: /shoe-shop/public/');
            exit();
        } else {
            $message = $loginResult->message;
            require_once '../src/views/login.php';
        }
    }
    function logout()
    {
        session_destroy();
        header('Location: /shoe-shop/public/');
        exit();
    }
    public function showForgotPasswordForm()
    {
        require_once __DIR__ . '/../views/auth/forgot_password.php';
    }

    public function handleForgotPassword()
    {
        $email = $_POST['email'] ?? '';
        $this->authService->initiatePasswordReset($email);

        // Luôn hiển thị thông báo chung để bảo mật
        $_SESSION['forgot_message'] = 'Nếu email của bạn tồn tại trong hệ thống, một liên kết đặt lại mật khẩu đã được gửi đến.';
        header('Location: /shoe-shop/public/forgot-password');
        exit();
    }

    public function showResetPasswordForm()
    {
        $token = $_GET['token'] ?? '';
        $user = $this->authService->verifyResetToken($token);

        if (!$user) {
            // Token không hợp lệ hoặc hết hạn
            echo "<h1>Lỗi</h1><p>Mã đặt lại mật khẩu không hợp lệ hoặc đã hết hạn. Vui lòng thử lại.</p>";
            exit();
        }

        // Token hợp lệ, hiển thị form
        require_once __DIR__ . '/../views/auth/reset_password.php';
    }

    public function handleResetPassword()
    {
        $token = $_POST['token'] ?? '';
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        if ($password !== $confirmPassword) {
            // Load lại form với thông báo lỗi
            $_SESSION['reset_error'] = 'Mật khẩu xác nhận không khớp.';
            header('Location: /shoe-shop/public/reset-password?token=' . $token);
            exit();
        }

        $result = $this->authService->resetPassword($token, $password);

        if ($result['success']) {
            $_SESSION['login_message'] = 'Mật khẩu đã được thay đổi thành công. Vui lòng đăng nhập lại.';
            header('Location: /shoe-shop/public/login');
            exit();
        } else {
            $_SESSION['reset_error'] = $result['message'];
            header('Location: /shoe-shop/public/reset-password?token=' . $token);
            exit();
        }
    }
}
