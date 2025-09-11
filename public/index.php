<?php require_once '../src/models/repositories/database.php'; 

$path=str_replace('/shoe_shop/public','',$_SERVER['REQUEST_URI']);
$path=parse_url($path,PHP_URL_PATH);
if ($path === '') {
    $path = '/';
}
//
$method=$_SERVER['REQUEST_METHOD'];


switch ($path) {
    case '/register':
        # code...
        require_once'../src/controllers/auth.controller.php';
        $controller=new AuthController($conn);
        if($method=='GET'){
            $controller->showRegisterForm();
        }elseif($method=='POST'){
            $controller->register();
        }
        break;
    case '/':
    case 'home':
        echo('Trang Chủ');
        break;
    case '/login':
        echo('Đây là trang đăng nhập'); // Thêm case này để tránh lỗi 404 sau khi đăng ký
        break;
    default:
        # code...
        echo "404 - Trang không tồn tại";
        break;
}

?>