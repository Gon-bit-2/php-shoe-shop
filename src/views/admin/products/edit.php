<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Sửa Sản phẩm</title>
    <link href="/shoe-shop/public/css/style.css" rel="stylesheet">
</head>

<body class="bg-gray-100">
    <div class="container mx-auto mt-10 p-8 bg-white shadow-md rounded-lg">
        <h1 class="text-3xl font-bold mb-6">Sửa Sản phẩm</h1>

        <?php if (isset($errorMessage) && !empty($errorMessage)): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <strong class="font-bold">Lỗi!</strong>
                <span class="block sm:inline"><?= htmlspecialchars($errorMessage) ?></span>
            </div>
        <?php endif; ?>

        <form action="/shoe-shop/public/admin/products/edit/<?= $product->id ?>" method="POST" enctype="multipart/form-data" novalidate>
            <div class="mb-4">
                <label for="name" class="block text-gray-700 font-bold mb-2">Tên sản phẩm:</label>
                <input type="text" name="name" id="name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" value="<?= htmlspecialchars($product->name) ?>" required>
            </div>

            <div class="mb-4">
                <label for="image_url" class="block text-gray-700 font-bold mb-2">Ảnh đại diện sản phẩm:</label>
                <input type="file" name="image" id="image_url" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div class="mb-4">
                    <label for="price" class="block text-gray-700 font-bold mb-2">Giá bán (VNĐ):</label>
                    <input type="number" step="1000" name="price" id="price" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" value="<?= htmlspecialchars($product->price) ?>" required>
                </div>

                <div class="mb-4">
                    <label for="stock" class="block text-gray-700 font-bold mb-2">Số lượng tồn kho:</label>
                    <input type="number" name="stock" id="stock" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" value="<?= htmlspecialchars($product->stock) ?>" required>
                </div>
            </div>

            <div class="mb-4">
                <label for="description" class="block text-gray-700 font-bold mb-2">Mô tả chi tiết:</label>
                <textarea name="description" id="description" rows="6" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700"><?= htmlspecialchars($product->description) ?></textarea>
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 font-bold mb-2">Danh mục:</label>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
                    <?php if (!empty($categories)): ?>
                        <?php foreach ($categories as $category): ?>
                            <?php
                            $oldCategoryIDs = $categoryIDs;
                            $isChecked = in_array($category->id, $oldCategoryIDs);
                            ?>
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="categories[]" value="<?= $category->id ?>" class="h-5 w-5 text-blue-600 rounded" <?= $isChecked ? 'checked' : '' ?>>
                                <span class="ml-2 text-gray-700"><?= htmlspecialchars($category->name) ?></span>
                            </label>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 font-bold mb-2">Trạng thái:</label>
                <label class="inline-flex items-center">
                    <?php
                    $isActive = $product->is_active;
                    ?>
                    <input type="checkbox" name="is_active" id="is_active" value="1" class="h-5 w-5 text-blue-600 rounded" <?= $isActive ? 'checked' : '' ?>>
                    <span class="ml-2 text-gray-700">Kích hoạt sản phẩm</span>
                </label>
            </div>

            <div class="flex items-center justify-end gap-4">
                <a href="/shoe-shop/public/admin/products" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
                    Hủy
                </a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Lưu sản phẩm
                </button>
            </div>
        </form>
    </div>
    </div>
</body>

</html>
