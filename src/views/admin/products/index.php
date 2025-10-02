<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Quản lý Sản phẩm</title>
    <link href="/shoe-shop/public/css/style.css" rel="stylesheet">
</head>

<body class="bg-gray-100">
    <?php require_once __DIR__ . '/../partials/navbar.php'; ?>
    <div class="container mx-auto mt-10 p-8">
        <h1 class="text-3xl font-bold mb-6">Danh sách Sản phẩm</h1>

        <div class="mb-4">
            <a href="/shoe-shop/public/admin/products/create" class="bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 transition">Thêm sản phẩm mới</a>
        </div>

        <div class="bg-white shadow-md rounded-lg">
            <table class="min-w-full leading-normal">
                <thead>
                    <tr>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Tên sản phẩm</th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Ảnh</th>

                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Trạng thái</th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($products)): ?>
                        <tr>
                            <td colspan="4" class="px-5 py-5 border-b border-gray-200 text-center">Chưa có sản phẩm nào.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($products as $product): ?>
                            <tr>
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                    <p class="text-gray-900 whitespace-no-wrap"><?= htmlspecialchars($product->name) ?></p>
                                </td>
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                    <?php if (!empty($product->image_url)): ?>
                                        <img src="/shoe-shop/public<?= htmlspecialchars($product->image_url) ?>" alt="<?= htmlspecialchars($product->name) ?>" class="w-16 h-16 object-cover rounded">
                                    <?php else: ?>
                                        <span class="text-gray-400">Không có ảnh</span>
                                    <?php endif; ?>
                                </td>

                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                    <?php if ($product->is_active): ?>
                                        <span class="relative inline-block px-3 py-1 font-semibold text-green-900 leading-tight">
                                            <span aria-hidden class="absolute inset-0 bg-green-200 opacity-50 rounded-full"></span>
                                            <span class="relative">Hoạt động</span>
                                        </span>
                                    <?php else: ?>
                                        <span class="relative inline-block px-3 py-1 font-semibold text-red-900 leading-tight">
                                            <span aria-hidden class="absolute inset-0 bg-red-400 opacity-50 rounded-full"></span>
                                            <span class="relative text-white">Không hoạt động</span>
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-left">
                                    <a href="/shoe-shop/public/admin/products/edit/<?= $product->id ?>" class="text-indigo-600 hover:text-indigo-900">Sửa</a>
                                    <form action="/shoe-shop/public/admin/products/delete/<?= $product->id ?>" method="POST" class="inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?');">
                                        <button type="submit" class="text-red-600 hover:text-red-900 ml-4">Xóa</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>
