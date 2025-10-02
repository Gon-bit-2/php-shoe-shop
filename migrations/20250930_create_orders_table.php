<?php
require_once __DIR__ . '/../src/models/repositories/database.php';

try {
    echo "==================================================\n";
    echo "Bắt đầu chạy migration cho bảng Orders (đã nâng cấp)...\n";
    echo "==================================================\n";

    $conn->exec("SET FOREIGN_KEY_CHECKS=0;");
    $conn->exec("DROP TABLE IF EXISTS order_items;");
    $conn->exec("DROP TABLE IF EXISTS orders;");
    $conn->exec("SET FOREIGN_KEY_CHECKS=1;");
    echo " -> Các bảng orders cũ (nếu có) đã được xóa.\n";

    // Bảng `orders` (Không thay đổi)
    $sql_orders = "CREATE TABLE orders (
        id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
        user_id INT NOT NULL,
        customer_name VARCHAR(191) NOT NULL,
        customer_phone VARCHAR(20) NOT NULL,
        customer_address TEXT NOT NULL,
        total_amount DECIMAL(12, 2) NOT NULL,
        status ENUM('pending', 'processing', 'shipped', 'completed', 'cancelled') NOT NULL DEFAULT 'pending',
        created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        CONSTRAINT fk_order_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
    $conn->exec($sql_orders);
    echo " -> Bảng 'orders' đã được tạo.\n";

    // NÂNG CẤP BẢNG `order_items`
    $sql_order_items = "CREATE TABLE order_items (
        id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
        order_id BIGINT UNSIGNED NOT NULL,
        variant_id BIGINT UNSIGNED NULL, -- Đổi từ product_id
        product_name VARCHAR(191) NOT NULL,
        variant_attributes VARCHAR(255) NULL, -- Cột mới để lưu: '40, Đen'
        quantity INT UNSIGNED NOT NULL,
        price DECIMAL(12, 2) NOT NULL,
        CONSTRAINT fk_oi_order FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
        -- Khóa ngoại mới trỏ đến product_variants
        CONSTRAINT fk_oi_variant FOREIGN KEY (variant_id) REFERENCES product_variants(id) ON DELETE SET NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
    $conn->exec($sql_order_items);
    echo " -> Bảng 'order_items' đã được nâng cấp.\n";

    echo "✅ Migration cho Orders hoàn tất!\n";
} catch (PDOException $e) {
    die("❌ Lỗi khi chạy migration: " . $e->getMessage());
}
