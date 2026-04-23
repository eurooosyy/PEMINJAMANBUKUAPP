<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - Peminjaman Alat</title>
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
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .register-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 24px;
            padding: 50px 40px;
            width: 100%;
            max-width: 450px;
            box-shadow: 0 25px 45px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            animation: slideUp 0.8s cubic-bezier(0.25, 0.46, 0.45, 0.94);
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
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            box-shadow: 0 12px 30px rgba(102, 126, 234, 0.4);
        }

        .logo-icon i {
            color: white;
            font-size: 28px;
        }

        .register-title {
            font-size: 26px;
            font-weight: 700;
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 8px;
        }

        .form-group {
            margin-bottom: 24px;
            position: relative;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 16px 20px;
            border: 2px solid rgba(102, 126, 234, 0.2);
            border-radius: 14px;
            font-size: 15px;
            background: rgba(255, 255, 255, 0.8);
            transition: all 0.3s ease;
            font-family: 'Poppins', sans-serif;
        }

        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.15);
            transform: translateY(-2px);
            background: rgba(255, 255, 255, 0.95);
        }

        .form-group label {
            position: absolute;
            left: 20px;
            top: 16px;
            color: #6c757d;
            font-weight: 500;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.9);
            padding: 0 6px;
            pointer-events: none;
            font-size: 15px;
        }

        .form-group input:focus+label,
        .form-group input:valid+label,
        .form-group select:focus+label {
            top: -8px;
            left: 16px;
            font-size: 12px;
            color: #667eea;
            font-weight: 600;
        }

        .btn-register {
            width: 100%;
            padding: 16px;
            border: none;
            border-radius: 14px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            margin-top: 12px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            position: relative;
            overflow: hidden;
        }

        .btn-register:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(102, 126, 234, 0.4);
        }

        .error-message {
            color: #e74c3c;
            font-size: 13px;
            margin-top: 8px;
            padding: 10px;
            background: rgba(231, 76, 60, 0.1);
            border-radius: 8px;
            border-left: 4px solid #e74c3c;
        }

        .login-link {
            text-align: center;
            margin-top: 28px;
            padding-top: 25px;
            border-top: 1px solid rgba(102, 126, 234, 0.2);
            font-size: 15px;
        }

        .login-link a {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
        }

        .login-link a:hover {
            text-decoration: underline;
        }

        @media (max-width: 480px) {
            .register-container {
                padding: 40px 25px;
                margin: 10px;
            }
        }
    </style>
</head>

<body>
    <div class="register-container">
        <div class="logo-section">
            <div class="logo-icon">
                <i class="fas fa-user-plus"></i>
            </div>
            <h1 class="register-title">Peminjaman Alat</h1>
            <p style="color: #6c757d; font-size: 15px; text-align: center;">Buat akun baru</p>
        </div>

        @if (session('success'))
            <div
                style="background: linear-gradient(135deg, #56ab2f, #a8e6cf); color: white; padding: 15px; border-radius: 12px; margin-bottom: 25px; text-align: center; font-weight: 500;">
                <i class="fas fa-check-circle" style="margin-right: 8px;"></i>
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}">
            @csrf
            <div class="form-group">
                <input type="text" name="name" id="name" placeholder=" " value="{{ old('name') }}"
                    required>
                <label for="name"><i class="fas fa-user"></i> Nama Lengkap</label>
                @error('name')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <input type="email" name="email" id="email" placeholder=" " value="{{ old('email') }}"
                    required>
                <label for="email"><i class="fas fa-envelope"></i> Email</label>
                @error('email')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <select name="role_id" id="role_id" required>
                    <option value="">-- Pilih Role --</option>
                    @forelse($roles as $role)
                        <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                            {{ $role->name }}
                        </option>
                    @empty
                        <option disabled>Tidak ada role tersedia</option>
                    @endforelse
                </select>
                <label for="role_id"><i class="fas fa-user-tag"></i> Role</label>
                @error('role_id')
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

            <div class="form-group">
                <input type="password" name="password_confirmation" id="password_confirmation" placeholder=" " required>
                <label for="password_confirmation"><i class="fas fa-lock-open"></i> Konfirmasi Password</label>
                @error('password_confirmation')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn-register">
                <i class="fas fa-user-plus"></i>
                Daftar Akun
            </button>
        </form>

        <div class="login-link">
            Sudah punya akun? <a href="{{ route('login') }}">Masuk sekarang</a>
        </div>
    </div>
</body>

</html>
