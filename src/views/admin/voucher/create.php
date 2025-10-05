<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Thêm Voucher mới</title>
    <link href="/shoe-shop/public/css/style.css" rel="stylesheet">
</head>

<body class="bg-gray-100">
    <?php require_once __DIR__ . '/../partials/navbar.php'; ?>
    <div class="container mx-auto mt-10 p-8 bg-white shadow-md rounded-lg">
        <h1 class="text-3xl font-bold mb-6">Thêm Voucher mới</h1>

        <?php if (isset($errorMessage) && !empty($errorMessage)): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <?= htmlspecialchars($errorMessage); ?>
            </div>
        <?php endif; ?>

        <form action="/shoe-shop/public/admin/vouchers/create" method="POST">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="mb-4">
                    <label for="code" class="block text-gray-700 font-bold mb-2">Mã Code (viết liền, không dấu)</label>
                    <input type="text" name="code" id="code" value="<?= htmlspecialchars($oldInput['code'] ?? '') ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 uppercase" required>
                </div>
                <div class="mb-4">
                    <label for="quantity" class="block text-gray-700 font-bold mb-2">Số lượng</label>
                    <input type="number" name="quantity" id="quantity" value="<?= htmlspecialchars($oldInput['quantity'] ?? '') ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" required min="0">
                </div>
                <div class="mb-4">
                    <label for="type" class="block text-gray-700 font-bold mb-2">Loại giảm giá</label>
                    <select name="type" id="type" class="shadow border rounded w-full py-2 px-3 text-gray-700">
                        <option value="percent" <?= (isset($oldInput['type']) && $oldInput['type'] == 'percent') ? 'selected' : '' ?>>Phần trăm (%)</option>
                        <option value="fixed" <?= (isset($oldInput['type']) && $oldInput['type'] == 'fixed') ? 'selected' : '' ?>>Số tiền cố định (VNĐ)</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label for="value" class="block text-gray-700 font-bold mb-2">Giá trị (nhập số % hoặc số tiền)</label>
                    <input type="number" name="value" id="value" value="<?= htmlspecialchars($oldInput['value'] ?? '') ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" required min="0">
                </div>
                <div class="mb-4">
                    <label for="starts_at" class="block text-gray-700 font-bold mb-2">Ngày bắt đầu (tùy chọn)</label>
                    <input type="datetime-local" name="starts_at" id="starts_at" value="<?= htmlspecialchars($oldInput['starts_at'] ?? '') ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700">
                </div>
                <div class="mb-4">
                    <label for="expires_at" class="block text-gray-700 font-bold mb-2">Ngày hết hạn (tùy chọn)</label>
                    <input type="datetime-local" name="expires_at" id="expires_at" value="<?= htmlspecialchars($oldInput['expires_at'] ?? '') ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700">
                </div>
                <div class="mb-4 md:col-span-2">
                    <label for="min_spend" class="block text-gray-700 font-bold mb-2">Giá trị đơn hàng tối thiểu (VNĐ)</label>
                    <input type="number" name="min_spend" id="min_spend" value="<?= htmlspecialchars($oldInput['min_spend'] ?? '0') ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" required min="0">
                </div>
            </div>

            <div class="mb-6 mt-4">
                <label class="block text-gray-700 font-bold mb-2">Trạng thái:</label>
                <label class="inline-flex items-center">
                    <input type="checkbox" name="is_active" value="1" class="mr-2 h-5 w-5" checked>
                    <span>Kích hoạt voucher</span>
                </label>
            </div>

            <div class="flex items-center justify-end gap-4 mt-8">
                <a href="/shoe-shop/public/admin/vouchers" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">Hủy</a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Lưu Voucher</button>
            </div>
        </form>
    </div>
</body>

</html>
