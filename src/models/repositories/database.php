<?php
$host = "localhost";
$port = 3307;
$dbname = "shoe_shop_db";
$username = "root";
$password = "";
try {

    $conn = new PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // echo "Kết nối thành công";
} catch (PDOException $e) {
    //throw $th;
    die("Kết nối thất bại: " . $e->getMessage());
}
