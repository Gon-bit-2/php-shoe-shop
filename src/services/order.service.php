<?php
require_once __DIR__ . '/../models/repositories/order.repository.php';
require_once __DIR__ . '/../services/cart.service.php';
require_once __DIR__ . '/../services/mail.service.php';
class OrderService
{
    private $orderRepository;
    private $cartService;
    private $conn;
    private $mailService;
    public function __construct($conn)
    {
        $this->conn = $conn;
        $this->orderRepository = new OrderRepository($conn);
        $this->cartService = new CartService($conn);
        $this->mailService = new MailService();
    }

    /**
     * Tạo đơn hàng
     * @param mixed $userId
     * @param mixed $customerName
     * @param mixed $customerPhone
     * @param mixed $customerAddress
     * @param mixed $paymentMethod
     * @return array
     * @throws Exception
     */
    public function createOrder($userId, $customerName, $customerPhone, $customerAddress, $paymentMethod)
    {
        $cartDetails = $this->cartService->getFinalCartDetails();

        if (empty($cartDetails->items)) {
            return ['success' => false, 'message' => 'Giỏ hàng của bạn trống!'];
        }

        try {
            $this->conn->beginTransaction();

            $orderId = $this->orderRepository->createOrderRecord(
                $userId,
                $customerName,
                $customerPhone,
                $customerAddress,
                $cartDetails->final_total,
                $paymentMethod,
                $cartDetails->voucher_code,
                $cartDetails->discount
            );

            if (!$orderId) {
                throw new Exception("Không thể tạo đơn hàng.");
            }

            foreach ($cartDetails->items as $variantId => $item) {
                $this->orderRepository->updateVariantStock($variantId, $item->quantity);
                $this->orderRepository->createOrderItemRecord($orderId, $variantId, $item);
            }

            if ($cartDetails->voucher_code) {
                require_once __DIR__ . '/../models/repositories/voucher.repository.php';
                $voucherRepo = new VoucherRepository($this->conn);
                $voucherRepo->incrementUsedCount($cartDetails->voucher_code);
            }

            $this->conn->commit();
            $this->cartService->clearCart();

            $orderDetails = $this->getOrderDetail($orderId);
            $this->mailService->sendOrderStatusEmail($orderDetails);

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
        if (!in_array($newStatus, $allowedStatuses)) {
            return false;
        }

        // Lấy trạng thái cũ trước khi cập nhật
        $orderBeforeUpdate = $this->orderRepository->findOrderById($orderId);
        $oldStatus = $orderBeforeUpdate ? $orderBeforeUpdate->status : null;

        $result = $this->orderRepository->updateStatus($orderId, $newStatus);

        //  gửi email nếu cập nhật thành công
        if ($result && $newStatus !== $oldStatus && in_array($newStatus, ['shipped', 'completed', 'cancelled'])) {
            $orderDetails = $this->getOrderDetail($orderId);
            $this->mailService->sendOrderStatusEmail($orderDetails);
        }

        return $result;
    }
    //
    public function getOrdersByUserId($userId)
    {
        return $this->orderRepository->findOrdersByUserId($userId);
    }
    //////////////review////////////////
    public function getProductsAwaitingReview($userId)
    {
        return $this->orderRepository->findProductsAwaitingReview($userId);
    }
}
