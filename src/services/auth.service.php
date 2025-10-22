<?php
require_once '../src/models/repositories/user.repository.php';
require_once '../src/models/user.php';
require_once '../src/services/mail.service.php';
class AuthService
{
    private $userRepository;
    private $mailService;
    public function __construct($conn)
    {
        $this->userRepository = new UserRepository($conn);
        $this->mailService = new MailService();
    }
    function register($fullname, $email, $password)
    {
        //1.check email
        if ($this->userRepository->findUserByEmail($email)) {
            return ['message' => 'Email đã tồn tại', 'status' => false];
        }
        //2.create user
        $user = new User();
        $user->fullname = $fullname;
        $user->email = $email;
        $user->password = password_hash($password, PASSWORD_DEFAULT);;
        //3.save user
        $newUser = $this->userRepository->save($user);
        return $newUser;
    }
    function login($email, $password)
    {
        $user = $this->userRepository->findUserByEmail($email);
        if (!$user) {
            return (object)['message' => 'Email không tồn tại', 'status' => false];
        }
        if (!password_verify($password, $user->password)) {
            return (object)['message' => 'Mật khẩu không đúng', 'status' => false];
        }
        if (!$user->is_active) {
            return (object)['message' => 'Tài khoản của bạn đã bị khóa. Vui lòng liên hệ quản trị viên.', 'status' => false];
        }

        return (object)['message' => 'Đăng nhập thành công', 'status' => true, 'user' => $user];
    }
    function forgotPassword($email)
    {
        $user = $this->userRepository->findUserByEmail($email);
        if (!$user) {
            return (object)['message' => 'Email không tồn tại', 'status' => false];
        }
        return (object)['message' => 'Đã gửi email khôi phục mật khẩu', 'status' => true];
    }
    public function initiatePasswordReset($email)
    {
        $user = $this->userRepository->findUserByEmail($email);
        if (!$user) {
            return ['success' => true];
        }

        //token ngẫu nhiên
        $token = bin2hex(random_bytes(32));


        $expiresAt = date('Y-m-d H:i:s', time() + 3600);

        $this->userRepository->saveResetToken($user->id, $token, $expiresAt);

        // Gửi email
        $this->mailService->sendPasswordResetEmail($user->email, $user->fullname, $token);

        return ['success' => true];
    }

    public function verifyResetToken($token)
    {
        return $this->userRepository->findByResetToken($token);
    }

    public function resetPassword($token, $newPassword)
    {
        $user = $this->verifyResetToken($token);
        if (!$user) {
            return ['success' => false, 'message' => 'Mã token không hợp lệ hoặc đã hết hạn.'];
        }

        $result = $this->userRepository->resetPassword($user->id, $newPassword);
        if ($result) {
            return ['success' => true, 'message' => 'Mật khẩu đã được đặt lại thành công.'];
        }

        return ['success' => false, 'message' => 'Có lỗi xảy ra, vui lòng thử lại.'];
    }
}
