<?php
require_once '../src/models/repositories/database.php';
require_once '../src/middleware/auth.middleware.php';
require_once '../src/middleware/product.middleware.php';
require_once '../src/controllers/product.controller.php';
require_once '../src/controllers/auth.controller.php';
require_once '../src/controllers/dashBoard.controller.php';
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
$productMiddleware = new ProductMiddleware();

switch ($path) {
    case '/':
    case '/home': // Thêm dấu / cho nhất quán
        $controller = new ProductController($conn);
        $controller->getAllProductsActive();
        break;
    case '/register':
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
        $controller = new AuthController($conn);
        $controller->logout();
        break;
    case '/admin':
        $controller = new DashBoardController($conn);
        $controller->index();
        break;
    case '/admin/products':
        $controller = new ProductController($conn);
        $controller->getAllProducts();
        break;

    case '/admin/products/create':
        $controller = new ProductController($conn);
        if ($method == 'GET') {
            $controller->create();
        } elseif ($method == 'POST') {
            $errorMessage = $productMiddleware->validateProductBody($_POST);
            if ($errorMessage) {
                $controller->create($errorMessage, $_POST);
                exit();
            }
            $controller->store();
        }
        break;
    default:
        // Xử lý route động cho trang edit
        if (preg_match('/^\/admin\/products\/edit\/(\d+)$/', $path, $matches)) {
            $controller = new ProductController($conn);
            $productId = $matches[1]; // Lấy ra ID từ URL

            if ($method == 'GET') {
                $controller->getEditPage($productId); // Gọi hàm edit với ID vừa lấy được
            } elseif ($method == 'POST') {
                $errorMessage = $productMiddleware->validateProductBody($_POST);
                if ($errorMessage) {
                    $controller->getEditPage($productId, $errorMessage, $_POST);
                    exit();
                } else {
                    $controller->update($productId, $_POST);
                }
            }
            break;
        }

        if (preg_match('/^\/admin\/products\/delete\/(\d+)$/', $path, $matches)) {
            if ($method == 'POST') {
                $controller = new ProductController($conn);
                $productId = $matches[1];
                $controller->delete($productId);
            }
            break;
        }
        //
        if (preg_match('/^\/product\/(\d+)$/', $path, $matches)) {
            require_once '../src/controllers/product.controller.php';
            $controller = new ProductController($conn);
            $productId = $matches[1];
            $controller->showProductDetail($productId);
            break; //
        }
        echo "404 - Trang không tồn tại";
        break;
}
