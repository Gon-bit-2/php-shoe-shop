<?php
date_default_timezone_set('Asia/Ho_Chi_Minh');
require_once '../src/models/repositories/database.php';
require_once '../src/models/productVariant.php';
// Middleware
require_once '../src/middleware/auth.middleware.php';
require_once '../src/middleware/product.middleware.php';
require_once '../src/middleware/voucher.middleware.php';
require_once '../src/middleware/category.middleware.php';
require_once '../src/middleware/user.middleware.php';
require_once '../src/middleware/order.middleware.php';
require_once '../src/middleware/cart.middleware.php';
require_once '../src/middleware/review.middleware.php';

// Controllers
require_once '../src/controllers/product.controller.php';
require_once '../src/controllers/auth.controller.php';
require_once '../src/controllers/dashBoard.controller.php';
require_once '../src/controllers/cart.controller.php';
require_once '../src/controllers/order.controller.php';
require_once '../src/controllers/voucher.controller.php';
require_once '../src/controllers/user.controller.php';
require_once '../src/controllers/category.controller.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$path = str_replace('/shoe-shop/public', '', $_SERVER['REQUEST_URI']);
$path = parse_url($path, PHP_URL_PATH);
if ($path === '') {
    $path = '/';
}
// Khởi tạo middleware instances
$method = $_SERVER['REQUEST_METHOD'];
$authMiddleware = new AuthMiddleware();
$authMiddleware->applyGlobalMiddleware($path);
$productMiddleware = new ProductMiddleware();
$voucherMiddleware = new VoucherMiddleware();
$categoryMiddleware = new CategoryMiddleware();
$userMiddleware = new UserMiddleware();
$orderMiddleware = new OrderMiddleware();
$cartMiddleware = new CartMiddleware();
$reviewMiddleware = new ReviewMiddleware();

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
    // user
    case '/profile':
        $authMiddleware->requireAuth(); // Bắt buộc đăng nhập
        $controller = new UserController($conn);
        if ($method == 'GET') {
            $controller->showProfile();
        } elseif ($method == 'POST') {
            $errorMessage = $userMiddleware->validateProfileUpdateBody($_POST);
            if ($errorMessage) {
                $_SESSION['profile_error'] = $errorMessage;
                header('Location: /shoe-shop/public/profile');
                exit();
            }
            $controller->updateProfile();
        }
        break;
    case '/forgot-password':
        $controller = new AuthController($conn);
        if ($method == 'GET') {
            $controller->showForgotPasswordForm();
        } elseif ($method == 'POST') {
            $errorMessage = $authMiddleware->validateForgotPasswordBody($_POST);
            if ($errorMessage) {
                $_SESSION['forgot_error'] = $errorMessage;
                header('Location: /shoe-shop/public/forgot-password');
                exit();
            }
            $controller->handleForgotPassword();
        }
        break;

    case '/reset-password':
        $controller = new AuthController($conn);
        if ($method == 'GET') {
            $controller->showResetPasswordForm();
        } elseif ($method == 'POST') {
            $errorMessage = $authMiddleware->validateResetPasswordBody($_POST);
            if ($errorMessage) {
                $_SESSION['reset_error'] = $errorMessage;
                $token = $_POST['token'] ?? '';
                header('Location: /shoe-shop/public/reset-password?token=' . urlencode($token));
                exit();
            }
            $controller->handleResetPassword();
        }
        break;
    // admin
    case '/admin':
        $controller = new DashBoardController($conn);
        $controller->index();
        break;
    case '/admin/users':
        $controller = new UserController($conn);
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
            $errorMessage = $voucherMiddleware->validateVoucherBody($_POST);
            if ($errorMessage) {
                $controller->create($errorMessage, $_POST);
                exit();
            }
            $controller->store();
        }
        break;
    case '/admin/categories':
        $controller = new CategoryController($conn);
        $controller->index();
        break;
    case '/admin/categories/create':
        $controller = new CategoryController($conn);
        if ($method == 'GET') {
            $controller->create();
        } elseif ($method == 'POST') {
            $errorMessage = $categoryMiddleware->validateCategoryBody($_POST, $_FILES['image'] ?? null);
            if ($errorMessage) {
                $_SESSION['category_error'] = $errorMessage;
                header('Location: /shoe-shop/public/admin/categories/create');
                exit();
            }
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
            $errorMessage = $cartMiddleware->validateAddToCartBody($_POST);
            if ($errorMessage) {
                $_SESSION['cart_error'] = $errorMessage;
                header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? '/shoe-shop/public/'));
                exit();
            }
            $controller = new CartController($conn);
            $controller->add();
        }
        break;
    case '/cart/update':
        if ($method == 'POST') {
            $errorMessage = $cartMiddleware->validateUpdateCartBody($_POST);
            if ($errorMessage) {
                $_SESSION['cart_error'] = $errorMessage;
                header('Location: /shoe-shop/public/cart');
                exit();
            }
            $controller = new CartController($conn);
            $controller->update();
        }
        break;
    case '/cart/apply-voucher':
        if ($method == 'POST') {
            $controller = new CartController($conn);
            $controller->applyVoucher();
        }
        break;
    case '/cart/remove-voucher':
        if ($method == 'POST') {
            $controller = new CartController($conn);
            $controller->removeVoucher();
        }
        break;
    case '/cart/remove':
        if ($method == 'POST') {
            $errorMessage = $cartMiddleware->validateRemoveFromCartBody($_POST);
            if ($errorMessage) {
                $_SESSION['cart_error'] = $errorMessage;
                header('Location: /shoe-shop/public/cart');
                exit();
            }
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
            $errorMessage = $orderMiddleware->validateCheckoutBody($_POST);
            if ($errorMessage) {
                $controller->showCheckoutForm($errorMessage);
                exit();
            }
            $controller->placeOrder();
        }
        break;
    case '/history':
        $controller = new OrderController($conn);
        $controller->showPurchaseHistory();
        break;
    default:
        // admin edit user
        if (preg_match('/^\/admin\/users\/edit\/(\d+)$/', $path, $matches)) {
            $controller = new UserController($conn);
            $userId = $matches[1];
            if ($method == 'GET') {
                $controller->edit($userId);
            } elseif ($method == 'POST') {
                $errorMessage = $userMiddleware->validateUserEditBody($_POST);
                if ($errorMessage) {
                    $_SESSION['user_error'] = $errorMessage;
                    header('Location: /shoe-shop/public/admin/users/edit/' . $userId);
                    exit();
                }
                $controller->update($userId);
            }
            break;
        }
        // admin edit product
        if (preg_match('/^\/admin\/products\/edit\/(\d+)$/', $path, $matches)) {
            $controller = new ProductController($conn);
            $productId = $matches[1]; // Lấy ra ID từ URL
            if ($method == 'GET') {
                $controller->getEditPage($productId);
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
        //admin edit category
        if (preg_match('/^\/admin\/categories\/edit\/(\d+)$/', $path, $matches)) {
            $controller = new CategoryController($conn);
            $categoryId = $matches[1];
            if ($method == 'GET') {
                $controller->edit($categoryId);
            } elseif ($method == 'POST') {
                $errorMessage = $categoryMiddleware->validateCategoryBody($_POST, $_FILES['image'] ?? null);
                if ($errorMessage) {
                    $_SESSION['category_error'] = $errorMessage;
                    header('Location: /shoe-shop/public/admin/categories/edit/' . $categoryId);
                    exit();
                }
                $controller->update($categoryId);
            }
            break;
        }
        // admin order success
        if (preg_match('/^\/order-success\/(\d+)$/', $path, $matches)) {
            $controller = new OrderController($conn);
            $orderId = $matches[1];
            $controller->showOrderSuccessPage($orderId);
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
            $errorMessage = $reviewMiddleware->validateReviewBody($_POST);
            if ($errorMessage) {
                $_SESSION['review_error'] = $errorMessage;
                $productId = $matches[1];
                header('Location: /shoe-shop/public/product/' . $productId);
                exit();
            }
            $controller = new ProductController($conn);
            $productId = $matches[1];
            $controller->addReview($productId);
            break;
        }
        //admin update status order
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
        // admin edit voucher
        if (preg_match('/^\/admin\/vouchers\/edit\/(\d+)$/', $path, $matches)) {
            $controller = new VoucherController($conn);
            $voucherId = $matches[1];
            if ($method == 'GET') {
                $controller->edit($voucherId);
            } elseif ($method == 'POST') {
                $errorMessage = $voucherMiddleware->validateVoucherBody($_POST);
                if ($errorMessage) {
                    $controller->edit($voucherId, $errorMessage, $_POST);
                    exit();
                }
                $controller->update($voucherId);
            }
            break;
        }
        http_response_code(404);
        require_once __DIR__ . '/../src/views/errors/404.php';
        break;
        //

}
