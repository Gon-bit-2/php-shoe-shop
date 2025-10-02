<?php
require_once 'src/models/repositories/database.php';

try {
    // Kiểm tra cấu trúc bảng products
    $query = "DESCRIBE products";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo "Cấu trúc bảng products:\n";
    foreach ($columns as $column) {
        echo "- " . $column['Field'] . " (" . $column['Type'] . ")\n";
    }

    // Kiểm tra xem có bảng product_variants không
    $query = "SHOW TABLES LIKE 'product_variants'";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $result = $stmt->fetch();

    if ($result) {
        echo "\nBảng product_variants tồn tại\n";

        // Kiểm tra cấu trúc bảng product_variants
        $query = "DESCRIBE product_variants";
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo "Cấu trúc bảng product_variants:\n";
        foreach ($columns as $column) {
            echo "- " . $column['Field'] . " (" . $column['Type'] . ")\n";
        }
    } else {
        echo "\nBảng product_variants KHÔNG tồn tại\n";
    }

    // Kiểm tra bảng attributes
    $query = "SHOW TABLES LIKE 'attributes'";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $result = $stmt->fetch();

    if ($result) {
        echo "\nBảng attributes tồn tại\n";

        // Kiểm tra dữ liệu trong bảng attributes
        $query = "SELECT * FROM attributes";
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $attributes = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo "Dữ liệu trong bảng attributes:\n";
        foreach ($attributes as $attr) {
            echo "- ID: " . $attr['id'] . ", Name: " . $attr['name'] . "\n";
        }
    } else {
        echo "\nBảng attributes KHÔNG tồn tại\n";
    }

    // Kiểm tra bảng attribute_values
    $query = "SHOW TABLES LIKE 'attribute_values'";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $result = $stmt->fetch();

    if ($result) {
        echo "\nBảng attribute_values tồn tại\n";

        // Kiểm tra dữ liệu trong bảng attribute_values
        $query = "SELECT * FROM attribute_values";
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $values = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo "Dữ liệu trong bảng attribute_values:\n";
        foreach ($values as $value) {
            echo "- ID: " . $value['id'] . ", Attribute ID: " . $value['attribute_id'] . ", Value: " . $value['value'] . "\n";
        }
    } else {
        echo "\nBảng attribute_values KHÔNG tồn tại\n";
    }

    // Kiểm tra bảng variant_values
    $query = "SHOW TABLES LIKE 'variant_values'";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $result = $stmt->fetch();

    if ($result) {
        echo "\nBảng variant_values tồn tại\n";
    } else {
        echo "\nBảng variant_values KHÔNG tồn tại\n";
    }
} catch (Exception $e) {
    echo "Lỗi: " . $e->getMessage();
}
