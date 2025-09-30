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
        $productId = $_POST['product_id'] ?? null;
        $quantity = (int)($_POST['quantity'] ?? 1);

        if (!$productId || $quantity <= 0) {
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit();
        }

        // Gọi đến cartService
        $this->cartService->addToCart($productId, $quantity);
    }
    function index()
    {
        $cartItems = $this->cartService->getCartItems();
        $cartTotal = $this->cartService->getCartTotal();
        require_once __DIR__ . '/../views/home/cart/index.php';
    }
    function update()
    {
        $productId = $_POST['product_id'] ?? null;
        $quantity = (int)($_POST['quantity'] ?? 1);
        $this->cartService->updateCartItemQuantity($productId, $quantity);
        //
        header('Location: /shoe-shop/public/cart');
        exit();
    }
    function remove()
    {
        $productId = $_POST['product_id'] ?? null;
        $this->cartService->removeCartItem($productId);
        //
        header('Location: /shoe-shop/public/cart');
        exit();
    }
    function clear()
    {
        $this->cartService->clearCart();
    }
}
