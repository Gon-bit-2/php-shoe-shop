<?php
// File: migrations/20250919_create_ecommerce_schema.php

require_once __DIR__ . '/../src/models/repositories/database.php';

try {
    echo "==================================================\n";
    echo "Bắt đầu chạy migration...\n";
    echo "==================================================\n";

    // BẮT ĐẦU TRANSACTION ĐỂ ĐẢM BẢO AN TOÀN

    // Bước 1: Xóa các bảng cũ theo thứ tự ngược lại của khóa ngoại để tránh lỗi
    // Thứ tự này rất quan trọng!
    echo "Bước 1: Xóa các bảng sản phẩm cũ (nếu tồn tại)...\n";
    $conn->exec("DROP TABLE IF EXISTS inventories;");
    $conn->exec("DROP TABLE IF EXISTS product_variants;");
    $conn->exec("DROP TABLE IF EXISTS product_images;");
    $conn->exec("DROP TABLE IF EXISTS product_category_map;");
    $conn->exec("DROP TABLE IF EXISTS products;");
    $conn->exec("DROP TABLE IF EXISTS categories;");
    $conn->exec("DROP TABLE IF EXISTS brands;"); // Xóa cả bảng brands cũ nếu có
    echo " -> Xong.\n";

    // Bước 2: Tạo các bảng mới theo đúng thứ tự
    echo "Bước 2: Tạo các bảng mới...\n";

    // Bảng CATEGORIES
    $sql_categories = "CREATE TABLE categories (
        id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
        parent_id BIGINT UNSIGNED NULL,
        name VARCHAR(191) NOT NULL,
        slug VARCHAR(191) NOT NULL UNIQUE,
        is_active BOOLEAN NOT NULL DEFAULT TRUE,
        created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        CONSTRAINT fk_cat_parent FOREIGN KEY (parent_id) REFERENCES categories(id) ON DELETE SET NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
    $conn->exec($sql_categories);
    echo " -> Bảng 'categories' đã được tạo.\n";
    // ... trong file migrations/20250918_add_product_relations.php
    // Ngay sau lệnh $conn->exec($sql_categories);

    // --- BẮT ĐẦU ĐOẠN CODE MỚI ---

    echo " -> Chèn dữ liệu mẫu cho 'categories'...\n";
    // Dùng INSERT IGNORE để không báo lỗi nếu dữ liệu đã tồn tại
    $conn->exec("INSERT IGNORE INTO categories (name, slug) VALUES
    ('Giày chạy bộ', 'giay-chay-bo'),
    ('Giày thời trang', 'giay-thoi-trang'),
    ('Giày công sở', 'giay-cong-so'),
    ('Giày đá bóng', 'giay-da-bong');
");
    echo " -> Xong.\n";

    // --- KẾT THÚC ĐOẠN CODE MỚI ---

    // Bảng PRODUCTS (giữ nguyên)
    $sql_products = "CREATE TABLE products (...)";
    // ...
    // Bảng PRODUCTS
    $sql_products = "CREATE TABLE products (
        id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
        sku VARCHAR(64) UNIQUE,
        name VARCHAR(191) NOT NULL,
        slug VARCHAR(191) NOT NULL UNIQUE,
        short_desc VARCHAR(500),
        description TEXT,
        base_price DECIMAL(12,2) NOT NULL,
        cost_price DECIMAL(12,2) DEFAULT 0.00,
        is_active BOOLEAN NOT NULL DEFAULT TRUE,
        created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FULLTEXT KEY ft_products (name, short_desc, description)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
    $conn->exec($sql_products);
    echo " -> Bảng 'products' đã được tạo.\n";

    // Bảng PRODUCT_CATEGORY_MAP (Bảng nối)
    $sql_product_category_map = "CREATE TABLE product_category_map (
        product_id BIGINT UNSIGNED NOT NULL,
        category_id BIGINT UNSIGNED NOT NULL,
        PRIMARY KEY (product_id, category_id),
        CONSTRAINT fk_pcm_p FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
        CONSTRAINT fk_pcm_c FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
    $conn->exec($sql_product_category_map);
    echo " -> Bảng 'product_category_map' đã được tạo.\n";

    // Bảng PRODUCT_IMAGES
    $sql_product_images = "CREATE TABLE product_images (
        id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
        product_id BIGINT UNSIGNED NOT NULL,
        image_url VARCHAR(512) NOT NULL,
        is_primary BOOLEAN NOT NULL DEFAULT FALSE,
        sort_order INT NOT NULL DEFAULT 0,
        CONSTRAINT fk_pimg_p FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
    $conn->exec($sql_product_images);
    echo " -> Bảng 'product_images' đã được tạo.\n";

    // Bảng PRODUCT_VARIANTS
    $sql_product_variants = "CREATE TABLE product_variants (
        id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
        product_id BIGINT UNSIGNED NOT NULL,
        sku VARCHAR(64) UNIQUE,
        name VARCHAR(191),
        attributes JSON, -- {\"size\":\"M\",\"color\":\"Black\"}
        price DECIMAL(12,2) NOT NULL,
        compare_at_price DECIMAL(12,2),
        weight_gram INT UNSIGNED DEFAULT 0,
        is_active BOOLEAN NOT NULL DEFAULT TRUE,
        created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        CONSTRAINT fk_pv_p FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
    $conn->exec($sql_product_variants);
    echo " -> Bảng 'product_variants' đã được tạo.\n";

    // Bảng INVENTORIES
    $sql_inventories = "CREATE TABLE inventories (
        id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
        variant_id BIGINT UNSIGNED NOT NULL UNIQUE, -- Mỗi biến thể chỉ có 1 dòng kho
        in_stock INT NOT NULL DEFAULT 0,
        reserved INT NOT NULL DEFAULT 0,
        low_stock_thresh INT NOT NULL DEFAULT 5,
        updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        CONSTRAINT fk_inv_v FOREIGN KEY (variant_id) REFERENCES product_variants(id) ON DELETE CASCADE,
        CONSTRAINT ck_inv_nonneg CHECK (in_stock >= 0 AND reserved >= 0)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
    $conn->exec($sql_inventories);
    echo " -> Bảng 'inventories' đã được tạo.\n";
} catch (PDOException $e) {
    die("❌ Lỗi khi chạy migration: " . $e->getMessage());
}
