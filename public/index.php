<?php
require_once '../src/models/repositories/database.php';
require_once '../src/middleware/auth.middleware.php';
require_once '../src/middleware/product.middleware.php';
require_once '../src/controllers/product.controller.php';
require_once '../src/controllers/auth.controller.php';
require_once '../src/controllers/dashBoard.controller.php';
require_once '../src/controllers/cart.controller.php';
require_once '../src/controllers/order.controller.php';
require_once '../src/controllers/voucher.controller.php';
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
    // admin
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
    case '/admin/orders':
        $controller = new OrderController($conn);
        $controller->getAllOrders();
        break;
    case '/admin/vouchers':
        $controller = new VoucherController($conn);
        $controller->index();
        break;
    case '/admin/vouchers/create':
        $controller = new VoucherController($conn);
        if ($method == 'GET') {
            $controller->create();
        } elseif ($method == 'POST') {
            // Sẽ thêm validation sau
            $controller->store();
        }
        break;
    //
    case '/products':
        $controller = new ProductController($conn);
        // Gọi đến hàm hiển thị trang lọc sản phẩm
        $controller->showProductPage();
        break;
    //
    case '/cart':
        $controller = new CartController($conn);
        $controller->index();
        break;
    case '/cart/add':
        if ($method == 'POST') {
            $controller = new CartController($conn);
            $controller->add();
        }
        break;
    case '/cart/update':
        if ($method == 'POST') {
            $controller = new CartController($conn);
            $controller->update();
        }
        break;
    case '/cart/remove':
        if ($method == 'POST') {
            $controller = new CartController($conn);
            $controller->remove();
        }
        break;
    case '/cart/clear':
        if ($method == 'POST') {
            $controller = new CartController($conn);
            $controller->clear();
        }
        break;
    case '/checkout':
        $authMiddleware->requireAuth(); // Bắt buộc đăng nhập để vào trang checkout
        $controller = new OrderController($conn);
        if ($method == 'GET') {
            $controller->showCheckoutForm();
        } elseif ($method == 'POST') {
            $controller->placeOrder();
        }
        break;
    case '/history':
        // Middleware sẽ tự động kiểm tra đăng nhập vì route này nằm trong
        // mảng 'protected' của file routes.php (nếu bạn đã cấu hình)
        // Hoặc bạn có thể gọi trực tiếp: $authMiddleware->requireAuth();
        $controller = new OrderController($conn);
        $controller->showPurchaseHistory();
        break;
    default:
        // admin edit product
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
        // admin delete product
        if (preg_match('/^\/admin\/products\/delete\/(\d+)$/', $path, $matches)) {
            if ($method == 'POST') {
                $controller = new ProductController($conn);
                $productId = $matches[1];
                $controller->delete($productId);
            }
            break;
        }
        // user detail product
        if (preg_match('/^\/product\/(\d+)$/', $path, $matches)) {
            $controller = new ProductController($conn);
            $productId = $matches[1];
            $controller->showProductDetail($productId);
            break; //
        }
        // user add review
        if (preg_match('/^\/product\/(\d+)\/review$/', $path, $matches) && $method == 'POST') {
            $controller = new ProductController($conn);
            $productId = $matches[1];
            $controller->addReview($productId);
            break;
        }
        //
        if (preg_match('/^\/admin\/orders\/update-status\/(\d+)$/', $path, $matches) && $method == 'POST') {
            $controller = new OrderController($conn);
            $orderId = $matches[1];
            $controller->updateStatus($orderId);
            break;
        }
        // admin detail order
        if (preg_match('/^\/admin\/orders\/view\/(\d+)$/', $path, $matches)) {
            $controller = new OrderController($conn);
            $orderId = $matches[1]; // Lấy ID từ URL
            $controller->getOrderDetail($orderId);
            break;
        }
        echo "404 - Trang không tồn tại";
        break;
        //

}
