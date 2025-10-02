<?php
require_once __DIR__ . '/../models/repositories/order.repository.php';
require_once __DIR__ . '/../services/cart.service.php';

class OrderService
{
    private $orderRepository;
    private $cartService;
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
        $this->orderRepository = new OrderRepository($conn);
        $this->cartService = new CartService($conn);
    }

    public function createOrder($userId, $customerName, $customerPhone, $customerAddress, $paymentMethod)
    {
        $cartItems = $this->cartService->getCartItems();
        $cartTotal = $this->cartService->getCartTotal();

        if (empty($cartItems)) {
            return ['success' => false, 'message' => 'Giỏ hàng của bạn trống!'];
        }

        try {
            $this->conn->beginTransaction();

            // Truyền thêm $paymentMethod vào hàm
            $orderId = $this->orderRepository->createOrderRecord($userId, $customerName, $customerPhone, $customerAddress, $cartTotal, $paymentMethod);
            if (!$orderId) {
                throw new Exception("Không thể tạo đơn hàng.");
            }

            foreach ($cartItems as $variantId => $item) {
                $this->orderRepository->updateVariantStock($variantId, $item->quantity);
                $this->orderRepository->createOrderItemRecord($orderId, $variantId, $item);
            }

            $this->conn->commit();
            $this->cartService->clearCart();

            return ['success' => true, 'order_id' => $orderId];
        } catch (Exception $e) {
            $this->conn->rollBack();
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
    // admin quan ly
    public function getAllOrders()
    {
        return $this->orderRepository->findAll();
    }

    public function getOrderDetail($orderId)
    {
        $order = $this->orderRepository->findOrderById($orderId);
        if (!$order) {
            return (object)['status' => false, 'message' => 'Đơn hàng không tồn tại'];
        }
        // items
        $items = $this->orderRepository->findItemsByOrderId($orderId);
        if (!$items) {
            return (object)['status' => false, 'message' => 'Đơn hàng không có sản phẩm'];
        }
        return (object)['status' => true, 'order' => $order, 'items' => $items];
    }
    public function updateOrderStatus($orderId, $newStatus)
    {
        $allowedStatuses = ['pending', 'processing', 'shipped', 'completed', 'cancelled'];

        // Kiểm tra xem trạng thái mới có nằm trong danh sách được phép hay không
        if (!in_array($newStatus, $allowedStatuses)) {
            return false;
        }

        // Nếu hợp lệ, gọi repository để cập nhật
        return $this->orderRepository->updateStatus($orderId, $newStatus);
    }
    //
    public function getOrdersByUserId($userId)
    {
        return $this->orderRepository->findOrdersByUserId($userId);
    }
}
