<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Sửa Sản phẩm: <?= htmlspecialchars($product->name) ?></title>
    <link href="/shoe-shop/public/css/style.css" rel="stylesheet">
</head>

<body class="bg-gray-100">
    <?php require_once __DIR__ . '/../partials/navbar.php'; ?>
    <div class="container mx-auto mt-10 p-8 bg-white shadow-md rounded-lg">
        <h1 class="text-3xl font-bold mb-6">Sửa Sản phẩm</h1>

        <?php if (isset($errorMessage) && !empty($errorMessage)): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <?= htmlspecialchars($errorMessage); ?>
            </div>
        <?php endif; ?>

        <form action="/shoe-shop/public/admin/products/edit/<?= $product->id ?>" method="POST" enctype="multipart/form-data">

            <div class="mb-4">
                <label for="name" class="block text-gray-700 font-bold mb-2">Tên sản phẩm:</label>
                <input type="text" name="name" id="name" value="<?= htmlspecialchars($product->name) ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" required>
            </div>
            <div class="mb-4">
                <label for="description" class="block text-gray-700 font-bold mb-2">Mô tả chi tiết:</label>
                <textarea name="description" id="description" rows="6" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700"><?= htmlspecialchars($product->description) ?></textarea>
            </div>
            <div class="mb-4">
                <label for="image" class="block text-gray-700 font-bold mb-2">Ảnh đại diện sản phẩm:</label>
                <input type="file" name="image" id="image" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700">
                <?php if ($product->image_url): ?>
                    <div class="mt-2">
                        <img src="<?= htmlspecialchars($product->image_url) ?>" alt="Ảnh hiện tại" class="w-32 h-32 object-cover rounded">
                        <p class="text-xs text-gray-500">Ảnh hiện tại. Tải lên ảnh mới sẽ thay thế ảnh này.</p>
                    </div>
                <?php endif; ?>
            </div>
            <div class="mb-6">
                <label class="block text-gray-700 font-bold mb-2">Danh mục:</label>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
                    <?php foreach ($categories as $category): ?>
                        <label class="flex items-center">
                            <input type="checkbox" name="categories[]" value="<?= $category->id; ?>" <?= in_array($category->id, $categoryIDs) ? 'checked' : '' ?> class="mr-2">
                            <span><?= htmlspecialchars($category->name); ?></span>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="mb-6">
                <label class="block text-gray-700 font-bold mb-2">Trạng thái:</label>
                <label class="inline-flex items-center mr-4">
                    <input type="radio" name="is_active" value="1" <?= $product->is_active ? 'checked' : '' ?> class="mr-2">
                    <span>Kích hoạt</span>
                </label>
                <label class="inline-flex items-center">
                    <input type="radio" name="is_active" value="0" <?= !$product->is_active ? 'checked' : '' ?> class="mr-2">
                    <span>Không kích hoạt</span>
                </label>
            </div>
            <div class="mt-8 border-t pt-6">
                <h2 class="text-2xl font-bold mb-4">Quản lý các biến thể</h2>

                <div class="mb-6 p-4 border rounded-md bg-gray-50">
                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2">Thêm biến thể mới (Chọn Size & Màu):</label>
                        <div class="grid grid-cols-4 md:grid-cols-8 gap-2">
                            <?php foreach ($sizes as $size): ?>
                                <label class="flex items-center p-2 border rounded-md hover:bg-gray-100 cursor-pointer">
                                    <input type="checkbox" name="selected_sizes[]" value="<?= htmlspecialchars($size->value) ?>" class="mr-2 h-4 w-4">
                                    <span><?= htmlspecialchars($size->value) ?></span>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <div class="mb-4">
                        <div class="grid grid-cols-4 md:grid-cols-8 gap-2">
                            <?php foreach ($colors as $color): ?>
                                <label class="flex items-center p-2 border rounded-md hover:bg-gray-100 cursor-pointer">
                                    <input type="checkbox" name="selected_colors[]" value="<?= htmlspecialchars($color->value) ?>" class="mr-2 h-4 w-4">
                                    <span><?= htmlspecialchars($color->value) ?></span>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <div class="flex justify-end">
                        <button type="button" id="generate-variants-btn" class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded">
                            Thêm các biến thể đã chọn
                        </button>
                    </div>
                </div>

                <h3 class="text-xl font-bold mt-6 mb-4">Các biến thể hiện có:</h3>
                <div id="variants-container" class="space-y-4">
                    <?php foreach ($variants as $index => $variant): ?>
                        <div class="variant-row border p-4 rounded-md relative grid grid-cols-1 md:grid-cols-4 gap-4 items-center">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Biến thể</label>
                                <p class="font-bold mt-1">
                                    <?= htmlspecialchars($variant['attributes']['Size'] ?? '') ?> / <?= htmlspecialchars($variant['attributes']['Màu sắc'] ?? '') ?>
                                </p>
                                <input type="hidden" name="variants[<?= $index ?>][size]" value="<?= htmlspecialchars($variant['attributes']['Size'] ?? '') ?>">
                                <input type="hidden" name="variants[<?= $index ?>][color]" value="<?= htmlspecialchars($variant['attributes']['Màu sắc'] ?? '') ?>">
                                <input type="hidden" name="variants[<?= $index ?>][id]" value="<?= $variant['id'] ?>">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Giá (VNĐ)</label>
                                <input type="number" name="variants[<?= $index ?>][price]" value="<?= htmlspecialchars($variant['price']) ?>" class="mt-1 block w-full py-2 px-3 border rounded-md" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Tồn kho</label>
                                <input type="number" name="variants[<?= $index ?>][stock]" value="<?= htmlspecialchars($variant['stock']) ?>" class="mt-1 block w-full py-2 px-3 border rounded-md" required>
                            </div>
                            <button type="button" class="remove-variant-btn text-red-500 hover:text-red-700 justify-self-end">&times; Xóa</button>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="flex items-center justify-end gap-4 mt-8">
                <a href="/shoe-shop/public/admin/products" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">Hủy</a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Lưu thay đổi</button>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const generateBtn = document.getElementById('generate-variants-btn');
            const variantsContainer = document.getElementById('variants-container');
            let variantIndex = variantsContainer.querySelectorAll('.variant-row').length;
            generateBtn.addEventListener('click', function() {
                const selectedSizes = Array.from(document.querySelectorAll('input[name="selected_sizes[]"]:checked')).map(cb => cb.value);
                const selectedColors = Array.from(document.querySelectorAll('input[name="selected_colors[]"]:checked')).map(cb => cb.value);
                if (selectedSizes.length === 0 || selectedColors.length === 0) {
                    alert('Vui lòng chọn ít nhất một Size và một Màu sắc để thêm.');
                    return;
                }
                selectedSizes.forEach(size => {
                    selectedColors.forEach(color => {
                        const exists = Array.from(variantsContainer.querySelectorAll('.variant-row')).some(row => {
                            const existingSize = row.querySelector('input[name*="[size]"]').value;
                            const existingColor = row.querySelector('input[name*="[color]"]').value;
                            return existingSize === size && existingColor === color;
                        });
                        if (!exists) {
                            const newRow = createVariantRow(size, color, variantIndex);
                            variantsContainer.appendChild(newRow);
                            variantIndex++;
                        }
                    });
                });
                document.querySelectorAll('input[name="selected_sizes[]"]:checked').forEach(cb => cb.checked = false);
                document.querySelectorAll('input[name="selected_colors[]"]:checked').forEach(cb => cb.checked = false);
            });

            function createVariantRow(size, color, index) {
                const div = document.createElement('div');
                div.className = 'variant-row border p-4 rounded-md relative grid grid-cols-1 md:grid-cols-4 gap-4 items-center';
                div.innerHTML = `
            <div>
                <label class="block text-sm font-medium text-gray-700">Biến thể (Mới)</label>
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
