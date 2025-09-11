<?php 
require_once '../src/models/repositories/user.repository.php';
require_once '../src/models/user.php';
class AuthService {
     private $userRepository;
    public function __construct($conn) {
        $this->userRepository=new UserRepository($conn);
    }
    function register($fullname, $email, $password) {
        //1.check email
        if($this->userRepository->findByEmail($email)){
            return ['message' => 'Email đã tồn tại', 'status' => false];
        }
        //2.create user
        $user=new User();
        $user->fullname=$fullname;
        $user->email=$email;
        $user->password=$password;
        //3.save user
        return $this->userRepository->save($user);
    }
}

?>