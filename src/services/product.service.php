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
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $imageUrl = $this->handleImageUpload($_FILES['image']);
        }
        $categoryIDs = $data['categories'] ?? [];
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $data['name'])));
        $product = new Product();
        $product->name = $data['name'];
        $product->slug = $slug;
        $product->description = $data['description'];
        $product->price = $data['price'];
        $product->image_url = $imageUrl;
        $product->stock = $data['stock'];
        $product->is_active = isset($data['is_active']) ? 1 : 0;
        $product->created_at = date('Y-m-d H:i:s');
        $product->updated_at = date('Y-m-d H:i:s');
        return $this->productRepository->save($product, $categoryIDs);
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
    function updateProduct($id, $data)
    {
        // 1. Lấy ra sản phẩm hiện tại từ database
        $product = $this->productRepository->findById($id);
        if (!$product) {
            return false; // Trả về false nếu không tìm thấy sản phẩm
        }
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $imageUrl = $this->handleImageUpload($_FILES['image']);
        }
        // 2. Cập nhật các thuộc tính của đối tượng product với dữ liệu mới từ form
        $product->name = $data['name'];
        $product->description = $data['description'];
        $product->price = $data['price'];
        $product->image_url = $imageUrl;
        $product->stock = $data['stock'];

        // 3. Xử lý logic cho checkbox 'is_active'
        // Nếu checkbox được tick, $data['is_active'] sẽ là '1'. Nếu không, nó sẽ không tồn tại.
        $product->is_active = isset($data['is_active']) ? 1 : 0;

        // 4. Tạo slug mới dựa trên tên mới
        $product->slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $data['name'])));

        $product->updated_at = date('Y-m-d H:i:s');

        // 5. Lấy danh sách category IDs mới
        $categoryIDs = $data['categories'] ?? [];

        // 6. Ra lệnh cho Repository cập nhật sản phẩm với thông tin mới
        return $this->productRepository->update($product, $categoryIDs);
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
}
