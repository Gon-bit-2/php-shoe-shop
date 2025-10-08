<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Quản lý Người dùng</title>
    <link href="/shoe-shop/public/css/style.css" rel="stylesheet">
</head>

<body class="bg-gray-100">
    <?php require_once __DIR__ . '/../partials/navbar.php'; ?>
    <div class="container mx-auto mt-10 p-8">
        <h1 class="text-3xl font-bold mb-6">Quản lý Người dùng</h1>
        <div class="bg-white shadow-md rounded-lg overflow-x-auto">
            <table class="min-w-full leading-normal">
                <thead>
                    <tr>
                        <th class="px-5 py-3 border-b-2 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase">Họ và tên</th>
                        <th class="px-5 py-3 border-b-2 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase">Email</th>
                        <th class="px-5 py-3 border-b-2 bg-gray-50 text-center text-xs font-semibold text-gray-600 uppercase">Vai trò</th>
                        <th class="px-5 py-3 border-b-2 bg-gray-50 text-center text-xs font-semibold text-gray-600 uppercase">Trạng thái</th>
                        <th class="px-5 py-3 border-b-2 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td class="px-5 py-5 border-b bg-white text-sm"><?= htmlspecialchars($user->fullname) ?></td>
                            <td class="px-5 py-5 border-b bg-white text-sm"><?= htmlspecialchars($user->email) ?></td>
                            <td class="px-5 py-5 border-b bg-white text-sm text-center"><?= htmlspecialchars($user->role_name) ?></td>
                            <td class="px-5 py-5 border-b bg-white text-sm text-center">
                                <span class="px-2 py-1 font-semibold leading-tight rounded-full <?= $user->is_active ? 'bg-green-200 text-green-800' : 'bg-red-200 text-red-800' ?>">
                                    <?= $user->is_active ? 'Hoạt động' : 'Bị khóa' ?>
                                </span>
                            </td>
                            <td class="px-5 py-5 border-b bg-white text-sm">
                                <a href="/shoe-shop/public/admin/users/edit/<?= $user->id ?>" class="text-indigo-600 hover:text-indigo-900">Sửa</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>
