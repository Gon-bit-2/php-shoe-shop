<?php
require_once __DIR__ . '/../category.php';
class CategoryRepository
{
    private $conn;
    public function __construct($conn)
    {
        $this->conn = $conn;
    }
    public function findAll()
    {
        $query = "SELECT * FROM categories";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
}
