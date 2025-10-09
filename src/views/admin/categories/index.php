<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Quản lý Danh mục</title>
    <link href="/shoe-shop/public/css/style.css" rel="stylesheet">
</head>

<body class="bg-gray-100">
    <?php require_once __DIR__ . '/../partials/navbar.php'; ?>
    <div class="container mx-auto mt-10 p-8">
        <h1 class="text-3xl font-bold mb-6">Quản lý Danh mục</h1>
        <div class="mb-4">
            <a href="/shoe-shop/public/admin/categories/create" class="bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700">Thêm Danh mục mới</a>
        </div>
        <div class="bg-white shadow-md rounded-lg overflow-x-auto">
            <table class="min-w-full leading-normal">
                <thead>
                    <tr>
                        <th class="px-5 py-3 border-b-2 bg-gray-50 text-left">Ảnh</th>
                        <th class="px-5 py-3 border-b-2 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase">Tên Danh mục</th>
                        <th class="px-5 py-3 border-b-2 bg-gray-50 text-center text-xs font-semibold text-gray-600 uppercase">Trạng thái</th>
                        <th class="px-5 py-3 border-b-2 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($categories as $category): ?>
                        <tr>
                            <td class="px-5 py-5 border-b bg-white text-sm">
                                <img src="<?= htmlspecialchars($category->image_url ?? '/shoe-shop/public/images/placeholder.png') ?>" alt="<?= htmlspecialchars($category->name) ?>" class="w-16 h-16 object-cover rounded">
                            </td>
                            <td class="px-5 py-5 border-b bg-white text-sm font-semibold"><?= htmlspecialchars($category->name) ?></td>
                            <td class="px-5 py-5 border-b bg-white text-sm text-center">
                                <span class="px-2 py-1 font-semibold leading-tight rounded-full <?= $category->is_active ? 'bg-green-200 text-green-800' : 'bg-red-200 text-red-800' ?>">
                                    <?= $category->is_active ? 'Hoạt động' : 'Ẩn' ?>
                                </span>
                            </td>
                            <td class="px-5 py-5 border-b bg-white text-sm">
                                <a href="/shoe-shop/public/admin/categories/edit/<?= $category->id ?>" class="text-indigo-600 hover:text-indigo-900">Sửa</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>
