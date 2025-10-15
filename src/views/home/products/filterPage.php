<?php
require_once __DIR__ . '/../../../helper/pagination_helper.php';

// Hàm này giúp giữ lại các bộ lọc hiện tại khi người dùng chọn một bộ lọc mới
function buildFilterUrl($newParams)
{
    // Bắt đầu với các query params hiện tại trên URL
    $queryParams = $_GET;
    // Gộp (hoặc ghi đè) các params mới vào
    $queryParams = array_merge($queryParams, $newParams);
    // Luôn reset về trang 1 khi có bộ lọc mới được áp dụng
    $queryParams['page'] = 1;
    // Xây dựng lại URL
    return '/shoe-shop/public/products?' . http_build_query($queryParams);
}

// Hàm để kiểm tra xem một giá trị lọc có đang được chọn hay không
function isFilterActive($paramName, $value)
{
    return isset($_GET[$paramName]) && $_GET[$paramName] == $value;
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
                            <a href="/shoe-shop/public/products" class="font-semibold text-gray-700 hover:text-indigo-600 transition <?= !isset($_GET['category']) ? 'text-indigo-600' : '' ?>">
                                Tất cả sản phẩm
                            </a>
                        </li>
                        <?php foreach ($categories as $category): ?>
                            <li class="mb-2">
                                <a href="/shoe-shop/public/products?category=<?= $category->id ?>" class="text-gray-700 hover:text-indigo-600 transition <?= isFilterActive('category', $category->id) ? 'text-indigo-600 font-bold' : '' ?>">
                                    <?= htmlspecialchars($category->name) ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>

                    <h3 class="text-xl font-bold mt-8 mb-4 border-b pb-2">Lọc theo Giá</h3>
                    <ul>
                        <li class="mb-2"><a href="<?= buildFilterUrl(['price' => '0-500000']) ?>" class="text-gray-700 hover:text-indigo-600 transition <?= isFilterActive('price', '0-500000') ? 'text-indigo-600 font-bold' : '' ?>">Dưới 500,000 VNĐ</a></li>
                        <li class="mb-2"><a href="<?= buildFilterUrl(['price' => '500000-1000000']) ?>" class="text-gray-700 hover:text-indigo-600 transition <?= isFilterActive('price', '500000-1000000') ? 'text-indigo-600 font-bold' : '' ?>">500,000 - 1,000,000 VNĐ</a></li>
                        <li class="mb-2"><a href="<?= buildFilterUrl(['price' => '1000000-2000000']) ?>" class="text-gray-700 hover:text-indigo-600 transition <?= isFilterActive('price', '1000000-2000000') ? 'text-indigo-600 font-bold' : '' ?>">1,000,000 - 2,000,000 VNĐ</a></li>
                        <li class="mb-2"><a href="<?= buildFilterUrl(['price' => '2000000']) ?>" class="text-gray-700 hover:text-indigo-600 transition <?= isFilterActive('price', '2000000') ? 'text-indigo-600 font-bold' : '' ?>">Trên 2,000,000 VNĐ</a></li>
                    </ul>

                    <?php if (!empty($_GET['category']) || !empty($_GET['price'])): ?>
                        <div class="mt-6">
                            <a href="/shoe-shop/public/products" class="w-full block text-center bg-red-500 text-white py-2 rounded-lg hover:bg-red-600 transition text-sm">Xóa bộ lọc</a>
                        </div>
                    <?php endif; ?>

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

                <div class="bg-white p-4 rounded-lg shadow-md mb-6 flex items-center justify-between">
                    <span class="text-gray-600">Sắp xếp theo:</span>
                    <div>
                        <a href="<?= buildFilterUrl(['sort' => 'newest']) ?>" class="px-3 py-1 rounded-full <?= (!isset($_GET['sort']) || $_GET['sort'] == 'newest') ? 'bg-gray-600 text-white' : 'text-gray-700' ?>">Mới nhất</a>
                        <a href="<?= buildFilterUrl(['sort' => 'price_asc']) ?>" class="px-3 py-1 rounded-full <?= isFilterActive('sort', 'price_asc') ? 'bg-gray-600 text-white' : 'text-gray-700' ?>">Giá tăng dần</a>
                        <a href="<?= buildFilterUrl(['sort' => 'price_desc']) ?>" class="px-3 py-1 rounded-full <?= isFilterActive('sort', 'price_desc') ? 'bg-gray-600 text-white' : 'text-gray-700' ?>">Giá giảm dần</a>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php if (empty($products)): ?>
                        <p class="col-span-full text-center text-gray-500 py-10">Không tìm thấy sản phẩm nào phù hợp.</p>
                    <?php else: ?>
                        <?php foreach ($products as $product): ?>
                            <div class="bg-white rounded-lg shadow-md overflow-hidden group">
                                <a href="/shoe-shop/public/product/<?= htmlspecialchars($product->id) ?>">
                                    <div class="relative overflow-hidden">
                                        <img src="<?= htmlspecialchars($product->image_url) ?>" alt="<?= htmlspecialchars($product->name) ?>" class="w-full h-64 object-cover transition-transform duration-300 group-hover:scale-105">
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

                <div class="mt-8">
                    <?php renderPagination($totalPages, $currentPage, '/shoe-shop/public/products'); ?>
                </div>
            </div>
        </div>
    </main>

    <?php require_once __DIR__ . '/../../layout/footer.php'; ?>
</body>

</html>
