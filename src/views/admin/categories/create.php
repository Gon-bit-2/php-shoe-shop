<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Thêm Danh mục mới</title>
    <link href="/shoe-shop/public/css/style.css" rel="stylesheet">
</head>

<body class="bg-gray-100">
    <?php require_once __DIR__ . '/../partials/navbar.php'; ?>
    <div class="container mx-auto mt-10 p-8 bg-white max-w-lg shadow-md rounded-lg">
        <h1 class="text-3xl font-bold mb-6">Thêm Danh mục mới</h1>
        <form action="/shoe-shop/public/admin/categories/create" method="POST" enctype="multipart/form-data">
            <div class="mb-4">
                <label for="name" class="block text-gray-700 font-bold mb-2">Tên Danh mục</label>
                <input type="text" name="name" id="name" class="shadow border rounded w-full py-2 px-3" required>
            </div>
            <div class="mb-4">
                <label for="image" class="block text-gray-700 font-bold mb-2">Ảnh đại diện</label>
                <input type="file" name="image" id="image" class="shadow border rounded w-full py-2 px-3">
            </div>
            <div class="mb-6">
                <label class="block text-gray-700 font-bold mb-2">Trạng thái</label>
                <label class="inline-flex items-center">
                    <input type="checkbox" name="is_active" value="1" class="mr-2 h-5 w-5" checked>
                    <span>Hiển thị (Hoạt động)</span>
                </label>
            </div>
            <div class="flex items-center justify-end gap-4 mt-8">
                <a href="/shoe-shop/public/admin/categories" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">Hủy</a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Lưu Danh mục</button>
            </div>
        </form>
    </div>
</body>

</html>
