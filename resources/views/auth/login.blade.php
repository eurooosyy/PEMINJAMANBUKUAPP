<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Peminjaman Buku</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            height: 100vh;
            overflow: hidden;
            position: relative;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        /* Animated Background */
        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background:
                radial-gradient(circle at 20% 80%, rgba(120, 119, 198, 0.3) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(255, 119, 198, 0.3) 0%, transparent 50%),
                radial-gradient(circle at 40% 40%, rgba(120, 219, 255, 0.3) 0%, transparent 50%);
            animation: float 20s ease-in-out infinite;
            z-index: 1;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0px) rotate(0deg);
            }

            50% {
                transform: translateY(-20px) rotate(1deg);
            }
        }

        /* Login Container */
        .login-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 24px;
            padding: 50px 40px;
            width: 100%;
            max-width: 420px;
            box-shadow:
                0 25px 45px rgba(0, 0, 0, 0.1),
                0 0 0 1px rgba(255, 255, 255, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.2);
            transform: translateY(0);
            animation: slideUp 0.8s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            position: relative;
            z-index: 2;
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

        .logo-section {
            text-align: center;
            margin-bottom: 35px;
        }

        .logo-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            box-shadow: 0 15px 35px rgba(102, 126, 234, 0.4);
            animation: pulse 2s infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.05);
            }
        }

        .logo-icon i {
            font-size: 32px;
            color: white;
        }

        .login-title {
            font-size: 28px;
            font-weight: 700;
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 8px;
            letter-spacing: -0.5px;
        }

        .login-subtitle {
            color: #6c757d;
            font-size: 15px;
            font-weight: 400;
        }

        .form-group {
            margin-bottom: 22px;
            position: relative;
        }

        .form-group input {
            width: 100%;
            padding: 16px 20px;
            border: 2px solid rgba(102, 126, 234, 0.2);
            border-radius: 14px;
            font-size: 15px;
            font-weight: 500;
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(10px);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            font-family: 'Poppins', sans-serif;
        }

        .form-group input:focus {
            outline: none;
            border-color: #667eea;
            background: rgba(255, 255, 255, 0.95);
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.15);
            transform: translateY(-2px);
        }

        .form-group label {
            position: absolute;
            left: 20px;
            top: 16px;
            font-size: 15px;
            font-weight: 500;
            color: #6c757d;
            pointer-events: none;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.9);
            padding: 0 6px;
        }

        .form-group input:focus+label,
        .form-group input:valid+label {
            top: -8px;
            left: 16px;
            font-size: 12px;
            color: #667eea;
            font-weight: 600;
        }

        .btn-login {
            width: 100%;
            padding: 16px;
            border: none;
            border-radius: 14px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            margin-top: 10px;
            position: relative;
            overflow: hidden;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .btn-login:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(102, 126, 234, 0.4);
        }

        .btn-login:active {
            transform: translateY(-1px);
        }

        .error-message {
            color: #e74c3c;
            font-size: 13px;
            margin-top: 8px;
            padding: 10px;
            background: rgba(231, 76, 60, 0.1);
            border-radius: 8px;
            border-left: 4px solid #e74c3c;
            animation: shake 0.5s ease-in-out;
        }

        @keyframes shake {

            0%,
            100% {
                transform: translateX(0);
            }

            25% {
                transform: translateX(-5px);
            }

            75% {
                transform: translateX(5px);
            }
        }

        .register-link {
            text-align: center;
            margin-top: 28px;
            padding-top: 25px;
            border-top: 1px solid rgba(102, 126, 234, 0.2);
        }

        .register-link a {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
            font-size: 15px;
        }

        .register-link a:hover {
            text-decoration: underline;
        }

        @media (max-width: 480px) {
            .login-container {
                padding: 40px 30px;
                margin: 10px;
            }

            .login-title {
                font-size: 24px;
            }
        }

        .success-message {
            background: linear-gradient(135deg, #56ab2f, #a8e6cf);
            color: white;
            padding: 12px;
            border-radius: 12px;
            margin-bottom: 20px;
            text-align: center;
            font-weight: 500;
            animation: slideUp 0.6s ease;
        }
    </style>
</head>

<body>
    <div class="login-container">
        <div class="logo-section">
            <div class="logo-icon">
                <i class="fas fa-book-open"></i>
            </div>
            <h1 class="login-title">Peminjaman Buku</h1>
            <p class="login-subtitle">Sistem Manajemen Peminjaman Buku Perpustakaan</p>
        </div>

        <!-- Error Messages -->
        @if ($errors->any())
            <div class="error-message">
                <i class="fas fa-exclamation-triangle"></i>
                {{ $errors->first() }}
            </div>
        @endif

        @if (session('error'))
            <div class="error-message">
                <i class="fas fa-exclamation-triangle"></i>
                {{ session('error') }}
            </div>
        @endif

        @if (session('success'))
            <div class="success-message">
                <i class="fas fa-check-circle"></i>
                {{ session('success') }}
            </div>
        @endif

        <!-- Login Form -->
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="form-group">
                <input type="email" name="email" id="email" placeholder=" " value="{{ old('email') }}" required
                    autofocus>
                <label for="email"><i class="fas fa-envelope"></i> Email</label>
                @error('email')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <input type="password" name="password" id="password" placeholder=" " required>
                <label for="password"><i class="fas fa-lock"></i> Password</label>
                @error('password')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <button class="btn-login" type="submit">
                <i class="fas fa-sign-in-alt"></i>
                Masuk ke Sistem
            </button>
        </form>

        <div class="register-link">
            Belum punya akun? <a href="{{ route('register') }}">Daftar Sekarang</a>
        </div>
    </div>

    <script>
        // Form enhancement
        document.querySelectorAll('input').forEach(input => {
            input.addEventListener('keyup', function() {
                this.style.background = 'rgba(255,255,255,0.95)';
            });
        });
    </script>
</body>

</html>
