<?php
require_once __DIR__ . '/../src/models/repositories/database.php';
try {
    $sql = "ALTER TABLE `users`
            ADD COLUMN `is_active` BOOLEAN NOT NULL DEFAULT TRUE AFTER `role_id`,
            ADD COLUMN `reset_token` VARCHAR(255) NULL AFTER `is_active`,
            ADD COLUMN `reset_token_expires_at` TIMESTAMP NULL AFTER `reset_token`;";
    $conn->exec($sql);
    echo "✅ Bảng 'users' đã được cập nhật cho chức năng quên mật khẩu.\n";
} catch (PDOException $e) {
    if ($e->errorInfo[1] == 1060) {
        echo "✅ Các cột cho chức năng quên mật khẩu đã tồn tại.\n";
    } else {
        die("❌ Lỗi khi cập nhật bảng: " . $e->getMessage());
    }
}
