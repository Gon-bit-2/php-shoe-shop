<?php
require_once __DIR__ . '/../models/repositories/category.repository.php';
require_once __DIR__ . '/../models/category.php';

class CategoryService
{
    private $categoryRepository;

    public function __construct($conn)
    {
        $this->categoryRepository = new CategoryRepository($conn);
    }

    public function getAllCategories()
    {
        return $this->categoryRepository->findAll();
    }

    public function getCategoryById($id)
    {
        return $this->categoryRepository->findById($id);
    }

    public function createCategory($data, $file)
    {
        $imageUrl = $this->handleImageUpload($file);

        $category = new Category();
        $category->name = trim($data['name']);
        $category->slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $data['name'])));
        $category->image_url = $imageUrl;
        $category->is_active = isset($data['is_active']) ? 1 : 0;

        return $this->categoryRepository->save($category);
    }

    public function updateCategory($id, $data, $file)
    {
        $category = $this->getCategoryById($id);
        if (!$category) {
            return false;
        }

        $imageUrl = $category->image_url; // Giữ ảnh cũ làm mặc định
        if (isset($file['image']) && $file['image']['error'] === UPLOAD_ERR_OK) {
            $newImageUrl = $this->handleImageUpload($file['image']);
            if ($newImageUrl) {
                // Xóa ảnh cũ nếu có và upload ảnh mới thành công
                if ($category->image_url && file_exists(__DIR__ . '/../../public' . $category->image_url)) {
                    unlink(__DIR__ . '/../../public' . $category->image_url);
                }
                $imageUrl = $newImageUrl;
            }
        }

        $category->name = trim($data['name']);
        $category->slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $data['name'])));
        $category->image_url = $imageUrl;
        $category->is_active = isset($data['is_active']) ? 1 : 0;

        return $this->categoryRepository->update($category);
    }

    private function handleImageUpload($file)
    {
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return null;
        }
        $targetDir = __DIR__ . '/../../public/images/categories/';
        if (!file_exists($targetDir)) {
            mkdir($targetDir, 0777, true);
        }
        $fileExtension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $fileName = uniqid() . '-' . time() . '.' . $fileExtension;
        $targetFile = $targetDir . $fileName;

        if (move_uploaded_file($file['tmp_name'], $targetFile)) {
            return '/shoe-shop/public/images/categories/' . $fileName;
        }
        return null;
    }
}
