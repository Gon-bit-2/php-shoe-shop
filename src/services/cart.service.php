<?php
require_once __DIR__ . '/../models/repositories/product.repository.php';

class CartService
{
    private $productRepository;

    public function __construct($conn)
    {
        $this->productRepository = new ProductRepository($conn);
    }
    public function getVariantDetails($variantId)
    {
        return $this->productRepository->getVariantDetails($variantId);
    }
    public function addToCart($productId, $quantity, $variantId)
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Khởi tạo giỏ hàng nếu chưa có
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        // Lấy thông tin sản phẩm từ Repository
        $variant = $this->getVariantDetails($variantId);

        if (!$variant || $variant->stock < $quantity) {
            $_SESSION['error_message'] = 'Sản phẩm không tồn tại hoặc không đủ hàng!';
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit();
        }

        // Key của giỏ hàng giờ là variant_id
        if (isset($_SESSION['cart'][$variantId])) {
            $_SESSION['cart'][$variantId]['quantity'] += $quantity;
        } else {
            $_SESSION['cart'][$variantId] = [
                'id' => $variant->id,
                'name' => $variant->product_name, // Tên sản phẩm chính
                // TODO: Thêm tên thuộc tính (VD: Size 40, Màu Đen)
                'price' => $variant->price,
                'image_url' => $variant->image_url,
                'quantity' => $quantity
            ];
        }

        $_SESSION['success_message'] = 'Đã thêm sản phẩm vào giỏ hàng!';
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit();
    }
    public function getCartItems()
    {
        return $_SESSION['cart'] ?? [];
    }

    public function getCartTotal()
    {
        $total = 0;
        $cartItems = $this->getCartItems();

        foreach ($cartItems as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        return $total;
    }

    public function updateCartItem($productId, $quantity)
    {
        $cartItems = $this->getCartItems();
        $cartItems[$productId]['quantity'] = $quantity;
        $_SESSION['cart'] = $cartItems;
    }

    public function removeCartItem($productId)
    {
        $cartItems = $this->getCartItems();
        unset($cartItems[$productId]);
        $_SESSION['cart'] = $cartItems;
    }
    public function clearCart()
    {
        $_SESSION['cart'] = [];
    }
    public function getCartItemCount()
    {
        return count($this->getCartItems());
    }
    public function updateCartItemQuantity($productId, $quantity)
    {
        // 1. Chuyển đổi số lượng sang số nguyên
        $quantity = (int)$quantity;

        // 2. Nếu số lượng là 0 hoặc ít hơn, hãy xóa sản phẩm khỏi giỏ hàng
        if ($quantity <= 0) {
            $this->removeCartItem($productId);
            return;
        }

        // 3. Lấy thông tin sản phẩm để kiểm tra tồn kho
        $product = $this->productRepository->findById($productId);

        // 4. Kiểm tra xem sản phẩm có tồn tại và số lượng cập nhật có lớn hơn tồn kho không
        if (!$product || $quantity > $product->stock) {
            // (Quan trọng) Tạo một thông báo lỗi để hiển thị cho người dùng
            $_SESSION['cart_error_message'] = "Sản phẩm '" . htmlspecialchars($product->name) . "' chỉ còn " . $product->stock . " sản phẩm trong kho.";
            // Không làm gì cả và để người dùng ở lại trang giỏ hàng để thấy lỗi
            return;
        }

        // 5. Nếu mọi thứ hợp lệ, cập nhật số lượng trong session
        if (isset($_SESSION['cart'][$productId])) {
            $_SESSION['cart'][$productId]['quantity'] = $quantity;
            // (Tùy chọn) Xóa thông báo lỗi nếu có
            unset($_SESSION['cart_error_message']);
        }
    }
}
