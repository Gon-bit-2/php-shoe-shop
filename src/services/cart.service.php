<?php
require_once __DIR__ . '/../models/repositories/product.repository.php';
require_once __DIR__ . '/../models/productVariant.php';

class CartService
{
    private $productRepository;

    public function __construct($conn)
    {
        $this->productRepository = new ProductRepository($conn);
    }

    public function addToCart($variantId, $quantity)
    {
        // Luôn đảm bảo session đã được bắt đầu
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $variant = $this->productRepository->findVariantById($variantId);

        // --- BẮT ĐẦU SỬA LỖI ---
        if (!$variant) {
            // Nếu không tìm thấy biến thể, hãy đặt một thông báo lỗi và quay lại
            $_SESSION['cart_error_message'] = "Lỗi: Không tìm thấy sản phẩm. Vui lòng thử lại.";
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit();
        }
        // --- KẾT THÚC SỬA LỖI ---

        $currentQuantityInCart = $_SESSION['cart'][$variantId]->quantity ?? 0;
        if ($variant->stock < ($quantity + $currentQuantityInCart)) {
            $_SESSION['cart_error_message'] = "Sản phẩm không đủ số lượng trong kho!";
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit();
        }

        if (isset($_SESSION['cart'][$variantId])) {
            $_SESSION['cart'][$variantId]->quantity += $quantity;
        } else {
            $cartItem = new ProductVariant();
            $cartItem->id = $variant->id;
            $cartItem->product_name = $variant->product_name;
            $cartItem->attributes = $variant->attributes;
            $cartItem->price = $variant->price;
            $cartItem->image_url = $variant->image_url;
            $cartItem->quantity = $quantity;
            $_SESSION['cart'][$variantId] = $cartItem;
        }

        header('Location: /shoe-shop/public/cart');
        exit();
    }

    // Các hàm khác giữ nguyên...
    public function updateCartItemQuantity($variantId, $quantity)
    {
        // Đảm bảo session đã được bắt đầu
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $variant = $this->productRepository->findVariantById($variantId);
        $quantity = (int)$quantity;

        if ($quantity <= 0) {
            $this->removeCartItem($variantId);
            return;
        }

        if (!$variant || $quantity > $variant->stock) {
            $_SESSION['cart_error_message'] = "Sản phẩm '" . htmlspecialchars($variant->product_name) . "' không đủ số lượng trong kho.";
            return;
        }

        if (isset($_SESSION['cart'][$variantId])) {
            $_SESSION['cart'][$variantId]->quantity = $quantity;
        }
    }

    public function removeCartItem($variantId)
    {
        // Đảm bảo session đã được bắt đầu
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        unset($_SESSION['cart'][$variantId]);
    }

    public function getCartItems()
    {
        // Đảm bảo session đã được bắt đầu
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        return $_SESSION['cart'] ?? [];
    }

    public function getCartTotal()
    {
        $total = 0;
        foreach ($this->getCartItems() as $item) {
            $total += $item->price * $item->quantity;
        }
        return $total;
    }

    public function clearCart()
    {
        // Đảm bảo session đã được bắt đầu
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        unset($_SESSION['cart']);
    }
}
