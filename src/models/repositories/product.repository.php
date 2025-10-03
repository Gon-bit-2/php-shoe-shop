<?php
require_once __DIR__ . '/../product.php';
require_once __DIR__ . '/../productVariant.php';
require_once __DIR__ . '/../attribute_value.php';
class ProductRepository
{
    private $conn;
    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function save(Product $product, array $categoryIDs, array $variantsData)
    {
        try {
            $this->conn->beginTransaction();

            // Bước 1: Lưu sản phẩm chính (không có price và stock nữa)
            $query = "INSERT INTO products (name, slug, description, image_url, is_active)
              VALUES (:name, :slug, :description, :image_url, :is_active)";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([
                ':name' => $product->name,
                ':slug' => $product->slug,
                ':description' => $product->description,
                ':image_url' => $product->image_url,
                ':is_active' => $product->is_active
            ]);
            $productId = $this->conn->lastInsertId();

            // Bước 2: Xử lý danh mục
            if (!empty($categoryIDs)) {
                // Chuẩn bị câu lệnh MỘT LẦN bên ngoài vòng lặp
                $mapQuery = "INSERT INTO product_category_map (product_id, category_id) VALUES (?, ?)";
                $mapStmt = $this->conn->prepare($mapQuery);
                foreach ($categoryIDs as $categoryID) {
                    // Thực thi nhiều lần với dữ liệu khác nhau
                    $mapStmt->execute([$productId, $categoryID]);
                }
            }

            // Bước 3: Xử lý các biến thể
            // Chuẩn bị các câu lệnh MỘT LẦN bên ngoài vòng lặp
            $variantQuery = "INSERT INTO product_variants (product_id, price, stock) VALUES (?, ?, ?)";
            $variantStmt = $this->conn->prepare($variantQuery);

            $vvQuery = "INSERT INTO variant_values (variant_id, attribute_value_id) VALUES (?, ?)";
            $vvStmt = $this->conn->prepare($vvQuery);

            foreach ($variantsData as $variant) {
                // 3.1. Tạo dòng trong `product_variants`
                $variantStmt->execute([
                    $productId,
                    $variant['price'],
                    $variant['stock']
                ]);
                $variantId = $this->conn->lastInsertId();

                // 3.2. Lấy ID của giá trị thuộc tính (Size và Màu)
                $sizeValueId = $this->findOrCreateAttributeValue(1, $variant['size']);
                $colorValueId = $this->findOrCreateAttributeValue(2, $variant['color']);

                // 3.3. Tạo liên kết trong `variant_values` (thực thi 2 lần)
                $vvStmt->execute([$variantId, $sizeValueId]);
                $vvStmt->execute([$variantId, $colorValueId]);
            }

            $this->conn->commit();
            return $productId;
        } catch (Exception $e) {
            $this->conn->rollBack();
            error_log("Lỗi khi lưu sản phẩm: " . $e->getMessage());
            return false;
        }
    }

    public function findOrCreateAttributeValue($attributeId, $value)
    {
        // Thử tìm giá trị thuộc tính đã tồn tại
        $query = "SELECT id FROM attribute_values WHERE attribute_id = :attribute_id AND `value` = :value";
        $stmt = $this->conn->prepare($query);
        // Sử dụng execute với mảng để an toàn và nhất quán
        $stmt->execute([
            ':attribute_id' => $attributeId,
            ':value' => $value
        ]);
        $result = $stmt->fetchColumn();

        if ($result) {
            // Nếu đã có, trả về ID
            return $result;
        } else {
            // Nếu chưa có, tạo mới và trả về ID
            $insertQuery = "INSERT INTO attribute_values (attribute_id, `value`) VALUES (:attribute_id, :value)";
            $insertStmt = $this->conn->prepare($insertQuery);
            // Sử dụng execute với mảng
            $insertStmt->execute([
                ':attribute_id' => $attributeId,
                ':value' => $value
            ]);
            return $this->conn->lastInsertId();
        }
    }

    // ---- CÁC HÀM KHÁC (save, findAll,...) GIỮ NGUYÊN ----
    // ...
    public function findAttributeValuesByName($attributeName)
    {
        // Câu lệnh này JOIN 2 bảng attributes và attribute_values
        // để tìm tất cả `value` thuộc về một `name` cụ thể
        $query = "SELECT av.id, av.value
              FROM attribute_values av
              JOIN attributes a ON av.attribute_id = a.id
              WHERE a.name = :attribute_name";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":attribute_name", $attributeName);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_CLASS, 'AttributeValue');
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
        if (empty($id) || !is_numeric($id)) {
            return null;
        }
        $query = "SELECT * FROM products WHERE id=:id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Product');
        $result = $stmt->fetch();

        return $result === false ? null : $result;
    }
    function findCategoryByProductId($productId)
    {
        $query = "SELECT category_id FROM product_category_map WHERE product_id = :productId";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":productId", $productId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
    public function findVariantsByProductId($productId)
    {
        // Đây là một câu lệnh SQL phức tạp để lấy tất cả thông tin cần thiết trong một lần truy vấn
        $query = "SELECT
                pv.id, pv.price, pv.stock,
                a.name AS attribute_name,
                av.value AS attribute_value
              FROM product_variants pv
              JOIN variant_values vv ON pv.id = vv.variant_id
              JOIN attribute_values av ON vv.attribute_value_id = av.id
              JOIN attributes a ON av.attribute_id = a.id
              WHERE pv.product_id = :product_id
              ORDER BY pv.id, a.name";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":product_id", $productId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_CLASS, 'ProductVariant');
    }
    function getVariantDetails($variantId)
    {
        // (Đây là một cách làm đơn giản, có thể tối ưu bằng cách tạo hàm riêng trong Repository)
        $query = "SELECT
                    pv.id, pv.price, pv.stock, p.name AS product_name, p.image_url
                  FROM product_variants pv
                  JOIN products p ON pv.product_id = p.id
                  WHERE pv.id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $variantId);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_CLASS, 'ProductVariant');
    }
    public function update(Product $product, array $categoryIDs, array $variantsData)
    {
        try {
            $this->conn->beginTransaction();

            // Bước 1: Cập nhật thông tin sản phẩm chính
            $query = "UPDATE products SET
                    name = :name,
                    slug = :slug,
                    description = :description,
                    image_url = :image_url,
                    is_active = :is_active
                  WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":id", $product->id);
            $stmt->bindParam(":name", $product->name);
            $stmt->bindParam(":slug", $product->slug);
            $stmt->bindParam(":description", $product->description);
            $stmt->bindParam(":image_url", $product->image_url);
            $stmt->bindParam(":is_active", $product->is_active);
            $stmt->execute();

            // Bước 2: Cập nhật danh mục (xóa cũ, thêm mới)
            $this->conn->prepare("DELETE FROM product_category_map WHERE product_id = ?")->execute([$product->id]);
            if (!empty($categoryIDs)) {
                $mapQuery = "INSERT INTO product_category_map (product_id, category_id) VALUES (?, ?)";
                $mapStmt = $this->conn->prepare($mapQuery);
                foreach ($categoryIDs as $categoryID) {
                    $mapStmt->execute([$product->id, $categoryID]);
                }
            }

            // Bước 3: Đồng bộ hóa các biến thể (phần phức tạp nhất)
            $existingVariantIds = $this->getVariantIdsByProductId($product->id);
            $submittedVariantIds = [];

            foreach ($variantsData as $variant) {
                $variantId = $variant['id'] ?? null;

                if ($variantId) {
                    // A. Biến thể đã có -> UPDATE
                    $updateVariantQuery = "UPDATE product_variants SET price = ?, stock = ? WHERE id = ?";
                    $this->conn->prepare($updateVariantQuery)->execute([$variant['price'], $variant['stock'], $variantId]);
                    $submittedVariantIds[] = $variantId;
                } else {
                    // B. Biến thể mới -> INSERT
                    $insertVariantQuery = "INSERT INTO product_variants (product_id, price, stock) VALUES (?, ?, ?)";
                    $this->conn->prepare($insertVariantQuery)->execute([$product->id, $variant['price'], $variant['stock']]);
                    $newVariantId = $this->conn->lastInsertId();

                    $sizeValueId = $this->findOrCreateAttributeValue(1, $variant['size']);
                    $colorValueId = $this->findOrCreateAttributeValue(2, $variant['color']);

                    $vvQuery = "INSERT INTO variant_values (variant_id, attribute_value_id) VALUES (?, ?)";
                    $vvStmt = $this->conn->prepare($vvQuery);
                    $vvStmt->execute([$newVariantId, $sizeValueId]);
                    $vvStmt->execute([$newVariantId, $colorValueId]);
                }
            }

            // C. Tìm và xóa các biến thể đã bị admin xóa khỏi form
            $variantsToDelete = array_diff($existingVariantIds, $submittedVariantIds);
            if (!empty($variantsToDelete)) {
                $deleteQuery = "DELETE FROM product_variants WHERE id IN (" . implode(',', array_fill(0, count($variantsToDelete), '?')) . ")";
                $this->conn->prepare($deleteQuery)->execute(array_values($variantsToDelete));
            }

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            error_log("Lỗi khi cập nhật sản phẩm: " . $e->getMessage());
            return false;
        }
    }


    private function getVariantIdsByProductId($productId)
    {
        $stmt = $this->conn->prepare("SELECT id FROM product_variants WHERE product_id = ?");
        $stmt->execute([$productId]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN); // Lấy về mảng các ID
    }

    // Phương thức mới để tìm kiếm và lọc
    public function findWithFilters($filters = [])
    {
        $searchTerm = $filters['search'] ?? '';
        $categoryId = $filters['category'] ?? null;
        $priceRange = $filters['price'] ?? null;

        // **TRUY VẤN ĐÃ SỬA ĐỔI BẮT ĐẦU TỪ ĐÂY**
        // Chúng ta chọn p.* và thêm một trường mới 'price' là giá MIN từ các biến thể
        $query = "SELECT p.*, MIN(pv.price) as price
              FROM products p
              LEFT JOIN product_category_map pcm ON p.id = pcm.product_id
              LEFT JOIN categories c ON pcm.category_id = c.id
              -- Quan trọng: JOIN với product_variants để lấy giá
              LEFT JOIN product_variants pv ON p.id = pv.product_id
              WHERE p.is_active = 1";
        // **TRUY VẤN ĐÃ SỬA ĐỔI KẾT THÚC TẠI ĐÂY**

        $params = [];

        if (!empty($searchTerm)) {
            $query .= " AND p.name LIKE :search";
            $params[':search'] = '%' . $searchTerm . '%';
        }

        if (!empty($categoryId)) {
            // Thay đổi điều kiện để không gây lỗi ambiguous column
            $query .= " AND pcm.category_id = :category_id";
            $params[':category_id'] = $categoryId;
        }

        // Lọc giá bây giờ hoạt động trên giá của các biến thể
        if (!empty($priceRange)) {
            $priceParts = explode('-', $priceRange);
            $minPrice = $priceParts[0] ?? 0;
            $maxPrice = $priceParts[1] ?? null;

            // Chúng ta sẽ lọc dựa trên giá của các biến thể
            if (is_numeric($minPrice)) {
                // Sửa đổi để lọc trên bảng pv
                $query .= " AND pv.price >= :min_price";
                $params[':min_price'] = $minPrice;
            }

            if (is_numeric($maxPrice)) {
                // Sửa đổi để lọc trên bảng pv
                $query .= " AND pv.price <= :max_price";
                $params[':max_price'] = $maxPrice;
            }
        }

        $query .= " GROUP BY p.id ORDER BY p.created_at DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_CLASS, 'Product');
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
    public function findAllProductActive()
    {
        // Câu lệnh JOIN phức tạp để lấy thêm tên danh mục
        // Chúng ta dùng LEFT JOIN để sản phẩm nào chưa có danh mục vẫn hiển thị
        // GROUP_CONCAT để gộp nhiều tên danh mục vào một chuỗi
        $query = "SELECT p.*, GROUP_CONCAT(c.name SEPARATOR ', ') as category_names
              FROM products p
              LEFT JOIN product_category_map pcm ON p.id = pcm.product_id
              LEFT JOIN categories c ON pcm.category_id = c.id
              WHERE p.is_active = 1
              GROUP BY p.id
              ORDER BY p.created_at DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_CLASS, 'Product');
    }
    public function findVariantById($variantId)
    {
        $query = "SELECT
                pv.id, pv.price, pv.stock,
                p.name AS product_name, p.image_url,
                GROUP_CONCAT(av.value SEPARATOR ', ') as attributes
              FROM product_variants pv
              JOIN products p ON pv.product_id = p.id
              LEFT JOIN variant_values vv ON pv.id = vv.variant_id
              LEFT JOIN attribute_values av ON vv.attribute_value_id = av.id
              WHERE pv.id = :id
              GROUP BY pv.id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $variantId);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'ProductVariant');
        return $stmt->fetch();
    }
}
