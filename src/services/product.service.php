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
        $categoryIDs = $data['categories'] ?? [];
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $data['name'])));
        $product = new Product();
        $product->name = $data['name'];
        $product->slug = $slug;
        $product->short_desc = $data['short_desc'];
        $product->base_price = $data['base_price'];
        $product->cost_price = $data['cost_price'];
        $product->is_active = $data['is_active'];
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
    function updateProduct($data)
    {
        $categoryIDs = $data['categories'] ?? [];
        $product = new Product();
        $product->id = $data['id'];
        $product->name = $data['name'];
        $product->sku = $data['sku'];
        $product->slug = $data['slug'];
        $product->short_desc = $data['short_desc'];
        $product->description = $data['description'];
        $product->base_price = $data['base_price'];
        $product->cost_price = $data['cost_price'];
        $product->is_active = $data['is_active'];
        $product->updated_at = date('Y-m-d H:i:s');
        return $this->productRepository->update($product, $categoryIDs);
    }
}
