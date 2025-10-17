<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập - GON SHOES</title>
    <link href="/shoe-shop/public/css/style.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
            background: url('/shoe-shop/public/images/background/bg-auth.png') no-repeat center center fixed;
            background-size: cover;
            position: relative;
        }

        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(0, 0, 0, 0.7) 0%, rgba(0, 0, 0, 0.5) 100%);
            z-index: 1;
        }

        .auth-container {
            position: relative;
            z-index: 2;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .auth-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 24px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            width: 100%;
            max-width: 450px;
            padding: 48px 40px;
            animation: slideUp 0.6s ease-out;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .auth-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .auth-logo {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            margin-bottom: 16px;
        }

        .auth-logo i {
            font-size: 36px;
            color: #000;
        }

        .auth-logo span {
            font-size: 28px;
            font-weight: 800;
            color: #000;
            letter-spacing: -0.5px;
        }

        .auth-title {
            font-size: 28px;
            font-weight: 700;
            color: #1a202c;
            margin-bottom: 8px;
        }

        .auth-subtitle {
            font-size: 15px;
            color: #718096;
        }

        .alert {
            padding: 14px 16px;
            border-radius: 12px;
            margin-bottom: 24px;
            font-size: 14px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 10px;
            animation: slideDown 0.3s ease-out;
            transition: opacity 0.3s ease-out, transform 0.3s ease-out;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .alert-error {
            background: #fee;
            color: #c53030;
            border: 1px solid #fc8181;
        }

        .alert-success {
            background: #f0fff4;
            color: #2f855a;
            border: 1px solid #9ae6b4;
        }

        .form-group {
            margin-bottom: 24px;
        }

        .form-label {
            display: block;
            font-size: 14px;
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 8px;
        }

        .input-wrapper {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #a0aec0;
            font-size: 16px;
            pointer-events: none;
            transition: color 0.3s;
        }

        .form-input {
            width: 100%;
            padding: 14px 16px 14px 48px;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            font-size: 15px;
            color: #2d3748;
            transition: all 0.3s;
            background: #fff;
        }

        .form-input:focus {
            outline: none;
            border-color: #000;
            box-shadow: 0 0 0 4px rgba(0, 0, 0, 0.05);
        }

        .form-input:focus+.input-icon {
            color: #000;
        }

        .password-wrapper {
            position: relative;
        }

        .password-toggle {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #a0aec0;
            cursor: pointer;
            font-size: 16px;
            padding: 4px;
            transition: color 0.3s;
        }

        .password-toggle:hover {
            color: #000;
        }

        .forgot-password {
            text-align: right;
            margin-top: -12px;
            margin-bottom: 24px;
        }

        .forgot-password a {
            font-size: 14px;
            color: #000;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s;
        }

        .forgot-password a:hover {
            color: #4a5568;
        }

        .btn-primary {
            width: 100%;
            padding: 14px;
            background: #000;
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            position: relative;
            overflow: hidden;
        }

        .btn-primary::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
            transition: left 0.5s;
        }

        .btn-primary:hover::before {
            left: 100%;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
            background: #1a1a1a;
        }

        .btn-primary:active {
            transform: translateY(0);
        }

        .divider {
            text-align: center;
            margin: 32px 0;
            position: relative;
        }

        .divider::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            width: 100%;
            height: 1px;
            background: #e2e8f0;
        }

        .divider span {
            position: relative;
            background: rgba(255, 255, 255, 0.95);
            padding: 0 16px;
            color: #718096;
            font-size: 14px;
            font-weight: 500;
        }

        .auth-footer {
            text-align: center;
            font-size: 15px;
            color: #4a5568;
        }

        .auth-footer a {
            color: #000;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s;
        }

        .auth-footer a:hover {
            color: #4a5568;
        }

        @media (max-width: 640px) {
            .auth-card {
                padding: 32px 24px;
            }

            .auth-title {
                font-size: 24px;
            }
        }
    </style>
</head>

<body>
    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-header">
                <div class="auth-logo">
                    <i class="fas fa-shoe-prints"></i>
                    <span>GON SHOES</span>
                </div>
                <h1 class="auth-title">Chào mừng trở lại!</h1>
                <p class="auth-subtitle">Đăng nhập để tiếp tục mua sắm</p>
            </div>

            <?php if (isset($_SESSION['success_message']) && !empty($_SESSION['success_message'])): ?>
                <div class="alert alert-success" id="successAlert">
                    <i class="fas fa-check-circle"></i>
                    <span><?= htmlspecialchars($_SESSION['success_message']) ?></span>
                </div>
                <?php unset($_SESSION['success_message']); ?>
            <?php endif; ?>

            <?php if (isset($errorMessage) && !empty($errorMessage)): ?>
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle"></i>
                    <span><?= htmlspecialchars($errorMessage) ?></span>
                </div>
            <?php endif; ?>
            <?php if (isset($_SESSION['login_message'])): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    <span><?= htmlspecialchars($_SESSION['login_message']) ?></span>
                </div>
                <?php unset($_SESSION['login_message']); ?>
            <?php endif; ?>
            <form action="/shoe-shop/public/login" method="POST" novalidate>
                <div class="form-group">
                    <label for="email" class="form-label">Email</label>
                    <div class="input-wrapper">
                        <input
                            type="email"
                            id="email"
                            name="email"
                            class="form-input"
                            placeholder="example@email.com"
                            value="<?= htmlspecialchars($oldInput['email'] ?? '') ?>"
                            required>
                        <i class="fas fa-envelope input-icon"></i>
                    </div>
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">Mật khẩu</label>
                    <div class="input-wrapper password-wrapper">
                        <input
                            type="password"
                            id="password"
                            name="password"
                            class="form-input"
                            placeholder="Nhập mật khẩu của bạn"
                            required>
                        <i class="fas fa-lock input-icon"></i>
                        <button type="button" class="password-toggle" onclick="togglePassword()">
                            <i class="fas fa-eye" id="toggleIcon"></i>
                        </button>
                    </div>
                </div>

                <div class="forgot-password">
                    <a href="/shoe-shop/public/forgot-password">
                        <i class="fas fa-key"></i> Quên mật khẩu?
                    </a>
                </div>

                <button type="submit" class="btn-primary">
                    <i class="fas fa-sign-in-alt"></i> Đăng nhập
                </button>
            </form>

            <div class="divider">
                <span>Hoặc</span>
            </div>

            <div class="auth-footer">
                Chưa có tài khoản? <a href="/shoe-shop/public/register">Đăng ký ngay</a>
            </div>
        </div>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('toggleIcon');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }

        // Add floating label effect
        document.querySelectorAll('.form-input').forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.style.transform = 'scale(1.01)';
            });

            input.addEventListener('blur', function() {
                this.parentElement.style.transform = 'scale(1)';
            });
        });

        // Auto-hide success message after 5 seconds
        const successAlert = document.getElementById('successAlert');
        if (successAlert) {
            setTimeout(() => {
                successAlert.style.opacity = '0';
                successAlert.style.transform = 'translateY(-10px)';
                setTimeout(() => {
                    successAlert.remove();
                }, 300);
            }, 5000);
        }
    </script>
</body>

</html>
