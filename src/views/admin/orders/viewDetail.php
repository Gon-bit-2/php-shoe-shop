<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Chi tiết Đơn hàng #<?= htmlspecialchars($orderDetails->order->id) ?></title>
    <link href="/shoe-shop/public/css/style.css" rel="stylesheet">
</head>

<body class="bg-gray-100">
    <?php require_once __DIR__ . '/../partials/navbar.php'; ?>
    <div class="container mx-auto mt-10 p-8">
        <h1 class="text-3xl font-bold mb-6">Chi tiết Đơn hàng #<?= htmlspecialchars($orderDetails->order->id) ?></h1>

        <div class="flex flex-col lg:flex-row gap-8">
            <div class="w-full lg:w-1/3 bg-white p-6 rounded-lg shadow-md self-start">
                <h2 class="text-xl font-bold mb-4 border-b pb-2">Thông tin Khách hàng</h2>
                <p><strong>Tên:</strong> <?= htmlspecialchars($orderDetails->order->customer_name) ?></p>
                <p><strong>Email:</strong> <?= htmlspecialchars($orderDetails->order->email) ?></p>
                <p><strong>Điện thoại:</strong> <?= htmlspecialchars($orderDetails->order->customer_phone) ?></p>
                <p><strong>Địa chỉ:</strong> <?= htmlspecialchars($orderDetails->order->customer_address) ?></p>

                <h2 class="text-xl font-bold mt-6 mb-4 border-b pb-2">Thông tin Thanh toán</h2>
                <p><strong>Phương thức:</strong> <?= $orderDetails->order->payment_method == 'cod' ? 'Thanh toán khi nhận hàng (COD)' : 'Chuyển khoản' ?></p>
                <?php if ($orderDetails->order->voucher_code): ?>
                    <p><strong>Mã giảm giá:</strong> <span class="font-semibold text-green-600"><?= htmlspecialchars($orderDetails->order->voucher_code) ?></span></p>
                <?php endif; ?>

                <h2 class="text-xl font-bold mt-6 mb-4 border-b pb-2">Trạng thái Đơn hàng</h2>
                <p>Trạng thái hiện tại: <strong><?= htmlspecialchars(ucfirst($orderDetails->order->status)) ?></strong></p>
                <form action="/shoe-shop/public/admin/orders/update-status/<?= $orderDetails->order->id ?>" method="POST" class="mt-4">
                    <label for="status" class="block text-gray-700 font-medium mb-2">Thay đổi trạng thái:</label>
                    <div class="flex">
                        <select name="status" id="status" class="w-full px-3 py-2 border rounded-l-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="pending" <?= $orderDetails->order->status == 'pending' ? 'selected' : '' ?>>Pending</option>
                            <option value="processing" <?= $orderDetails->order->status == 'processing' ? 'selected' : '' ?>>Processing</option>
                            <option value="shipped" <?= $orderDetails->order->status == 'shipped' ? 'selected' : '' ?>>Shipped</option>
                            <option value="completed" <?= $orderDetails->order->status == 'completed' ? 'selected' : '' ?>>Completed</option>
                            <option value="cancelled" <?= $orderDetails->order->status == 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                        </select>
                        <button type="submit" class="bg-blue-600 text-white py-2 px-4 rounded-r-lg hover:bg-blue-700 transition">
                            Cập nhật
                        </button>
                    </div>
                </form>
            </div>

            <div class="w-full lg:w-2/3 bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-xl font-bold mb-4 border-b pb-2">Danh sách Sản phẩm</h2>
                <table class="min-w-full">
                    <thead>
                        <tr>
                            <th class="text-left py-2">Sản phẩm</th>
                            <th class="text-center py-2">Số lượng</th>
                            <th class="text-right py-2">Đơn giá</th>
                            <th class="text-right py-2">Thành tiền</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orderDetails->items as $item): ?>
                            <tr class="border-b">
                                <td class="py-3">
                                    <p class="font-semibold"><?= htmlspecialchars($item->product_name) ?></p>
                                    <p class="text-sm text-gray-500"><?= htmlspecialchars($item->variant_attributes) ?></p>
                                </td>
                                <td class="text-center py-3"><?= htmlspecialchars($item->quantity) ?></td>
                                <td class="text-right py-3"><?= number_format($item->price) ?> VNĐ</td>
                                <td class="text-right py-3"><?= number_format($item->price * $item->quantity) ?> VNĐ</td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <?php
                        // Tính toán lại subtotal để hiển thị
                        $subtotal = $orderDetails->order->total_amount + $orderDetails->order->discount_amount;
                        ?>
                        <tr class="font-medium">
                            <td colspan="3" class="text-right py-2">Tạm tính:</td>
                            <td class="text-right py-2"><?= number_format($subtotal) ?> VNĐ</td>
                        </tr>
                        <?php if ($orderDetails->order->discount_amount > 0): ?>
                            <tr class="font-medium text-green-600">
                                <td colspan="3" class="text-right py-2">Giảm giá:</td>
                                <td class="text-right py-2">- <?= number_format($orderDetails->order->discount_amount) ?> VNĐ</td>
                            </tr>
                        <?php endif; ?>
                        <tr class="font-bold border-t-2">
                            <td colspan="3" class="text-right py-3 text-xl">Tổng cộng:</td>
                            <td class="text-right py-3 text-xl"><?= number_format($orderDetails->order->total_amount) ?> VNĐ</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</body>

</html>
