<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Đặt lại mật khẩu</title>
    <link href="/shoe-shop/public/css/style.css" rel="stylesheet">
</head>

<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="w-full max-w-md bg-white p-8 rounded-lg shadow-md">
        <h2 class="text-2xl font-bold text-center mb-6">Tạo mật khẩu mới</h2>
        <?php if (isset($_SESSION['reset_error'])): ?>
            <div class="bg-red-100 text-red-700 p-3 rounded mb-4 text-center">
                <?= htmlspecialchars($_SESSION['reset_error']) ?>
            </div>
            <?php unset($_SESSION['reset_error']); ?>
        <?php endif; ?>
        <form action="/shoe-shop/public/reset-password" method="POST">
            <input type="hidden" name="token" value="<?= htmlspecialchars($_GET['token'] ?? '') ?>">
            <div class="mb-4">
                <label for="password" class="block text-gray-700 font-medium mb-2">Mật khẩu mới</label>
                <input type="password" name="password" id="password" class="w-full px-4 py-2 border rounded-lg" required>
            </div>
            <div class="mb-6">
                <label for="confirm_password" class="block text-gray-700 font-medium mb-2">Xác nhận mật khẩu</label>
                <input type="password" name="confirm_password" id="confirm_password" class="w-full px-4 py-2 border rounded-lg" required>
            </div>
            <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700">Đặt lại mật khẩu</button>
        </form>
    </div>
</body>

</html>
