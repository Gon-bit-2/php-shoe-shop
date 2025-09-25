<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Shoe Shop - Phong cách trên từng bước chân</title>
    <link href="/shoe-shop/public/css/style.css" rel="stylesheet">
</head>

<body class="bg-white">

    <?php require_once __DIR__ . '/../layout/header.php'; ?>

    <main>
        <section class="relative bg-gray-900 text-white">
            <img src="https://images.unsplash.com/photo-1542291026-7eec264c27ab?q=80&w=2070&auto=format&fit=crop"
                alt="Running shoe banner" class="w-full h-[60vh] object-cover opacity-50">
            <div class="absolute inset-0 flex flex-col items-center justify-center text-center p-4">
                <h1 class="text-4xl md:text-6xl font-extrabold leading-tight mb-4">Bộ Sưu Tập Mới Nhất</h1>
                <p class="text-lg md:text-xl mb-8 max-w-2xl">Khám phá những mẫu giày mới nhất, kết hợp hoàn hảo giữa phong cách và sự thoải mái.</p>
                <a href="#" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-8 rounded-full transition duration-300">Mua Ngay</a>
            </div>
        </section>

        <section class="container mx-auto px-6 py-16">
            <h2 class="text-3xl font-bold text-center text-gray-800 mb-8">Khám phá Danh mục</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <?php if (!empty($categories)): ?>
                    <?php
                    // Chỉ lấy 4 danh mục đầu tiên để hiển thị
                    $featuredCategories = array_slice($categories, 0, 4);
                    ?>
                    <?php foreach ($featuredCategories as $category): ?>
                        <a href="#" class="relative rounded-lg overflow-hidden group">
                            <img src="https://via.placeholder.com/400x300.png/007bff/FFFFFF?text=<?= urlencode($category->name) ?>"
                                alt="<?= htmlspecialchars($category->name) ?>" class="w-full h-48 object-cover">
                            <div class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center">
                                <h3 class="text-white text-2xl font-bold group-hover:scale-110 transition-transform duration-300"><?= htmlspecialchars($category->name) ?></h3>
                            </div>
                        </a>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </section>

        <section class="bg-gray-50 py-16">
            <div class="container mx-auto px-6">
                <h2 class="text-3xl font-bold text-center text-gray-800 mb-8">Sản Phẩm Nổi Bật</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                    <?php if (empty($products)): ?>
                        <p class="col-span-full text-center text-gray-500">Hiện chưa có sản phẩm nào.</p>
                    <?php else: ?>
                        <?php foreach ($products as $product): ?>
                            <div class="bg-white rounded-lg shadow-md overflow-hidden group">
                                <a href="/shoe-shop/public/product/<?= htmlspecialchars($product->id) ?>">
                                    <div class="relative hover:scale-105 transition-all duration-300">
                                        <img src="/shoe-shop/public<?= htmlspecialchars($product->image_url) ?>" alt="<?= htmlspecialchars($product->name) ?>" class="w-full h-64 object-cover ">
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

    <?php require_once __DIR__ . '/../layout/footer.php'; ?>

</body>

</html>
