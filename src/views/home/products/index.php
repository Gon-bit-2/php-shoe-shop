<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Shoe Shop - Phong cách trên từng bước chân</title>
    <link href="/shoe-shop/public/css/style.css" rel="stylesheet">
</head>

<body class="bg-white">

    <?php require_once __DIR__ . '/../../layout/header.php'; ?>

    <main>
        <section class="relative bg-gray-900 text-white">
            <img src="./images/banner/banner01.png"
                alt="Running shoe banner" class="w-full h-[60vh] object-cover opacity-50">
            <div class="absolute inset-0 flex flex-col items-center justify-center text-center p-4">
                <h1 class="text-4xl md:text-6xl font-extrabold leading-tight mb-4">Bộ Sưu Tập Mới Nhất</h1>
                <p class="text-lg md:text-xl mb-8 max-w-2xl">Khám phá những mẫu giày mới nhất, kết hợp hoàn hảo giữa phong cách và sự thoải mái.</p>
                <a href="/shoe-shop/public/products" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-8 rounded-full transition duration-300">Mua Ngay</a>
            </div>
        </section>

        <!-- Cam kết của Shop -->
        <section class="bg-white py-8 border-b border-gray-200">
            <div class="container mx-auto px-6">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                    <!-- Miễn phí vận chuyển -->
                    <div class="flex flex-col items-center text-center group hover:transform hover:scale-105 transition duration-300">
                        <div class="bg-indigo-100 rounded-full p-4 mb-3 group-hover:bg-indigo-200 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0" />
                            </svg>
                        </div>
                        <h3 class="font-semibold text-gray-800 mb-1">Miễn Phí Vận Chuyển</h3>
                        <p class="text-sm text-gray-500">Cho đơn hàng trên 500k</p>
                    </div>

                    <!-- Bảo hành 12 tháng -->
                    <div class="flex flex-col items-center text-center group hover:transform hover:scale-105 transition duration-300">
                        <div class="bg-green-100 rounded-full p-4 mb-3 group-hover:bg-green-200 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                        </div>
                        <h3 class="font-semibold text-gray-800 mb-1">Bảo Hành 12 Tháng</h3>
                        <p class="text-sm text-gray-500">Cam kết chất lượng</p>
                    </div>

                    <!-- Đổi trả dễ dàng -->
                    <div class="flex flex-col items-center text-center group hover:transform hover:scale-105 transition duration-300">
                        <div class="bg-yellow-100 rounded-full p-4 mb-3 group-hover:bg-yellow-200 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                        </div>
                        <h3 class="font-semibold text-gray-800 mb-1">Đổi Trả Dễ Dàng</h3>
                        <p class="text-sm text-gray-500">Trong vòng 7 ngày</p>
                    </div>

                    <!-- Hàng chính hãng -->
                    <div class="flex flex-col items-center text-center group hover:transform hover:scale-105 transition duration-300">
                        <div class="bg-red-100 rounded-full p-4 mb-3 group-hover:bg-red-200 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                            </svg>
                        </div>
                        <h3 class="font-semibold text-gray-800 mb-1">Hàng Chính Hãng 100%</h3>
                        <p class="text-sm text-gray-500">Đảm bảo nguồn gốc</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="container mx-auto px-6 py-16">
            <h2 class="text-3xl font-bold text-center text-gray-800 mb-8">Khám phá Danh mục</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <?php if (!empty($categories)): ?>
                    <?php $featuredCategories = array_slice($categories, 0, 4); ?>
                    <?php foreach ($featuredCategories as $category): ?>
                        <a href="/shoe-shop/public/products?category=<?= $category->id ?>" class="relative rounded-lg overflow-hidden group">
                            <img src="<?= htmlspecialchars($category->image_url) ?>" alt="<?= htmlspecialchars($category->name) ?>" class="w-full h-48 object-cover transition-transform duration-300 group-hover:scale-110">
                            <div class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center">
                                <h3 class="text-white text-2xl font-bold group-hover:scale-110 transition-transform duration-300"><?= htmlspecialchars($category->name) ?></h3>
                            </div>
                        </a>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </section>
        <section class="bg-gray-50 py-16">
            <div class="container mx-auto px-6">
                <h2 class="text-3xl font-bold text-center text-gray-800 mb-8">Bán Chạy Nhất</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                    <?php if (empty($topSellingProducts)): ?>
                        <p class="col-span-full text-center text-gray-500">Chưa có dữ liệu sản phẩm bán chạy.</p>
                    <?php else: ?>
                        <?php foreach ($topSellingProducts as $product): ?>
                            <div class="bg-white rounded-lg shadow-md overflow-hidden group">
                                <a href="/shoe-shop/public/product/<?= htmlspecialchars($product->product_id) ?>">
                                    <div class="relative overflow-hidden">
                                        <img src="<?= htmlspecialchars($product->image_url) ?>" alt="<?= htmlspecialchars($product->product_name) ?>" class="w-full h-64 object-cover transition-transform duration-300 group-hover:scale-105">
                                        <div class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                            <span class="text-white text-lg font-bold border-2 border-white py-2 px-4 rounded-md">Xem Chi Tiết</span>
                                        </div>
                                    </div>
                                    <div class="p-4 text-center">
                                        <h3 class="text-lg font-semibold text-gray-800 truncate"><?= htmlspecialchars($product->product_name) ?></h3>
                                        <p class="text-sm text-gray-500"><?= htmlspecialchars($product->variant_attributes) ?></p>
                                        <p class="text-red-600 font-bold text-lg mt-2">Đã bán: <?= number_format($product->total_sold) ?></p>
                                    </div>
                                </a>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </section>
        <section class="bg-gray-50 py-16">
            <div class="container mx-auto px-6">
                <h2 class="text-3xl font-bold text-center text-gray-800 mb-8">Sản Phẩm Mới Nhất</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                    <?php if (empty($products)): ?>
                        <p class="col-span-full text-center text-gray-500">Hiện chưa có sản phẩm nào.</p>
                    <?php else: ?>
                        <?php foreach (array_slice($products, 0, 4) as $product): // Giới hạn 8 sản phẩm mới
                        ?>
                            <div class="bg-white rounded-lg shadow-md overflow-hidden group">
                                <a href="/shoe-shop/public/product/<?= htmlspecialchars($product->id) ?>">
                                    <div class="relative overflow-hidden">
                                        <img src="<?= htmlspecialchars($product->image_url) ?>" alt="<?= htmlspecialchars($product->name) ?>" class="w-full h-64 object-cover transition-transform duration-300 group-hover:scale-105">
                                        <div class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                            <span class="text-white text-lg font-bold border-2 border-white py-2 px-4 rounded-md">Xem Chi Tiết</span>
                                        </div>
                                    </div>
                                    <div class="p-4 text-center">
                                        <h3 class="text-lg font-semibold text-gray-800 truncate"><?= htmlspecialchars($product->name) ?></h3>
                                        <p class="text-black font-bold text-xl mt-2">Giá: <?= number_format($product->price) ?> VNĐ</p>
                                    </div>
                                </a>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </section>
        <section class="bg-gray-50 py-16">
            <div class="container mx-auto px-6">
                <h2 class="text-3xl font-bold text-center text-gray-800 mb-8">Sản Phẩm Nổi Bật</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                    <?php if (empty($products)): ?>
                        <p class="col-span-full text-center text-gray-500">Hiện chưa có sản phẩm nào.</p>
                    <?php else: ?>
                        <?php foreach (array_slice($products, 0, 8) as $product): ?>
                            <div class="bg-white rounded-lg shadow-md overflow-hidden group">
                                <a href="/shoe-shop/public/product/<?= htmlspecialchars($product->id) ?>">
                                    <div class="relative hover:scale-105 transition-all duration-300">
                                        <img src="<?= htmlspecialchars($product->image_url) ?>" alt="<?= htmlspecialchars($product->name) ?>" class="w-full h-64 object-cover ">
                                        <div class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition">
                                            <span class="text-white text-lg font-bold border-2 border-white py-2 px-4">Xem Chi Tiết</span>
                                        </div>
                                    </div>
                                    <div class="p-4 text-center">
                                        <h3 class="text-lg font-semibold text-gray-800 truncate"><?= htmlspecialchars($product->name) ?></h3>
                                        <p class="text-black font-bold text-xl mt-2">Giá: <?= number_format($product->price) ?> VNĐ</p>
                                    </div>
                                </a>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </section>


    </main>

    <?php require_once __DIR__ . '/../../layout/footer.php'; ?>

</body>

</html>
