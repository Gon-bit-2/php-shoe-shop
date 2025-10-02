<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Thêm Sản phẩm mới</title>
    <link href="/shoe-shop/public/css/style.css" rel="stylesheet">
</head>

<body class="bg-gray-100">
    <?php require_once __DIR__ . '/../partials/navbar.php'; ?>
    <div class="container mx-auto mt-10 p-8 bg-white shadow-md rounded-lg">
        <h1 class="text-3xl font-bold mb-6">Thêm Sản phẩm mới</h1>

        <?php if (isset($errorMessage) && !empty($errorMessage)): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <?php echo htmlspecialchars($errorMessage); ?>
            </div>
        <?php endif; ?>

        <form action="/shoe-shop/public/admin/products/create" method="POST" enctype="multipart/form-data">
            <div class="mb-4">
                <label for="name" class="block text-gray-700 font-bold mb-2">Tên sản phẩm:</label>
                <input type="text" name="name" id="name"
                    value="<?php echo isset($oldInput['name']) ? htmlspecialchars($oldInput['name']) : ''; ?>"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" required>
            </div>
            <div class="mb-4">
                <label for="image" class="block text-gray-700 font-bold mb-2">Ảnh đại diện sản phẩm:</label>
                <input type="file" name="image" id="image" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700">
            </div>
            <div class="mb-4">
                <label for="description" class="block text-gray-700 font-bold mb-2">Mô tả chi tiết:</label>
                <textarea name="description" id="description" rows="6"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700"><?php echo isset($oldInput['description']) ? htmlspecialchars($oldInput['description']) : ''; ?></textarea>
            </div>

            <!-- Danh mục -->
            <div class="mb-6">
                <label class="block text-gray-700 font-bold mb-2">Danh mục:</label>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
                    <?php if (isset($categories) && !empty($categories)): ?>
                        <?php foreach ($categories as $category): ?>
                            <label class="flex items-center">
                                <input type="checkbox" name="categories[]" value="<?php echo $category->id; ?>"
                                    <?php echo (isset($oldInput['categories']) && in_array($category->id, $oldInput['categories'])) ? 'checked' : ''; ?>
                                    class="mr-2">
                                <span class="text-sm"><?php echo htmlspecialchars($category->name); ?></span>
                            </label>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-gray-500">Không có danh mục nào</p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Trạng thái -->
            <div class="mb-6">
                <label class="block text-gray-700 font-bold mb-2">Trạng thái:</label>
                <div class="flex space-x-4">
                    <label class="flex items-center">
                        <input type="radio" name="is_active" value="1"
                            <?php echo (!isset($oldInput['is_active']) || $oldInput['is_active'] == '1') ? 'checked' : ''; ?>
                            class="mr-2">
                        <span>Kích hoạt</span>
                    </label>
                    <label class="flex items-center">
                        <input type="radio" name="is_active" value="0"
                            <?php echo (isset($oldInput['is_active']) && $oldInput['is_active'] == '0') ? 'checked' : ''; ?>
                            class="mr-2">
                        <span>Không kích hoạt</span>
                    </label>
                </div>
            </div>

            <div class="mt-8 border-t pt-6">
                <h2 class="text-2xl font-bold mb-4">Tạo các biến thể sản phẩm</h2>

                <div class="mb-6">
                    <label class="block text-gray-700 font-bold mb-2">Chọn các Size có sẵn:</label>
                    <div id="sizes-checkbox-container" class="grid grid-cols-4 md:grid-cols-6 gap-2">
                        <?php foreach ($sizes as $size): ?>
                            <label class="flex items-center p-2 border rounded-md hover:bg-gray-50 cursor-pointer">
                                <input type="checkbox" name="selected_sizes[]" value="<?= htmlspecialchars($size->value) ?>" class="mr-2 h-4 w-4">
                                <span class="text-sm"><?= htmlspecialchars($size->value) ?></span>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 font-bold mb-2">Chọn các Màu sắc có sẵn:</label>
                    <div id="colors-checkbox-container" class="grid grid-cols-4 md:grid-cols-6 gap-2">
                        <?php foreach ($colors as $color): ?>
                            <label class="flex items-center p-2 border rounded-md hover:bg-gray-50 cursor-pointer">
                                <input type="checkbox" name="selected_colors[]" value="<?= htmlspecialchars($color->value) ?>" class="mr-2 h-4 w-4">
                                <span class="text-sm"><?= htmlspecialchars($color->value) ?></span>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="flex justify-end">
                    <button type="button" id="generate-variants-btn" class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded">
                        Tạo biến thể từ các lựa chọn
                    </button>
                </div>

                <div id="variants-container" class="mt-6 space-y-4">
                </div>
            </div>
            <div class="flex items-center justify-end gap-4 mt-8">
                <a href="/shoe-shop/public/admin/products" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
                    Hủy
                </a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Lưu sản phẩm
                </button>
            </div>
        </form>
    </div>




    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const generateBtn = document.getElementById('generate-variants-btn');
            const variantsContainer = document.getElementById('variants-container');
            let variantIndex = 0;

            generateBtn.addEventListener('click', function() {
                // Lấy giá trị từ các checkbox đã được tích
                const selectedSizes = Array.from(document.querySelectorAll('input[name="selected_sizes[]"]:checked')).map(cb => cb.value);
                const selectedColors = Array.from(document.querySelectorAll('input[name="selected_colors[]"]:checked')).map(cb => cb.value);

                if (selectedSizes.length === 0 || selectedColors.length === 0) {
                    alert('Vui lòng chọn ít nhất một Size và một Màu sắc.');
                    return;
                }

                variantsContainer.innerHTML = '';
                variantIndex = 0;

                selectedSizes.forEach(size => {
                    selectedColors.forEach(color => {
                        const newRow = createVariantRow(size, color, variantIndex);
                        variantsContainer.appendChild(newRow);
                        variantIndex++;
                    });
                });
            });

            function createVariantRow(size, color, index) {
                const div = document.createElement('div');
                div.className = 'variant-row border p-4 rounded-md relative grid grid-cols-1 md:grid-cols-4 gap-4 items-center';

                div.innerHTML = `
            <div>
                <label class="block text-sm font-medium text-gray-700">Biến thể</label>
                <p class="font-bold mt-1">${size} / ${color}</p>
                <input type="hidden" name="variants[${index}][size]" value="${size}">
                <input type="hidden" name="variants[${index}][color]" value="${color}">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Giá (VNĐ)</label>
                <input type="number" name="variants[${index}][price]" class="mt-1 block w-full py-2 px-3 border rounded-md" placeholder="Nhập giá" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Tồn kho</label>
                <input type="number" name="variants[${index}][stock]" class="mt-1 block w-full py-2 px-3 border rounded-md" placeholder="Nhập số lượng" required>
            </div>
            <button type="button" class="remove-variant-btn text-red-500 hover:text-red-700 justify-self-end">&times; Xóa</button>
        `;
                return div;
            }

            variantsContainer.addEventListener('click', function(e) {
                if (e.target && e.target.classList.contains('remove-variant-btn')) {
                    e.target.closest('.variant-row').remove();
                }
            });
        });
    </script>
</body>

</html>
