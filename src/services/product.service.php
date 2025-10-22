<?php
require_once '../src/models/repositories/product.repository.php';
require_once '../src/models/repositories/category.repository.php';
require_once '../src/services/review.service.php';
class ProductService
{
    private $productRepository;
    private $categoryRepository;
    private $reviewService;
    public function __construct($conn)
    {
        $this->productRepository = new ProductRepository($conn);
        $this->categoryRepository = new CategoryRepository($conn);
        $this->reviewService = new ReviewService($conn);
    }
    public function getAllCategories()
    {
        return $this->categoryRepository->findAll(true);
    }
    public function createProduct($data)
    {
        $imageUrl = null;
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $imageUrl = $this->handleImageUpload($_FILES['image']);
        }

        // Lấy thông tin sản phẩm chính
        $product = new Product();
        $product->name = $data['name'];
        $product->slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $data['name'])));
        $product->description = $data['description'];
        $product->image_url = $imageUrl;
        $product->is_active = isset($data['is_active']) ? 1 : 0;

        // Lấy thông tin danh mục và các biến thể
        $categoryIDs = $data['categories'] ?? [];
        $variantsData = $data['variants'] ?? [];

        // Kiểm tra xem có ít nhất một biến thể không
        if (empty($variantsData)) {
            return false;
        }

        return $this->productRepository->save($product, $categoryIDs, $variantsData);
    }
    function getAllProducts($page)
    {
        $page = is_numeric($page) ? (int)$page : 1;
        $productsPerPage = 8;
        $offset = ($page - 1) * $productsPerPage;

        $products = $this->productRepository->findAll($productsPerPage, $offset);
        $totalProducts = $this->productRepository->countAll();
        $totalPages = ceil($totalProducts / $productsPerPage);

        return [
            'products' => $products,
            'totalPages' => $totalPages,
            'currentPage' => $page,
            'totalProducts' => $totalProducts
        ];
    }
    function getProductById($id)
    {
        return $this->productRepository->findById($id);
    }
    function getCategoryByProductId($productId)
    {
        return $this->productRepository->findCategoryByProductId($productId);
    }
    public function getAttributeValuesByName($name)
    {
        return $this->productRepository->findAttributeValuesByName($name);
    }
    public function getProductWithVariants($productId)
    {
        $product = $this->productRepository->findById($productId);
        if (!$product) {
            return null;
        }

        $variantsRaw = $this->productRepository->findVariantsByProductId($productId);
        $reviews = $this->reviewService->getApprovedReviewsForProduct($productId);

        $variants = []; // mảng các biến thể
        $options = []; // mảng để lưu các lựa chọn Size, Màu
        $relatedProducts = []; // mảng sản phẩm liên quan
        $categoryIDs = $this->productRepository->findCategoryByProductId($productId);

        // kiểm tra mảng không rỗng
        if (!empty($categoryIDs)) {
            $relatedProducts = $this->productRepository->findRelatedProducts($categoryIDs, $productId, 4);
        }
        foreach ($variantsRaw as $row) {
            $variantId = $row->id;

            // gán thông tin chung cho biến thể
            if (!isset($variants[$variantId])) {
                $variants[$variantId] = [
                    'id' => $variantId,
                    'price' => $row->price,
                    'stock' => $row->stock,
                    'attributes' => [],

                ];
            }

            // thêm thuộc tính (Size, Màu) vào biến thể
            $variants[$variantId]['attributes'][$row->attribute_name] = $row->attribute_value;

            // thêm giá trị thuộc tính vào danh sách các lựa chọn
            if (!isset($options[$row->attribute_name])) {
                $options[$row->attribute_name] = [];
            }
            if (!in_array($row->attribute_value, $options[$row->attribute_name])) {
                $options[$row->attribute_name][] = $row->attribute_value;
            }
        }

        return (object)[
            'product' => $product,
            'variants' => array_values($variants),
            'options' => $options,
            'reviews' => $reviews,
            'relatedProducts' => $relatedProducts
        ];
    }
    public function updateProduct($id, $data)
    {
        $product = $this->productRepository->findById($id);
        if (!$product) {
            return false;
        }

        $imageUrl = $product->image_url;
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $newImageUrl = $this->handleImageUpload($_FILES['image']);
            if ($newImageUrl) {
                // Xóa ảnh cũ nếu có
                if ($product->image_url && file_exists(__DIR__ . '/../../public' . $product->image_url)) {
                    unlink(__DIR__ . '/../../public' . $product->image_url);
                }
                $imageUrl = $newImageUrl;
            }
        }

        // Cập nhật
        $product->name = $data['name'];
        $product->slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $data['name'])));
        $product->description = $data['description'];
        $product->image_url = $imageUrl;
        $product->is_active = isset($data['is_active']) && $data['is_active'] == '1' ? 1 : 0;

        $categoryIDs = $data['categories'] ?? [];
        $variantsData = $data['variants'] ?? [];

        return $this->productRepository->update($product, $categoryIDs, $variantsData);
    }

    function handleImageUpload($file)
    {
        // Kiểm tra xem có lỗi upload không
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return null;
        }

        // nơi lưu ảnh
        $targetDir = __DIR__ . '/../../public/images/products/';

        // tạo thư mục nếu nó chưa tồn tại
        if (!file_exists($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        // lấy đuôi file
        $fileExtension = pathinfo($file['name'], PATHINFO_EXTENSION);

        // tên file duy nhất
        $fileName = uniqid() . '-' . time() . '.' . $fileExtension;
        $targetFile = $targetDir . $fileName;

        // Di chuyển file từ thư mục tạm sang thư mục đích
        if (move_uploaded_file($file['tmp_name'], $targetFile)) {
            // Trả về đường dẫn đầy đủ và chính xác từ thư mục gốc của web
            return '/shoe-shop/public/images/products/' . $fileName;
        }

        return null;
    }
    function deleteProduct($id)
    {
        return $this->productRepository->delete($id);
    }
    public function getAllProductsActive($filters = [])
    {
        $page = isset($filters['page']) && is_numeric($filters['page']) ? (int)$filters['page'] : 1;
        $productsPerPage = isset($filters['limit']) ? (int)$filters['limit'] : 9;
        $offset = ($page - 1) * $productsPerPage;

        // Lấy danh sách sản phẩm cho trang hiện tại
        $products = $this->productRepository->findWithFilters($filters, $productsPerPage, $offset);

        // Đếm tổng số sản phẩm khớp với bộ lọc
        $totalProducts = $this->productRepository->countWithFilters($filters);

        // Tính tổng số trang
        $totalPages = ceil($totalProducts / $productsPerPage);

        return [
            'products' => $products,
            'totalPages' => $totalPages,
            'currentPage' => $page
        ];
    }
}
