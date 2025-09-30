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

    public function createOrder($userId, $customerName, $customerPhone, $customerAddress)
    {
        $cartItems = $this->cartService->getCartItems();
        $cartTotal = $this->cartService->getCartTotal();

        if (empty($cartItems)) {
            return ['success' => false, 'message' => 'Giỏ hàng của bạn trống!'];
        }

        try {
            $this->conn->beginTransaction();

            $orderId = $this->orderRepository->createOrderRecord($userId, $customerName, $customerPhone, $customerAddress, $cartTotal);
            if (!$orderId) {
                throw new Exception("Không thể tạo đơn hàng.");
            }

            foreach ($cartItems as $productId => $item) {
                $this->orderRepository->updateProductStock($productId, $item['quantity']);
                $this->orderRepository->createOrderItemRecord($orderId, $productId, $item);
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
}
