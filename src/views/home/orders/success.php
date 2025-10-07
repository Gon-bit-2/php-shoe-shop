    <?php require_once __DIR__ . '/../../../helper/status_helper.php'; ?>
    <!DOCTYPE html>
    <html lang="vi">

    <head>
        <meta charset="UTF-8">
        <title>Đặt hàng thành công!</title>
        <link href="/shoe-shop/public/css/style.css" rel="stylesheet">
    </head>

    <body class="bg-gray-100">
        <?php require_once __DIR__ . '/../../layout/header.php'; ?>
        <main class="container mx-auto px-6 py-16 text-center">
            <div class="bg-white max-w-2xl mx-auto p-8 rounded-lg shadow-md">
                <svg class="w-16 h-16 mx-auto text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <h1 class="text-3xl font-bold text-gray-800 mt-4 mb-2">Đặt hàng thành công!</h1>
                <p class="text-gray-600 mb-6">Cảm ơn bạn đã tin tưởng và mua hàng tại ShoeShop. Đơn hàng của bạn đã được tiếp nhận.</p>

                <div class="text-left border-t border-b py-4 my-6">
                    <p><strong>Mã đơn hàng:</strong> #<?= htmlspecialchars($order->id) ?></p>
                    <p><strong>Tổng tiền thanh toán:</strong> <span class="font-bold"><?= number_format($order->total_amount) ?> VNĐ</span></p>
                    <p><strong>Trạng thái:</strong> <?= translateOrderStatus($order->status) ?></p>
                </div>

                <p class="text-gray-600 mb-6">Một email xác nhận chứa chi tiết đơn hàng đã được gửi đến địa chỉ <strong><?= htmlspecialchars($order->email) ?></strong>.</p>
                <div class="flex justify-center gap-4">
                    <a href="/shoe-shop/public/" class="bg-gray-800 text-white font-bold py-2 px-6 rounded-lg hover:bg-black transition">Tiếp tục mua sắm</a>
                    <a href="/shoe-shop/public/history" class="border border-gray-800 text-gray-800 font-bold py-2 px-6 rounded-lg hover:bg-gray-100 transition">Xem lịch sử đơn hàng</a>
                </div>
            </div>
        </main>
        <?php require_once __DIR__ . '/../../layout/footer.php'; ?>
    </body>

    </html>
