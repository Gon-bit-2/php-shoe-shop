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

    /**
     * Lưu sản phẩm
     * @param Product $product
     * @param int[] $categoryIDs
     * @param array $variantsData
     * @return bool
     */
    public function save(Product $product, array $categoryIDs, array $variantsData)
    {
        try {
            $this->conn->beginTransaction();

            //lưu sản phẩm chính
            $query = "INSERT INTO products (name, slug, description, image_url, is_active)
              VALUES (:name, :slug, :description, :image_url, :is_active)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':name', $product->name);
            $stmt->bindParam(':slug', $product->slug);
            $stmt->bindParam(':description', $product->description);
            $stmt->bindParam(':image_url', $product->image_url);
            $stmt->bindParam(':is_active', $product->is_active);
            $stmt->execute();
            $productId = $this->conn->lastInsertId();

            //xử lý danh mục
            if (!empty($categoryIDs)) {
                //chuẩn bị câu lệnh một lần bên ngoài vòng lặp
                $mapQuery = "INSERT INTO product_category_map (product_id, category_id) VALUES (?, ?)";
                $mapStmt = $this->conn->prepare($mapQuery);
                foreach ($categoryIDs as $categoryID) {
                    //thực thi nhiều lần với dữ liệu khác nhau
                    $mapStmt->execute([$productId, $categoryID]);
                }
            }


            $variantQuery = "INSERT INTO product_variants (product_id, price, stock) VALUES (?, ?, ?)";
            $variantStmt = $this->conn->prepare($variantQuery);

            $vvQuery = "INSERT INTO variant_values (variant_id, attribute_value_id) VALUES (?, ?)";
            $vvStmt = $this->conn->prepare($vvQuery);

            foreach ($variantsData as $variant) {
                //tạo biến thể trong `product_variants`
                $variantStmt->execute([
                    $productId,
                    $variant['price'],
                    $variant['stock']
                ]);
                $variantId = $this->conn->lastInsertId();

                //lấy ID Size và Màu
                $sizeValueId = $this->findOrCreateAttributeValue(1, $variant['size']);
                $colorValueId = $this->findOrCreateAttributeValue(2, $variant['color']);

                //tạo liên kết trong `variant_values`
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
    /**
     * Đếm số lượng sản phẩm
     * @return int
     */
    public function countAll()
    {
        $query = "SELECT COUNT(id) FROM products";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return (int) $stmt->fetchColumn();
    }
    /**
     * Tìm tất cả sản phẩm
     * @param int $limit
     * @param int $offset
     * @return Product[]
     */
    function findAll($limit, $offset)
    {
        $query = "SELECT * FROM products ORDER BY created_at DESC LIMIT :limit OFFSET :offset";
        $stmt = $this->conn->prepare($query);
        // Dùng bindValue để PDO xử lý đúng kiểu dữ liệu INT
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_CLASS, 'Product');
    }
    /**
     * Tìm giá trị thuộc tính đã tồn tại hoặc tạo mới
     * @param int $attributeId
     * @param string $value
     * @return int
     */
    public function findOrCreateAttributeValue($attributeId, $value)
    {
        //find thuộc tính tồn tại
        $query = "SELECT id FROM attribute_values WHERE attribute_id = :attribute_id AND `value` = :value";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([
            ':attribute_id' => $attributeId,
            ':value' => $value
        ]);
        $result = $stmt->fetchColumn();

        if ($result) {
            return $result;
        } else {
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

    /**
     * Tìm tất cả giá trị thuộc tính theo tên
     * @param string $attributeName
     * @return AttributeValue[]
     */
    public function findAttributeValuesByName($attributeName)
    {

        $query = "SELECT av.id, av.value
              FROM attribute_values av
              JOIN attributes a ON av.attribute_id = a.id
              WHERE a.name = :attribute_name";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":attribute_name", $attributeName);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_CLASS, 'AttributeValue');
    }

    /**
     * Tìm sản phẩm theo ID
     * @param int $id
     * @return Product|null
     */
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
    /**
     * Tìm danh mục theo ID sản phẩm
     * @param int $productId
     * @return int[]
     */
    function findCategoryByProductId($productId)
    {
        $query = "SELECT category_id FROM product_category_map WHERE product_id = :productId";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":productId", $productId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
    /**
     * Tìm các biến thể theo ID sản phẩm
     * @param int $productId
     * @return ProductVariant[]
     */
    public function findVariantsByProductId($productId)
    {

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
    /**
     * Cập nhật sản phẩm
     * @param Product $product
     * @param int[] $categoryIDs
     * @param array $variantsData
     * @return bool
     */
    public function update(Product $product, array $categoryIDs, array $variantsData)
    {
        try {
            $this->conn->beginTransaction();

            // Cập nhật thông tin sản phẩm chính
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

            //Cập nhật danh mục
            $this->conn->prepare("DELETE FROM product_category_map WHERE product_id = ?")->execute([$product->id]); // xóa danh mục cũ
            if (!empty($categoryIDs)) {
                $mapQuery = "INSERT INTO product_category_map (product_id, category_id) VALUES (?, ?)"; // thêm danh mục mới
                $mapStmt = $this->conn->prepare($mapQuery);
                foreach ($categoryIDs as $categoryID) {
                    $mapStmt->execute([$product->id, $categoryID]); // thêm danh mục mới
                }
            }

            $existingVariantIds = $this->getVariantIdsByProductId($product->id); // mảng để lưu id các biến thể cũ
            $submittedVariantIds = []; // mảng để lưu id các biến thể mới
            foreach ($variantsData as $variant) {
                $variantId = $variant['id'] ?? null;

                if ($variantId) {
                    //biến thể đã có -> UPDATE
                    $updateVariantQuery = "UPDATE product_variants SET price = ?, stock = ? WHERE id = ?";
                    $this->conn->prepare($updateVariantQuery)->execute([$variant['price'], $variant['stock'], $variantId]);
                    $submittedVariantIds[] = $variantId;
                } else {
                    //biến thể mới -> INSERT
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

            //tìm và xóa các biến thể
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


    /**
     * Tìm ID các biến thể theo ID sản phẩm
     * @param int $productId
     * @return int[]
     */
    public function getVariantIdsByProductId($productId)
    {
        $stmt = $this->conn->prepare("SELECT id FROM product_variants WHERE product_id = ?");
        $stmt->execute([$productId]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN); // Lấy về mảng các ID
    }
    /**
     * Đếm số lượng sản phẩm với các điều kiện lọc
     * @param array $filters
     * @return int
     */
    public function countWithFilters($filters = [])
    {
        $query = "SELECT COUNT(DISTINCT p.id)
              FROM products p
              LEFT JOIN product_category_map pcm ON p.id = pcm.product_id
              LEFT JOIN product_variants pv ON p.id = pv.product_id
              WHERE p.is_active = 1";
        $params = [];

        if (!empty($filters['search'])) {
            $query .= " AND p.name LIKE :search";
            $params[':search'] = '%' . $filters['search'] . '%';
        }
        if (!empty($filters['category'])) {
            $query .= " AND pcm.category_id = :category_id";
            $params[':category_id'] = $filters['category'];
        }
        if (!empty($filters['price'])) {
            $priceParts = explode('-', $filters['price']);
            if (is_numeric($priceParts[0] ?? null)) {
                $query .= " AND pv.price >= :min_price";
                $params[':min_price'] = $priceParts[0];
            }
            if (is_numeric($priceParts[1] ?? null)) {
                $query .= " AND pv.price <= :max_price";
                $params[':max_price'] = $priceParts[1];
            }
        }

        $stmt = $this->conn->prepare($query);
        $stmt->execute($params);
        return (int) $stmt->fetchColumn();
    }
    /**
     * Tìm kiếm và lọc sản phẩm
     * @param array $filters
     * @param int $limit
     * @param int $offset
     * @return Product[]
     */
    public function findWithFilters($filters = [], $limit, $offset)
    {
        $searchTerm = $filters['search'] ?? '';
        $categoryId = $filters['category'] ?? null;
        $priceRange = $filters['price'] ?? null;
        $sort = $filters['sort'] ?? 'newest';

        //  chọn p.* và 'price'
        $query = "SELECT p.*, MIN(pv.price) as price
              FROM products p
              LEFT JOIN product_category_map pcm ON p.id = pcm.product_id
              LEFT JOIN categories c ON pcm.category_id = c.id
              --  JOIN với product_variants để lấy giá
              LEFT JOIN product_variants pv ON p.id = pv.product_id
              WHERE p.is_active = 1";

        $params = [];

        if (!empty($searchTerm)) {
            $query .= " AND p.name LIKE :search";
            $params[':search'] = '%' . $searchTerm . '%';
        }

        if (!empty($categoryId)) {
            $query .= " AND pcm.category_id = :category_id";
            $params[':category_id'] = $categoryId;
        }

        // lọc giá trên giá của các biến thể
        if (!empty($priceRange)) {
            $priceParts = explode('-', $priceRange);
            $minPrice = $priceParts[0] ?? 0;
            $maxPrice = $priceParts[1] ?? null;

            if (is_numeric($minPrice)) {
                $query .= " AND pv.price >= :min_price";
                $params[':min_price'] = $minPrice;
            }

            if (is_numeric($maxPrice)) {
                $query .= " AND pv.price <= :max_price";
                $params[':max_price'] = $maxPrice;
            }
        }
        $query .= " GROUP BY p.id";

        switch ($sort) {
            case 'price_asc':
                $query .= " ORDER BY price ASC";
                break;
            case 'price_desc':
                $query .= " ORDER BY price DESC";
                break;
            default:
                $query .= " ORDER BY p.created_at DESC";
                break;
        }
        $query .= " LIMIT :limit OFFSET :offset";

        $stmt = $this->conn->prepare($query);
        //bind các tham số
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }

        //bind limit và offset
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

        $stmt->execute();
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
    /**
     * Tìm tất cả sản phẩm active
     * @return Product[]
     */
    public function findAllProductActive()
    {
        //một sản phẩm có thể có n<=>n danh mục
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
    /**
     * Tìm biến thể theo ID
     * @param int $variantId
     * @return ProductVariant|null
     */
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
    /**
     * Tìm sản phẩm liên quan
     * @param int[] $categoryIDs
     * @param int $currentProductId
     * @param int $limit
     * @return Product[]
     */
    public function findRelatedProducts($categoryIDs, $currentProductId, $limit = 4)
    {
        // check $categoryIDs
        if (empty($categoryIDs) || !is_array($categoryIDs)) {
            return [];
        }

        $placeholders = implode(',', array_fill(0, count($categoryIDs), '?'));

        $query = "SELECT p.*, MIN(pv.price) as price
                  FROM products p
                  JOIN product_category_map pcm ON p.id = pcm.product_id
                  JOIN product_variants pv ON p.id = pv.product_id
                  WHERE pcm.category_id IN ($placeholders)
                    AND p.id != ?
                    AND p.is_active = 1
                  GROUP BY p.id
                  ORDER BY RAND()
                  LIMIT ?";

        $params = array_merge($categoryIDs, [$currentProductId, $limit]);

        $stmt = $this->conn->prepare($query);

        foreach ($params as $key => $value) {
            $stmt->bindValue($key + 1, $value, is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_CLASS, 'Product');
    }
}
