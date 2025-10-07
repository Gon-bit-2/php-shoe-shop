# 🛒 HỆ THỐNG QUẢN LÝ CỬA HÀNG GIÀY - SHOE SHOP

## 📋 TỔNG QUAN DỰ ÁN

Shoe Shop là một ứng dụng web thương mại điện tử (E-commerce) chuyên về bán giày, được xây dựng bằng PHP thuần (Native PHP) với kiến trúc MVC (Model-View-Controller) và Repository Pattern.

### Công nghệ sử dụng

- **Backend**: PHP 7.4+
- **Database**: MySQL/MariaDB với PDO
- **Frontend**: HTML, CSS (Tailwind CSS), JavaScript
- **Build Tool**: Tailwind CLI

---

## 🚀 CÀI ĐẶT VÀ KHỞI CHẠY

### Yêu cầu hệ thống

- PHP 7.4 trở lên
- MySQL 5.7+ hoặc MariaDB
- XAMPP/WAMP/LAMP hoặc server tương tự
- Node.js và npm (cho Tailwind CSS)

### Các bước cài đặt

#### 1. Clone dự án

```bash
git clone <repository-url>
cd shoe-shop
```

#### 2. Cấu hình Database

Mở file `src/models/repositories/database.php` và chỉnh sửa thông tin kết nối:

```php
$host = "localhost";
$port = 1133;           // Thay đổi theo cổng MySQL của bạn
$dbname = "shoe_shop_db";
$username = "root";      // Username MySQL
$password = "";          // Password MySQL
```

#### 3. Tạo Database

Tạo database trong MySQL:

```sql
CREATE DATABASE shoe_shop_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

#### 4. Chạy Migration (Tạo cấu trúc bảng)

Chạy các file migration theo thứ tự:

```bash
# Bước 1: Tạo bảng Users và Roles
php migrations/setup.php

# Bước 2: Tạo bảng Categories (nếu có migration)
# Lưu ý: Bảng categories phải được tạo trước products

# Bước 3: Tạo bảng Products
php migrations/20250923_products_table.php

# Bước 4: Tạo bảng Product Variants (Biến thể)
php migrations/20251003_add_product_variants.php

# Bước 5: Tạo bảng Orders
php migrations/20250930_create_orders_table.php

# Bước 6: Thêm payment method vào Orders
php migrations/20251002_add_payment_method_to_orders.php

# Bước 7: Tạo bảng Reviews
php migrations/20251002_create_reviews_table.php

# Bước 8: Tạo bảng Vouchers
php migrations/20251005_create_vouchers_table.php

# Bước 9: Thêm cột voucher vào Orders
php migrations/20251005_add_voucher_to_orders_table.php

# Bước 10: Thêm cột image vào Categories
php migrations/20251007_add_image_to_categories.php
```

#### 5. Cài đặt Tailwind CSS

```bash
npm install
```

#### 6. Build CSS (Development mode)

```bash
npm run twind:dev
```

Lệnh này sẽ watch và tự động compile CSS khi có thay đổi.

#### 7. Khởi động Server

- Đặt project trong thư mục `htdocs` (XAMPP) hoặc `www` (WAMP)
- Khởi động Apache và MySQL
- Truy cập: `http://localhost/shoe-shop/public/`

---

## 📂 CẤU TRÚC THÚ MỤC

```
shoe-shop/
│
├── migrations/              # Database migrations
│   ├── setup.php           # Tạo bảng users, roles
│   ├── 20250923_products_table.php
│   ├── 20251003_add_product_variants.php
│   └── ...
│
├── public/                 # Thư mục public (Document Root)
│   ├── index.php          # Entry point - Router chính
│   ├── css/               # CSS compiled
│   ├── js/                # JavaScript
│   └── images/            # Hình ảnh upload
│
├── src/                   # Source code chính
│   ├── config/
│   │   └── routes.php     # Cấu hình routes
│   │
│   ├── controllers/       # Controllers - Xử lý request
│   │   ├── auth.controller.php
│   │   ├── product.controller.php
│   │   ├── cart.controller.php
│   │   ├── order.controller.php
│   │   ├── voucher.controller.php
│   │   └── dashBoard.controller.php
│   │
│   ├── middleware/        # Middleware - Xử lý trước request
│   │   ├── auth.middleware.php
│   │   └── product.middleware.php
│   │
│   ├── models/           # Models - Entities
│   │   ├── repositories/  # Repository Pattern - Truy vấn DB
│   │   │   ├── database.php
│   │   │   ├── user.repository.php
│   │   │   ├── product.repository.php
│   │   │   ├── order.repository.php
│   │   │   ├── voucher.repository.php
│   │   │   └── review.repository.php
│   │   │
│   │   ├── user.php
│   │   ├── product.php
│   │   ├── order.php
│   │   └── ...
│   │
│   ├── services/         # Business Logic Layer
│   │   ├── auth.service.php
│   │   ├── product.service.php
│   │   ├── cart.service.php
│   │   ├── order.service.php
│   │   ├── voucher.service.php
│   │   └── review.service.php
│   │
│   └── views/           # Views - Giao diện
│       ├── admin/       # Giao diện Admin
│       ├── home/        # Giao diện User
│       ├── layout/      # Layout chung (header, footer)
│       ├── login.php
│       └── register.php
│
├── package.json          # NPM dependencies
├── tailwind.config.js    # Cấu hình Tailwind
└── README.md            # File này
```

---

## 🏗️ KIẾN TRÚC HỆ THỐNG

### 1. MVC Pattern (Model-View-Controller)

#### **Model**: Đại diện cho dữ liệu và logic nghiệp vụ

- **Entity Models** (`src/models/*.php`): Đại diện cho các đối tượng nghiệp vụ
- **Repository Pattern** (`src/models/repositories/`): Tách biệt logic truy vấn database

#### **View**: Giao diện người dùng

- Các file PHP template trong `src/views/`
- Sử dụng Tailwind CSS cho styling

#### **Controller**: Điều phối giữa Model và View

- Nhận request từ Router
- Gọi Service để xử lý logic
- Trả về View tương ứng

### 2. Routing System

File `public/index.php` hoạt động như một **Front Controller**:

```php
// Ví dụ routing
switch ($path) {
    case '/':
        $controller = new ProductController($conn);
        $controller->getAllProductsActive();
        break;

    case '/login':
        $controller = new AuthController($conn);
        if ($method == 'GET') {
            $controller->showLoginForm();
        } elseif ($method == 'POST') {
            $controller->login();
        }
        break;
}
```

**Luồng hoạt động**:

1. Request → `public/index.php`
2. Parse URL path và HTTP method
3. Apply Middleware (authentication, validation)
4. Route đến Controller tương ứng
5. Controller gọi Service xử lý logic
6. Service gọi Repository để truy vấn DB
7. Controller render View và trả về Response

### 3. Middleware System

**Middleware** là các tầng xử lý trung gian trước khi request đến Controller.

**AuthMiddleware** (`src/middleware/auth.middleware.php`):

- `requireAuth()`: Bắt buộc đăng nhập
- `requireAdmin()`: Bắt buộc quyền Admin
- `redirectIfAuthenticated()`: Chuyển hướng nếu đã đăng nhập
- `applyGlobalMiddleware()`: Áp dụng middleware toàn cục

**ProductMiddleware** (`src/middleware/product.middleware.php`):

- Validate dữ liệu sản phẩm trước khi lưu

### 4. Repository Pattern

**Repository** là lớp trung gian giữa Business Logic và Database, giúp:

- Tách biệt logic truy vấn
- Dễ dàng test và maintain
- Tái sử dụng code

**Ví dụ**: `ProductRepository`

```php
class ProductRepository {
    public function findAll() { /* SELECT * FROM products */ }
    public function findById($id) { /* SELECT * FROM products WHERE id = ? */ }
    public function create($data) { /* INSERT INTO products ... */ }
    public function update($id, $data) { /* UPDATE products ... */ }
    public function delete($id) { /* DELETE FROM products ... */ }
}
```

---

## 📚 CÁC TÍNH NĂNG CHÍNH

## ✨ TÍNH NĂNG 1: HỆ THỐNG XÁC THỰC VÀ PHÂN QUYỀN (AUTHENTICATION & AUTHORIZATION)

### Mô tả

Hệ thống quản lý người dùng với 2 vai trò: **Admin** và **User**. Bao gồm đăng ký, đăng nhập, đăng xuất và phân quyền truy cập.

### Cấu trúc Database

#### Bảng `roles`

| Cột  | Kiểu dữ liệu | Mô tả                    |
| ---- | ------------ | ------------------------ |
| id   | INT          | Primary Key              |
| name | VARCHAR(50)  | Tên vai trò (admin/user) |

#### Bảng `users`

| Cột        | Kiểu dữ liệu | Mô tả                   |
| ---------- | ------------ | ----------------------- |
| id         | INT          | Primary Key             |
| fullname   | VARCHAR(100) | Họ và tên               |
| email      | VARCHAR(100) | Email (unique)          |
| password   | VARCHAR(255) | Mật khẩu đã hash        |
| role_id    | INT          | Foreign Key → roles(id) |
| created_at | TIMESTAMP    | Thời gian tạo           |

### Cách hoạt động

#### 1. Đăng ký (Register)

**Luồng xử lý**:

```
User nhập form → POST /register → AuthMiddleware validate
→ AuthController::register() → AuthService::register()
→ UserRepository::create() → Lưu vào DB với password đã hash
```

**File liên quan**:

- Controller: `src/controllers/auth.controller.php`
- Service: `src/services/auth.service.php`
- Repository: `src/models/repositories/user.repository.php`
- View: `src/views/register.php`

**Validation**:

```php
// Middleware: auth.middleware.php
- Fullname: Tối thiểu 2 ký tự
- Email: Đúng định dạng email
- Password: Tối thiểu 6 ký tự
```

**Mã hóa mật khẩu**:

```php
// Service: auth.service.php
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);
```

> **Kiến thức**: `password_hash()` sử dụng thuật toán bcrypt, tự động tạo salt và hash mật khẩu an toàn.

#### 2. Đăng nhập (Login)

**Luồng xử lý**:

```
User nhập form → POST /login → AuthMiddleware validate
→ AuthController::login() → AuthService::login()
→ Kiểm tra email → Verify password → Lưu session → Redirect
```

**Xác thực mật khẩu**:

```php
// Service: auth.service.php
if (password_verify($password, $user->password)) {
    // Đăng nhập thành công
    $_SESSION['user'] = [
        'id' => $user->id,
        'fullname' => $user->fullname,
        'email' => $user->email,
        'role_id' => $user->role_id
    ];
}
```

> **Kiến thức**: `password_verify()` so sánh mật khẩu nhập vào với hash trong DB một cách an toàn.

#### 3. Phân quyền (Authorization)

**Middleware bảo vệ route**:

```php
// middleware/auth.middleware.php

// Bảo vệ route cần đăng nhập
public function requireAuth() {
    if (!isset($_SESSION['user'])) {
        header('Location: /shoe-shop/public/login');
        exit();
    }
}

// Bảo vệ route chỉ Admin truy cập được
public function requireAdmin() {
    $this->requireAuth(); // Kiểm tra đăng nhập trước
    if ($_SESSION['user']['role_id'] != 1) {
        http_response_code(403);
        echo "403 Forbidden";
        exit();
    }
}
```

**Áp dụng middleware**:

```php
// public/index.php
$authMiddleware = new AuthMiddleware();
$authMiddleware->applyGlobalMiddleware($path);

// Tất cả route /admin/* tự động yêu cầu quyền Admin
if (strpos($path, '/admin') === 0) {
    $this->requireAdmin();
}
```

### Cách sử dụng

#### Đăng ký tài khoản mới

1. Truy cập: `http://localhost/shoe-shop/public/register`
2. Nhập thông tin: Họ tên, Email, Mật khẩu
3. Submit form
4. Tài khoản mới được tạo với vai trò **User** (role_id = 2)

#### Đăng nhập

1. Truy cập: `http://localhost/shoe-shop/public/login`
2. Nhập Email và Mật khẩu
3. Submit form
4. Nếu thành công, được chuyển hướng về trang chủ

#### Tạo tài khoản Admin

Thêm trực tiếp vào database hoặc cập nhật role_id:

```sql
UPDATE users SET role_id = 1 WHERE email = 'admin@example.com';
```

### Kiến thức kỹ thuật

#### 1. Session Management

```php
// Khởi tạo session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Lưu thông tin user vào session
$_SESSION['user'] = [/* data */];

// Kiểm tra đăng nhập
if (isset($_SESSION['user'])) { /* logged in */ }

// Đăng xuất
session_destroy();
```

> **Session** là cơ chế lưu trữ dữ liệu người dùng trên server, được liên kết bởi Session ID lưu trong cookie.

#### 2. Password Hashing (Bcrypt)

- **Không bao giờ** lưu mật khẩu dạng plain text
- Sử dụng `password_hash()` với `PASSWORD_DEFAULT` (Bcrypt)
- Bcrypt tự động tạo **salt** (chuỗi ngẫu nhiên) để chống **rainbow table attack**

#### 3. Middleware Pattern

Middleware cho phép xử lý request theo chuỗi (chain), mỗi middleware có thể:

- Kiểm tra và từ chối request
- Thêm/sửa dữ liệu request
- Chuyển tiếp đến middleware/controller tiếp theo

---

## ✨ TÍNH NĂNG 2: QUẢN LÝ SẢN PHẨM VỚI BIẾN THỂ (PRODUCT VARIANTS)

### Mô tả

Hệ thống quản lý sản phẩm linh hoạt với khả năng tạo nhiều biến thể (variants) cho mỗi sản phẩm. Ví dụ: Một đôi giày có nhiều size (39, 40, 41...) và nhiều màu sắc (Đen, Trắng, Xanh...).

### Cấu trúc Database

#### Bảng `products` (Sản phẩm chính)

| Cột         | Kiểu dữ liệu | Mô tả               |
| ----------- | ------------ | ------------------- |
| id          | BIGINT       | Primary Key         |
| name        | VARCHAR(191) | Tên sản phẩm        |
| slug        | VARCHAR(191) | URL-friendly name   |
| description | TEXT         | Mô tả chi tiết      |
| image_url   | VARCHAR(512) | Ảnh đại diện        |
| is_active   | BOOLEAN      | Trạng thái hiển thị |

> **Lưu ý**: Bảng products KHÔNG có cột `price` và `stock` vì mỗi biến thể có giá và số lượng riêng.

#### Bảng `attributes` (Thuộc tính)

| Cột  | Kiểu dữ liệu | Mô tả                          |
| ---- | ------------ | ------------------------------ |
| id   | BIGINT       | Primary Key                    |
| name | VARCHAR(100) | Tên thuộc tính (Size, Màu sắc) |

#### Bảng `attribute_values` (Giá trị thuộc tính)

| Cột          | Kiểu dữ liệu | Mô tả                        |
| ------------ | ------------ | ---------------------------- |
| id           | BIGINT       | Primary Key                  |
| attribute_id | BIGINT       | Foreign Key → attributes(id) |
| value        | VARCHAR(100) | Giá trị (40, Đen, XL...)     |

#### Bảng `product_variants` (Biến thể sản phẩm)

| Cột        | Kiểu dữ liệu  | Mô tả                      |
| ---------- | ------------- | -------------------------- |
| id         | BIGINT        | Primary Key                |
| product_id | BIGINT        | Foreign Key → products(id) |
| sku        | VARCHAR(100)  | Mã SKU duy nhất            |
| price      | DECIMAL(12,2) | Giá bán của biến thể này   |
| stock      | INT           | Số lượng tồn kho           |
| image_url  | VARCHAR(512)  | Ảnh riêng (nếu có)         |

#### Bảng `variant_values` (Bảng nối)

| Cột                | Kiểu dữ liệu | Mô tả                              |
| ------------------ | ------------ | ---------------------------------- |
| variant_id         | BIGINT       | Foreign Key → product_variants(id) |
| attribute_value_id | BIGINT       | Foreign Key → attribute_values(id) |

**Composite Primary Key**: (variant_id, attribute_value_id)

### Mô hình quan hệ

```
products (1) ----< (n) product_variants (n) >---- (n) attribute_values
                            |                              |
                            |                              |
                            +----------< variant_values >--+
```

**Ví dụ cụ thể**:

- Product: "Giày Nike Air Max"
- Variants:
  - Variant 1: Size 40, Màu Đen → Giá 1,500,000đ, Tồn kho 10
  - Variant 2: Size 40, Màu Trắng → Giá 1,500,000đ, Tồn kho 5
  - Variant 3: Size 41, Màu Đen → Giá 1,600,000đ, Tồn kho 8

### Cách hoạt động

#### 1. Tạo sản phẩm mới (Admin)

**Luồng xử lý**:

```
Admin truy cập /admin/products/create
→ Form hiển thị: Name, Description, Image, Categories
→ Checkboxes: Size (39, 40, 41...), Màu sắc (Đen, Trắng...)
→ Nhập giá và số lượng cho mỗi tổ hợp
→ Submit → ProductController::store()
→ ProductService::createProduct()
→ Lưu vào DB (transaction)
```

**Service xử lý** (`product.service.php`):

```php
public function createProduct($data) {
    // 1. Lưu thông tin sản phẩm chính
    $productId = $this->productRepo->create([
        'name' => $data['name'],
        'slug' => $this->generateSlug($data['name']),
        'description' => $data['description'],
        'image_url' => $uploadedImagePath,
        'is_active' => true
    ]);

    // 2. Lưu danh mục
    foreach ($data['categories'] as $categoryId) {
        $this->productRepo->addCategory($productId, $categoryId);
    }

    // 3. Tạo các biến thể (variants)
    foreach ($data['variants'] as $variant) {
        $variantId = $this->productRepo->createVariant([
            'product_id' => $productId,
            'sku' => $this->generateSKU(),
            'price' => $variant['price'],
            'stock' => $variant['stock'],
            'image_url' => $variant['image'] ?? null
        ]);

        // 4. Liên kết biến thể với attribute values
        // Ví dụ: Size 40 (id=2), Màu Đen (id=6)
        $this->productRepo->addVariantValue($variantId, $variant['size_id']);
        $this->productRepo->addVariantValue($variantId, $variant['color_id']);
    }
}
```

> **Transaction**: Toàn bộ quá trình tạo sản phẩm nằm trong transaction để đảm bảo tính toàn vẹn dữ liệu.

#### 2. Hiển thị sản phẩm (User)

**Trang danh sách sản phẩm**:

```
GET /products → ProductController::showProductPage()
→ ProductService::getAllProductsActive($filters)
→ ProductRepository::findAllActive()
→ JOIN với categories, tính giá min/max từ variants
```

**Query lấy sản phẩm**:

```sql
SELECT
    p.id,
    p.name,
    p.image_url,
    MIN(pv.price) as min_price,
    MAX(pv.price) as max_price,
    SUM(pv.stock) as total_stock
FROM products p
JOIN product_variants pv ON p.id = pv.product_id
WHERE p.is_active = TRUE
GROUP BY p.id
```

#### 3. Xem chi tiết sản phẩm

**Luồng xử lý**:

```
GET /product/{id} → ProductController::showProductDetail($id)
→ ProductService::getProductWithVariants($id)
→ Lấy product info + tất cả variants + options (sizes, colors)
```

**Service xử lý**:

```php
public function getProductWithVariants($productId) {
    // 1. Lấy thông tin sản phẩm
    $product = $this->productRepo->findById($productId);

    // 2. Lấy tất cả variants
    $variants = $this->productRepo->getVariantsByProductId($productId);

    // 3. Lấy các options (sizes và colors có sẵn)
    $options = [
        'sizes' => $this->productRepo->getAvailableSizes($productId),
        'colors' => $this->productRepo->getAvailableColors($productId)
    ];

    // 4. Lấy reviews
    $reviews = $this->reviewRepo->getByProductId($productId);

    // 5. Lấy sản phẩm liên quan (cùng category)
    $relatedProducts = $this->productRepo->getRelatedProducts($productId);

    return (object)[
        'product' => $product,
        'variants' => $variants,
        'options' => $options,
        'reviews' => $reviews,
        'relatedProducts' => $relatedProducts
    ];
}
```

**View hiển thị** (`detailProduct.php`):

- Dropdown chọn Size
- Dropdown chọn Màu sắc
- Khi user chọn → JavaScript tìm variant tương ứng
- Hiển thị giá và trạng thái tồn kho của variant đó
- Nút "Thêm vào giỏ" gửi `variant_id` (không phải `product_id`)

### Cách sử dụng

#### Admin - Tạo sản phẩm mới

1. Truy cập: `http://localhost/shoe-shop/public/admin/products/create`
2. Nhập thông tin:
   - Tên sản phẩm
   - Mô tả
   - Upload ảnh đại diện
   - Chọn danh mục (categories)
3. Tạo biến thể:
   - Chọn Size: 39, 40, 41
   - Chọn Màu: Đen, Trắng
   - Nhập giá và số lượng cho từng tổ hợp
4. Submit → Sản phẩm được tạo với tất cả biến thể

#### Admin - Chỉnh sửa sản phẩm

1. Truy cập: `/admin/products/edit/{id}`
2. Sửa thông tin chính
3. Cập nhật variants (thêm/xóa/sửa)
4. Submit

#### User - Mua hàng

1. Vào trang chi tiết sản phẩm
2. Chọn Size và Màu sắc mong muốn
3. Nhập số lượng
4. Click "Thêm vào giỏ hàng"
5. Hệ thống thêm `variant_id` vào giỏ

### Kiến thức kỹ thuật

#### 1. Normalization (Chuẩn hóa Database)

- **3NF (Third Normal Form)**: Tách attributes thành bảng riêng để tránh duplicate
- **Many-to-Many**: Sử dụng bảng nối `variant_values`

#### 2. Database Transaction

```php
try {
    $conn->beginTransaction();

    // Thực hiện nhiều INSERT/UPDATE
    $productId = insertProduct();
    insertVariants($productId);
    insertCategories($productId);

    $conn->commit(); // Lưu tất cả
} catch (Exception $e) {
    $conn->rollBack(); // Hủy tất cả nếu lỗi
    throw $e;
}
```

> **Transaction** đảm bảo **ACID**: Atomicity (Toàn vẹn), Consistency (Nhất quán), Isolation (Cô lập), Durability (Bền vững)

#### 3. Composite Key

Bảng `variant_values` có composite primary key `(variant_id, attribute_value_id)`:

- Đảm bảo mỗi cặp chỉ xuất hiện 1 lần
- Tối ưu query JOIN

#### 4. SKU Generation

```php
private function generateSKU() {
    // Ví dụ: SHOE-20250105-ABC123
    return 'SHOE-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -6));
}
```

---

## ✨ TÍNH NĂNG 3: GIỎ HÀNG (SHOPPING CART)

### Mô tả

Hệ thống giỏ hàng sử dụng **Session** để lưu trữ, cho phép user thêm/xóa/cập nhật sản phẩm trước khi đặt hàng.

### Cấu trúc dữ liệu

**Session Cart Structure**:

```php
$_SESSION['cart'] = [
    'variant_id' => quantity,
    // Ví dụ:
    12 => 2,  // Variant ID 12, số lượng 2
    15 => 1,  // Variant ID 15, số lượng 1
];
```

> **Lưu ý**: Cart lưu `variant_id` (không phải `product_id`) vì mỗi biến thể có giá và tồn kho riêng.

### Cách hoạt động

#### 1. Thêm sản phẩm vào giỏ

**Luồng xử lý**:

```
User chọn Size + Color → Form POST /cart/add với variant_id
→ CartController::add()
→ CartService::addToCart($variantId, $quantity)
→ Lưu vào $_SESSION['cart']
```

**Service xử lý** (`cart.service.php`):

```php
public function addToCart($variantId, $quantity) {
    // 1. Kiểm tra variant có tồn tại không
    $variant = $this->productRepo->getVariantById($variantId);
    if (!$variant) {
        throw new Exception("Sản phẩm không tồn tại");
    }

    // 2. Kiểm tra tồn kho
    if ($variant->stock < $quantity) {
        throw new Exception("Không đủ hàng trong kho");
    }

    // 3. Thêm vào giỏ
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    if (isset($_SESSION['cart'][$variantId])) {
        // Đã có trong giỏ → Cộng thêm số lượng
        $_SESSION['cart'][$variantId] += $quantity;
    } else {
        // Chưa có → Thêm mới
        $_SESSION['cart'][$variantId] = $quantity;
    }
}
```

#### 2. Xem giỏ hàng

**Luồng xử lý**:

```
GET /cart → CartController::index()
→ CartService::getFinalCartDetails()
→ Lấy thông tin chi tiết của từng variant
→ Tính tổng tiền
→ Render view
```

**Service xử lý**:

```php
public function getFinalCartDetails() {
    $cart = $_SESSION['cart'] ?? [];
    $items = [];
    $subtotal = 0;

    foreach ($cart as $variantId => $quantity) {
        // Lấy thông tin variant từ DB
        $variant = $this->productRepo->getVariantById($variantId);

        if ($variant) {
            $itemTotal = $variant->price * $quantity;
            $items[] = [
                'variant_id' => $variantId,
                'product_name' => $variant->product_name,
                'attributes' => $variant->attributes, // VD: "40, Đen"
                'image' => $variant->image_url,
                'price' => $variant->price,
                'quantity' => $quantity,
                'total' => $itemTotal
            ];
            $subtotal += $itemTotal;
        }
    }

    // Xử lý voucher (nếu có)
    $discount = 0;
    $voucher = null;
    if (isset($_SESSION['voucher'])) {
        $voucher = $this->voucherRepo->findByCode($_SESSION['voucher']);
        $discount = $this->calculateDiscount($subtotal, $voucher);
    }

    $finalTotal = $subtotal - $discount;

    return [
        'items' => $items,
        'subtotal' => $subtotal,
        'discount' => $discount,
        'voucher' => $voucher,
        'final_total' => $finalTotal
    ];
}
```

#### 3. Cập nhật số lượng

**Luồng xử lý**:

```
POST /cart/update với variant_id và quantity mới
→ CartController::update()
→ CartService::updateCartItemQuantity($variantId, $newQuantity)
→ Cập nhật $_SESSION['cart'][$variantId] = $newQuantity
```

#### 4. Xóa sản phẩm khỏi giỏ

```php
public function removeCartItem($variantId) {
    if (isset($_SESSION['cart'][$variantId])) {
        unset($_SESSION['cart'][$variantId]);
    }
}
```

#### 5. Xóa toàn bộ giỏ hàng

```php
public function clearCart() {
    unset($_SESSION['cart']);
    unset($_SESSION['voucher']); // Xóa luôn voucher đã áp dụng
}
```

### Cách sử dụng

#### Thêm vào giỏ

1. Vào trang chi tiết sản phẩm: `/product/{id}`
2. Chọn Size và Màu sắc
3. Nhập số lượng
4. Click "Thêm vào giỏ hàng"

#### Xem giỏ hàng

1. Click icon giỏ hàng hoặc truy cập: `/cart`
2. Xem danh sách sản phẩm đã thêm
3. Có thể:
   - Tăng/giảm số lượng
   - Xóa từng sản phẩm
   - Áp dụng mã giảm giá
   - Tiến hành thanh toán

### Kiến thức kỹ thuật

#### 1. Session Storage

- **Session** lưu trữ dữ liệu trên server
- Mỗi user có Session ID riêng (lưu trong cookie)
- Dữ liệu tồn tại cho đến khi:
  - User đóng browser (session timeout)
  - Gọi `session_destroy()`

**Ưu điểm**:

- Bảo mật hơn cookie (data ở server)
- Không giới hạn kích thước (so với cookie 4KB)

**Nhược điểm**:

- Mất dữ liệu khi session hết hạn
- Không sync giữa các devices

#### 2. Tại sao lưu variant_id thay vì product_id?

- Mỗi variant có giá và tồn kho khác nhau
- Dễ dàng kiểm tra stock availability
- Chính xác khi tạo order

#### 3. Tính toán giá

```php
// Subtotal (chưa giảm giá)
$subtotal = array_reduce($items, function($sum, $item) {
    return $sum + ($item['price'] * $item['quantity']);
}, 0);

// Discount (nếu có voucher)
$discount = ($voucher->type === 'percent')
    ? ($subtotal * $voucher->value / 100)
    : $voucher->value;

// Final total
$finalTotal = $subtotal - $discount;
```

---

## ✨ TÍNH NĂNG 4: HỆ THỐNG MÃ GIẢM GIÁ (VOUCHER)

### Mô tả

Cho phép Admin tạo và quản lý các mã giảm giá (voucher), User có thể áp dụng vào đơn hàng để được giảm giá.

### Cấu trúc Database

#### Bảng `vouchers`

| Cột        | Kiểu dữ liệu  | Mô tả                                     |
| ---------- | ------------- | ----------------------------------------- |
| id         | BIGINT        | Primary Key                               |
| code       | VARCHAR(100)  | Mã voucher (unique) VD: SALE50            |
| type       | ENUM          | 'fixed' (giảm số tiền) hoặc 'percent' (%) |
| value      | DECIMAL(12,2) | Giá trị giảm (50000 hoặc 10%)             |
| min_spend  | DECIMAL(12,2) | Đơn hàng tối thiểu để áp dụng             |
| quantity   | INT           | Tổng số lượt sử dụng                      |
| used_count | INT           | Số lượt đã sử dụng                        |
| starts_at  | TIMESTAMP     | Ngày bắt đầu hiệu lực                     |
| expires_at | TIMESTAMP     | Ngày hết hạn                              |
| is_active  | BOOLEAN       | Bật/tắt voucher                           |

#### Bảng `orders` (thêm cột)

| Cột             | Kiểu dữ liệu  | Mô tả                      |
| --------------- | ------------- | -------------------------- |
| voucher_id      | BIGINT        | Foreign Key → vouchers(id) |
| discount_amount | DECIMAL(12,2) | Số tiền đã giảm            |

### Cách hoạt động

#### 1. Tạo voucher (Admin)

**Luồng xử lý**:

```
Admin truy cập /admin/vouchers/create
→ Nhập form: Code, Type, Value, Min Spend, Quantity, Dates
→ POST /admin/vouchers/create
→ VoucherController::store()
→ VoucherService::createVoucher()
→ VoucherRepository::create()
```

**Ví dụ tạo voucher**:

```
Code: SALE50
Type: fixed
Value: 50000
Min Spend: 500000
Quantity: 100
Starts At: 2025-01-01 00:00:00
Expires At: 2025-01-31 23:59:59
```

→ Giảm 50,000đ cho đơn hàng từ 500,000đ trở lên, giới hạn 100 lượt sử dụng.

#### 2. Áp dụng voucher (User)

**Luồng xử lý**:

```
User ở trang /cart → Nhập mã voucher → POST /cart/apply-voucher
→ CartController::applyVoucher()
→ CartService::applyVoucher($code)
→ VoucherRepository::findByCode($code)
→ Validate voucher
→ Lưu vào $_SESSION['voucher']
```

**Service validate** (`cart.service.php`):

```php
public function applyVoucher($code) {
    // 1. Tìm voucher
    $voucher = $this->voucherRepo->findByCode($code);
    if (!$voucher) {
        return ['success' => false, 'message' => 'Mã không tồn tại'];
    }

    // 2. Kiểm tra trạng thái
    if (!$voucher->is_active) {
        return ['success' => false, 'message' => 'Mã đã bị vô hiệu hóa'];
    }

    // 3. Kiểm tra thời gian
    $now = time();
    if ($voucher->starts_at && strtotime($voucher->starts_at) > $now) {
        return ['success' => false, 'message' => 'Mã chưa có hiệu lực'];
    }
    if ($voucher->expires_at && strtotime($voucher->expires_at) < $now) {
        return ['success' => false, 'message' => 'Mã đã hết hạn'];
    }

    // 4. Kiểm tra số lượng
    if ($voucher->used_count >= $voucher->quantity) {
        return ['success' => false, 'message' => 'Mã đã hết lượt sử dụng'];
    }

    // 5. Kiểm tra min_spend
    $cartTotal = $this->getCartTotal();
    if ($cartTotal < $voucher->min_spend) {
        return [
            'success' => false,
            'message' => 'Đơn hàng tối thiểu ' . number_format($voucher->min_spend) . 'đ'
        ];
    }

    // 6. Lưu vào session
    $_SESSION['voucher'] = $code;
    return ['success' => true, 'message' => 'Áp dụng mã thành công'];
}
```

#### 3. Tính toán giảm giá

```php
private function calculateDiscount($subtotal, $voucher) {
    if (!$voucher) return 0;

    if ($voucher->type === 'fixed') {
        // Giảm số tiền cố định
        return min($voucher->value, $subtotal); // Không giảm quá tổng tiền
    } else {
        // Giảm theo phần trăm
        return ($subtotal * $voucher->value) / 100;
    }
}
```

**Ví dụ**:

- Subtotal: 1,000,000đ
- Voucher: Type = percent, Value = 10
- Discount = 1,000,000 \* 10 / 100 = 100,000đ
- Final Total = 900,000đ

#### 4. Sử dụng voucher khi đặt hàng

**Luồng xử lý**:

```
User checkout → OrderService::createOrder()
→ Kiểm tra $_SESSION['voucher']
→ Validate lại voucher
→ Tính discount
→ Lưu order với voucher_id và discount_amount
→ Tăng used_count của voucher
→ Xóa voucher khỏi session
```

**Service xử lý** (`order.service.php`):

```php
public function createOrder($userId, $customerInfo, $paymentMethod) {
    $cart = $_SESSION['cart'] ?? [];
    $subtotal = $this->calculateSubtotal($cart);

    // Xử lý voucher
    $voucherId = null;
    $discountAmount = 0;
    if (isset($_SESSION['voucher'])) {
        $voucher = $this->voucherRepo->findByCode($_SESSION['voucher']);

        // Validate lại (có thể đã hết hạn trong lúc user điền form)
        if ($this->isVoucherValid($voucher, $subtotal)) {
            $voucherId = $voucher->id;
            $discountAmount = $this->calculateDiscount($subtotal, $voucher);

            // Tăng used_count
            $this->voucherRepo->incrementUsedCount($voucherId);
        }
    }

    $totalAmount = $subtotal - $discountAmount;

    // Lưu order
    $orderId = $this->orderRepo->create([
        'user_id' => $userId,
        'customer_name' => $customerInfo['name'],
        'customer_phone' => $customerInfo['phone'],
        'customer_address' => $customerInfo['address'],
        'total_amount' => $totalAmount,
        'voucher_id' => $voucherId,
        'discount_amount' => $discountAmount,
        'payment_method' => $paymentMethod,
        'status' => 'pending'
    ]);

    // Lưu order items và giảm stock...

    // Xóa cart và voucher
    unset($_SESSION['cart']);
    unset($_SESSION['voucher']);

    return ['success' => true, 'order_id' => $orderId];
}
```

### Cách sử dụng

#### Admin - Tạo voucher

1. Truy cập: `/admin/vouchers/create`
2. Nhập thông tin:
   - **Code**: Mã voucher (VD: NEWYEAR2025)
   - **Type**: Chọn Fixed (giảm tiền) hoặc Percent (giảm %)
   - **Value**: Giá trị giảm
   - **Min Spend**: Đơn hàng tối thiểu
   - **Quantity**: Số lượt sử dụng
   - **Start/End Date**: Thời gian hiệu lực
3. Submit

#### Admin - Quản lý voucher

1. Truy cập: `/admin/vouchers`
2. Xem danh sách voucher với thông tin:
   - Mã
   - Loại
   - Giá trị
   - Đã sử dụng / Tổng số
   - Trạng thái
3. Có thể Edit hoặc Vô hiệu hóa

#### User - Sử dụng voucher

1. Thêm sản phẩm vào giỏ hàng
2. Truy cập: `/cart`
3. Nhập mã voucher vào ô "Mã giảm giá"
4. Click "Áp dụng"
5. Nếu hợp lệ, tổng tiền sẽ được giảm
6. Tiến hành checkout

### Kiến thức kỹ thuật

#### 1. ENUM Type

```sql
type ENUM('fixed', 'percent')
```

- **ENUM** giới hạn giá trị chỉ có thể là một trong các lựa chọn định sẵn
- Tiết kiệm bộ nhớ hơn VARCHAR
- Đảm bảo data integrity

#### 2. Timestamp và Timezone

```php
// So sánh thời gian
$now = time(); // Unix timestamp
$startTime = strtotime($voucher->starts_at);

if ($startTime > $now) {
    // Voucher chưa có hiệu lực
}
```

#### 3. Race Condition

**Vấn đề**: Nhiều user dùng voucher cùng lúc, có thể vượt quá `quantity`.

**Giải pháp**: Sử dụng Database Transaction và Lock.

```php
// Trong OrderService::createOrder()
$conn->beginTransaction();
try {
    // Lock row để không user khác thay đổi
    $voucher = $this->voucherRepo->findByCodeForUpdate($code);

    if ($voucher->used_count >= $voucher->quantity) {
        throw new Exception("Voucher đã hết");
    }

    // Tăng used_count
    $this->voucherRepo->incrementUsedCount($voucher->id);

    // Lưu order...

    $conn->commit();
} catch (Exception $e) {
    $conn->rollBack();
    throw $e;
}
```

**Query với Lock**:

```sql
SELECT * FROM vouchers WHERE code = ? FOR UPDATE;
```

> **FOR UPDATE**: Lock row này cho đến khi transaction kết thúc, ngăn các transaction khác đọc/sửa.

---

## ✨ TÍNH NĂNG 5: QUẢN LÝ ĐỘN HÀNG (ORDER MANAGEMENT)

### Mô tả

Hệ thống quản lý đơn hàng từ lúc khách đặt hàng, Admin xử lý, theo dõi trạng thái, cho đến khi hoàn thành.

### Cấu trúc Database

#### Bảng `orders`

| Cột              | Kiểu dữ liệu  | Mô tả                   |
| ---------------- | ------------- | ----------------------- |
| id               | BIGINT        | Primary Key             |
| user_id          | INT           | Foreign Key → users(id) |
| customer_name    | VARCHAR(191)  | Tên người nhận          |
| customer_phone   | VARCHAR(20)   | SĐT người nhận          |
| customer_address | TEXT          | Địa chỉ giao hàng       |
| total_amount     | DECIMAL(12,2) | Tổng tiền               |
| discount_amount  | DECIMAL(12,2) | Số tiền giảm giá        |
| voucher_id       | BIGINT        | FK → vouchers(id)       |
| payment_method   | ENUM          | 'cod', 'bank_transfer'  |
| status           | ENUM          | Trạng thái đơn hàng     |
| created_at       | TIMESTAMP     | Ngày đặt                |
| updated_at       | TIMESTAMP     | Ngày cập nhật           |

**Status values**:

- `pending`: Chờ xác nhận
- `processing`: Đang xử lý
- `shipped`: Đã giao vận chuyển
- `completed`: Hoàn thành
- `cancelled`: Đã hủy

#### Bảng `order_items` (Chi tiết đơn hàng)

| Cột                | Kiểu dữ liệu  | Mô tả                      |
| ------------------ | ------------- | -------------------------- |
| id                 | BIGINT        | Primary Key                |
| order_id           | BIGINT        | FK → orders(id)            |
| variant_id         | BIGINT        | FK → product_variants(id)  |
| product_name       | VARCHAR(191)  | Tên sản phẩm (snapshot)    |
| variant_attributes | VARCHAR(255)  | Thuộc tính (VD: "40, Đen") |
| quantity           | INT           | Số lượng                   |
| price              | DECIMAL(12,2) | Giá tại thời điểm đặt      |

> **Snapshot Pattern**: Lưu `product_name`, `price` vào order để tránh bị ảnh hưởng khi sản phẩm bị xóa/đổi giá sau này.

### Cách hoạt động

#### 1. Đặt hàng (User)

**Luồng xử lý**:

```
User ở /cart → Click "Thanh toán"
→ Yêu cầu đăng nhập (nếu chưa)
→ Redirect đến /checkout
→ Nhập thông tin: Tên, SĐT, Địa chỉ, Phương thức thanh toán
→ POST /checkout
→ OrderController::placeOrder()
→ OrderService::createOrder()
```

**Service xử lý** (`order.service.php`):

```php
public function createOrder($userId, $name, $phone, $address, $paymentMethod) {
    // 1. Lấy cart
    $cart = $_SESSION['cart'] ?? [];
    if (empty($cart)) {
        return ['success' => false, 'message' => 'Giỏ hàng trống'];
    }

    // 2. Tính subtotal
    $subtotal = 0;
    $orderItems = [];
    foreach ($cart as $variantId => $quantity) {
        $variant = $this->productRepo->getVariantById($variantId);

        // Kiểm tra tồn kho
        if ($variant->stock < $quantity) {
            return [
                'success' => false,
                'message' => "Sản phẩm {$variant->product_name} không đủ hàng"
            ];
        }

        $itemPrice = $variant->price;
        $itemTotal = $itemPrice * $quantity;
        $subtotal += $itemTotal;

        $orderItems[] = [
            'variant_id' => $variantId,
            'product_name' => $variant->product_name,
            'variant_attributes' => $variant->attributes, // "40, Đen"
            'quantity' => $quantity,
            'price' => $itemPrice
        ];
    }

    // 3. Xử lý voucher
    $voucherId = null;
    $discountAmount = 0;
    if (isset($_SESSION['voucher'])) {
        $voucher = $this->voucherRepo->findByCode($_SESSION['voucher']);
        if ($this->isVoucherValid($voucher, $subtotal)) {
            $voucherId = $voucher->id;
            $discountAmount = $this->calculateDiscount($subtotal, $voucher);
        }
    }

    $totalAmount = $subtotal - $discountAmount;

    // 4. Tạo order (Transaction)
    $conn->beginTransaction();
    try {
        // 4a. Insert order
        $orderId = $this->orderRepo->create([
            'user_id' => $userId,
            'customer_name' => $name,
            'customer_phone' => $phone,
            'customer_address' => $address,
            'total_amount' => $totalAmount,
            'discount_amount' => $discountAmount,
            'voucher_id' => $voucherId,
            'payment_method' => $paymentMethod,
            'status' => 'pending'
        ]);

        // 4b. Insert order items và giảm stock
        foreach ($orderItems as $item) {
            $this->orderRepo->createItem($orderId, $item);
            $this->productRepo->decreaseStock($item['variant_id'], $item['quantity']);
        }

        // 4c. Tăng voucher used_count
        if ($voucherId) {
            $this->voucherRepo->incrementUsedCount($voucherId);
        }

        $conn->commit();

        // 5. Xóa cart và voucher
        unset($_SESSION['cart']);
        unset($_SESSION['voucher']);

        return ['success' => true, 'order_id' => $orderId];
    } catch (Exception $e) {
        $conn->rollBack();
        return ['success' => false, 'message' => $e->getMessage()];
    }
}
```

#### 2. Xem lịch sử đơn hàng (User)

**Luồng xử lý**:

```
GET /history → OrderController::showPurchaseHistory()
→ OrderService::getOrdersByUserId($userId)
→ Lấy danh sách orders của user
→ Hiển thị: Mã đơn, Ngày đặt, Tổng tiền, Trạng thái
```

#### 3. Quản lý đơn hàng (Admin)

**Luồng xử lý**:

```
GET /admin/orders → OrderController::getAllOrders()
→ OrderService::getAllOrders()
→ Hiển thị danh sách tất cả đơn hàng
→ Có thể lọc theo status
```

#### 4. Xem chi tiết đơn hàng (Admin)

**Luồng xử lý**:

```
GET /admin/orders/view/{id}
→ OrderController::getOrderDetail($id)
→ OrderService::getOrderDetail($id)
→ Lấy:
  - Thông tin order
  - Danh sách order_items
  - Thông tin user
  - Thông tin voucher (nếu có)
```

**Query chi tiết**:

```sql
SELECT
    o.*,
    u.fullname, u.email,
    v.code as voucher_code
FROM orders o
JOIN users u ON o.user_id = u.id
LEFT JOIN vouchers v ON o.voucher_id = v.id
WHERE o.id = ?;

-- Order items
SELECT
    oi.*,
    pv.image_url
FROM order_items oi
LEFT JOIN product_variants pv ON oi.variant_id = pv.id
WHERE oi.order_id = ?;
```

#### 5. Cập nhật trạng thái (Admin)

**Luồng xử lý**:

```
Admin chọn status mới → POST /admin/orders/update-status/{id}
→ OrderController::updateStatus($id)
→ OrderService::updateOrderStatus($id, $newStatus)
→ UPDATE orders SET status = ?, updated_at = NOW() WHERE id = ?
```

**Workflow trạng thái**:

```
pending → processing → shipped → completed
            ↓
        cancelled
```

### Cách sử dụng

#### User - Đặt hàng

1. Thêm sản phẩm vào giỏ
2. Truy cập `/cart`
3. (Tùy chọn) Áp dụng voucher
4. Click "Thanh toán"
5. Đăng nhập nếu chưa
6. Nhập thông tin:
   - Tên người nhận
   - Số điện thoại
   - Địa chỉ giao hàng
   - Phương thức thanh toán (COD/Chuyển khoản)
7. Click "Đặt hàng"
8. Thành công → Hiển thị mã đơn hàng

#### User - Xem lịch sử

1. Truy cập `/history`
2. Xem danh sách đơn hàng đã đặt
3. Xem chi tiết từng đơn
4. Theo dõi trạng thái

#### Admin - Quản lý đơn hàng

1. Truy cập `/admin/orders`
2. Xem danh sách tất cả đơn hàng
3. Click "Xem chi tiết" để xem thông tin đầy đủ
4. Cập nhật trạng thái:
   - Pending → Processing (Xác nhận đơn)
   - Processing → Shipped (Đã giao cho vận chuyển)
   - Shipped → Completed (Khách đã nhận)
   - Hoặc Cancelled (Hủy đơn)

### Kiến thức kỹ thuật

#### 1. Snapshot Pattern

**Vấn đề**: Nếu chỉ lưu `product_id`, khi sản phẩm bị xóa hoặc đổi giá, đơn hàng cũ sẽ hiển thị sai.

**Giải pháp**: Lưu snapshot (bản chụp) tại thời điểm đặt hàng.

```php
// Lưu vào order_items
[
    'product_name' => $product->name,  // Tên tại thời điểm đặt
    'price' => $variant->price,        // Giá tại thời điểm đặt
    'variant_attributes' => "40, Đen"  // Thuộc tính
]
```

#### 2. Inventory Management (Quản lý tồn kho)

```php
// Khi đặt hàng → Giảm stock
public function decreaseStock($variantId, $quantity) {
    $sql = "UPDATE product_variants
            SET stock = stock - ?
            WHERE id = ? AND stock >= ?";
    $stmt = $this->conn->prepare($sql);
    $stmt->execute([$quantity, $variantId, $quantity]);

    if ($stmt->rowCount() === 0) {
        throw new Exception("Không đủ hàng trong kho");
    }
}

// Khi hủy đơn → Hoàn lại stock
public function increaseStock($variantId, $quantity) {
    $sql = "UPDATE product_variants
            SET stock = stock + ?
            WHERE id = ?";
    $stmt = $this->conn->prepare($sql);
    $stmt->execute([$quantity, $variantId]);
}
```

#### 3. Transaction cho Order

**Tại sao cần transaction?**

- Tạo order và order_items phải cùng thành công hoặc cùng thất bại
- Giảm stock phải đồng bộ với việc tạo order
- Tăng voucher used_count phải nguyên tử

```php
$conn->beginTransaction();
try {
    $orderId = $this->createOrder($data);
    $this->createOrderItems($orderId, $items);
    $this->decreaseStock($items);
    $this->incrementVoucherUsedCount($voucherId);
    $conn->commit();
} catch (Exception $e) {
    $conn->rollBack(); // Hủy tất cả thay đổi
    throw $e;
}
```

---

## ✨ TÍNH NĂNG 6: HỆ THỐNG ĐÁNH GIÁ SẢN PHẨM (PRODUCT REVIEWS)

### Mô tả

Cho phép khách hàng đánh giá sản phẩm sau khi mua và nhận hàng. Hệ thống kiểm tra quyền đánh giá (chỉ được đánh giá sau khi đã mua).

### Cấu trúc Database

#### Bảng `reviews`

| Cột        | Kiểu dữ liệu | Mô tả                             |
| ---------- | ------------ | --------------------------------- |
| id         | BIGINT       | Primary Key                       |
| product_id | BIGINT       | FK → products(id)                 |
| user_id    | INT          | FK → users(id)                    |
| order_id   | BIGINT       | FK → orders(id)                   |
| rating     | TINYINT      | Điểm đánh giá (1-5)               |
| comment    | TEXT         | Nội dung bình luận                |
| status     | ENUM         | 'pending', 'approved', 'rejected' |
| created_at | TIMESTAMP    | Ngày đánh giá                     |

**Unique constraint**: `(user_id, product_id, order_id)`
→ Mỗi user chỉ được đánh giá 1 sản phẩm 1 lần cho mỗi đơn hàng.

### Cách hoạt động

#### 1. Kiểm tra quyền đánh giá

**Luồng xử lý**:

```
User vào trang chi tiết sản phẩm
→ ProductController::showProductDetail($id)
→ Kiểm tra: User đã mua sản phẩm này chưa?
→ ReviewService::checkReviewEligibility($userId, $productId)
```

**Service xử lý** (`review.service.php`):

```php
public function checkReviewEligibility($userId, $productId) {
    // 1. Tìm đơn hàng đã hoàn thành có chứa sản phẩm này
    $sql = "SELECT o.id as order_id
            FROM orders o
            JOIN order_items oi ON o.id = oi.order_id
            JOIN product_variants pv ON oi.variant_id = pv.id
            WHERE o.user_id = ?
              AND pv.product_id = ?
              AND o.status = 'completed'
            LIMIT 1";

    $stmt = $this->conn->prepare($sql);
    $stmt->execute([$userId, $productId]);
    $order = $stmt->fetch();

    if (!$order) {
        return (object)['canReview' => false, 'orderId' => null];
    }

    $orderId = $order['order_id'];

    // 2. Kiểm tra đã đánh giá chưa
    $sql = "SELECT id FROM reviews
            WHERE user_id = ? AND product_id = ? AND order_id = ?";
    $stmt = $this->conn->prepare($sql);
    $stmt->execute([$userId, $productId, $orderId]);
    $existingReview = $stmt->fetch();

    if ($existingReview) {
        return (object)['canReview' => false, 'orderId' => null];
    }

    // 3. Được phép đánh giá
    return (object)['canReview' => true, 'orderId' => $orderId];
}
```

#### 2. Gửi đánh giá

**Luồng xử lý**:

```
User nhập form đánh giá → POST /product/{id}/review
→ ProductController::addReview($productId)
→ ReviewService::createReview($productId, $userId, $orderId, $rating, $comment)
→ Validate và lưu vào DB
```

**Service xử lý**:

```php
public function createReview($productId, $userId, $orderId, $rating, $comment) {
    // 1. Validate
    if ($rating < 1 || $rating > 5) {
        return ['success' => false, 'message' => 'Điểm đánh giá từ 1-5'];
    }

    // 2. Kiểm tra lại quyền
    $eligibility = $this->checkReviewEligibility($userId, $productId);
    if (!$eligibility->canReview) {
        return ['success' => false, 'message' => 'Bạn không thể đánh giá sản phẩm này'];
    }

    // 3. Lưu review
    try {
        $this->reviewRepo->create([
            'product_id' => $productId,
            'user_id' => $userId,
            'order_id' => $orderId,
            'rating' => $rating,
            'comment' => trim($comment),
            'status' => 'approved' // Hoặc 'pending' nếu cần kiểm duyệt
        ]);

        return ['success' => true, 'message' => 'Đánh giá thành công'];
    } catch (Exception $e) {
        return ['success' => false, 'message' => 'Có lỗi xảy ra'];
    }
}
```

#### 3. Hiển thị đánh giá

**Trong trang chi tiết sản phẩm**:

```php
// ProductService::getProductWithVariants()
$reviews = $this->reviewRepo->getByProductId($productId);

// Tính trung bình rating
$avgRating = $this->reviewRepo->getAverageRating($productId);
```

**Query lấy reviews**:

```sql
SELECT
    r.id,
    r.rating,
    r.comment,
    r.created_at,
    u.fullname as user_name
FROM reviews r
JOIN users u ON r.user_id = u.id
WHERE r.product_id = ?
  AND r.status = 'approved'
ORDER BY r.created_at DESC
```

#### 4. Tính trung bình rating

```php
public function getAverageRating($productId) {
    $sql = "SELECT AVG(rating) as avg_rating, COUNT(*) as total_reviews
            FROM reviews
            WHERE product_id = ? AND status = 'approved'";
    $stmt = $this->conn->prepare($sql);
    $stmt->execute([$productId]);
    $result = $stmt->fetch();

    return [
        'avg_rating' => round($result['avg_rating'], 1), // VD: 4.3
        'total_reviews' => $result['total_reviews']
    ];
}
```

### Cách sử dụng

#### User - Đánh giá sản phẩm

1. Đặt hàng và chờ Admin xác nhận completed
2. Truy cập `/history` → Xem đơn hàng đã hoàn thành
3. Hoặc truy cập trang chi tiết sản phẩm
4. Nếu chưa đánh giá, hiển thị form:
   - Chọn số sao (1-5)
   - Nhập bình luận
5. Submit
6. Đánh giá được lưu và hiển thị ngay

#### Admin - Kiểm duyệt đánh giá (Nếu có)

Nếu `status` mặc định là 'pending':

1. Admin vào trang quản lý reviews
2. Xem danh sách reviews chờ duyệt
3. Approve hoặc Reject

### Kiến thức kỹ thuật

#### 1. Unique Constraint

```sql
UNIQUE KEY uq_user_product_order (user_id, product_id, order_id)
```

- Ngăn user đánh giá trùng
- Database tự động reject nếu vi phạm

#### 2. Status cho kiểm duyệt

- **pending**: Chờ admin duyệt
- **approved**: Đã duyệt, hiển thị public
- **rejected**: Bị từ chối

#### 3. Average Rating với SQL

```sql
SELECT AVG(rating) FROM reviews WHERE product_id = ? AND status = 'approved';
```

- `AVG()`: Hàm aggregate tính trung bình
- Chỉ tính reviews đã approved

---

## ✨ TÍNH NĂNG 7: DASHBOARD QUẢN TRỊ (ADMIN DASHBOARD)

### Mô tả

Trang tổng quan cho Admin hiển thị các chỉ số quan trọng về doanh thu, đơn hàng, sản phẩm, người dùng.

### Cách hoạt động

**Luồng xử lý**:

```
GET /admin → DashboardController::index()
→ DashboardService::getDashboardData()
→ Aggregate queries từ nhiều bảng
→ Render view với dữ liệu thống kê
```

**Service xử lý** (`dashboard.service.php`):

```php
public function getDashboardData() {
    return [
        'totalRevenue' => $this->getTotalRevenue(),
        'totalOrders' => $this->getTotalOrders(),
        'pendingOrders' => $this->getPendingOrders(),
        'totalProducts' => $this->getTotalProducts(),
        'totalUsers' => $this->getTotalUsers(),
        'recentOrders' => $this->getRecentOrders(10),
        'topProducts' => $this->getTopSellingProducts(5),
        'revenueByMonth' => $this->getRevenueByMonth()
    ];
}
```

**Các query thống kê**:

1. **Tổng doanh thu**:

```sql
SELECT SUM(total_amount) as total_revenue
FROM orders
WHERE status IN ('completed', 'shipped');
```

2. **Đơn hàng chờ xử lý**:

```sql
SELECT COUNT(*) FROM orders WHERE status = 'pending';
```

3. **Top sản phẩm bán chạy**:

```sql
SELECT
    p.id,
    p.name,
    SUM(oi.quantity) as total_sold
FROM order_items oi
JOIN product_variants pv ON oi.variant_id = pv.id
JOIN products p ON pv.product_id = p.id
GROUP BY p.id
ORDER BY total_sold DESC
LIMIT 5;
```

4. **Doanh thu theo tháng** (Chart):

```sql
SELECT
    DATE_FORMAT(created_at, '%Y-%m') as month,
    SUM(total_amount) as revenue
FROM orders
WHERE status IN ('completed', 'shipped')
  AND created_at >= DATE_SUB(NOW(), INTERVAL 12 MONTH)
GROUP BY month
ORDER BY month;
```

### Cách sử dụng

1. Đăng nhập với tài khoản Admin
2. Truy cập `/admin`
3. Xem các chỉ số:
   - Tổng doanh thu
   - Số đơn hàng (tổng, đang chờ, hoàn thành)
   - Số sản phẩm, số user
   - Đơn hàng gần đây
   - Sản phẩm bán chạy
   - Biểu đồ doanh thu theo tháng

---

## ✨ TÍNH NĂNG 8: LỌC VÀ TÌM KIẾM SẢN PHẨM

### Mô tả

User có thể tìm kiếm sản phẩm theo từ khóa, lọc theo danh mục, lọc theo khoảng giá.

### Cách hoạt động

**Luồng xử lý**:

```
GET /products?search=nike&category=3&price=0-1000000
→ ProductController::showProductPage()
→ ProductService::getAllProductsActive($filters)
→ ProductRepository::findAllActive($filters)
→ WHERE conditions được build động
```

**Repository xử lý** (`product.repository.php`):

```php
public function findAllActive($filters = []) {
    $sql = "SELECT DISTINCT
                p.id,
                p.name,
                p.slug,
                p.image_url,
                MIN(pv.price) as min_price,
                MAX(pv.price) as max_price
            FROM products p
            JOIN product_variants pv ON p.id = pv.product_id
            WHERE p.is_active = TRUE";

    $params = [];

    // 1. Lọc theo search keyword
    if (!empty($filters['search'])) {
        $sql .= " AND p.name LIKE ?";
        $params[] = '%' . $filters['search'] . '%';
    }

    // 2. Lọc theo category
    if (!empty($filters['category'])) {
        $sql .= " AND EXISTS (
            SELECT 1 FROM product_category_map pcm
            WHERE pcm.product_id = p.id AND pcm.category_id = ?
        )";
        $params[] = $filters['category'];
    }

    $sql .= " GROUP BY p.id";

    // 3. Lọc theo price range (sau khi GROUP)
    if (!empty($filters['price'])) {
        // VD: price = "0-1000000"
        list($minPrice, $maxPrice) = explode('-', $filters['price']);
        $sql .= " HAVING min_price >= ? AND min_price <= ?";
        $params[] = (int)$minPrice;
        $params[] = (int)$maxPrice;
    }

    $sql .= " ORDER BY p.created_at DESC";

    $stmt = $this->conn->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll(PDO::FETCH_OBJ);
}
```

### Cách sử dụng

1. Truy cập `/products`
2. Nhập từ khóa vào ô tìm kiếm
3. Chọn danh mục từ dropdown
4. Chọn khoảng giá
5. Click "Lọc" → URL: `/products?search=...&category=...&price=...`

### Kiến thức kỹ thuật

#### Dynamic Query Building

- Build SQL query dựa trên filters có giá trị
- Sử dụng **Prepared Statements** để tránh SQL Injection

#### LIKE Operator

```sql
WHERE name LIKE '%nike%'
```

- `%`: Wildcard (đại diện cho 0 hoặc nhiều ký tự)
- Tìm kiếm không phân biệt hoa thường (nếu collation là `ci`)

#### Subquery vs JOIN

```sql
-- Cách 1: Subquery
WHERE EXISTS (SELECT 1 FROM product_category_map WHERE ...)

-- Cách 2: JOIN
JOIN product_category_map pcm ON p.id = pcm.product_id
```

- Subquery đơn giản hơn khi chỉ kiểm tra tồn tại
- JOIN nhanh hơn nếu cần lấy dữ liệu từ bảng liên kết

---

## 🛡️ BẢO MẬT

### 1. SQL Injection Prevention

**Luôn sử dụng Prepared Statements**:

```php
// BAD ❌
$sql = "SELECT * FROM users WHERE email = '$email'";

// GOOD ✅
$sql = "SELECT * FROM users WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$email]);
```

### 2. XSS (Cross-Site Scripting) Prevention

**Escape output**:

```php
// Trong view
<?php echo htmlspecialchars($user->fullname, ENT_QUOTES, 'UTF-8'); ?>
```

### 3. CSRF (Cross-Site Request Forgery) Prevention

**Sử dụng CSRF Token** (Nên implement):

```php
// Generate token
$_SESSION['csrf_token'] = bin2hex(random_bytes(32));

// Trong form
<input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

// Validate
if ($_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    die('Invalid CSRF token');
}
```

### 4. Password Security

- **Không bao giờ** lưu password dạng plain text
- Sử dụng `password_hash()` với `PASSWORD_DEFAULT`
- Verify với `password_verify()`

### 5. File Upload Security

```php
// Validate file type
$allowed = ['image/jpeg', 'image/png', 'image/gif'];
if (!in_array($_FILES['image']['type'], $allowed)) {
    die('Invalid file type');
}

// Validate file size (VD: max 2MB)
if ($_FILES['image']['size'] > 2 * 1024 * 1024) {
    die('File too large');
}

// Rename file để tránh overwrite
$newName = uniqid() . '-' . time() . '.png';
move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $newName);
```

---

## 🧪 TESTING VÀ DEBUG

### Check Database Connection

```php
// public/test_connection.php
<?php
require_once '../src/models/repositories/database.php';
echo "Kết nối thành công!";
```

### Check Migrations

```bash
php check_database_structure.php
```

### Debug Tips

1. **Hiển thị lỗi PHP**:

```php
// Đầu file
ini_set('display_errors', 1);
error_reporting(E_ALL);
```

2. **Debug SQL Query**:

```php
// Sau khi execute
var_dump($stmt->errorInfo());
```

3. **Check Session**:

```php
echo '<pre>';
print_r($_SESSION);
echo '</pre>';
```

---

## 📝 CONVENTIONS VÀ BEST PRACTICES

### 1. Naming Conventions

- **Files**: `snake_case.php`
- **Classes**: `PascalCase`
- **Methods/Functions**: `camelCase`
- **Database**: `snake_case`

### 2. Code Organization

- **Controller**: Chỉ xử lý request/response, không chứa business logic
- **Service**: Chứa business logic
- **Repository**: Chỉ chứa database queries
- **Model**: Chỉ đại diện cho entities

### 3. Error Handling

```php
try {
    // Code có thể gây lỗi
} catch (PDOException $e) {
    // Log error
    error_log($e->getMessage());
    // Hiển thị thông báo user-friendly
    echo "Có lỗi xảy ra, vui lòng thử lại sau.";
}
```

---

## 🚀 DEPLOYMENT

### Production Checklist

- [ ] Tắt `display_errors`
- [ ] Thay đổi database credentials
- [ ] Cấu hình HTTPS
- [ ] Cấu hình CSRF protection
- [ ] Thêm `.htaccess` cho security headers
- [ ] Backup database định kỳ
- [ ] Monitor error logs

### .htaccess Example

```apache
# Redirect HTTP to HTTPS
RewriteEngine On
RewriteCond %{HTTPS} off
RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]

# Prevent directory listing
Options -Indexes

# Security headers
Header set X-Content-Type-Options "nosniff"
Header set X-Frame-Options "SAMEORIGIN"
Header set X-XSS-Protection "1; mode=block"
```

---

## 📚 TÀI LIỆU THAM KHẢO

### PHP

- [PHP Official Documentation](https://www.php.net/docs.php)
- [PDO Tutorial](https://www.php.net/manual/en/book.pdo.php)
- [Password Hashing](https://www.php.net/manual/en/function.password-hash.php)

### MySQL

- [MySQL Reference Manual](https://dev.mysql.com/doc/)
- [SQL Injection Prevention](https://www.php.net/manual/en/security.database.sql-injection.php)

### Design Patterns

- [Repository Pattern](https://designpatternsphp.readthedocs.io/en/latest/More/Repository/README.html)
- [MVC Pattern](https://www.tutorialspoint.com/design_pattern/mvc_pattern.htm)

---

## 🐛 TROUBLESHOOTING

### Lỗi thường gặp

#### 1. "Access denied for user"

**Nguyên nhân**: Sai thông tin database trong `database.php`
**Giải pháp**: Kiểm tra lại username, password, port MySQL

#### 2. "Call to undefined function"

**Nguyên nhân**: Thiếu `require_once`
**Giải pháp**: Include đúng file chứa class/function

#### 3. "Headers already sent"

**Nguyên nhân**: Có output (echo, space, BOM) trước `header()`
**Giải pháp**: Xóa mọi output trước `header()`, kiểm tra BOM

#### 4. "Session not working"

**Nguyên nhân**: Chưa gọi `session_start()`
**Giải pháp**: Thêm `session_start()` đầu file

#### 5. "Undefined index"

**Nguyên nhân**: Truy cập key không tồn tại trong array
**Giải pháp**: Sử dụng `$_POST['key'] ?? ''` hoặc `isset()`

---

## 📧 LIÊN HỆ & HỖ TRỢ

Nếu có câu hỏi hoặc gặp vấn đề, vui lòng:

1. Kiểm tra phần **Troubleshooting** ở trên
2. Xem lại log lỗi trong file `error.log` hoặc console
3. Tạo issue trên GitHub repository (nếu có)

---

## 📄 LICENSE

MIT License - Free to use for learning and personal projects.

---
