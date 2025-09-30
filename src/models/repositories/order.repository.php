<?php
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
        $stmt->execute([
            ':user_id' => $userId,
            ':name' => $name,
            ':phone' => $phone,
            ':address' => $address,
            ':total' => $total
        ]);
        return $this->conn->lastInsertId();
    }
    public function createOrderItemRecord($orderId, $productId, $item)
    {
        $query = "INSERT INTO order_items (order_id, product_id, product_name, quantity, price)
                  VALUES (:order_id, :product_id, :product_name, :quantity, :price)";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([
            ':order_id' => $orderId,
            ':product_id' => $productId,
            ':product_name' => $item['name'],
            ':quantity' => $item['quantity'],
            ':price' => $item['price']
        ]);
    }

    public function updateProductStock($productId, $quantitySold)
    {
        $checkStmt = $this->conn->prepare("SELECT name, stock FROM products WHERE id = :id FOR UPDATE");
        $checkStmt->execute([':id' => $productId]);
        $product = $checkStmt->fetch(PDO::FETCH_ASSOC);

        if (!$product || $product['stock'] < $quantitySold) {
            throw new Exception("Sản phẩm '" . htmlspecialchars($product['name'] ?? 'Không rõ') . "' không đủ số lượng trong kho.");
        }

        $query = "UPDATE products SET stock = stock - :quantity WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([':quantity' => $quantitySold, ':id' => $productId]);
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
        return $stmt->fetchAll(PDO::FETCH_ASSOC); // Lấy kết quả dưới dạng mảng
    }
}
