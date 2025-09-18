<?php
require_once '../src/models/repositories/database.php';

try {
    // -- Bảng 1: ROLES --
    $conn->exec("CREATE TABLE IF NOT EXISTS roles (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(50) NOT NULL UNIQUE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");

    echo "Bảng 'roles' đã được tạo thành công hoặc đã tồn tại.<br>";

    // Thêm các vai trò mặc định (chỉ chạy một lần)
    // Dùng INSERT IGNORE để không báo lỗi nếu vai trò đã tồn tại
    $conn->exec("INSERT IGNORE INTO roles (id, name) VALUES (1, 'admin'), (2, 'user');");
    echo "Các vai trò mặc định đã được thêm vào.<br>";


    // -- Bảng 2: USERS (Cập nhật lại) --
    // Xóa cột `role` cũ và thêm cột `role_id`
    $sql_users = "CREATE TABLE IF NOT EXISTS users (
        id INT(11) AUTO_INCREMENT PRIMARY KEY,
        fullname VARCHAR(100) NOT NULL,
        email VARCHAR(100) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        role_id INT NOT NULL DEFAULT 2, -- Mặc định là 'user' (ID=2)
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        CONSTRAINT fk_user_role FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE RESTRICT ON UPDATE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";

    $conn->exec($sql_users);

    echo "Bảng 'users' đã được cập nhật thành công.<br>";
} catch (PDOException $e) {
    die("Lỗi khi thiết lập database: " . $e->getMessage());
}
