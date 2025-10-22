<?php
require_once __DIR__ . '/../models/repositories/user.repository.php';
class UserController
{
    private $userRepository;
    public function __construct($conn)
    {
        $this->userRepository = new UserRepository($conn);
    }
    public function index()
    {
        $users = $this->userRepository->findAll();
        require_once __DIR__ . '/../views/admin/users/index.php';
    }

    public function edit($id)
    {
        $user = $this->userRepository->findById($id);
        if (!$user) {
            echo "404 - Người dùng không tồn tại";
            exit();
        }
        require_once __DIR__ . '/../views/admin/users/edit.php';
    }

    public function update($id)
    {
        $user = $this->userRepository->findById($id);
        if (!$user) {
            echo "404 - Người dùng không tồn tại";
            exit();
        }

        $user->fullname = trim($_POST['fullname']);
        $user->email = trim($_POST['email']);
        $user->role_id = (int)$_POST['role_id'];
        $user->is_active = isset($_POST['is_active']) ? 1 : 0;

        $this->userRepository->update($user);

        header('Location: /shoe-shop/public/admin/users');
        exit();
    }
    public function showProfile()
    {
        $userId = $_SESSION['user']['id'];
        $user = $this->userRepository->findById($userId);

        require_once __DIR__ . '/../views/home/users/profile.php';
    }

    public function updateProfile()
    {
        $userId = $_SESSION['user']['id'];
        $user = $this->userRepository->findById($userId);
        if (!$user) {
            header('Location: /shoe-shop/public/');
            exit();
        }

        $user->fullname = trim($_POST['fullname']);

        if (!empty($_POST['new_password'])) {
            if (password_verify($_POST['current_password'], $user->password)) {
                if ($_POST['new_password'] === $_POST['confirm_password']) {
                    $user->password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
                } else {
                    $_SESSION['profile_error'] = 'Mật khẩu mới không khớp.';
                    header('Location: /shoe-shop/public/profile');
                    exit();
                }
            } else {
                $_SESSION['profile_error'] = 'Mật khẩu hiện tại không đúng.';
                header('Location: /shoe-shop/public/profile');
                exit();
            }
        }

        $this->userRepository->update($user);

        $_SESSION['user']['fullname'] = $user->fullname;
        $_SESSION['profile_success'] = 'Cập nhật thông tin thành công!';
        header('Location: /shoe-shop/public/profile');
        exit();
    }
}
