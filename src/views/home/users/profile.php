<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Thông tin cá nhân</title>
    <link href="/shoe-shop/public/css/style.css" rel="stylesheet">
</head>

<body class="bg-gray-100">
    <?php require_once __DIR__ . '/../../layout/header.php'; ?>
    <main class="container mx-auto px-6 py-12">
        <div class="max-w-2xl mx-auto bg-white p-8 rounded-lg shadow-md">
            <h1 class="text-3xl font-bold mb-6 text-center">Thông tin cá nhân</h1>

            <?php if (isset($_SESSION['profile_success'])): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <?= htmlspecialchars($_SESSION['profile_success']) ?>
                </div>
                <?php unset($_SESSION['profile_success']); ?>
            <?php endif; ?>
            <?php if (isset($_SESSION['profile_error'])): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <?= htmlspecialchars($_SESSION['profile_error']) ?>
                </div>
                <?php unset($_SESSION['profile_error']); ?>
            <?php endif; ?>

            <form action="/shoe-shop/public/profile" method="POST">
                <div class="border-b pb-6">
                    <h2 class="text-xl font-semibold mb-4">Thông tin cơ bản</h2>
                    <div class="mb-4">
                        <label for="fullname" class="block text-gray-700 font-medium mb-2">Họ và tên</label>
                        <input type="text" name="fullname" id="fullname" value="<?= htmlspecialchars($user->fullname) ?>" class="w-full px-4 py-2 border rounded-lg">
                    </div>
                    <div class="mb-4">
                        <label for="email" class="block text-gray-700 font-medium mb-2">Email</label>
                        <input type="email" id="email" value="<?= htmlspecialchars($user->email) ?>" class="w-full px-4 py-2 border rounded-lg bg-gray-200" readonly>
                    </div>
                </div>

                <div class="mt-6">
                    <h2 class="text-xl font-semibold mb-4">Đổi mật khẩu</h2>
                    <div class="mb-4">
                        <label for="current_password" class="block text-gray-700 font-medium mb-2">Mật khẩu hiện tại</label>
                        <input type="password" name="current_password" id="current_password" class="w-full px-4 py-2 border rounded-lg" placeholder="Bỏ trống nếu không đổi">
                    </div>
                    <div class="mb-4">
                        <label for="new_password" class="block text-gray-700 font-medium mb-2">Mật khẩu mới</label>
                        <input type="password" name="new_password" id="new_password" class="w-full px-4 py-2 border rounded-lg">
                    </div>
                    <div class="mb-4">
                        <label for="confirm_password" class="block text-gray-700 font-medium mb-2">Xác nhận mật khẩu mới</label>
                        <input type="password" name="confirm_password" id="confirm_password" class="w-full px-4 py-2 border rounded-lg">
                    </div>
                </div>

                <div class="mt-8">
                    <button type="submit" class="w-full bg-blue-600 text-white font-bold py-3 rounded-lg hover:bg-blue-700 transition">Lưu thay đổi</button>
                </div>
            </form>
        </div>
    </main>
    <?php require_once __DIR__ . '/../../layout/footer.php'; ?>
</body>

</html>
