<?php
require_once '../src/services/order.service.php';
require_once '../src/services/cart.service.php';

class OrderController
{
    private $orderService;
    private $cartService;

    public function __construct($conn)
    {
        $this->orderService = new OrderService($conn);
        $this->cartService = new CartService($conn);
    }

    public function showCheckoutForm($errorMessage = '')
    {
        $cartItems = $this->cartService->getCartItems();
        $cartTotal = $this->cartService->getCartTotal();
        require_once __DIR__ . '/../views/home/cart/checkout.php';
    }

    public function placeOrder()
    {
        if (!isset($_SESSION['user']['id'])) {
            header('Location: /shoe-shop/public/login');
            exit();
        }

        $userId = $_SESSION['user']['id'];
        $customerName = trim($_POST['customer_name'] ?? '');
        $customerPhone = trim($_POST['customer_phone'] ?? '');
        $customerAddress = trim($_POST['customer_address'] ?? '');

        if (empty($customerName) || empty($customerPhone) || empty($customerAddress)) {
            $this->showCheckoutForm("Vui lòng nhập đầy đủ thông tin giao hàng!");
            return;
        }

        $result = $this->orderService->createOrder($userId, $customerName, $customerPhone, $customerAddress);

        if ($result['success']) {
            // Tạm thời hiển thị thông báo thành công
            // Sau này có thể chuyển hướng đến trang cảm ơn
            echo "<h1>Đặt hàng thành công!</h1><p>Cảm ơn bạn đã mua hàng. Mã đơn hàng của bạn là: " . $result['order_id'] . "</p>";
            echo '<a href="/shoe-shop/public/">Quay lại trang chủ</a>';
            exit();
        } else {
            $this->showCheckoutForm($result['message']);
        }
    }
    public function getAllOrders()
    {
        $orders = $this->orderService->getAllOrders();
        require_once __DIR__ . '/../views/admin/orders/index.php';
    }
    public function getOrderDetail($orderId)
    {
        $orderDetails = $this->orderService->getOrderDetail($orderId);

        // Nếu không có dữ liệu
        if (!$orderDetails) {
            http_response_code(404);
            echo "<h1>404 Not Found</h1><p>Đơn hàng không tồn tại.</p>";
            exit();
        }

        // Nếu có dữ liệu, nạp view và truyền dữ liệu sang
        require_once __DIR__ . '/../views/admin/orders/viewDetail.php';
    }
    public function updateStatus($id)
    {
        // Lấy trạng thái mới từ form POST
        $newStatus = $_POST['status'] ?? '';

        // Gọi service để thực hiện cập nhật
        $result = $this->orderService->updateOrderStatus($id, $newStatus);

        // Sau khi cập nhật, chuyển hướng admin quay lại chính trang chi tiết đơn hàng
        // để họ thấy trạng thái đã được thay đổi
        header('Location: /shoe-shop/public/admin/orders/view/' . $id);
        exit();
    }
}
