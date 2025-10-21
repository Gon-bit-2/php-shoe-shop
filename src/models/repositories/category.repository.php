<?php
require_once __DIR__ . '/../category.php';

class CategoryRepository
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function findAll($activeOnly = false)
    {
        if ($activeOnly) {
            $query = "SELECT * FROM categories WHERE is_active = 1 ORDER BY name ASC";
        } else {
            $query = "SELECT * FROM categories ORDER BY name ASC";
        }
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_CLASS, 'Category');
    }

    public function findById($id)
    {
        $query = "SELECT * FROM categories WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([':id' => $id]);
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Category');
        return $stmt->fetch();
    }

    public function save(Category $category)
    {
        $query = "INSERT INTO categories (name, slug, image_url, is_active) VALUES (:name, :slug, :image_url, :is_active)";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([
            ':name' => $category->name,
            ':slug' => $category->slug,
            ':image_url' => $category->image_url,
            ':is_active' => $category->is_active,
        ]);
    }

    public function update(Category $category)
    {
        $query = "UPDATE categories SET name = :name, slug = :slug, image_url = :image_url, is_active = :is_active WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([
            ':id' => $category->id,
            ':name' => $category->name,
            ':slug' => $category->slug,
            ':image_url' => $category->image_url,
            ':is_active' => $category->is_active,
        ]);
    }
}
