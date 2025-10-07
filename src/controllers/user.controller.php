<?php
require_once __DIR__ . '/../models/repositories/user.repository.php';
class UserController
{
    private $userRepository;
    public function __construct($conn)
    {
        $this->userRepository = new UserRepository($conn);
    }
    public function showProfile()
    {
        // Lấy ID người dùng từ session
        $userId = $_SESSION['user']['id'];
        $user = $this->userRepository->findById($userId);

        require_once __DIR__ . '/../views/home/users/profile.php';
    }

    public function updateProfile()
    {
        $userId = $_SESSION['user']['id'];
        $user = $this->userRepository->findById($userId);
        if (!$user) {
            // Xử lý lỗi
            header('Location: /shoe-shop/public/');
            exit();
        }

        // Cập nhật thông tin
        $user->fullname = trim($_POST['fullname']);
        // (Thêm các trường khác nếu có, ví dụ: địa chỉ, sđt)

        // Kiểm tra và cập nhật mật khẩu nếu người dùng nhập
        if (!empty($_POST['new_password'])) {
            if (password_verify($_POST['current_password'], $user->password)) {
                if ($_POST['new_password'] === $_POST['confirm_password']) {
                    $user->password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
                } else {
                    // Mật khẩu mới không khớp -> báo lỗi
                    $_SESSION['profile_error'] = 'Mật khẩu mới không khớp.';
                    header('Location: /shoe-shop/public/profile');
                    exit();
                }
            } else {
                // Mật khẩu hiện tại sai -> báo lỗi
                $_SESSION['profile_error'] = 'Mật khẩu hiện tại không đúng.';
                header('Location: /shoe-shop/public/profile');
                exit();
            }
        }

        $this->userRepository->update($user); // Tái sử dụng hàm update của admin

        // Cập nhật lại session và thông báo thành công
        $_SESSION['user']['fullname'] = $user->fullname;
        $_SESSION['profile_success'] = 'Cập nhật thông tin thành công!';
        header('Location: /shoe-shop/public/profile');
        exit();
    }
}
