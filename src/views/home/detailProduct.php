<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($product->name) ?></title>
    <link href="/shoe-shop/public/css/style.css" rel="stylesheet">
</head>

<body class="bg-gray-50">
    <?php require_once __DIR__ . '/../layout/header.php'; ?>
    <main class="container mx-auto px-6 py-12">
        <div class="md:flex md:items-start">
            <div class="w-full md:w-1/2">
                <div class="bg-white rounded-lg shadow-md p-4">
                    <img class="w-full h-auto object-cover rounded-lg" src="/shoe-shop/public<?= htmlspecialchars($product->image_url) ?>" alt="<?= htmlspecialchars($product->name) ?>">
                </div>
            </div>
            <div class="w-full md:w-1/2 md:pl-10 mt-8 md:mt-0">
                <h1 class="text-4xl font-extrabold text-gray-800 mb-2"><?= htmlspecialchars($product->name) ?></h1>
                <div class="mb-6">
                    <span class="text-3xl font-bold text-indigo-600"><?= htmlspecialchars($product->price) ?> VNĐ</span>
                </div>
                <div class="mb-6">
                    <h2 class="text-xl font-semibold text-gray-700 mb-2">Mô tả sản phẩm</h2>
                    <p class="text-gray-600 leading-relaxed"><?= nl2br(htmlspecialchars($product->description)) ?></p>
                </div>
                <form action="#" method="POST">
                    <div class="flex items-center mb-6">
                        <label for="quantity" class="mr-4 text-gray-700 font-semibold">Số lượng:</label>
                        <input type="number" id="quantity" name="quantity" value="1" min="1" max="<?= $product->stock ?>" class="w-20 text-center border rounded-md py-2 px-3">
                    </div>

                    <button type="submit" class="w-full bg-indigo-600 text-white font-bold py-3 px-6 rounded-lg hover:bg-indigo-700 transition duration-300">
                        Thêm vào giỏ hàng
                    </button>
                </form>

                <div class="mt-4 text-sm text-gray-500">
                    Còn lại: <?= $product->stock ?> sản phẩm
                </div>
            </div>
        </div>
    </main>
    <?php require_once __DIR__ . '/../layout/footer.php'; ?>
</body>

</html>
