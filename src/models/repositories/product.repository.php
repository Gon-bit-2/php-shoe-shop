<?php
require_once __DIR__ . '/../product.php';
class ProductRepository
{
    private $conn;
    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function save(Product $product)
    {
        try {
            $this->conn->beginTransaction();
            $query = "INSERT INTO products (sku,slug,short_desc,base_price,cost_price,is_active,created_at,updated_at) VALUES (:sku,:slug,:short_desc,:base_price,:cost_price,:is_active,:created_at,:updated_at)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":sku", $product->sku);
            $stmt->bindParam(":slug", $product->slug);
            $stmt->bindParam(":short_desc", $product->short_desc);
            $stmt->bindParam(":base_price", $product->base_price);
            $stmt->bindParam(":cost_price", $product->cost_price);
            $stmt->bindParam(":is_active", $product->is_active);
            $stmt->bindParam(":created_at", $product->created_at);
            $stmt->bindParam(":updated_at", $product->updated_at);
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
}
