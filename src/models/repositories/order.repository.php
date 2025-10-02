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
    public function createOrderRecord($userId, $name, $phone, $address, $total, $paymentMethod)
    {
        $query = "INSERT INTO orders (user_id, customer_name, customer_phone, customer_address, total_amount, payment_method)
              VALUES (:user_id, :name, :phone, :address, :total, :payment_method)";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([
            ':user_id' => $userId,
            ':name' => $name,
            ':phone' => $phone,
            ':address' => $address,
            ':total' => $total,
            ':payment_method' => $paymentMethod
        ]);
        return $this->conn->lastInsertId();
    }
    public function createOrderItemRecord($orderId, $variantId, $item)
    {
        $query = "INSERT INTO order_items (order_id, variant_id, product_name, variant_attributes, quantity, price)
              VALUES (:order_id, :variant_id, :product_name, :variant_attributes, :quantity, :price)";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([
            ':order_id' => $orderId,
            ':variant_id' => $variantId,
            ':product_name' => $item->product_name,
            ':variant_attributes' => $item->attributes,
            ':quantity' => $item->quantity,
            ':price' => $item->price
        ]);
    }

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
    public function findAll()
    {
        // Chúng ta JOIN với bảng users để lấy được tên khách hàng
        $query = "SELECT o.*, u.fullname
              FROM orders o
              JOIN users u ON o.user_id = u.id
              ORDER BY o.created_at DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_CLASS, 'Order');
    }
    public function findOrderById($orderId)
    {
        $query = "SELECT o.*,u.email FROM orders o JOIN users u ON o.user_id=u.id WHERE o.id=:id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $orderId);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Order');
        return $stmt->fetch();
    }
    public function findItemsByOrderId($orderId)
    {
        $query = "SELECT * FROM order_items WHERE order_id = :order_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":order_id", $orderId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_CLASS, 'OrderItem');
    }
    public function updateStatus($orderId, $newStatus)
    {
        $query = "UPDATE orders SET status = :status WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":status", $newStatus);
        $stmt->bindParam(":id", $orderId);
        $stmt->execute();
    }
    //for u
    public function findOrdersByUserId($userId)
    {
        // Câu lệnh này tương tự findAll, nhưng có thêm điều kiện WHERE
        $query = "SELECT * FROM orders
              WHERE user_id = :user_id
              ORDER BY created_at DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute([':user_id' => $userId]);
        // Chúng ta vẫn dùng FETCH_CLASS để nhận về một mảng các đối tượng Order
        return $stmt->fetchAll(PDO::FETCH_CLASS, 'Order');
    }
}
