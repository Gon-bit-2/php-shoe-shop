<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Thanh toán đơn hàng</title>
    <link href="/shoe-shop/public/css/style.css" rel="stylesheet">
</head>

<body class="bg-gray-100">

    <?php require_once __DIR__ . '/../../layout/header.php'; ?>

    <main class="container mx-auto px-6 py-12">
        <h1 class="text-3xl font-bold text-center mb-8">Thông tin Thanh toán</h1>

        <?php
        // Lấy lại chi tiết giỏ hàng để hiển thị tóm tắt đơn hàng
        $cartDetails = $this->cartService->getFinalCartDetails();
        $cartItems = $cartDetails->items;
        ?>

        <?php if (empty($cartItems)): ?>
            <div class="text-center bg-white p-8 rounded-lg shadow-md">
                <p class="text-gray-600 mb-4">Giỏ hàng của bạn đang trống. Không thể tiến hành thanh toán.</p>
                <a href="/shoe-shop/public/" class="bg-indigo-600 text-white font-bold py-2 px-4 rounded-lg hover:bg-indigo-700 transition">
                    Quay lại trang chủ
                </a>
            </div>
        <?php else: ?>
            <div class="flex flex-col lg:flex-row gap-8">

                <div class="w-full lg:w-3/5 bg-white p-6 rounded-lg shadow-md">
                    <h2 class="text-xl font-bold mb-4">Thông tin giao hàng</h2>
                    <?php if (isset($errorMessage) && !empty($errorMessage)): ?>
                        <div class="mb-4 text-center text-red-500 bg-red-100 p-3 rounded-md">
                            <?= htmlspecialchars($errorMessage) ?>
                        </div>
                    <?php endif; ?>

                    <form action="/shoe-shop/public/checkout" method="POST">
                        <div class="mb-4">
                            <label for="customer_name" class="block text-gray-700 font-medium mb-2">Họ và tên</label>
                            <input type="text" id="customer_name" name="customer_name" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" value="<?= htmlspecialchars($_SESSION['user']['fullname'] ?? '') ?>" required>
                        </div>
                        <div class="mb-4">
                            <label for="customer_phone" class="block text-gray-700 font-medium mb-2">Số điện thoại</label>
                            <input type="tel" id="customer_phone" name="customer_phone" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        </div>
                        <div class="mb-6">
                            <label for="customer_address" class="block text-gray-700 font-medium mb-2">Địa chỉ nhận hàng</label>
                            <textarea id="customer_address" name="customer_address" rows="3" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required></textarea>
                        </div>
                        <div class="mt-6 mb-6">
                            <label class="block text-gray-700 font-medium mb-2">Phương thức thanh toán</label>
                            <div class="space-y-2">
                                <label class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-gray-50">
                                    <input type="radio" name="payment_method" value="cod" class="mr-3 h-4 w-4" checked>
                                    <span>Thanh toán khi nhận hàng (COD)</span>
                                </label>
                                <label class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-gray-50">
                                    <input type="radio" name="payment_method" value="bank_transfer" class="mr-3 h-4 w-4">
                                    <span>Chuyển khoản MoMo / Ngân hàng</span>
                                </label>
                            </div>
                        </div>
                        <button type="submit" class="w-full bg-indigo-600 text-white font-bold py-3 rounded-lg hover:bg-indigo-700 transition">
                            Xác nhận và Đặt hàng
                        </button>
                    </form>
                </div>

                <div class="w-full lg:w-2/5">
                    <div class="bg-white p-6 rounded-lg shadow-md">
                        <h2 class="text-xl font-bold border-b pb-4 mb-4">Đơn hàng của bạn</h2>
                        <?php foreach ($cartItems as $item): ?>
                            <div class="flex justify-between items-center mb-3">
                                <div class="flex items-center">
                                    <div class="relative">
                                        <img src="<?= htmlspecialchars($item->image_url) ?>" alt="<?= htmlspecialchars($item->product_name) ?>" class="w-16 h-16 object-cover rounded-md">
                                        <span class="absolute -top-2 -right-2 bg-gray-600 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center"><?= $item->quantity ?></span>
                                    </div>
                                    <div class="ml-4">
                                        <p class="font-bold text-sm"><?= htmlspecialchars($item->product_name) ?></p>
                                        <p class="text-xs text-gray-500"><?= htmlspecialchars($item->attributes) ?></p>
                                    </div>
                                </div>
                                <span class="font-medium"><?= number_format($item->price * $item->quantity) ?> VNĐ</span>
                            </div>
                        <?php endforeach; ?>

                        <div class="border-t mt-4 pt-4">
                            <div class="flex justify-between mb-2">
                                <span>Tạm tính</span>
                                <span><?= number_format($cartDetails->subtotal) ?> VNĐ</span>
                            </div>

                            <?php if ($cartDetails->discount > 0): ?>
                                <div class="flex justify-between mb-2 text-green-600">
                                    <span>Giảm giá (<?= htmlspecialchars($cartDetails->voucher_code) ?>)</span>
                                    <span>- <?= number_format($cartDetails->discount) ?> VNĐ</span>
                                </div>
                            <?php endif; ?>

                            <div class="border-t flex justify-between font-bold text-xl pt-4">
                                <span>Tổng cộng</span>
                                <span><?= number_format($cartDetails->final_total) ?> VNĐ</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </main>

    <?php require_once __DIR__ . '/../../layout/footer.php'; ?>

</body>

</html>
