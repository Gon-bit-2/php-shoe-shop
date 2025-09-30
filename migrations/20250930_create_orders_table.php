<?php

require_once __DIR__ . '/../src/models/repositories/database.php';

try {
    echo "==================================================\n";
    echo "Bắt đầu chạy migration cho bảng Orders...\n";
    echo "==================================================\n";

    // Xóa bảng cũ nếu tồn tại (để có thể chạy lại migration nhiều lần)
    $conn->exec("DROP TABLE IF EXISTS order_items;");
    $conn->exec("DROP TABLE IF EXISTS orders;");
    echo " -> Các bảng orders cũ (nếu có) đã được xóa.\n";

    // 1. TẠO BẢNG `orders`
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

    // 2. TẠO BẢNG `order_items`
    $sql_order_items = "CREATE TABLE order_items (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    order_id BIGINT UNSIGNED NOT NULL,
    product_id BIGINT UNSIGNED NULL,
    product_name VARCHAR(191) NOT NULL,
    quantity INT UNSIGNED NOT NULL,
    price DECIMAL(12, 2) NOT NULL,
    CONSTRAINT fk_oi_order FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    CONSTRAINT fk_oi_product FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
    $conn->exec($sql_order_items);
    echo " -> Bảng 'order_items' đã được tạo.\n";


    echo "✅ Migration cho Orders hoàn tất!\n";
} catch (PDOException $e) {
    die("❌ Lỗi khi chạy migration: " . $e->getMessage());
}
