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
        // Lấy phương thức thanh toán từ POST
        $paymentMethod = $_POST['payment_method'] ?? 'cod';

        if (empty($customerName) || empty($customerPhone) || empty($customerAddress)) {
            $this->showCheckoutForm("Vui lòng nhập đầy đủ thông tin giao hàng!");
            return;
        }

        // Truyền thêm $paymentMethod vào service
        $result = $this->orderService->createOrder($userId, $customerName, $customerPhone, $customerAddress, $paymentMethod);

        if ($result['success']) {
            // Sau này, bạn có thể kiểm tra $paymentMethod ở đây
            // Nếu là 'bank_transfer', chuyển hướng đến trang hướng dẫn thanh toán
            // Nếu là 'cod', chuyển hướng đến trang cảm ơn
            header('Location: /shoe-shop/public/order-success/' . $result['order_id']);
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
    //
    public function showPurchaseHistory()
    {
        $userId = $_SESSION['user']['id'];

        // Lấy danh sách đơn hàng (giữ nguyên)
        $orders = $this->orderService->getOrdersByUserId($userId);

        // THÊM MỚI: Lấy danh sách các sản phẩm đang chờ được đánh giá
        $productsToReview = $this->orderService->getProductsAwaitingReview($userId);

        // Nạp view và truyền cả 2 biến sang
        require_once __DIR__ . '/../views/home/history/index.php';
    }
    public function showOrderSuccessPage($orderId)
    {
        // Lấy chi tiết đơn hàng để hiển thị
        $order = $this->orderService->getOrderDetail($orderId)->order;
        if (!$order) {
            // Xử lý nếu không tìm thấy đơn hàng
            header('Location: /shoe-shop/public/');
            exit();
        }
        require_once __DIR__ . '/../views/home/orders/success.php';
    }
}
