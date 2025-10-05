<?php
require_once '../src/services/product.service.php';
require_once '../src/services/review.service.php';
class ProductController
{
    private $productService;
    private $reviewService;
    public function __construct($conn)
    {
        $this->productService = new ProductService($conn);
        $this->reviewService = new ReviewService($conn);
    }
    //method admin
    public function create($errorMessage = '', $oldInput = [])
    {
        // Lấy danh sách categories để hiển thị trong form
        $categories = $this->productService->getAllCategories();
        // Lấy danh sách các giá trị cho Size và Màu sắc
        $sizes = $this->productService->getAttributeValuesByName('Size');
        $colors = $this->productService->getAttributeValuesByName('Màu sắc');
        // Nạp view, bây giờ các biến $errorMessage và $oldInput sẽ có sẵn cho view sử dụng
        require_once __DIR__ . '/../views/admin/products/create.php';
    }

    public function store($errorMessage = '', $oldInput = [])
    {
        $result = $this->productService->createProduct($_POST);

        if ($result) {
            header('Location: /shoe-shop/public/admin/products');
            exit();
        } else {
            // Xử lý lỗi
            $categories = $this->productService->getAllCategories();

            // --- BẮT ĐẦU SỬA LỖI ---
            // Lấy lại cả sizes và colors, giống hệt như trong hàm create()
            $sizes = $this->productService->getAttributeValuesByName('Size');
            $colors = $this->productService->getAttributeValuesByName('Màu sắc');
            // --- KẾT THÚC SỬA LỖI ---

            $errorMessage = $errorMessage ?: "Có lỗi xảy ra khi lưu sản phẩm, vui lòng thử lại.";
            $oldInput = $oldInput ?: $_POST;

            // Nạp lại view với đầy đủ dữ liệu
            require_once __DIR__ . '/../views/admin/products/create.php';
        }
    }
    function getAllProducts()
    {
        $products = $this->productService->getAllProducts();
        require_once __DIR__ . '/../views/admin/products/index.php';
    }
    public function getEditPage($id, $errorMessage = '', $oldInput = [])
    {
        // Lấy thông tin sản phẩm và các biến thể của nó
        $data = $this->productService->getProductWithVariants($id);
        if (!$data) {
            http_response_code(404);
            echo "Sản phẩm không tồn tại";
            return;
        }

        // Gán dữ liệu vào các biến để view dễ sử dụng
        $product = $data->product;
        $variants = $data->variants;

        // Lấy tất cả các lựa chọn có thể có để hiển thị checkbox
        $categories = $this->productService->getAllCategories();
        $sizes = $this->productService->getAttributeValuesByName('Size');
        $colors = $this->productService->getAttributeValuesByName('Màu sắc');

        // Lấy các danh mục mà sản phẩm này đang thuộc về
        $categoryIDs = $this->productService->getCategoryByProductId($id);

        // Nạp view
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
            if (!$product) {
                http_response_code(404);
                echo "Sản phẩm không tồn tại";
                return;
            }
            $categories = $this->productService->getAllCategories();
            $categoryIDs = $this->productService->getCategoryByProductId($id);

            // Nạp lại view với đầy đủ dữ liệu
            require_once __DIR__ . '/../views/admin/products/edit.php';
        }
    }
    public function delete($id)
    {
        $result = $this->productService->deleteProduct($id);
        if ($result) {
            header('Location: /shoe-shop/public/admin/products');
            exit();
        }
    }
    //end
    //method user
    public function getAllProductsActive()
    {
        $products = $this->productService->getAllProductsActive([]);
        $categories = $this->productService->getAllCategories();
        require_once __DIR__ . '/../views/home/products/index.php';
    }
    public function showProductDetail($id)
    {
        $data = $this->productService->getProductWithVariants($id);

        if (!$data) {
            http_response_code(404);
            echo "404 - Sản phẩm không tồn tại";
            exit();
        }

        // Gán dữ liệu vào các biến để view dễ sử dụng
        $product = $data->product;
        $variants = $data->variants;
        $options = $data->options;
        $reviews = $data->reviews;
        $relatedProducts = $data->relatedProducts;
        //
        $eligibility = (object)['canReview' => false, 'orderId' => null];
        if (isset($_SESSION['user']['id'])) {
            $userId = $_SESSION['user']['id'];
            $eligibility = $this->reviewService->checkReviewEligibility($userId, $id);
        }

        // Truyền thẳng kết quả từ service vào view
        $canReview = $eligibility->canReview;
        $orderIdForReview = $eligibility->orderId;
        // Nạp view và truyền dữ liệu sang
        // (Đổi tên file view nếu cần, ở đây tôi dùng lại file cũ)
        require_once __DIR__ . '/../views/home/products/detailProduct.php';
    }
    public function addReview($productId)
    {
        // 1. Kiểm tra đăng nhập
        if (!isset($_SESSION['user']['id'])) {
            // Controller vẫn chịu trách nhiệm điều hướng
            header('Location: /shoe-shop/public/login');
            exit();
        }

        // 2. Lấy dữ liệu từ request
        $userId = $_SESSION['user']['id'];
        $orderId = $_POST['order_id'] ?? null;
        $rating = $_POST['rating'] ?? 0;
        $comment = $_POST['comment'] ?? '';

        // 3. Giao toàn bộ việc xử lý cho Service
        $result = $this->reviewService->createReview($productId, $userId, $orderId, $rating, $comment);

        // 4. Dựa vào kết quả từ Service để điều hướng
        // Ví dụ: Lưu thông báo vào session để hiển thị cho người dùng
        $_SESSION['flash_message'] = $result['message'];

        header('Location: /shoe-shop/public/product/' . $productId);
        exit();
    }
    public function showProductPage()
    {
        // Lấy các tham số từ URL (phương thức GET)
        $filters = [
            'search' => $_GET['search'] ?? '',
            'category' => $_GET['category'] ?? null,
            // --- THÊM DÒNG MỚI ---
            'price' => $_GET['price'] ?? null // Thêm tham số price
        ];

        // Gọi service với các bộ lọc (không cần thay đổi gì thêm ở đây)
        $products = $this->productService->getAllProductsActive($filters);
        $categories = $this->productService->getAllCategories();

        // Nạp view
        require_once __DIR__ . '/../views/home/products/filterPage.php';
    }
    //end
}
