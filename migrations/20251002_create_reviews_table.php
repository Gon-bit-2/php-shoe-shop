<?php
// File: migrations/20251004_create_reviews_table.php

require_once __DIR__ . '/../src/models/repositories/database.php';

try {
    echo "==================================================\n";
    echo "Bắt đầu chạy migration cho bảng Reviews...\n";
    echo "==================================================\n";

    // Bắt đầu transaction để đảm bảo an toàn
    $conn->beginTransaction();

    // -- BƯỚC 1: XÓA BẢNG CŨ NẾU TỒN TẠI --
    // Điều này giúp script có thể chạy lại nhiều lần mà không gây lỗi.
    echo "Bước 1: Xóa bảng 'reviews' cũ (nếu tồn tại)...\n";
    $conn->exec("SET FOREIGN_KEY_CHECKS=0;"); // Tạm thời tắt kiểm tra khóa ngoại
    $conn->exec("DROP TABLE IF EXISTS reviews;");
    $conn->exec("SET FOREIGN_KEY_CHECKS=1;"); // Bật lại ngay sau đó
    echo " -> Xong.\n";

    // -- BƯỚC 2: TẠO BẢNG `reviews` MỚI --
    echo "Bước 2: Tạo bảng 'reviews' mới...\n";
    $sql_reviews = "CREATE TABLE reviews (
        id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
        product_id BIGINT UNSIGNED NOT NULL COMMENT 'Sản phẩm được đánh giá',
        user_id INT NOT NULL COMMENT 'Người dùng viết đánh giá',
        order_id BIGINT UNSIGNED NOT NULL COMMENT 'Đơn hàng đã mua sản phẩm này',
        rating TINYINT UNSIGNED NOT NULL COMMENT 'Điểm đánh giá từ 1 đến 5',
        comment TEXT NULL COMMENT 'Nội dung bình luận chi tiết',
        status ENUM('pending', 'approved', 'rejected') NOT NULL DEFAULT 'pending' COMMENT 'Trạng thái để kiểm duyệt',
        created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

        -- Khóa ngoại trỏ đến các bảng liên quan
        CONSTRAINT fk_review_product FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
        CONSTRAINT fk_review_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
        CONSTRAINT fk_review_order FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,

        -- Ràng buộc duy nhất: Mỗi người dùng chỉ được đánh giá 1 sản phẩm 1 lần cho mỗi đơn hàng
        UNIQUE KEY uq_user_product_order (user_id, product_id, order_id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";

    $conn->exec($sql_reviews);
    echo " -> Bảng 'reviews' đã được tạo thành công.\n";

    // Hoàn tất và lưu lại mọi thay đổi
    $conn->commit();
    echo "✅ Migration cho bảng 'reviews' hoàn tất thành công!\n";
} catch (PDOException $e) {
    // Nếu có lỗi, hủy bỏ mọi thay đổi đã thực hiện
    if ($conn->inTransaction()) {
        $conn->rollBack();
    }
    // Thông báo lỗi chi tiết
    die("❌ Lỗi khi chạy migration: " . $e->getMessage());
}
