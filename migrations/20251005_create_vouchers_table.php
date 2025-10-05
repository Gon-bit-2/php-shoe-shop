<?php
// File: migrations/20251005_create_vouchers_table.php (ĐÃ SỬA LỖI)

require_once __DIR__ . '/../src/models/repositories/database.php';

try {
    echo "==================================================\n";
    echo "Bắt đầu chạy migration cho bảng Vouchers...\n";
    echo "==================================================\n";

    // -- BƯỚC 1: XÓA BẢNG CŨ NẾU TỒN TẠI --
    echo "Bước 1: Xóa bảng 'vouchers' cũ (nếu tồn tại)...\n";
    $conn->exec("SET FOREIGN_KEY_CHECKS=0;");
    $conn->exec("DROP TABLE IF EXISTS vouchers;");
    $conn->exec("SET FOREIGN_KEY_CHECKS=1;");
    echo " -> Xong.\n";

    // -- BƯỚC 2: TẠO BẢNG `vouchers` MỚI --
    echo "Bước 2: Tạo bảng 'vouchers' mới...\n";
    $sql_vouchers = "CREATE TABLE vouchers (
        id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
        code VARCHAR(100) NOT NULL UNIQUE COMMENT 'Mã voucher, ví dụ: SALE50, FREESHIP',
        type ENUM('fixed', 'percent') NOT NULL COMMENT 'fixed: Giảm số tiền cố định, percent: Giảm theo %',
        `value` DECIMAL(12, 2) NOT NULL COMMENT 'Giá trị giảm (50000 hoặc 10 cho 10%)',
        min_spend DECIMAL(12, 2) NOT NULL DEFAULT 0.00 COMMENT 'Số tiền tối thiểu để áp dụng',
        quantity INT UNSIGNED NOT NULL DEFAULT 0 COMMENT 'Tổng số lượt sử dụng',
        used_count INT UNSIGNED NOT NULL DEFAULT 0 COMMENT 'Số lượt đã sử dụng',
        starts_at TIMESTAMP NULL COMMENT 'Ngày bắt đầu có hiệu lực',
        expires_at TIMESTAMP NULL COMMENT 'Ngày hết hiệu lực',
        is_active BOOLEAN NOT NULL DEFAULT TRUE COMMENT 'Bật/tắt voucher',
        created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        INDEX idx_voucher_code (code),
        INDEX idx_voucher_dates (starts_at, expires_at)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";

    $conn->exec($sql_vouchers);
    echo " -> Bảng 'vouchers' đã được tạo thành công.\n";

    // Không cần commit nữa
    echo "✅ Migration cho bảng 'vouchers' hoàn tất thành công!\n";
} catch (PDOException $e) {
    // Không cần rollback nữa
    die("❌ Lỗi khi chạy migration: " . $e->getMessage());
}
