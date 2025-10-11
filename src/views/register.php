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

        <?php if (isset($errorMessage) && !empty($errorMessage)): ?>
            <div class="mb-4 text-center text-red-500 bg-red-100 p-3 rounded-md">
                <?= htmlspecialchars($errorMessage) ?>
            </div>
        <?php endif; ?>

        <form action="/shoe-shop/public/register" method="POST" novalidate id="registerForm">
            <div class="mb-4">
                <label for="fullname" class="block text-gray-700 font-medium mb-2 ">Họ và tên</label>
                <input type="text" id="fullname" name="fullname" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" value="<?= htmlspecialchars($oldInput['fullname'] ?? '') ?>" required>

            </div>
            <div class="mb-4">
                <label for="email" class="block text-gray-700 font-medium mb-2">Email</label>
                <input type="email" id="email" name="email" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" value="<?= htmlspecialchars($oldInput['email'] ?? '') ?>" required>

            </div>
            <div class="mb-4">
                <label for="password" class="block text-gray-700 font-medium mb-2">Mật khẩu</label>
                <input type="password" id="password" name="password" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                <div id="password-error" class="text-red-500 text-sm mt-1 hidden"></div>
            </div>
            <div class="mb-6">
                <label for="confirm_password" class="block text-gray-700 font-medium mb-2">Xác nhận mật khẩu</label>
                <input type="password" id="confirm_password" name="confirm_password" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                <div id="confirm-password-error" class="text-red-500 text-sm mt-1 hidden"></div>
            </div>
            <div>
                <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition duration-300">Đăng ký</button>
            </div>
        </form>

        <p class="text-center mt-6 text-gray-600">
            Đã có tài khoản? <a href="/shoe-shop/public/login" class="text-blue-600 hover:underline">Đăng nhập ngay</a>
        </p>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const passwordInput = document.getElementById('password');
            const confirmPasswordInput = document.getElementById('confirm_password');
            const passwordError = document.getElementById('password-error');
            const confirmPasswordError = document.getElementById('confirm-password-error');
            const form = document.getElementById('registerForm');

            function validatePassword() {
                const password = passwordInput.value;
                const confirmPassword = confirmPasswordInput.value;

                // Clear previous errors
                passwordError.classList.add('hidden');
                confirmPasswordError.classList.add('hidden');

                // Validate password length
                if (password.length > 0 && password.length < 6) {
                    passwordError.textContent = 'Mật khẩu phải có ít nhất 6 ký tự!';
                    passwordError.classList.remove('hidden');
                    return false;
                }

                // Validate password confirmation
                if (confirmPassword.length > 0 && password !== confirmPassword) {
                    confirmPasswordError.textContent = 'Mật khẩu xác nhận không khớp!';
                    confirmPasswordError.classList.remove('hidden');
                    return false;
                }

                return true;
            }

            // Real-time validation
            passwordInput.addEventListener('input', validatePassword);
            confirmPasswordInput.addEventListener('input', validatePassword);

            // Form submission validation
            form.addEventListener('submit', function(e) {
                if (!validatePassword()) {
                    e.preventDefault();
                }
            });
        });
    </script>

</body>

</html>
