<?php
// File: migrations/20251003_add_product_variants.php

require_once __DIR__ . '/../src/models/repositories/database.php';

try {
    echo "==================================================\n";
    echo "Bắt đầu migration cho Biến thể Sản phẩm...\n";
    echo "==================================================\n";

    // Bắt đầu transaction để đảm bảo nếu có lỗi, mọi thứ sẽ được khôi phục
    $conn->beginTransaction();

    // -- BƯỚC 1: XÓA CÁC BẢNG CŨ THEO ĐÚNG THỨ TỰ --
    // Việc này đảm bảo bạn có thể chạy lại file này nhiều lần mà không bị lỗi
    echo "Bước 1: Xóa các bảng liên quan (nếu tồn tại)...\n";
    $conn->exec("SET FOREIGN_KEY_CHECKS = 0;"); // Tạm thời tắt kiểm tra khóa ngoại
    $conn->exec("DROP TABLE IF EXISTS variant_values;");
    $conn->exec("DROP TABLE IF EXISTS product_variants;");
    $conn->exec("DROP TABLE IF EXISTS attribute_values;");
    $conn->exec("DROP TABLE IF EXISTS attributes;");
    $conn->exec("SET FOREIGN_KEY_CHECKS = 1;"); // Bật lại kiểm tra khóa ngoại
    echo " -> Xong.\n";

    // -- BƯỚC 2: TẠO CÁC BẢNG MỚI --
    echo "Bước 2: Tạo các bảng mới cho thuộc tính và biến thể...\n";

    // Bảng `attributes`: Lưu tên thuộc tính (VD: 'Size', 'Màu sắc')
    $sql_attributes = "CREATE TABLE attributes (
        id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
        name VARCHAR(100) NOT NULL UNIQUE COMMENT 'Tên thuộc tính, vd: Size, Màu sắc'
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
    $conn->exec($sql_attributes);
    echo " -> Bảng 'attributes' đã được tạo.\n";

    // Bảng `attribute_values`: Lưu giá trị của thuộc tính (VD: '40', 'Xanh')
    $sql_attribute_values = "CREATE TABLE attribute_values (
        id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
        attribute_id BIGINT UNSIGNED NOT NULL,
        `value` VARCHAR(100) NOT NULL COMMENT 'Giá trị thuộc tính, vd: 40, Xanh',
        CONSTRAINT fk_av_attribute FOREIGN KEY (attribute_id) REFERENCES attributes(id) ON DELETE CASCADE,
        UNIQUE KEY uq_attribute_value (attribute_id, `value`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
    $conn->exec($sql_attribute_values);
    echo " -> Bảng 'attribute_values' đã được tạo.\n";

    // Bảng `product_variants`: Lưu các biến thể với giá và tồn kho riêng
    $sql_product_variants = "CREATE TABLE product_variants (
        id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
        product_id BIGINT UNSIGNED NOT NULL,
        sku VARCHAR(100) UNIQUE COMMENT 'Mã SKU duy nhất cho từng biến thể',
        price DECIMAL(12, 2) NOT NULL,
        stock INT UNSIGNED NOT NULL DEFAULT 0,
        image_url VARCHAR(512) NULL COMMENT 'Ảnh riêng cho biến thể nếu có',
        CONSTRAINT fk_pv_product FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
    $conn->exec($sql_product_variants);
    echo " -> Bảng 'product_variants' đã được tạo.\n";

    // Bảng `variant_values` (Bảng nối): Nối một biến thể với các giá trị thuộc tính của nó
    $sql_variant_values = "CREATE TABLE variant_values (
        variant_id BIGINT UNSIGNED NOT NULL,
        attribute_value_id BIGINT UNSIGNED NOT NULL,
        PRIMARY KEY (variant_id, attribute_value_id),
        CONSTRAINT fk_vv_variant FOREIGN KEY (variant_id) REFERENCES product_variants(id) ON DELETE CASCADE,
        CONSTRAINT fk_vv_attribute_value FOREIGN KEY (attribute_value_id) REFERENCES attribute_values(id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
    $conn->exec($sql_variant_values);
    echo " -> Bảng nối 'variant_values' đã được tạo.\n";

    // -- BƯỚC 3: SỬA ĐỔI BẢNG `products` --
    echo "Bước 3: Sửa đổi bảng 'products'...\n";
    try {
        $conn->exec("ALTER TABLE products DROP COLUMN price;");
        $conn->exec("ALTER TABLE products DROP COLUMN stock;");
        echo " -> Đã xóa thành công cột 'price' và 'stock' khỏi bảng 'products'.\n";
    } catch (PDOException $e) {
        // Bỏ qua lỗi nếu các cột này không tồn tại (do đã chạy migration trước đó)
        echo " -> Cảnh báo: Cột 'price' và 'stock' không tồn tại để xóa (có thể đã được xóa trước đó).\n";
    }

    // -- BƯỚC 4: THÊM DỮ LIỆU MẪU --
    echo "Bước 4: Chèn dữ liệu mẫu cho các thuộc tính...\n";
    $conn->exec("INSERT INTO attributes (id, name) VALUES (1, 'Size'), (2, 'Màu sắc');");
    $conn->exec("INSERT INTO attribute_values (attribute_id, `value`) VALUES
        (1, '39'), (1, '40'), (1, '41'), (1, '42'), (1, '43'),
        (2, 'Đen'), (2, 'Trắng'), (2, 'Xanh'), (2, 'Đỏ');
    ");
    echo " -> Đã chèn dữ liệu mẫu cho Size và Màu sắc.\n";

    // Hoàn tất và lưu lại mọi thay đổi
    $conn->commit();
    echo "✅ Migration hoàn tất thành công!\n";
} catch (PDOException $e) {
    // Kiểm tra xem có transaction đang hoạt động không trước khi rollback
    if ($conn->inTransaction()) {
        $conn->rollBack();
    }
    die("❌ Lỗi khi chạy migration: " . $e->getMessage());
}
