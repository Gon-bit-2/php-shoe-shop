<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Lịch sử mua hàng</title>
    <link href="/shoe-shop/public/css/style.css" rel="stylesheet">
</head>

<body class="bg-gray-100">
    <?php require_once __DIR__ . '/../../layout/header.php'; ?>
    <?php require_once __DIR__ . '/../../../helper/status_helper.php'; ?>
    <main class="container mx-auto px-6 py-12">
        <h1 class="text-3xl font-bold text-center mb-8">Lịch sử Mua hàng</h1>

        <?php if (!empty($productsToReview)): ?>
            <div class="bg-white shadow-md rounded-lg p-6 mb-8 border-l-4 border-yellow-400">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Sản phẩm chờ bạn đánh giá</h2>
                <p class="text-gray-600 mb-4">Cảm ơn bạn đã mua hàng! Hãy chia sẻ cảm nhận của bạn về những sản phẩm dưới đây nhé.</p>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <?php foreach ($productsToReview as $product): ?>
                        <div class="flex items-center bg-gray-50 p-3 rounded-lg">
                            <img src="<?= htmlspecialchars($product->image_url) ?>" alt="<?= htmlspecialchars($product->name) ?>" class="w-16 h-16 object-cover rounded-md flex-shrink-0">
                            <div class="ml-4 flex-grow">
                                <p class="font-semibold text-gray-700"><?= htmlspecialchars($product->name) ?></p>
                                <a href="/shoe-shop/public/product/<?= $product->id ?>" class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">Viết đánh giá &rarr;</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
        <h2 class="text-2xl font-bold mb-4">Các đơn hàng đã đặt</h2>
        <div class="bg-white shadow-md rounded-lg overflow-x-auto">
            <table class="min-w-full leading-normal">
                <thead>
                    <tr>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Mã ĐH</th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Ngày đặt</th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-50 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Tổng tiền</th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-50 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Trạng thái</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($orders)): ?>
                        <tr>
                            <td colspan="4" class="text-center py-10 text-gray-500">
                                Bạn chưa có đơn hàng nào.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($orders as $order): ?>
                            <tr>
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">#<?= htmlspecialchars($order->id) ?></td>
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm"><?= date('d/m/Y H:i', strtotime($order->created_at)) ?></td>
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-right font-semibold"><?= number_format($order->total_amount) ?> VNĐ</td>
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-center">
                                    <span class="relative inline-block px-3 py-1 font-semibold leading-tight <?= getStatusColorClass($order->status) ?>">
                                        <span aria-hidden class="absolute inset-0 opacity-50 rounded-full"></span>
                                        <span class="relative"><?= translateOrderStatus($order->status) ?></span>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>

    <?php require_once __DIR__ . '/../../layout/footer.php'; ?>
</body>

</html>
