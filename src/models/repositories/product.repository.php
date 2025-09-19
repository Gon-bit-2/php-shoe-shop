<?php
require_once __DIR__ . '/../product.php';
class ProductRepository
{
    private $conn;
    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function save(Product $product, array $categoryIDs)
    {
        try {
            $this->conn->beginTransaction();
            $query = "INSERT INTO products (name,sku,slug,short_desc,description,base_price,cost_price,is_active) VALUES (:name,:sku,:slug,:short_desc,:description,:base_price,:cost_price,:is_active)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":name", $product->name);
            $stmt->bindParam(":sku", $product->sku);
            $stmt->bindParam(":slug", $product->slug);
            $stmt->bindParam(":short_desc", $product->short_desc);
            $stmt->bindParam(":description", $product->description);
            $stmt->bindParam(":base_price", $product->base_price);
            $stmt->bindParam(":cost_price", $product->cost_price);
            $stmt->bindParam(":is_active", $product->is_active);
            $stmt->execute();
            $productID = $this->conn->lastInsertId();
            if (!empty($categoryIDs)) {
                $mapQuery = "INSERT INTO product_category_map (product_id,category_id) VALUES (:product_id,:category_id)";
                $mapStmt = $this->conn->prepare($mapQuery);
                foreach ($categoryIDs as $categoryID) {
                    $mapStmt->execute([':product_id' => $productID, ':category_id' => $categoryID]);
                }
            }
            $this->conn->commit();
            return $productID;
        } catch (Exception $e) {
            $this->conn->rollBack();
            return false;
        }
    }
    function findAll()
    {
        $query = "SELECT * FROM products ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_CLASS, 'Product');
    }
    function findById($id)
    {
        $query = "SELECT * FROM products WHERE id=:id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_CLASS, 'Product');
    }
    function findCategoryByProductId($productId)
    {
        $query = "SELECT category_id FROM product_category_map WHERE product_id = :productId";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":productId", $productId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
}
