# 🛍️ Shoe Shop - E-commerce Platform

Một nền tảng thương mại điện tử hoàn chỉnh được xây dựng bằng PHP thuần và TailwindCSS, chuyên về bán giày. Dự án bao gồm các tính năng quản lý sản phẩm, giỏ hàng, đặt hàng, thanh toán và hệ thống đánh giá sản phẩm.

## ✨ Tính năng

### 👥 Người dùng

- **Đăng ký & Đăng nhập**: Hệ thống xác thực an toàn
- **Quản lý hồ sơ**: Cập nhật thông tin cá nhân
- **Khôi phục mật khẩu**: Reset password qua email
- **Duyệt sản phẩm**: Xem danh sách sản phẩm với phân trang và lọc
- **Chi tiết sản phẩm**: Xem thông tin chi tiết, hình ảnh, biến thể
- **Giỏ hàng**: Thêm, cập nhật, xóa sản phẩm khỏi giỏ hàng
- **Voucher**: Áp dụng mã giảm giá cho đơn hàng
- **Đặt hàng**: Quy trình thanh toán hoàn chỉnh
- **Lịch sử mua hàng**: Xem các đơn hàng đã đặt
- **Đánh giá sản phẩm**: Viết và xem review từ khách hàng

### 👨‍💼 Admin

- **Dashboard**: Tổng quan về đơn hàng, doanh thu
- **Quản lý sản phẩm**: Thêm, sửa, xóa sản phẩm
- **Quản lý danh mục**: CRUD danh mục sản phẩm
- **Quản lý đơn hàng**: Xem chi tiết, cập nhật trạng thái đơn hàng
- **Quản lý voucher**: Tạo và quản lý mã giảm giá
- **Quản lý người dùng**: Xem và chỉnh sửa thông tin người dùng

## 🛠️ Công nghệ sử dụng

### Backend

- **PHP**: Phiên bản 8.0 trở lên
- **MySQL**: Cơ sở dữ liệu
- **PDO**: Database abstraction layer
- **Composer**: Quản lý dependencies
  - `phpmailer/phpmailer`: Gửi email
  - `vlucas/phpdotenv`: Quản lý biến môi trường

### Frontend

- **TailwindCSS**: Framework CSS utility-first
- **Vanilla JavaScript**: JavaScript thuần
- **HTML5/PHP**: Templating

### Kiến trúc

- **MVC Pattern**: Model-View-Controller
- **Repository Pattern**: Data access layer
- **Middleware Pattern**: Xử lý request/response
- **Service Layer**: Business logic

## 📋 Yêu cầu hệ thống

- PHP >= 8.0
- MySQL >= 5.7
- Composer
- NPM (cho TailwindCSS)
- Web Server (Apache/Nginx)

## 🚀 Cài đặt

### 1. Clone repository

```bash
git clone https://github.com/your-username/shoe-shop.git
cd shoe-shop
```

### 2. Cài đặt dependencies

```bash
# Cài đặt PHP packages
composer install

# Cài đặt Node packages (cho TailwindCSS)
npm install
```

### 3. Cấu hình môi trường

Tạo file `.env` trong thư mục gốc:

```env
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=shoe_shop
DB_USERNAME=root
DB_PASSWORD=your_password

# Cấu hình email (PHPMailer)
SMTP_HOST=smtp.gmail.com
SMTP_USER=your_email@gmail.com
SMTP_PASSWORD=your_app_password
SMTP_PORT=587
```

### 4. Thiết lập database

Chạy file setup:

```bash
php migrations/setup.php
```

Hoặc import file SQL nếu có.

### 5. Khởi tạo database schema

Chạy các migrations theo thứ tự:

```bash
php migrations/20250918_add_product_relations.php
php migrations/20250923_products_table.php
php migrations/20250930_create_orders_table.php
# ... và các file migration khác
```

### 6. Build CSS với TailwindCSS

```bash
# Development mode (watch)
npm run twind:dev

# Build production
npx tailwindcss -i ./public/css/input.css -o ./public/css/style.css --minify
```

### 7. Cấu hình web server

#### Apache

Tạo virtual host hoặc sử dụng DocumentRoot trỏ đến thư mục `public`:

```apache
<VirtualHost *:80>
    ServerName shoe-shop.local
    DocumentRoot "D:/App/Xampp/htdocs/shoe-shop/public"

    <Directory "D:/App/Xampp/htdocs/shoe-shop/public">
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

#### Nginx

```nginx
server {
    listen 80;
    server_name shoe-shop.local;
    root D:/App/Xampp/htdocs/shoe-shop/public;
    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass 127.0.0.1:9000;
        fastcgi_index index.php;
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
    }
}
```

### 8. Cấu hình hosts (Windows)

Thêm vào `C:\Windows\System32\drivers\etc\hosts`:

```
127.0.0.1   shoe-shop.local
```

## 📁 Cấu trúc dự án

```
shoe-shop/
├── public/                 # Public directory (web root)
│   ├── css/               # Stylesheets
│   ├── images/            # Static images
│   ├── js/                # JavaScript files
│   └── index.php          # Entry point
├── src/
│   ├── config/            # Configuration files
│   │   └── routes.php     # Route definitions
│   ├── controllers/       # Controllers (MVC)
│   ├── models/            # Models & Repositories
│   │   ├── repositories/  # Data access layer
│   │   └── *.php         # Model classes
│   ├── services/          # Business logic layer
│   ├── middleware/        # Request middleware
│   ├── helper/            # Helper functions
│   └── views/             # Views (Templates)
│       ├── admin/         # Admin views
│       ├── auth/          # Authentication views
│       ├── home/          # User views
│       └── layout/        # Layout templates
├── migrations/            # Database migrations
├── vendor/                # Composer dependencies
├── node_modules/          # NPM dependencies
├── composer.json          # PHP dependencies
├── package.json           # Node dependencies
├── tailwind.config.js     # TailwindCSS config
└── .env                   # Environment variables
```

## 🎯 Sử dụng

### Trang chủ

Truy cập: `http://shoe-shop.local/` hoặc `http://localhost/shoe-shop/public/`

### Đăng ký tài khoản

- Truy cập `/register`
- Điền thông tin và tạo tài khoản

### Đăng nhập admin

- Đăng nhập với tài khoản có quyền admin
- Truy cập `/admin` để vào dashboard

## 🔧 Development

### Chạy TailwindCSS watch mode

```bash
npm run twind:dev
```

File CSS sẽ tự động cập nhật khi bạn chỉnh sửa.

### Database Migrations

Các file migration được tổ chức theo ngày tháng và có thể chạy thủ công hoặc tích hợp vào workflow CI/CD.

### Code Style

Dự án sử dụng PSR standards cho code organization.

## 🧪 Testing

Để test ứng dụng:

1. Tạo dữ liệu mẫu trong database
2. Đăng ký tài khoản test
3. Test flow mua hàng hoàn chỉnh
4. Test các tính năng admin

## 🤝 Đóng góp

Contributions are welcome! Please feel free to submit a Pull Request.

1. Fork the project
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## 📝 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 👨‍💻 Tác giả

- **Gon Dev** - [@Gon-bit-2](https://github.com/Gon-bit-2)

## 🙏 Acknowledgments

- TailwindCSS team
- PHPMailer contributors
- PHP community

## 📧 Liên hệ

If you have any questions or suggestions, please feel free to open an issue or contact the maintainers.

---

⭐ Nếu dự án này hữu ích với bạn, hãy give it a star!
