<?php
require_once __DIR__ . '/../order.php';
require_once __DIR__ . '/../order_item.php';
require_once __DIR__ . '/../product.php';
class OrderRepository
{
    private $conn;
    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    /**
     * Tạo record trong bảng orders và trả về ID đơn hàng
     * @param mixed $userId
     * @param mixed $name
     * @param mixed $phone
     * @param mixed $address
     * @param mixed $total
     * @param mixed $paymentMethod
     * @param mixed $voucherCode
     * @param mixed $discountAmount
     */
    public function createOrderRecord($userId, $name, $phone, $address, $total, $paymentMethod, $voucherCode, $discountAmount)
    {
        $query = "INSERT INTO orders (user_id, customer_name, customer_phone, customer_address, total_amount, payment_method, voucher_code, discount_amount)
              VALUES (:user_id, :name, :phone, :address, :total, :payment_method, :voucher_code, :discount_amount)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":user_id", $userId);
        $stmt->bindParam(":name", $name);
        $stmt->bindParam(":phone", $phone);
        $stmt->bindParam(":address", $address);
        $stmt->bindParam(":total", $total);
        $stmt->bindParam(":payment_method", $paymentMethod);
        $stmt->bindParam(":voucher_code", $voucherCode);
        $stmt->bindParam(":discount_amount", $discountAmount);
        $stmt->execute();
        return $this->conn->lastInsertId();
    }
    /**
     * Tạo record trong bảng order_items
     * @param mixed $orderId
     * @param mixed $variantId
     * @param mixed $item
     */
    public function createOrderItemRecord($orderId, $variantId, $item)
    {
        $query = "INSERT INTO order_items (order_id, variant_id, product_name, variant_attributes, quantity, price)
              VALUES (:order_id, :variant_id, :product_name, :variant_attributes, :quantity, :price)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":order_id", $orderId);
        $stmt->bindParam(":variant_id", $variantId);
        $stmt->bindParam(":product_name", $item->product_name);
        $stmt->bindParam(":variant_attributes", $item->attributes);
        $stmt->bindParam(":quantity", $item->quantity);
        $stmt->bindParam(":price", $item->price);
        $stmt->execute();
    }
    /**
     * Cập nhật số lượng tồn kho của biến thể khi đặt hàng
     * @param mixed $variantId
     * @param mixed $quantitySold
     */
    public function updateVariantStock($variantId, $quantitySold)
    {
        $checkStmt = $this->conn->prepare("SELECT stock FROM product_variants WHERE id = :id FOR UPDATE");
        $checkStmt->execute([':id' => $variantId]);
        $currentStock = $checkStmt->fetchColumn();

        if ($currentStock === false || $currentStock < $quantitySold) {
            throw new Exception("Một sản phẩm trong giỏ hàng không đủ số lượng.");
        }

        $query = "UPDATE product_variants SET stock = stock - :quantity WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([':quantity' => $quantitySold, ':id' => $variantId]);
    }
    /**
     * Lấy tất cả các đơn hàng
     * @return array
     * @throws Exception
     */
    public function findAll()
    {
        //join với bảng users để lấy được tên khách hàng
        $query = "SELECT o.*, u.fullname
              FROM orders o
              JOIN users u ON o.user_id = u.id
              ORDER BY o.created_at DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_CLASS, 'Order');
    }
    /**
     * Lấy đơn hàng theo ID
     * @param mixed $orderId
     * @return Order
     * @throws Exception
     */
    public function findOrderById($orderId)
    {
        $query = "SELECT o.*,u.email FROM orders o JOIN users u ON o.user_id=u.id WHERE o.id=:id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $orderId);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Order');
        return $stmt->fetch();
    }
    /**
     * Lấy các sản phẩm trong đơn hàng
     * @param mixed $orderId
     * @return array
     * @throws Exception
     */
    public function findItemsByOrderId($orderId)
    {
        $query = "SELECT
                oi.*,
                p.image_url
              FROM order_items oi
              JOIN product_variants pv ON oi.variant_id = pv.id
              JOIN products p ON pv.product_id = p.id
              WHERE oi.order_id = :order_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":order_id", $orderId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_CLASS, 'OrderItem');
    }
    /**
     * Cập nhật trạng thái đơn hàng
     * @param mixed $orderId
     * @param mixed $newStatus
     * @return bool
     * @throws Exception
     */
    public function updateStatus($orderId, $newStatus)
    {
        $query = "UPDATE orders SET status = :status WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":status", $newStatus);
        $stmt->bindParam(":id", $orderId);
        $stmt->execute();
        return true;
    }
    /**
     * Lấy tất cả các đơn hàng của người dùng
     * @param mixed $userId
     * @return array
     * @throws Exception
     */
    public function findOrdersByUserId($userId)
    {
        $query = "SELECT * FROM orders
              WHERE user_id = :user_id
              ORDER BY created_at DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute([':user_id' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_CLASS, 'Order');
    }

    //////////////review////////////////
    /**
     * Lấy đơn hàng đã mua và chưa đánh giá
     * @param mixed $userId
     * @param mixed $productId
     * @return mixed
     * @throws Exception
     */
    public function findPurchasedOrderForReview($userId, $productId)
    {
        $query = "SELECT o.id
                  FROM orders o
                  JOIN order_items oi ON o.id = oi.order_id
                  JOIN product_variants pv ON oi.variant_id = pv.id
                  -- LEFT JOIN để kiểm tra xem đã có review chưa
                  LEFT JOIN reviews r ON o.id = r.order_id AND pv.product_id = r.product_id AND o.user_id = r.user_id
                  WHERE o.user_id = :user_id
                    AND pv.product_id = :product_id
                    AND o.status = 'completed'
                    -- Chỉ lấy đơn hàng mà chưa có review tương ứng
                    AND r.id IS NULL
                  LIMIT 1"; // Chỉ cần tìm 1 đơn hàng là đủ

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":user_id", $userId);
        $stmt->bindParam(":product_id", $productId);
        $stmt->execute();

        // Trả về ID đơn hàng nếu tìm thấy, ngược lại trả về false
        return $stmt->fetchColumn();
    }
    /**
     * Lấy các sản phẩm chưa đánh giá
     * @param mixed $userId
     * @return array
     * @throws Exception
     */
    public function findProductsAwaitingReview($userId)
    {
        // Câu lệnh này sẽ trả về thông tin của từng sản phẩm cần được đánh giá
        $query = "SELECT DISTINCT
                    p.id,
                    p.name,
                    p.image_url
                  FROM orders o
                  JOIN order_items oi ON o.id = oi.order_id
                  JOIN product_variants pv ON oi.variant_id = pv.id
                  JOIN products p ON pv.product_id = p.id
                  -- Dùng LEFT JOIN để tìm những sản phẩm chưa có trong bảng reviews
                  LEFT JOIN reviews r ON p.id = r.product_id AND o.user_id = r.user_id
                  WHERE o.user_id = :user_id
                    AND o.status = 'completed'
                    AND r.id IS NULL"; // Điều kiện quan trọng: chỉ lấy những sp chưa có review

        $stmt = $this->conn->prepare($query);
        $stmt->execute([':user_id' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_CLASS, 'Product');
    }
    ////
    /**
     * Lấy các số liệu thống kê tổng quan cho dashboard.
     * @return object
     * @throws Exception
     */

    public function getDashboardStats()
    {
        // chỉ tính doanh thu từ các đơn hàng đã hoàn thành
        $query = "SELECT
                    -- Tổng doanh thu
                    (SELECT SUM(total_amount) FROM orders WHERE status = 'completed') as total_revenue,
                    -- Tổng doanh thu trong ngày
                    (SELECT SUM(total_amount) FROM orders WHERE DATE(created_at) = CURDATE() AND status = 'completed') as today_revenue,
                    -- Tổng doanh thu trong tháng
                    (SELECT SUM(total_amount) FROM orders WHERE DATE_FORMAT(created_at, '%Y-%m') = DATE_FORMAT(CURDATE(), '%Y-%m') AND status = 'completed') as month_revenue,
                    -- Số đơn hàng mới trong ngày hôm nay
                    (SELECT COUNT(id) FROM orders WHERE DATE(created_at) = CURDATE()) as today_orders,

                    -- Số đơn hàng đang chờ xử lý
                    (SELECT COUNT(id) FROM orders WHERE status = 'pending') as pending_orders,

                    -- Tổng số khách hàng
                    (SELECT COUNT(id) FROM users WHERE role_id = 2) as total_customers";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    /**
     * Tìm các sản phẩm bán chạy nhất.
     * @param mixed $limit
     * @return array
     * @throws Exception
     */
    public function getTopSellingProducts($limit = 5)
    {

        $query = "SELECT
                p.id as product_id,
                p.image_url,
                oi.product_name,
                oi.variant_attributes,
                SUM(oi.quantity) as total_sold
              FROM order_items oi
              JOIN orders o ON oi.order_id = o.id
              JOIN product_variants pv ON oi.variant_id = pv.id
              JOIN products p ON pv.product_id = p.id
              WHERE o.status = 'completed'
              GROUP BY p.id, p.image_url, oi.product_name, oi.variant_attributes
              ORDER BY total_sold DESC
              LIMIT :limit";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
}
