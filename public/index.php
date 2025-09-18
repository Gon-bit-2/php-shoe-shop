<?php require_once '../src/models/repositories/database.php';
require_once '../src/middleware/auth.middleware.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$path = str_replace('/shoe-shop/public', '', $_SERVER['REQUEST_URI']);
$path = parse_url($path, PHP_URL_PATH);
if ($path === '') {
    $path = '/';
}
//
$method = $_SERVER['REQUEST_METHOD'];
$authMiddleware = new AuthMiddleware();
$authMiddleware->applyGlobalMiddleware($path);

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
        require_once '../src/controllers/auth.controller.php';
        $controller = new AuthController($conn);
        if ($method == 'GET') {
            $controller->showLoginForm();
        } elseif ($method == 'POST') {
            $errorMessage = $authMiddleware->validateLoginBody($_POST);
            if ($errorMessage) {
                $message = $errorMessage;
                $controller->showLoginForm($message, $_POST);
                exit();
            } else {
                $controller->login();
            }
        }
        break;
    case '/logout':
        require_once '../src/controllers/auth.controller.php';
        $controller = new AuthController($conn);
        $controller->logout();
        break;

    case '/admin/products/create':
        require_once '../src/controllers/product.controller.php';
        $controller = new ProductController($conn);
        if ($method == 'GET') {
            $controller->create();
        } elseif ($method == 'POST') {
            $controller->store();
        }
        break;
    default:
        echo "404 - Trang không tồn tại";
        break;
}
