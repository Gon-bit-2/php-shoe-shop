<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Quên mật khẩu</title>
    <link href="/shoe-shop/public/css/style.css" rel="stylesheet">
</head>

<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="w-full max-w-md bg-white p-8 rounded-lg shadow-md">
        <h2 class="text-2xl font-bold text-center mb-4">Quên mật khẩu?</h2>
        <p class="text-center text-gray-600 mb-6">Nhập email của bạn để nhận liên kết đặt lại mật khẩu.</p>
        <?php if (isset($_SESSION['forgot_message'])): ?>
            <div class="bg-green-100 text-green-700 p-3 rounded mb-4 text-center">
                <?= htmlspecialchars($_SESSION['forgot_message']) ?>
            </div>
            <?php unset($_SESSION['forgot_message']); ?>
        <?php endif; ?>
        <form action="/shoe-shop/public/forgot-password" method="POST">
            <div class="mb-4">
                <label for="email" class="block text-gray-700 font-medium mb-2">Email</label>
                <input type="email" name="email" id="email" class="w-full px-4 py-2 border rounded-lg" required>
            </div>
            <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700">Gửi liên kết</button>
        </form>
        <p class="text-center mt-6"><a href="/shoe-shop/public/login" class="text-blue-600 hover:underline">Quay lại Đăng nhập</a></p>
    </div>
</body>

</html>
