<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng ký tài khoản</title>
    <link href="/shoe-shop/public/css/style.css" rel="stylesheet">
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    
    <div class="w-full max-w-md bg-white p-8 rounded-lg shadow-md">
        <h2 class="text-3xl font-bold text-center text-gray-800 mb-6">Tạo tài khoản mới</h2>

        <?php if (isset($message) && !empty($message)): ?>
            <div class="mb-4 text-center text-red-500 bg-red-100 p-3 rounded-md">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>

        <form action="/shoe-shop/public/register" method="POST">
            <div class="mb-4">
                <label for="fullname" class="block text-gray-700 font-medium mb-2 ">Họ và tên</label>
                <input type="text" id="fullname" name="fullname" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>
            <div class="mb-4">
                <label for="email" class="block text-gray-700 font-medium mb-2">Email</label>
                <input type="email" id="email" name="email" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>
            <div class="mb-6">
                <label for="password" class="block text-gray-700 font-medium mb-2">Mật khẩu</label>
                <input type="password" id="password" name="password" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>
            <div>
                <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition duration-300">Đăng ký</button>
            </div>
        </form>

        <p class="text-center mt-6 text-gray-600">
            Đã có tài khoản? <a href="/shoe-shop/public/login" class="text-blue-600 hover:underline">Đăng nhập ngay</a>
        </p>
    </div>

</body>
</html>