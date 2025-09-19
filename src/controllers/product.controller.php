<?php
require_once '../src/services/product.service.php';
class ProductController
{
    private $productService;
    public function __construct($conn)
    {
        $this->productService = new ProductService($conn);
    }

    public function create()
    {
        // Lấy danh sách categories để hiển thị trong form
        $categories = $this->productService->getAllCategories();
        require_once __DIR__ . '/../views/admin/products/create.php';
    }

    public function store()
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
            $errorMessage = "Có lỗi xảy ra, vui lòng thử lại.";
            require_once __DIR__ . '/../views/admin/products/create.php';
        }
    }
    function getAllProducts()
    {
        $products = $this->productService->getAllProducts();
        require_once __DIR__ . '/../views/admin/products/index.php';
    }
    public function edit($id)
    {

        $product = $this->productService->getProductById($id);
        $categories = $this->productService->getAllCategories();
        $categoryIDs = $this->productService->getCategoryByProductId($id);
        require_once __DIR__ . '/../views/admin/products/edit.php';
    }
}
