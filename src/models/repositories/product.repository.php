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
            // Chỉ INSERT vào các cột có dữ liệu từ form create
            $query = "INSERT INTO products (name, slug, description, price, image_url, stock, is_active)
                  VALUES (:name, :slug, :description, :price, :image_url, :stock, :is_active)";

            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(":name", $product->name);
            $stmt->bindParam(":slug", $product->slug);
            $stmt->bindParam(":description", $product->description);
            $stmt->bindParam(":price", $product->price);
            $stmt->bindParam(":image_url", $product->image_url);
            $stmt->bindParam(":stock", $product->stock);
            $stmt->bindParam(":is_active", $product->is_active);

            $stmt->execute();
            $productID = $this->conn->lastInsertId();

            if (!empty($categoryIDs)) {
                $mapQuery = "INSERT INTO product_category_map (product_id, category_id) VALUES (:product_id, :category_id)";
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
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Product');
        return $stmt->fetch();
    }
    function findCategoryByProductId($productId)
    {
        $query = "SELECT category_id FROM product_category_map WHERE product_id = :productId";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":productId", $productId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
    function update(Product $product, array $categoryIDs)
    {
        try {
            $this->conn->beginTransaction();
            $query = "UPDATE products SET name=:name,slug=:slug,description=:description,price=:price,image_url=:image_url,stock=:stock,is_active=:is_active,updated_at=:updated_at WHERE id=:id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":id", $product->id);
            $stmt->bindParam(":name", $product->name);
            $stmt->bindParam(":slug", $product->slug);
            $stmt->bindParam(":description", $product->description);
            $stmt->bindParam(":price", $product->price);
            $stmt->bindParam(":image_url", $product->image_url);
            $stmt->bindParam(":stock", $product->stock);
            $stmt->bindParam(":is_active", $product->is_active);
            $stmt->bindParam(":updated_at", $product->updated_at);
            $stmt->execute();
            $deleteQuery = "DELETE FROM product_category_map WHERE product_id = :product_id";
            $deleteStmt = $this->conn->prepare($deleteQuery);
            $deleteStmt->execute([':product_id' => $product->id]);
            if (!empty($categoryIDs)) {
                $mapQuery = "INSERT INTO product_category_map (product_id,category_id) VALUES (:product_id,:category_id)";
                $mapStmt = $this->conn->prepare($mapQuery);
                foreach ($categoryIDs as $categoryID) {
                    $mapStmt->execute([':product_id' => $product->id, ':category_id' => $categoryID]);
                }
            }
            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            return false;
        }
    }
    public function delete($id)
    {
        try {
            $query = "DELETE FROM products WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id);
            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }
}
