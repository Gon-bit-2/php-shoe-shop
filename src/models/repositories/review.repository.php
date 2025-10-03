<?php
require_once __DIR__ . '/../review.php';
class ReviewRepository
{
    private $conn;
    public function __construct($conn)
    {
        $this->conn = $conn;
    }
    public function save(Review $review)
    {
        $query = "INSERT INTO reviews (product_id, user_id, order_id, rating, comment, status)
                  VALUES (:product_id, :user_id, :order_id, :rating, :comment, :status)";
        $stmt = $this->conn->prepare($query);

        return $stmt->execute([
            ':product_id' => $review->product_id,
            ':user_id' => $review->user_id,
            ':order_id' => $review->order_id,
            ':rating' => $review->rating,
            ':comment' => $review->comment,
            ':status' => $review->status
        ]);
    }

    /**
     * Tìm tất cả các đánh giá đã được duyệt cho một sản phẩm.
     */
    public function findApprovedByProductId($productId)
    {
        $query = "SELECT r.*, u.fullname
                  FROM reviews r
                  JOIN users u ON r.user_id = u.id
                  WHERE r.product_id = :product_id AND r.status = 'approved'
                  ORDER BY r.created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":product_id", $productId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_CLASS, 'Review');
    }
    public function hasUserPurchasedProduct($userId, $productId)
    {
        $query = "SELECT COUNT(o.id)
              FROM orders o
              JOIN order_items oi ON o.id = oi.order_id
              JOIN product_variants pv ON oi.variant_id = pv.id
              WHERE o.user_id = :user_id
                AND pv.product_id = :product_id
                AND o.status = 'completed'"; // Chỉ tính đơn hàng đã hoàn thành

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":user_id", $userId);
        $stmt->bindParam(":product_id", $productId);
        $stmt->execute();

        return $stmt->fetchColumn() > 0;
    }
}
