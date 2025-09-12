<?php require_once '../src/models/repositories/database.php';

$path = str_replace('/shoe-shop/public', '', $_SERVER['REQUEST_URI']);
$path = parse_url($path, PHP_URL_PATH);
if ($path === '') {
    $path = '/';
}
//
$method = $_SERVER['REQUEST_METHOD'];


switch ($path) {
    case '/':
    case 'home':
        echo ('Trang Chủ');
        break;
    case '/register':
        require_once '../src/controllers/auth.controller.php';
        $controller = new AuthController($conn);
        if ($method == 'GET') {
            $controller->showRegisterForm();
        } elseif ($method == 'POST') {
            require_once '../src/middleware/auth.middleware.php';
            $authMiddleware = new AuthMiddleware();
            $errorMessage = $authMiddleware->validateRegisterBody($_POST);
            if ($errorMessage) {
                $message = $errorMessage;
                $controller->showRegisterForm($message, $_POST);
                exit();
            } else {
                $controller->register();
            }
        }
        break;

    case '/login':
        echo ('Đây là trang đăng nhập');
        break;
    default:
        # code...
        echo "404 - Trang không tồn tại";
        break;
}
