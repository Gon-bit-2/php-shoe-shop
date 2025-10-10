<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Giỏ hàng của bạn</title>
    <link href="/shoe-shop/public/css/style.css" rel="stylesheet">
</head>

<body class="bg-gray-100">
    <?php require_once __DIR__ . '/../../layout/header.php'; ?>

    <main class="container mx-auto px-6 py-12">
        <h1 class="text-3xl font-bold text-center mb-8">Giỏ hàng của bạn</h1>

        <?php if (isset($_SESSION['cart_error_message'])): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline"><?= htmlspecialchars($_SESSION['cart_error_message']) ?></span>
            </div>
            <?php unset($_SESSION['cart_error_message']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['cart_error'])): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline"><?= htmlspecialchars($_SESSION['cart_error']) ?></span>
            </div>
            <?php unset($_SESSION['cart_error']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['cart_success_message'])): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline"><?= htmlspecialchars($_SESSION['cart_success_message']) ?></span>
            </div>
            <?php unset($_SESSION['cart_success_message']); ?>
        <?php endif; ?>

        <?php if (empty($cartDetails->items)): ?>
            <div class="text-center bg-white p-8 rounded-lg shadow-md">
                <p class="text-gray-600">Giỏ hàng của bạn đang trống.</p>
            </div>
        <?php else: ?>
            <div class="flex flex-col lg:flex-row gap-8">
                <div class="w-full lg:w-2/3 bg-white p-6 rounded-lg shadow-md">
                    <h2 class="text-xl font-bold mb-4">Các sản phẩm trong giỏ</h2>
                    <?php foreach ($cartDetails->items as $variantId => $item): ?>
                        <div class="flex items-center justify-between border-b pb-4 mb-4">
                            <div class="flex items-center">
                                <img src="<?= htmlspecialchars($item->image_url) ?>" alt="<?= htmlspecialchars($item->product_name) ?>" class="w-24 h-24 object-cover rounded-md">
                                <div class="ml-4">
                                    <h3 class="font-bold text-lg"><?= htmlspecialchars($item->product_name) ?></h3>
                                    <p class="text-sm text-gray-500"><?= htmlspecialchars($item->attributes) ?></p>
                                    <p class="text-gray-600"><?= number_format($item->price) ?> VNĐ</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-4">
                                <form action="/shoe-shop/public/cart/update" method="POST" class="flex items-center">
                                    <input type="hidden" name="variant_id" value="<?= $variantId ?>">
                                    <input type="number" name="quantity" value="<?= $item->quantity ?>" min="1" class="w-16 text-center border rounded-md py-1">
                                    <button type="submit" class="ml-2 text-indigo-600 hover:text-indigo-800 text-sm">Cập nhật</button>
                                </form>
                                <form action="/shoe-shop/public/cart/remove" method="POST">
                                    <input type="hidden" name="variant_id" value="<?= $variantId ?>">
                                    <button type="submit" class="text-red-500 hover:text-red-700">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="w-full lg:w-1/3">
                    <div class="bg-white p-6 rounded-lg shadow-md">
                        <h2 class="text-xl font-bold border-b pb-4 mb-4">Tóm tắt đơn hàng</h2>

                        <form action="/shoe-shop/public/cart/apply-voucher" method="POST" class="mb-4">
                            <label for="voucher_code" class="block text-gray-700 font-medium mb-2">Mã giảm giá</label>
                            <div class="flex">
                                <input type="text" name="voucher_code" id="voucher_code" class="w-full px-3 py-2 border rounded-l-lg focus:outline-none" placeholder="Nhập mã giảm giá">
                                <button type="submit" class="bg-gray-800 text-white py-2 px-4 rounded-r-lg hover:bg-black transition">Áp dụng</button>
                            </div>
                        </form>

                        <div class="border-t pt-4">
                            <div class="flex justify-between mb-2">
                                <span>Tạm tính</span>
                                <span><?= number_format($cartDetails->subtotal) ?> VNĐ</span>
                            </div>

                            <?php if ($cartDetails->discount > 0): ?>
                                <div class="flex justify-between mb-2 text-green-600">
                                    <span>Giảm giá (<?= htmlspecialchars($cartDetails->voucher_code) ?>)</span>
                                    <span>- <?= number_format($cartDetails->discount) ?> VNĐ</span>
                                </div>
                                <form action="/shoe-shop/public/cart/remove-voucher" method="POST" class="text-right mb-4">
                                    <button type="submit" class="text-red-500 text-sm hover:underline">[Hủy bỏ]</button>
                                </form>
                            <?php endif; ?>

                            <div class="border-t flex justify-between font-bold text-xl pt-4">
                                <span>Tổng tiền</span>
                                <span><?= number_format($cartDetails->final_total) ?> VNĐ</span>
                            </div>
                            <a href="/shoe-shop/public/checkout" class="block text-center mt-6 w-full bg-indigo-600 text-white font-bold py-3 rounded-lg hover:bg-indigo-700 transition">
                                Tiến hành thanh toán
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </main>

    <?php require_once __DIR__ . '/../../layout/footer.php'; ?>
</body>

</html>
