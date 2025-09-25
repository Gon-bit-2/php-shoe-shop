<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Shoe Shop - Trang chủ</title>
    <link href="/shoe-shop/public/css/style.css" rel="stylesheet">
</head>

<body class="bg-gray-50">

    <?php require_once __DIR__ . '/../layout/header.php'; ?>

    <main class="container mx-auto px-6 mt-8">
        <div class="md:flex">
            <aside class="w-full md:w-1/4 lg:w-1/5 pr-8">
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <h3 class="text-xl font-bold mb-4">Danh Mục</h3>
                    <ul>
                        <?php foreach ($categories as $category): ?>
                            <li class="mb-2">
                                <a href="#" class="text-gray-700 hover:text-indigo-600 transition"><?= htmlspecialchars($category->name) ?></a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                    <h3 class="text-xl font-bold mt-8 mb-4">Lọc theo Giá</h3>
                </div>
            </aside>

            <div class="w-full md:w-3/4 lg:w-4/5">
                <h1 class="text-3xl font-bold mb-6">Tất cả sản phẩm</h1>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php if (empty($products)): ?>
                        <p class="col-span-full text-center text-gray-500">Hiện chưa có sản phẩm nào.</p>
                    <?php else: ?>
                        <?php foreach ($products as $product): ?>
                            <div class="bg-white rounded-lg shadow-md overflow-hidden group">
                                <a href="#">
                                    <div class="relative">
                                        <img src="/shoe-shop/public<?= htmlspecialchars($product->image_url) ?>" alt="<?= htmlspecialchars($product->name) ?>" class="w-full h-64 object-cover">
                                        <div class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition">
                                            <span class="text-white text-lg font-bold">Xem Chi Tiết</span>
                                        </div>
                                    </div>
                                    <div class="p-4">
                                        <p class="text-sm text-gray-500 mb-1"><?= htmlspecialchars($product->category_names ?? 'Chưa phân loại') ?></p>
                                        <h3 class="text-lg font-semibold text-gray-800 truncate"><?= htmlspecialchars($product->name) ?></h3>
                                        <p class="text-indigo-600 font-bold text-xl mt-2"><?= number_format($product->price) ?> VNĐ</p>
                                    </div>
                                </a>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>

    <?php require_once __DIR__ . '/../layout/footer.php'; ?>

</body>

</html>
