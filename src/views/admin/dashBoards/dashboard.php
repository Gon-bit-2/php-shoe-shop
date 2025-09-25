<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link href="/shoe-shop/public/css/style.css" rel="stylesheet">
</head>

<body class="bg-gray-100">
    <?php require_once __DIR__ . '/../partials/navbar.php'; ?>
    <div class="container mx-auto mt-10 p-8">
        <h1 class="text-3xl font-bold mb-6">Chào mừng đến với Trang Quản trị</h1>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <a href="/shoe-shop/public/admin/products" class="block p-6 bg-white border border-gray-200 rounded-lg shadow hover:bg-gray-100 transition">
                <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900">Quản lý Sản phẩm</h5>
                <p class="font-normal text-gray-700">Thêm, sửa, xóa và xem danh sách các sản phẩm.</p>
            </a>

            <a href="#" class="block p-6 bg-white border border-gray-200 rounded-lg shadow hover:bg-gray-100 transition">
                <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900">Quản lý Đơn hàng</h5>
                <p class="font-normal text-gray-700">Xem và cập nhật trạng thái các đơn hàng của khách.</p>
            </a>

            <a href="#" class="block p-6 bg-white border border-gray-200 rounded-lg shadow hover:bg-gray-100 transition">
                <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900">Quản lý Người dùng</h5>
                <p class="font-normal text-gray-700">Xem danh sách và quản lý tài khoản khách hàng.</p>
            </a>

        </div>

        <div class="mt-8">
            <a href="/shoe-shop/public/logout" class="text-red-600 hover:text-red-800 font-semibold">Đăng xuất</a>
        </div>
    </div>
</body>

</html>
