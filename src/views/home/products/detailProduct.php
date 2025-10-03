<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($product->name) ?></title>
    <link href="/shoe-shop/public/css/style.css" rel="stylesheet">
    <style>
        /* CSS tùy chỉnh cho các nút chọn thuộc tính */
        .option-btn {
            cursor: pointer;
            border: 1px solid #d1d5db;
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            transition: all 0.2s ease-in-out;
        }

        .option-btn.selected {
            border-color: #4f46e5;
            background-color: #eef2ff;
            color: #4f46e5;
            font-weight: 600;
        }

        .option-btn.disabled {
            cursor: not-allowed;
            background-color: #f3f4f6;
            color: #9ca3af;
            border-color: #e5e7eb;
            text-decoration: line-through;
        }
    </style>
</head>

<body class="bg-gray-50">
    <?php require_once __DIR__ . '/../../layout/header.php'; ?>
    <main class="container mx-auto px-6 py-12">
        <div class="md:flex md:items-start">
            <div class="w-full md:w-1/2">
                <div class="bg-white rounded-lg shadow-md p-4">
                    <img id="product-image" class="w-full h-auto object-cover rounded-lg" src="<?= htmlspecialchars($product->image_url) ?>" alt="<?= htmlspecialchars($product->name) ?>">
                </div>
            </div>

            <div class="w-full md:w-1/2 md:pl-10 mt-8 md:mt-0">
                <h1 class="text-4xl font-extrabold text-gray-800 mb-2"><?= htmlspecialchars($product->name) ?></h1>

                <div class="mb-6">
                    <span id="product-price" class="text-3xl font-bold text-indigo-600">Chọn Size và Màu</span>
                    <p id="stock-status" class="text-sm text-gray-500 mt-1">&nbsp;</p>
                </div>

                <?php if (isset($options['Size'])): ?>
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-700 mb-2">Size:</h3>
                        <div id="size-options" class="flex flex-wrap gap-2">
                            <?php foreach ($options['Size'] as $size): ?>
                                <button class="option-btn" data-group="Size" data-value="<?= htmlspecialchars($size) ?>"><?= htmlspecialchars($size) ?></button>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if (isset($options['Màu sắc'])): ?>
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-700 mb-2">Màu sắc:</h3>
                        <div id="color-options" class="flex flex-wrap gap-2">
                            <?php foreach ($options['Màu sắc'] as $color): ?>
                                <button class="option-btn" data-group="Màu sắc" data-value="<?= htmlspecialchars($color) ?>"><?= htmlspecialchars($color) ?></button>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <form id="add-to-cart-form" action="/shoe-shop/public/cart/add" method="POST">
                    <input type="hidden" name="variant_id" id="selected-variant-id">

                    <div class="flex items-center mb-6">
                        <label for="quantity" class="mr-4 text-gray-700 font-semibold">Số lượng:</label>
                        <input type="number" id="quantity" name="quantity" value="1" min="1" class="w-20 text-center border rounded-md py-2 px-3">
                    </div>

                    <button id="add-to-cart-btn" type="submit" class="w-full bg-indigo-600 text-white font-bold py-3 px-6 rounded-lg hover:bg-indigo-700 transition duration-300 disabled:bg-gray-400 disabled:cursor-not-allowed" disabled>
                        Chọn Size và Màu
                    </button>
                </form>

                <div class="mt-8">
                    <h2 class="text-xl font-semibold text-gray-700 mb-2">Mô tả sản phẩm</h2>
                    <p class="text-gray-600 leading-relaxed"><?= nl2br(htmlspecialchars($product->description)) ?></p>
                </div>
            </div>
        </div>
    </main>
    <?php require_once __DIR__ . '/../../layout/footer.php'; ?>

    <script>
        // 1. Lấy dữ liệu biến thể từ PHP và chuyển thành đối tượng JavaScript
        const variantsData = <?= json_encode($variants) ?>;

        // 2. Lấy các phần tử HTML cần tương tác
        const priceElement = document.getElementById('product-price');
        const stockElement = document.getElementById('stock-status');
        const addToCartBtn = document.getElementById('add-to-cart-btn');
        const variantIdInput = document.getElementById('selected-variant-id');
        const quantityInput = document.getElementById('quantity');
        const optionButtons = document.querySelectorAll('.option-btn');

        // 3. Biến để lưu lựa chọn hiện tại của người dùng
        let currentSelection = {
            'Size': null,
            'Màu sắc': null
        };

        // 4. Hàm cập nhật trạng thái giao diện
        function updateUI() {
            // Tìm biến thể phù hợp với lựa chọn
            const selectedVariant = variantsData.find(variant => {
                const sizeMatch = !currentSelection['Size'] || variant.attributes['Size'] === currentSelection['Size'];
                const colorMatch = !currentSelection['Màu sắc'] || variant.attributes['Màu sắc'] === currentSelection['Màu sắc'];
                return sizeMatch && colorMatch;
            });

            // Nếu tìm thấy một biến thể duy nhất khi đã chọn đủ
            if (currentSelection['Size'] && currentSelection['Màu sắc'] && selectedVariant) {
                priceElement.textContent = new Intl.NumberFormat('vi-VN', {
                    style: 'currency',
                    currency: 'VND'
                }).format(selectedVariant.price);

                if (selectedVariant.stock > 0) {
                    stockElement.textContent = `Còn lại: ${selectedVariant.stock} sản phẩm`;
                    stockElement.classList.remove('text-red-500');
                    addToCartBtn.disabled = false;
                    addToCartBtn.textContent = 'Thêm vào giỏ hàng';
                    quantityInput.max = selectedVariant.stock;
                } else {
                    stockElement.textContent = 'Hết hàng';
                    stockElement.classList.add('text-red-500');
                    addToCartBtn.disabled = true;
                    addToCartBtn.textContent = 'Hết hàng';
                }
                variantIdInput.value = selectedVariant.id;

            } else { // Nếu chưa chọn đủ hoặc không tìm thấy
                priceElement.textContent = 'Chọn Size và Màu';
                stockElement.innerHTML = '&nbsp;';
                addToCartBtn.disabled = true;
                addToCartBtn.textContent = 'Chọn Size và Màu';
                variantIdInput.value = '';
            }
        }

        // 5. Thêm sự kiện click cho tất cả các nút lựa chọn
        optionButtons.forEach(button => {
            button.addEventListener('click', () => {
                const group = button.dataset.group;
                const value = button.dataset.value;

                // Xóa lớp 'selected' khỏi các nút khác trong cùng một nhóm
                document.querySelectorAll(`.option-btn[data-group="${group}"]`).forEach(btn => btn.classList.remove('selected'));

                // Thêm lớp 'selected' cho nút vừa click
                button.classList.add('selected');

                // Cập nhật lựa chọn hiện tại
                currentSelection[group] = value;

                // Cập nhật giao diện
                updateUI();
            });
        });
    </script>
</body>

</html>
