<?php
// File: migrations/20251002_add_payment_method_to_orders.php

require_once __DIR__ . '/../src/models/repositories/database.php';

try {
    echo "==================================================\n";
    echo "Bắt đầu cập nhật bảng 'orders'...\n";
    echo "==================================================\n";

    // Lệnh SQL để thêm cột mới vào bảng `orders`
    // ADD COLUMN `payment_method` ... -> Thêm cột mới
    // AFTER `total_amount` -> Chỉ định vị trí của cột mới cho dễ nhìn (tùy chọn)
    $sql_alter_orders = "ALTER TABLE `orders`
                         ADD COLUMN `payment_method` ENUM('cod', 'bank_transfer') NOT NULL DEFAULT 'cod'
                         COMMENT 'cod: Tiền mặt, bank_transfer: Chuyển khoản'
                         AFTER `total_amount`;";

    // Thực thi câu lệnh
    $conn->exec($sql_alter_orders);

    echo "✅ Bảng 'orders' đã được cập nhật thành công với cột 'payment_method'.\n";
} catch (PDOException $e) {
    // Bắt lỗi nếu cột đã tồn tại (mã lỗi 1060)
    if ($e->errorInfo[1] == 1060) {
        echo "✅ Cột 'payment_method' đã tồn tại. Không cần thay đổi.\n";
    } else {
        // Báo các lỗi khác
        die("❌ Lỗi khi cập nhật bảng 'orders': " . $e->getMessage());
    }
}
