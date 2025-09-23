<?php
require_once '../src/services/product.service.php';
class ProductController
{
    private $productService;
    public function __construct($conn)
    {
        $this->productService = new ProductService($conn);
    }

    public function create($errorMessage = '', $oldInput = [])
    {
        // Lấy danh sách categories để hiển thị trong form
        $categories = $this->productService->getAllCategories();

        // Nạp view, bây giờ các biến $errorMessage và $oldInput sẽ có sẵn cho view sử dụng
        require_once __DIR__ . '/../views/admin/products/create.php';
    }

    public function store($errorMessage = '', $oldInput = [])
    {
        // Validation có thể được thêm ở đây sau
        $result = $this->productService->createProduct($_POST);

        if ($result) {
            // Chuyển hướng về trang danh sách nếu thành công
            header('Location: /shoe-shop/public/admin/products');
            exit();
        } else {
            // Xử lý lỗi
            $categories = $this->productService->getAllCategories();
            $errorMessage = $errorMessage ?? "Có lỗi xảy ra, vui lòng thử lại.";
            $oldInput = $oldInput ?? $_POST;
            require_once __DIR__ . '/../views/admin/products/create.php';
        }
    }
    function getAllProducts()
    {
        $products = $this->productService->getAllProducts();
        require_once __DIR__ . '/../views/admin/products/index.php';
    }
    public function getEditPage($id)
    {

        $product = $this->productService->getProductById($id);

        $categories = $this->productService->getAllCategories();
        $categoryIDs = $this->productService->getCategoryByProductId($id);
        require_once __DIR__ . '/../views/admin/products/edit.php';
    }
    public function update($id, $data)
    {
        $result = $this->productService->updateProduct($id, $data);

        if ($result) {
            header('Location: /shoe-shop/public/admin/products');
            exit();
        } else {
            // Cập nhật thất bại, phải tải lại toàn bộ dữ liệu cho trang edit
            $errorMessage = "Có lỗi xảy ra, vui lòng thử lại.";

            // Lấy lại dữ liệu giống hệt như trong hàm getEditPage()
            $product = $this->productService->getProductById($id);
            $categories = $this->productService->getAllCategories();
            $categoryIDs = $this->productService->getCategoryByProductId($id);

            // Nạp lại view với đầy đủ dữ liệu
            require_once __DIR__ . '/../views/admin/products/edit.php';
        }
    }
}
