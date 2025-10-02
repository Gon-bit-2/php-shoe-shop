<?php
function buildFilterUrl($newParams)
{
    $queryParams = $_GET;
    $queryParams = array_merge($queryParams, $newParams);
    return '/shoe-shop/public/products?' . http_build_query($queryParams);
}
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Sản phẩm - Shoe Shop</title>
    <link href="/shoe-shop/public/css/style.css" rel="stylesheet">
</head>

<body class="bg-gray-50">

    <?php require_once __DIR__ . '/../../layout/header.php'; ?>

    <main class="container mx-auto px-6 mt-8">
        <div class="md:flex">

            <aside class="w-full md:w-1/4 lg:w-1/5 pr-8">
                <div class="bg-white p-6 rounded-lg shadow-md">

                    <h3 class="text-xl font-bold mb-4 border-b pb-2">Danh Mục</h3>
                    <ul>
                        <li class="mb-2">
                            <a href="/shoe-shop/public/products" class="text-gray-700 hover:text-indigo-600 transition font-semibold">Tất cả sản phẩm</a>
                        </li>
                        <?php foreach ($categories as $category): ?>
                            <li class="mb-2">
                                <a href="/shoe-shop/public/products?category=<?= $category->id ?>" class="text-gray-700 hover:text-indigo-600 transition">
                                    <?= htmlspecialchars($category->name) ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                    <h3 class="text-xl font-bold mt-8 mb-4 border-b pb-2">Lọc theo Giá</h3>
                    <ul>
                        <li class="mb-2">
                            <a href="<?= buildFilterUrl(['price' => '0-500000']) ?>" class="text-gray-700 hover:text-indigo-600 transition">Dưới 500,000 VNĐ</a>
                        </li>
                        <li class="mb-2">
                            <a href="<?= buildFilterUrl(['price' => '500000-1000000']) ?>" class="text-gray-700 hover:text-indigo-600 transition">500,000 - 1,000,000 VNĐ</a>
                        </li>
                        <li class="mb-2">
                            <a href="<?= buildFilterUrl(['price' => '1000000-2000000']) ?>" class="text-gray-700 hover:text-indigo-600 transition">1,000,000 - 2,000,000 VNĐ</a>
                        </li>
                        <li class="mb-2">
                            <a href="<?= buildFilterUrl(['price' => '2000000']) ?>" class="text-gray-700 hover:text-indigo-600 transition">Trên 2,000,000 VNĐ</a>
                        </li>
                    </ul>
                </div>
            </aside>

            <div class="w-full md:w-3/4 lg:w-4/5 mt-8 md:mt-0">
                <h1 class="text-3xl font-bold mb-6">
                    <?php if (!empty($_GET['search'])): ?>
                        Kết quả tìm kiếm cho "<?= htmlspecialchars($_GET['search']) ?>"
                    <?php else: ?>
                        Tất cả sản phẩm
                    <?php endif; ?>
                </h1>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php if (empty($products)): ?>
                        <p class="col-span-full text-center text-gray-500 py-10">Không tìm thấy sản phẩm nào phù hợp.</p>
                    <?php else: ?>
                        <?php foreach ($products as $product): ?>
                            <div class="bg-white rounded-lg shadow-md overflow-hidden group">
                                <a href="/shoe-shop/public/product/<?= htmlspecialchars($product->id) ?>">
                                    <div class="relative">
                                        <img src="/shoe-shop/public<?= htmlspecialchars($product->image_url) ?>" alt="<?= htmlspecialchars($product->name) ?>" class="w-full h-64 object-cover">
                                    </div>
                                    <div class="p-4 text-center">
                                        <h3 class="text-lg font-semibold text-gray-800 truncate"><?= htmlspecialchars($product->name) ?></h3>
                                        <p class="text-black font-bold text-xl mt-2"><?= number_format($product->price) ?> VNĐ</p>
                                    </div>
                                </a>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>

    <?php require_once __DIR__ . '/../../layout/footer.php'; ?>
</body>

</html>
