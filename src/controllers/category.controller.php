<?php
require_once __DIR__ . '/../services/category.service.php';

class CategoryController
{
    private $categoryService;

    public function __construct($conn)
    {
        $this->categoryService = new CategoryService($conn);
    }

    public function index()
    {
        $categories = $this->categoryService->getAllCategories();
        require_once __DIR__ . '/../views/admin/categories/index.php';
    }

    public function create()
    {
        require_once __DIR__ . '/../views/admin/categories/create.php';
    }

    public function store()
    {
        $this->categoryService->createCategory($_POST, $_FILES['image']);
        header('Location: /shoe-shop/public/admin/categories');
        exit();
    }

    public function edit($id)
    {
        $category = $this->categoryService->getCategoryById($id);
        if (!$category) {
            http_response_code(404);
            echo "404 Not Found - Danh mục không tồn tại.";
            exit();
        }
        require_once __DIR__ . '/../views/admin/categories/edit.php';
    }

    public function update($id)
    {
        $this->categoryService->updateCategory($id, $_POST, $_FILES);
        header('Location: /shoe-shop/public/admin/categories');
        exit();
    }
}
