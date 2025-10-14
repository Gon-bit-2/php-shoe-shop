<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đặt lại mật khẩu - GON SHOES</title>
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
            margin-bottom: 32px;
        }

        .auth-logo {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            margin-bottom: 24px;
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

        .icon-wrapper {
            width: 80px;
            height: 80px;
            margin: 0 auto 24px;
            background: #000;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);
        }

        .icon-wrapper i {
            font-size: 36px;
            color: white;
        }

        .auth-title {
            font-size: 26px;
            font-weight: 700;
            color: #1a202c;
            margin-bottom: 12px;
        }

        .auth-subtitle {
            font-size: 15px;
            color: #718096;
            line-height: 1.6;
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

        .form-group {
            margin-bottom: 20px;
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

        .form-input.error {
            border-color: #fc8181;
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

        .error-message {
            font-size: 13px;
            color: #e53e3e;
            margin-top: 6px;
            display: none;
            animation: slideDown 0.2s ease-out;
        }

        .error-message.show {
            display: block;
        }

        .password-strength {
            margin-top: 8px;
            height: 4px;
            background: #e2e8f0;
            border-radius: 2px;
            overflow: hidden;
            display: none;
        }

        .password-strength.show {
            display: block;
        }

        .password-strength-bar {
            height: 100%;
            transition: all 0.3s;
            border-radius: 2px;
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
            margin-top: 8px;
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

        @media (max-width: 640px) {
            .auth-card {
                padding: 32px 24px;
            }

            .auth-title {
                font-size: 22px;
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
                <div class="icon-wrapper">
                    <i class="fas fa-lock-open"></i>
                </div>
                <h1 class="auth-title">Tạo mật khẩu mới</h1>
                <p class="auth-subtitle">Vui lòng nhập mật khẩu mới cho tài khoản của bạn.</p>
            </div>

            <?php if (isset($_SESSION['reset_error'])): ?>
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle"></i>
                    <span><?= htmlspecialchars($_SESSION['reset_error']) ?></span>
                </div>
                <?php unset($_SESSION['reset_error']); ?>
            <?php endif; ?>

            <form action="/shoe-shop/public/reset-password" method="POST" id="resetForm">
                <input type="hidden" name="token" value="<?= htmlspecialchars($_GET['token'] ?? '') ?>">

                <div class="form-group">
                    <label for="password" class="form-label">Mật khẩu mới</label>
                    <div class="input-wrapper password-wrapper">
                        <input
                            type="password"
                            name="password"
                            id="password"
                            class="form-input"
                            placeholder="Tối thiểu 6 ký tự"
                            required>
                        <i class="fas fa-lock input-icon"></i>
                        <button type="button" class="password-toggle" onclick="togglePassword('password', 'toggleIcon1')">
                            <i class="fas fa-eye" id="toggleIcon1"></i>
                        </button>
                    </div>
                    <div class="password-strength" id="passwordStrength">
                        <div class="password-strength-bar" id="passwordStrengthBar"></div>
                    </div>
                    <div class="error-message" id="password-error"></div>
                </div>

                <div class="form-group">
                    <label for="confirm_password" class="form-label">Xác nhận mật khẩu</label>
                    <div class="input-wrapper password-wrapper">
                        <input
                            type="password"
                            name="confirm_password"
                            id="confirm_password"
                            class="form-input"
                            placeholder="Nhập lại mật khẩu"
                            required>
                        <i class="fas fa-lock input-icon"></i>
                        <button type="button" class="password-toggle" onclick="togglePassword('confirm_password', 'toggleIcon2')">
                            <i class="fas fa-eye" id="toggleIcon2"></i>
                        </button>
                    </div>
                    <div class="error-message" id="confirm-password-error"></div>
                </div>

                <button type="submit" class="btn-primary">
                    <i class="fas fa-check-circle"></i> Đặt lại mật khẩu
                </button>
            </form>
        </div>
    </div>

    <script>
        function togglePassword(inputId, iconId) {
            const passwordInput = document.getElementById(inputId);
            const toggleIcon = document.getElementById(iconId);

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

        document.addEventListener('DOMContentLoaded', function() {
            const passwordInput = document.getElementById('password');
            const confirmPasswordInput = document.getElementById('confirm_password');
            const passwordError = document.getElementById('password-error');
            const confirmPasswordError = document.getElementById('confirm-password-error');
            const form = document.getElementById('resetForm');
            const passwordStrength = document.getElementById('passwordStrength');
            const passwordStrengthBar = document.getElementById('passwordStrengthBar');

            function checkPasswordStrength(password) {
                let strength = 0;
                if (password.length >= 6) strength += 25;
                if (password.length >= 8) strength += 25;
                if (/[a-z]/.test(password) && /[A-Z]/.test(password)) strength += 25;
                if (/\d/.test(password)) strength += 15;
                if (/[@$!%*?&]/.test(password)) strength += 10;

                if (password.length > 0) {
                    passwordStrength.classList.add('show');
                    passwordStrengthBar.style.width = strength + '%';

                    if (strength < 40) {
                        passwordStrengthBar.style.background = '#fc8181';
                    } else if (strength < 70) {
                        passwordStrengthBar.style.background = '#f6ad55';
                    } else {
                        passwordStrengthBar.style.background = '#68d391';
                    }
                } else {
                    passwordStrength.classList.remove('show');
                }
            }

            function validatePassword() {
                const password = passwordInput.value;
                const confirmPassword = confirmPasswordInput.value;
                let isValid = true;

                // Clear previous errors
                passwordError.classList.remove('show');
                confirmPasswordError.classList.remove('show');
                passwordInput.classList.remove('error');
                confirmPasswordInput.classList.remove('error');

                // Validate password length
                if (password.length > 0 && password.length < 6) {
                    passwordError.textContent = 'Mật khẩu phải có ít nhất 6 ký tự!';
                    passwordError.classList.add('show');
                    passwordInput.classList.add('error');
                    isValid = false;
                }

                // Validate password confirmation
                if (confirmPassword.length > 0 && password !== confirmPassword) {
                    confirmPasswordError.textContent = 'Mật khẩu xác nhận không khớp!';
                    confirmPasswordError.classList.add('show');
                    confirmPasswordInput.classList.add('error');
                    isValid = false;
                }

                return isValid;
            }

            // Real-time validation
            passwordInput.addEventListener('input', function() {
                checkPasswordStrength(this.value);
                validatePassword();
            });

            confirmPasswordInput.addEventListener('input', validatePassword);

            // Form submission validation
            form.addEventListener('submit', function(e) {
                if (!validatePassword()) {
                    e.preventDefault();
                }
            });

            // Add floating effect
            document.querySelectorAll('.form-input').forEach(input => {
                input.addEventListener('focus', function() {
                    this.parentElement.style.transform = 'scale(1.01)';
                });

                input.addEventListener('blur', function() {
                    this.parentElement.style.transform = 'scale(1)';
                });
            });
        });
    </script>
</body>

</html>
