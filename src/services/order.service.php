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
        // THAY ĐỔI 1: Lấy tất cả chi tiết giỏ hàng, bao gồm cả voucher
        $cartDetails = $this->cartService->getFinalCartDetails();

        if (empty($cartDetails->items)) {
            return ['success' => false, 'message' => 'Giỏ hàng của bạn trống!'];
        }

        try {
            $this->conn->beginTransaction();

            // THAY ĐỔI 2: Truyền vào tổng tiền cuối cùng và thông tin voucher
            $orderId = $this->orderRepository->createOrderRecord(
                $userId,
                $customerName,
                $customerPhone,
                $customerAddress,
                $cartDetails->final_total, // Sử dụng final_total
                $paymentMethod,
                $cartDetails->voucher_code, // Thêm voucher_code
                $cartDetails->discount // Thêm discount_amount
            );

            if (!$orderId) {
                throw new Exception("Không thể tạo đơn hàng.");
            }

            // Cập nhật kho và lưu các mục đơn hàng (giữ nguyên)
            foreach ($cartDetails->items as $variantId => $item) {
                $this->orderRepository->updateVariantStock($variantId, $item->quantity);
                $this->orderRepository->createOrderItemRecord($orderId, $variantId, $item);
            }

            // THAY ĐỔI 3: Nếu có voucher, tăng số lượt sử dụng
            if ($cartDetails->voucher_code) {
                $voucherRepo = new VoucherRepository($this->conn); // Tạo instance mới
                $voucherRepo->incrementUsedCount($cartDetails->voucher_code);
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
    //////////////review////////////////
    public function getProductsAwaitingReview($userId)
    {
        return $this->orderRepository->findProductsAwaitingReview($userId);
    }
}
