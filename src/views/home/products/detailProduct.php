<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($product->name) ?></title>
    <link href="/shoe-shop/public/css/style.css" rel="stylesheet">
    <style>
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

        .star-rating {
            display: flex;
            flex-direction: row-reverse;
            justify-content: flex-end;
        }

        .star-rating input[type="radio"] {
            display: none;
        }

        .star-rating label {
            font-size: 2rem;
            color: #d1d5db;
            cursor: pointer;
            transition: color 0.2s;
        }

        .star-rating input[type="radio"]:checked~label,
        .star-rating label:hover,
        .star-rating label:hover~label {
            color: #f59e0b;
        }
    </style>
</head>

<body class="bg-gray-50">
    <?php require_once __DIR__ . '/../../layout/header.php'; ?>

    <main class="container mx-auto px-6 py-12">

        <?php if (isset($_SESSION['flash_message'])): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6" role="alert">
                <span class="block sm:inline"><?= htmlspecialchars($_SESSION['flash_message']) ?></span>
            </div>
            <?php unset($_SESSION['flash_message']); ?>
        <?php endif; ?>

        <div class="md:flex md:items-start">
            <div class="w-full md:w-1/2">
                <div class="bg-white rounded-lg shadow-md p-4">
                    <img id="main-product-image" class="w-full h-auto object-cover rounded-lg mb-4" src="<?= htmlspecialchars($product->image_url) ?>" alt="<?= htmlspecialchars($product->name) ?>">

                </div>
            </div>
            <div class="w-full md:w-1/2 md:pl-10 mt-8 md:mt-0">
                <h1 class="text-4xl font-extrabold text-gray-800 mb-2"><?= htmlspecialchars($product->name) ?></h1>

                <div class="mb-6">
                    <span id="product-price" class="text-3xl font-bold text-gray-600">Chọn Size và Màu</span>
                    <p id="stock-status" class="text-sm text-gray-500 mt-1">&nbsp;</p>
                </div>

                <?php if (isset($options['Size'])): ?>
                    <div class="mb-4">
                        <div class="flex justify-between items-center mb-2">
                            <h3 class="text-lg font-semibold text-gray-700">Size:</h3>
                            <a href="#" class="text-sm text-gray-600 hover:underline">Bảng quy đổi kích cỡ</a>
                        </div>
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
                <!-- #region -->
                <div class="mt-6 border-t border-b py-4 grid grid-cols-2 gap-4">
                    <div class="flex items-center text-sm text-gray-600">
                        <svg class="w-5 h-5 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10l2 2h8a1 1 0 001-1zM3 10h10M16 16l4-4h-7m-4-4l-4 4"></path>
                        </svg>
                        <span>Giao hàng nhanh 2-4 ngày</span>
                    </div>
                    <div class="flex items-center text-sm text-gray-600">
                        <svg class="w-5 h-5 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        <span>Miễn phí đổi trả 30 ngày</span>
                    </div>
                </div>

                <div class="mt-8">
                    <h2 class="text-xl font-semibold text-gray-700 mb-2">Mô tả sản phẩm</h2>
                    <p class="text-gray-600 leading-relaxed"><?= nl2br(htmlspecialchars($product->description)) ?></p>
                </div>
            </div>
        </div>

        <div class="mt-12 bg-white p-8 rounded-lg shadow-md">
            <h2 class="text-2xl font-bold text-gray-800 mb-6 border-b pb-4">Đánh giá từ khách hàng</h2>

            <?php
            // PHP tính toán tóm tắt đánh giá (giữ nguyên)
            $totalReviews = count($reviews);
            $averageRating = 0;
            $ratingCounts = [5 => 0, 4 => 0, 3 => 0, 2 => 0, 1 => 0];
            if ($totalReviews > 0) {
                $totalStars = 0;
                foreach ($reviews as $review) {
                    $totalStars += $review->rating;
                    if (isset($ratingCounts[$review->rating])) {
                        $ratingCounts[$review->rating]++;
                    }
                }
                $averageRating = $totalStars / $totalReviews;
            }
            ?>

            <div class="flex flex-col md:flex-row gap-8 mb-8">
                <div class="text-center">
                    <p class="text-5xl font-bold"><?= number_format($averageRating, 1) ?></p>
                    <div class="flex justify-center">
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <svg class="w-6 h-6 <?= $i <= round($averageRating) ? 'text-yellow-400' : 'text-gray-300' ?>" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.956a1 1 0 00.95.69h4.162c.969 0 1.371 1.24.588 1.81l-3.368 2.448a1 1 0 00-.364 1.118l1.286 3.956c.3.921-.755 1.688-1.54 1.118l-3.368-2.448a1 1 0 00-1.175 0l-3.368 2.448c-.784.57-1.838-.197-1.539-1.118l1.286-3.956a1 1 0 00-.364-1.118L2.064 9.383c-.784-.57-.38-1.81.588-1.81h4.162a1 1 0 00.95-.69L9.049 2.927z" />
                            </svg>
                        <?php endfor; ?>
                    </div>
                    <p class="text-gray-600 mt-1"><?= $totalReviews ?> đánh giá</p>
                </div>
                <div class="flex-grow">
                    <?php for ($star = 5; $star >= 1; $star--): ?>
                        <div class="flex items-center gap-2 mb-1">
                            <span class="text-sm font-medium text-gray-600"><?= $star ?> sao</span>
                            <div class="progress-bar flex-grow">
                                <div class="progress-fill" style="width: <?= $totalReviews > 0 ? ($ratingCounts[$star] / $totalReviews * 100) : 0 ?>%;"></div>
                            </div>
                            <span class="text-sm text-gray-500 w-8 text-right"><?= $ratingCounts[$star] ?></span>
                        </div>
                    <?php endfor; ?>
                </div>
            </div>

            <?php if ($canReview): ?>
                <div class="mb-8 p-6 bg-gray-50 rounded-lg border">
                    <h3 class="text-xl font-semibold text-gray-700 mb-4">Viết đánh giá của bạn</h3>
                    <?php if (isset($_SESSION['review_error'])): ?>
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                            <?= htmlspecialchars($_SESSION['review_error']) ?>
                        </div>
                        <?php unset($_SESSION['review_error']); ?>
                    <?php endif; ?>
                    <form action="/shoe-shop/public/product/<?= $product->id ?>/review" method="POST">
                        <input type="hidden" name="order_id" value="<?= $orderIdForReview ?>">
                        <div class="mb-4">
                            <label class="block text-gray-700 font-medium mb-2">Bạn đánh giá sản phẩm này thế nào?</label>
                            <div class="star-rating">
                                <input type="radio" id="5-stars" name="rating" value="5" required /><label for="5-stars">★</label>
                                <input type="radio" id="4-stars" name="rating" value="4" /><label for="4-stars">★</label>
                                <input type="radio" id="3-stars" name="rating" value="3" /><label for="3-stars">★</label>
                                <input type="radio" id="2-stars" name="rating" value="2" /><label for="2-stars">★</label>
                                <input type="radio" id="1-star" name="rating" value="1" /><label for="1-star">★</label>
                            </div>
                        </div>
                        <div class="mb-4">
                            <label for="comment" class="block text-gray-700 font-medium mb-2">Bình luận của bạn</label>
                            <textarea name="comment" id="comment" rows="4" class="w-full border rounded-md p-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none" placeholder="Hãy chia sẻ cảm nhận của bạn về sản phẩm..."></textarea>
                        </div>
                        <button type="submit" class="bg-black text-white font-bold py-2 px-4 rounded-lg hover:bg-gray-900 transition">Gửi đánh giá</button>
                    </form>
                </div>
            <?php endif; ?>

            <div class="space-y-6">
                <?php if (empty($reviews)): ?>
                    <p class="text-gray-600">Chưa có đánh giá nào cho sản phẩm này. Hãy là người đầu tiên!</p>
                <?php else: ?>
                    <?php foreach ($reviews as $review): ?>
                        <div class="border-b pb-4">
                            <div class="flex items-center mb-2">
                                <strong class="font-semibold text-gray-800 mr-3"><?= htmlspecialchars($review->fullname) ?></strong>
                                <div class="flex">
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <svg class="w-5 h-5 <?= $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' ?>" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.956a1 1 0 00.95.69h4.162c.969 0 1.371 1.24.588 1.81l-3.368 2.448a1 1 0 00-.364 1.118l1.286 3.956c.3.921-.755 1.688-1.54 1.118l-3.368-2.448a1 1 0 00-1.175 0l-3.368 2.448c-.784.57-1.838-.197-1.539-1.118l1.286-3.956a1 1 0 00-.364-1.118L2.064 9.383c-.784-.57-.38-1.81.588-1.81h4.162a1 1 0 00.95-.69L9.049 2.927z" />
                                        </svg>
                                    <?php endfor; ?>
                                </div>
                            </div>
                            <?php if (!empty($review->comment)): ?>
                                <p class="text-gray-600 leading-relaxed"><?= nl2br(htmlspecialchars($review->comment)) ?></p>
                            <?php endif; ?>
                            <p class="text-xs text-gray-400 mt-2">Đánh giá vào ngày: <?= date('d/m/Y', strtotime($review->created_at)) ?></p>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
        <div class="mt-12">
            <h2 class="text-2xl font-bold text-gray-800 mb-6 border-b pb-4">Sản phẩm tương tự</h2>
            <?php if (empty($relatedProducts)): ?>
                <p class="text-gray-500">Không có sản phẩm tương tự.</p>
            <?php else: ?>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    <?php foreach ($relatedProducts as $relatedProduct): ?>
                        <div class="bg-white rounded-lg shadow-md overflow-hidden group">
                            <a href="/shoe-shop/public/product/<?= htmlspecialchars($relatedProduct->id) ?>">
                                <div class="relative overflow-hidden">
                                    <img src="<?= htmlspecialchars($relatedProduct->image_url) ?>" alt="<?= htmlspecialchars($relatedProduct->name) ?>" class="w-full h-64 object-cover transition-transform duration-300 group-hover:scale-105">
                                </div>
                                <div class="p-4 text-center">
                                    <h3 class="text-lg font-semibold text-gray-800 truncate"><?= htmlspecialchars($relatedProduct->name) ?></h3>
                                    <p class="text-black font-bold text-xl mt-2"><?= number_format($relatedProduct->price) ?> VNĐ</p>
                                </div>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </main>
    <?php require_once __DIR__ . '/../../layout/footer.php'; ?>

    <script>
        const variantsData = <?= json_encode($variants) ?>;
        const priceElement = document.getElementById('product-price');
        const stockElement = document.getElementById('stock-status');
        const addToCartBtn = document.getElementById('add-to-cart-btn');
        const variantIdInput = document.getElementById('selected-variant-id');
        const quantityInput = document.getElementById('quantity');
        const optionButtons = document.querySelectorAll('.option-btn');
        let currentSelection = {
            'Size': null,
            'Màu sắc': null
        };

        function updateUI() {
            const selectedVariant = variantsData.find(variant => {
                const sizeMatch = !currentSelection['Size'] || variant.attributes['Size'] === currentSelection['Size'];
                const colorMatch = !currentSelection['Màu sắc'] || variant.attributes['Màu sắc'] === currentSelection['Màu sắc'];
                return sizeMatch && colorMatch;
            });
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
            } else {
                priceElement.textContent = 'Chọn Size và Màu';
                stockElement.innerHTML = '&nbsp;';
                addToCartBtn.disabled = true;
                addToCartBtn.textContent = 'Chọn Size và Màu';
                variantIdInput.value = '';
            }
        }
        optionButtons.forEach(button => {
            button.addEventListener('click', () => {
                const group = button.dataset.group;
                const value = button.dataset.value;
                if (button.classList.contains('selected')) {
                    // Nếu đang được chọn, hãy bỏ chọn nó
                    button.classList.remove('selected');
                    currentSelection[group] = null;
                } else {
                    // Nếu không, hãy thực hiện logic chọn như cũ
                    document.querySelectorAll(`.option-btn[data-group="${group}"]`).forEach(btn => btn.classList.remove('selected'));
                    button.classList.add('selected');
                    currentSelection[group] = value;
                }
                updateUI();
            });
        });
    </script>
</body>

</html>
