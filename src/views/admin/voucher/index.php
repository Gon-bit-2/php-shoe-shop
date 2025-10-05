<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Quản lý Mã giảm giá</title>
    <link href="/shoe-shop/public/css/style.css" rel="stylesheet">
</head>

<body class="bg-gray-100">
    <?php require_once __DIR__ . '/../partials/navbar.php'; ?>
    <div class="container mx-auto mt-10 p-8">
        <h1 class="text-3xl font-bold mb-6">Quản lý Mã giảm giá (Vouchers)</h1>

        <div class="mb-4">
            <a href="/shoe-shop/public/admin/vouchers/create" class="bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 transition">Thêm Voucher mới</a>
        </div>

        <div class="bg-white shadow-md rounded-lg overflow-x-auto">
            <table class="min-w-full leading-normal">
                <thead>
                    <tr>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase">Mã Code</th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase">Loại</th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-50 text-right text-xs font-semibold text-gray-600 uppercase">Giá trị</th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-50 text-center text-xs font-semibold text-gray-600 uppercase">Đã dùng / Tổng</th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-50 text-center text-xs font-semibold text-gray-600 uppercase">Trạng thái</th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($vouchers)): ?>
                        <tr>
                            <td colspan="6" class="px-5 py-5 border-b border-gray-200 text-center text-gray-500">Chưa có voucher nào.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($vouchers as $voucher): ?>
                            <tr>
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                    <p class="text-gray-900 font-semibold"><?= htmlspecialchars($voucher->code) ?></p>
                                </td>
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                    <?= $voucher->type == 'fixed' ? 'Giảm tiền' : 'Giảm %' ?>
                                </td>
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-right">
                                    <?= $voucher->type == 'fixed' ? number_format($voucher->value) . ' VNĐ' : htmlspecialchars($voucher->value) . ' %' ?>
                                </td>
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-center">
                                    <?= htmlspecialchars($voucher->used_count) ?> / <?= htmlspecialchars($voucher->quantity) ?>
                                </td>
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-center">
                                    <?php if ($voucher->is_active): ?>
                                        <span class="text-green-600 font-semibold">Hoạt động</span>
                                    <?php else: ?>
                                        <span class="text-red-600 font-semibold">Vô hiệu</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                    <a href="/shoe-shop/public/admin/vouchers/edit/<?= $voucher->id ?>" class="text-indigo-600 hover:text-indigo-900">Sửa</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>
