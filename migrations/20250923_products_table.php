<?php
// File: migrations/20250923_simplify_products_table.php

require_once __DIR__ . '/../src/models/repositories/database.php';

try {
    echo "==================================================\n";
    echo "Bắt đầu migration đơn giản hóa bảng Products...\n";
    echo "==================================================\n";

    $conn->exec("DROP TABLE IF EXISTS product_category_map;");
    $conn->exec("DROP TABLE IF EXISTS products;");
    echo " -> Các bảng phức tạp cũ đã được xóa.\n";

    // Bước 2:  bảng Products
    $sql_products = "CREATE TABLE products (
        id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
        name VARCHAR(191) NOT NULL,
        slug VARCHAR(191) NOT NULL UNIQUE,
        description TEXT,
        price DECIMAL(12,2) NOT NULL,
        image_url VARCHAR(512) NULL,
        stock INT UNSIGNED NOT NULL DEFAULT 0,
        is_active BOOLEAN NOT NULL DEFAULT TRUE,
        created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
    $conn->exec($sql_products);
    echo " -> Bảng 'products'  đã được tạo.\n";

    // Bước 3: bảng nối product_category_map
    $sql_product_category_map = "CREATE TABLE product_category_map (
        product_id BIGINT UNSIGNED NOT NULL,
        category_id BIGINT UNSIGNED NOT NULL,
        PRIMARY KEY (product_id, category_id),
        CONSTRAINT fk_pcm_p FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
        CONSTRAINT fk_pcm_c FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
    $conn->exec($sql_product_category_map);
    echo " -> Bảng 'product_category_map' đã được tạo .\n";

    echo "✅ Migration đơn giản hóa hoàn tất!\n";
} catch (PDOException $e) {
    die("❌ Lỗi khi chạy migration: " . $e->getMessage());
}
