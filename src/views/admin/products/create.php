<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Thêm Sản phẩm mới</title>
    <link href="../../css/style.css" rel="stylesheet">
</head>

<body class="bg-gray-100">
    <div class="container mx-auto mt-10 p-8 bg-white shadow-md rounded-lg">
        <h1 class="text-3xl font-bold mb-6">Thêm Sản phẩm mới</h1>

        <?php if (isset($errorMessage)): ?>
            <div class="bg-red-100 text-red-700 p-4 rounded mb-4"><?= $errorMessage ?></div>
        <?php endif; ?>

        <form action="/shoe-shop/public/admin/products/create" method="POST">
            <div class="mb-4">
                <label for="name" class="block text-gray-700 font-bold mb-2">Tên sản phẩm:</label>
                <input type="text" name="name" id="name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
            </div>
            <div class="mb-4">
                <label for="base_price" class="block text-gray-700 font-bold mb-2">Giá gốc:</label>
                <input type="number" step="0.01" name="base_price" id="base_price" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" required>
            </div>
            <div class="mb-4">
                <label for="short_desc" class="block text-gray-700 font-bold mb-2">Mô tả ngắn:</label>
                <textarea name="short_desc" id="short_desc" rows="3" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700"></textarea>
            </div>
            <div class="mb-4">
                <label for="description" class="block text-gray-700 font-bold mb-2">Mô tả chi tiết:</label>
                <textarea name="description" id="description" rows="6" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700"></textarea>
            </div>
            <div class="mb-6">
                <label class="block text-gray-700 font-bold mb-2">Danh mục:</label>
                <?php foreach ($categories as $category): ?>
                    <label class="inline-flex items-center mr-4">
                        <input type="checkbox" name="categories[]" value="<?= $category->id ?>" class="h-5 w-5 text-blue-600 rounded focus:ring-blue-500">
                        <span class="ml-2 text-gray-700"><?= htmlspecialchars($category->name) ?></span>
                    </label>
                <?php endforeach; ?>
            </div>
            <div class="flex items-center justify-between">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Lưu sản phẩm
                </button>
                <a href="/shoe-shop/public/admin/products" class="bg-red-500 hover:bg-red-700 text-white font-bold text-sm px-4 py-2 rounded">
                    Hủy
                </a>
            </div>
        </form>
    </div>
</body>

</html>
