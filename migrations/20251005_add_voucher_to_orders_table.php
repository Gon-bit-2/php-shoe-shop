<?php
// File: migrations/20251006_add_voucher_to_orders_table.php

require_once __DIR__ . '/../src/models/repositories/database.php';

try {
    echo "==================================================\n";
    echo "Bắt đầu cập nhật bảng 'orders' để thêm thông tin voucher...\n";
    echo "==================================================\n";

    $sql_alter_orders = "ALTER TABLE `orders`
                         ADD COLUMN `voucher_code` VARCHAR(100) NULL AFTER `payment_method`,
                         ADD COLUMN `discount_amount` DECIMAL(12, 2) NOT NULL DEFAULT 0.00 AFTER `voucher_code`;";

    $conn->exec($sql_alter_orders);

    echo "✅ Bảng 'orders' đã được cập nhật thành công.\n";
} catch (PDOException $e) {
    if ($e->errorInfo[1] == 1060) { // Lỗi cột đã tồn tại
        echo "✅ Các cột 'voucher_code' và 'discount_amount' đã tồn tại. Không cần thay đổi.\n";
    } else {
        die("❌ Lỗi khi cập nhật bảng 'orders': " . $e->getMessage());
    }
}
