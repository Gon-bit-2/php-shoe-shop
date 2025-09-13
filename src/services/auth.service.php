<?php
require_once '../src/models/repositories/user.repository.php';
require_once '../src/models/user.php';
class AuthService
{
    private $userRepository;
    public function __construct($conn)
    {
        $this->userRepository = new UserRepository($conn);
    }
    function register($fullname, $email, $password)
    {
        //1.check email
        if ($this->userRepository->findByEmail($email)) {
            return ['message' => 'Email đã tồn tại', 'status' => false];
        }
        //2.create user
        $user = new User();
        $user->fullname = $fullname;
        $user->email = $email;
        $user->password = $password;
        //3.save user
        return $this->userRepository->save($user);
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
        return (object)['message' => 'Đăng nhập thành công', 'status' => true, 'user' => $user];
    }
}
