<?php
require_once '../src/models/repositories/product.repository.php';
require_once '../src/models/repositories/category.repository.php';
class ProductService
{
    private $productRepository;
    private $categoryRepository;
    public function __construct($conn)
    {
        $this->productRepository = new ProductRepository($conn);
        $this->categoryRepository = new CategoryRepository($conn);
    }
    public function getAllCategories()
    {
        return $this->categoryRepository->findAll();
    }
    public function createProduct($data)
    {
        // Xử lý ảnh upload (giữ nguyên)
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
            // Trả về lỗi nếu không có biến thể nào được thêm
            // Bạn có thể xử lý lỗi này ở Controller
            return false;
        }

        // Gọi đến Repository để lưu tất cả dữ liệu
        return $this->productRepository->save($product, $categoryIDs, $variantsData);
    }
    function getAllProducts()
    {
        return $this->productRepository->findAll();
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
        // Lấy thông tin sản phẩm chính
        $product = $this->productRepository->findById($productId);
        if (!$product) {
            return null;
        }

        // Lấy dữ liệu thô của các biến thể
        $variantsRaw = $this->productRepository->findVariantsByProductId($productId);

        // Xử lý dữ liệu thô thành một cấu trúc có tổ chức
        $variants = [];
        $options = []; // Mảng để lưu các lựa chọn Size, Màu có sẵn
        foreach ($variantsRaw as $row) {
            $variantId = $row->id;

            // Gán thông tin chung cho biến thể
            if (!isset($variants[$variantId])) {
                $variants[$variantId] = [
                    'id' => $variantId,
                    'price' => $row->price,
                    'stock' => $row->stock,
                    'attributes' => []
                ];
            }

            // Thêm thuộc tính (Size, Màu) vào biến thể
            $variants[$variantId]['attributes'][$row->attribute_name] = $row->attribute_value;

            // Thêm giá trị thuộc tính vào danh sách các lựa chọn
            if (!isset($options[$row->attribute_name])) {
                $options[$row->attribute_name] = [];
            }
            if (!in_array($row->attribute_value, $options[$row->attribute_name])) {
                $options[$row->attribute_name][] = $row->attribute_value;
            }
        }

        return (object)[
            'product' => $product,
            'variants' => array_values($variants), // Chuyển về mảng tuần tự
            'options' => $options // Ví dụ: ['Size' => ['40', '41'], 'Màu sắc' => ['Đen', 'Trắng']]
        ];
    }
    public function updateProduct($id, $data)
    {
        $product = $this->productRepository->findById($id);
        if (!$product) {
            return false;
        }

        // Xử lý ảnh
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

        // Cập nhật thông tin sản phẩm chính
        $product->name = $data['name'];
        $product->slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $data['name'])));
        $product->description = $data['description'];
        $product->image_url = $imageUrl;
        $product->is_active = isset($data['is_active']) ? 1 : 0;

        // Lấy thông tin danh mục và biến thể từ form
        $categoryIDs = $data['categories'] ?? [];
        $variantsData = $data['variants'] ?? [];

        // Gọi đến Repository để thực hiện cập nhật phức tạp
        return $this->productRepository->update($product, $categoryIDs, $variantsData);
    }

    function handleImageUpload($file)
    {
        // Kiểm tra xem có lỗi upload không
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return null; // Có lỗi, không xử lý
        }

        // Nơi chúng ta sẽ lưu ảnh
        $targetDir = __DIR__ . '/../../public/images/products/';

        // Tạo thư mục nếu nó chưa tồn tại
        if (!file_exists($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        // Lấy phần mở rộng của file (jpg, png...)
        $fileExtension = pathinfo($file['name'], PATHINFO_EXTENSION);

        // Tạo một tên file duy nhất để tránh trùng lặp
        $fileName = uniqid() . '-' . time() . '.' . $fileExtension;
        $targetFile = $targetDir . $fileName;

        // Di chuyển file từ thư mục tạm sang thư mục đích
        if (move_uploaded_file($file['tmp_name'], $targetFile)) {
            // Trả về đường dẫn công khai để lưu vào database
            return '/images/products/' . $fileName;
        }

        return null; // Di chuyển thất bại
    }
    function deleteProduct($id)
    {
        return $this->productRepository->delete($id);
    }
    public function getAllProductsActive($filters = [])
    {
        // Chỉ đơn giản là truyền các bộ lọc xuống cho Repository
        return $this->productRepository->findWithFilters($filters);
    }
}
