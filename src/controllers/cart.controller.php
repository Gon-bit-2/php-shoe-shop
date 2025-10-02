<?php
require_once '../src/services/cart.service.php';
class CartController
{
    private $cartService;

    public function __construct($conn)
    {
        $this->cartService = new CartService($conn);
    }

    function add()
    {
        $variantId = $_POST['variant_id'] ?? null; // Đổi từ product_id sang variant_id
        $quantity = (int)($_POST['quantity'] ?? 1);

        if (!$variantId || $quantity <= 0) {
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit();
        }

        // Gọi đến cartService với variantId
        $this->cartService->addToCart($variantId, $quantity);
    }

    function index()
    {
        // Đảm bảo session đã được bắt đầu
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $cartItems = $this->cartService->getCartItems();
        $cartTotal = $this->cartService->getCartTotal();
        require_once __DIR__ . '/../views/home/cart/index.php';
    }
    function update()
    {
        // Đảm bảo session đã được bắt đầu
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $variantId = $_POST['variant_id'] ?? null; // Đổi từ product_id sang variant_id
        $quantity = (int)($_POST['quantity'] ?? 1);
        $this->cartService->updateCartItemQuantity($variantId, $quantity);

        header('Location: /shoe-shop/public/cart');
        exit();
    }

    function remove()
    {
        // Đảm bảo session đã được bắt đầu
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $variantId = $_POST['variant_id'] ?? null; // Đổi từ product_id sang variant_id
        $this->cartService->removeCartItem($variantId);

        header('Location: /shoe-shop/public/cart');
        exit();
    }
    function clear()
    {
        // Đảm bảo session đã được bắt đầu
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $this->cartService->clearCart();
        // THÊM DÒNG NÀY ĐỂ CHUYỂN HƯỚNG SAU KHI XÓA
        header('Location: /shoe-shop/public/cart');
        exit();
    }
}
