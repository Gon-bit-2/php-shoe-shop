<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Sửa thông tin Người dùng</title>
    <link href="/shoe-shop/public/css/style.css" rel="stylesheet">
</head>

<body class="bg-gray-100">
    <?php require_once __DIR__ . '/../partials/navbar.php'; ?>
    <div class="container mx-auto mt-10 p-8 bg-white max-w-lg shadow-md rounded-lg">
        <h1 class="text-3xl font-bold mb-6">Sửa Người dùng</h1>
        <?php if (isset($_SESSION['user_error'])): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <?= htmlspecialchars($_SESSION['user_error']); ?>
            </div>
            <?php unset($_SESSION['user_error']); ?>
        <?php endif; ?>
        <form action="/shoe-shop/public/admin/users/edit/<?= $user->id ?>" method="POST">
            <div class="mb-4">
                <label for="fullname" class="block text-gray-700 font-bold mb-2">Họ và tên</label>
                <input type="text" name="fullname" id="fullname" value="<?= htmlspecialchars($user->fullname) ?>" class="shadow border rounded w-full py-2 px-3">
            </div>
            <div class="mb-4">
                <label for="email" class="block text-gray-700 font-bold mb-2">Email</label>
                <input type="email" name="email" id="email" value="<?= htmlspecialchars($user->email) ?>" class="shadow border rounded w-full py-2 px-3">
            </div>
            <div class="mb-4">
                <label for="role_id" class="block text-gray-700 font-bold mb-2">Vai trò</lgitabel>
                    <select name="role_id" id="role_id" class="shadow border rounded w-full py-2 px-3">
                        <option value="1" <?= $user->role_id == 1 ? 'selected' : '' ?>>Admin</option>
                        <option value="2" <?= $user->role_id == 2 ? 'selected' : '' ?>>User</option>
                    </select>
            </div>
            <div class="mb-6">
                <label class="block text-gray-700 font-bold mb-2">Trạng thái tài khoản</label>
                <label class="inline-flex items-center">
                    <input type="checkbox" name="is_active" value="1" class="mr-2 h-5 w-5" <?= $user->is_active ? 'checked' : '' ?>>
                    <span>Kích hoạt (Cho phép đăng nhập)</span>
                </label>
            </div>
            <div class="flex items-center justify-end gap-4 mt-8">
                <a href="/shoe-shop/public/admin/users" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">Hủy</a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Lưu thay đổi</button>
            </div>
        </form>
    </div>
</body>

</html>
