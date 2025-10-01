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
    public function createOrderRecord($userId, $name, $phone, $address, $total)
    {
        $query = "INSERT INTO orders (user_id, customer_name, customer_phone, customer_address, total_amount)
                  VALUES (:user_id, :name, :phone, :address, :total)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":user_id", $userId);
        $stmt->bindParam(":name", $name);
        $stmt->bindParam(":phone", $phone);
        $stmt->bindParam(":address", $address);
        $stmt->bindParam(":total", $total);
        $stmt->execute();
        return $this->conn->lastInsertId();
    }
    public function createOrderItemRecord($orderId, $productId, $item)
    {
        $query = "INSERT INTO order_items (order_id, product_id, product_name, quantity, price)
                  VALUES (:order_id, :product_id, :product_name, :quantity, :price)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":order_id", $orderId);
        $stmt->bindParam(":product_id", $productId);
        $stmt->bindParam(":product_name", $item['name']);
        $stmt->bindParam(":quantity", $item['quantity']);
        $stmt->bindParam(":price", $item['price']);
        $stmt->execute();
    }

    public function updateProductStock($productId, $quantitySold)
    {
        $checkStmt = $this->conn->prepare("SELECT name, stock FROM products WHERE id = :id FOR UPDATE");
        $checkStmt->bindParam(":id", $productId);
        $checkStmt->execute();
        $product = $checkStmt->fetch(PDO::FETCH_CLASS, 'Product');

        if (!$product || $product->stock < $quantitySold) {
            throw new Exception("Sản phẩm '" . htmlspecialchars($product->name ?? 'Không rõ') . "' không đủ số lượng trong kho.");
        }

        $query = "UPDATE products SET stock = stock - :quantity WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":quantity", $quantitySold);
        $stmt->bindParam(":id", $productId);
        $stmt->execute();
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
}
