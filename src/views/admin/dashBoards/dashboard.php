<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link href="/shoe-shop/public/css/style.css" rel="stylesheet">
</head>

<body class="bg-gray-100">
    <?php require_once __DIR__ . '/../partials/navbar.php'; ?>
    <div class="container mx-auto mt-10 p-8">
        <h1 class="text-3xl font-bold mb-6">Tổng quan Kinh doanh</h1>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white p-6 rounded-lg shadow-md flex items-center">
                <div class="bg-green-500 text-white p-3 rounded-full mr-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v.01"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Tổng Doanh Ngày</p>
                    <p class="text-2xl font-bold"><?= number_format($dashboardData->stats->today_revenue ?? 0) ?> VNĐ</p>
                </div>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-md flex items-center">
                <div class="bg-green-500 text-white p-3 rounded-full mr-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v.01"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Tổng Doanh Tháng</p>
                    <p class="text-2xl font-bold"><?= number_format($dashboardData->stats->month_revenue ?? 0) ?> VNĐ</p>
                </div>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-md flex items-center">
                <div class="bg-green-500 text-white p-3 rounded-full mr-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v.01"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Tổng Doanh thu</p>
                    <p class="text-2xl font-bold"><?= number_format($dashboardData->stats->total_revenue ?? 0) ?> VNĐ</p>
                </div>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-md flex items-center">
                <div class="bg-blue-500 text-white p-3 rounded-full mr-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Đơn hàng hôm nay</p>
                    <p class="text-2xl font-bold"><?= $dashboardData->stats->today_orders ?? 0 ?></p>
                </div>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-md flex items-center">
                <div class="bg-yellow-500 text-white p-3 rounded-full mr-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Đơn hàng chờ xử lý</p>
                    <p class="text-2xl font-bold"><?= $dashboardData->stats->pending_orders ?? 0 ?></p>
                </div>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-md flex items-center">
                <div class="bg-purple-500 text-white p-3 rounded-full mr-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Tổng số khách hàng</p>
                    <p class="text-2xl font-bold"><?= $dashboardData->stats->total_customers ?? 0 ?></p>
                </div>
            </div>
        </div>

        <div class="bg-white shadow-md rounded-lg p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Top 5 Sản phẩm Bán chạy</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full leading-normal">
                    <thead>
                        <tr>

                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Sản phẩm</th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Thuộc tính</th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-50 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Đã bán</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($dashboardData->topProducts)): ?>
                            <tr>
                                <td colspan="3" class="px-5 py-5 border-b border-gray-200 text-center text-gray-500">Chưa có dữ liệu.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($dashboardData->topProducts as $product): ?>
                                <tr>

                                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                        <p class="text-gray-900 whitespace-no-wrap font-semibold"><?= htmlspecialchars($product->product_name) ?></p>
                                    </td>
                                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                        <p class="text-gray-600 whitespace-no-wrap"><?= htmlspecialchars($product->variant_attributes) ?></p>
                                    </td>
                                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-right">
                                        <p class="text-gray-900 whitespace-no-wrap font-bold"><?= htmlspecialchars($product->total_sold) ?></p>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>

</html>
