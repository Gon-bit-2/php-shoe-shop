<?php
require_once __DIR__ . '/../src/models/repositories/database.php';

try {
    echo "Bắt đầu cập nhật bảng 'categories'...\n";
    $sql = "ALTER TABLE `categories` ADD COLUMN `image_url` VARCHAR(512) NULL AFTER `slug`;";
    $conn->exec($sql);
    echo "✅ Bảng 'categories' đã được cập nhật thành công với cột 'image_url'.\n";
} catch (PDOException $e) {
    if ($e->errorInfo[1] == 1060) {
        echo "✅ Cột 'image_url' đã tồn tại. Không cần thay đổi.\n";
    } else {
        die("❌ Lỗi khi cập nhật bảng: " . $e->getMessage());
    }
}
