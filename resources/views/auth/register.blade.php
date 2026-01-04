<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bina Desa - Registrasi</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --primary: #2c3e50;
            --secondary: #3498db;
            --accent: #27ae60;
        }

        body {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }

        .register-box {
            background: white;
            border-radius: 15px;
            padding: 35px;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 15px 30px rgba(0,0,0,0.15);
        }

        .logo-header {
            text-align: center;
            margin-bottom: 25px;
        }

        .logo-header h1 {
            color: var(--primary);
            font-weight: 700;
            font-size: 1.8rem;
            margin-bottom: 5px;
        }

        .logo-header p {
            color: #666;
            font-size: 0.9rem;
        }

        .form-label {
            font-weight: 500;
            color: #333;
            font-size: 0.9rem;
            margin-bottom: 5px;
        }

        .form-control {
            padding: 10px 12px;
            border-radius: 8px;
            border: 1px solid #ddd;
            font-size: 0.95rem;
        }

        .form-control:focus {
            border-color: var(--secondary);
            box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.25);
        }

        .btn-register {
            background: linear-gradient(135deg, var(--secondary) 0%, var(--accent) 100%);
            color: white;
            border: none;
            padding: 12px;
            border-radius: 8px;
            font-weight: 600;
            width: 100%;
            margin-top: 10px;
            font-size: 1rem;
        }

        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 15px rgba(52, 152, 219, 0.3);
        }

        .login-link {
            text-align: center;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }

        .login-link p {
            color: #666;
            font-size: 0.9rem;
            margin-bottom: 10px;
        }

        .btn-login {
            border: 1px solid var(--secondary);
            color: var(--secondary);
            background: white;
            padding: 10px;
            border-radius: 8px;
            font-weight: 500;
            width: 100%;
            text-decoration: none;
            display: block;
            text-align: center;
        }

        .btn-login:hover {
            background: var(--secondary);
            color: white;
        }

        .copyright {
            text-align: center;
            margin-top: 15px;
            font-size: 0.75rem;
            color: #888;
        }

        .alert {
            font-size: 0.9rem;
            padding: 10px 15px;
            margin-bottom: 20px;
        }

        .password-toggle {
            cursor: pointer;
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            color: #666;
            z-index: 10;
        }

        .input-group {
            position: relative;
        }
    </style>
</head>
<body>
    <div class="register-box">
        <!-- Header -->
        <div class="logo-header">
            <h1>BINA DESA</h1>
            <p>Sistem Administrasi Pertanahan</p>
        </div>

        <!-- Pesan Error/Success -->
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <!-- Form Register -->
        <form action="{{ route('auth.register.post') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label for="name" class="form-label">Nama Lengkap</label>
                <input type="text"
                       name="name"
                       class="form-control"
                       placeholder="Masukkan nama lengkap"
                       id="name"
                       value="{{ old('name') }}"
                       required>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email"
                       name="email"
                       class="form-control"
                       placeholder="contoh@email.com"
                       id="email"
                       value="{{ old('email') }}"
                       required>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <div class="input-group">
                    <input type="password"
                           name="password"
                           class="form-control"
                           placeholder="Minimal 6 karakter"
                           id="password"
                           required>
                    <span class="password-toggle" id="togglePassword">
                        <i class="fas fa-eye"></i>
                    </span>
                </div>
            </div>

            <div class="mb-3">
                <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                <div class="input-group">
                    <input type="password"
                           name="password_confirmation"
                           class="form-control"
                           placeholder="Ulangi password"
                           id="password_confirmation"
                           required>
                    <span class="password-toggle" id="toggleConfirmPassword">
                        <i class="fas fa-eye"></i>
                    </span>
                </div>
            </div>

            <!-- Hidden role field -->
            @if(Auth::check() && Auth::user()->role === 'admin')
            <div class="mb-3">
                <label for="role" class="form-label">Role</label>
                <select name="role" id="role" class="form-control">
                    <option value="warga">Warga</option>
                    <option value="operator">Operator</option>
                    <option value="admin">Admin</option>
                </select>
            </div>
            @else
            <input type="hidden" name="role" value="warga">
            @endif

            <button type="submit" class="btn btn-register">
                <i class="fas fa-user-plus me-2"></i> Daftar Sekarang
            </button>
        </form>

        <!-- Login Link -->
        <div class="login-link">
            <p>Sudah punya akun?</p>
            <a href="{{ route('login') }}" class="btn-login">
                <i class="fas fa-sign-in-alt me-2"></i> Masuk ke Akun
            </a>
        </div>

        <!-- Copyright -->
        <div class="copyright">
            &copy; {{ date('Y') }} Bina Desa - Sistem Administrasi Pertanahan
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Auto focus pada nama
            document.getElementById('name').focus();

            // Toggle password visibility
            const passwordInput = document.getElementById('password');
            const confirmInput = document.getElementById('password_confirmation');
            const toggleBtn = document.getElementById('togglePassword');
            const toggleConfirmBtn = document.getElementById('toggleConfirmPassword');

            if (passwordInput && toggleBtn) {
                toggleBtn.addEventListener('click', function() {
                    const type = passwordInput.type === 'password' ? 'text' : 'password';
                    passwordInput.type = type;
                    this.innerHTML = type === 'password' ?
                        '<i class="fas fa-eye"></i>' :
                        '<i class="fas fa-eye-slash"></i>';
                });
            }

            if (confirmInput && toggleConfirmBtn) {
                toggleConfirmBtn.addEventListener('click', function() {
                    const type = confirmInput.type === 'password' ? 'text' : 'password';
                    confirmInput.type = type;
                    this.innerHTML = type === 'password' ?
                        '<i class="fas fa-eye"></i>' :
                        '<i class="fas fa-eye-slash"></i>';
                });
            }

            // Validasi password match
            const form = document.querySelector('form');
            form.addEventListener('submit', function(e) {
                const password = document.getElementById('password').value;
                const confirm = document.getElementById('password_confirmation').value;

                if (password !== confirm) {
                    e.preventDefault();
                    alert('Password dan konfirmasi password tidak cocok!');
                    return false;
                }

                if (password.length < 6) {
                    e.preventDefault();
                    alert('Password minimal 6 karakter!');
                    return false;
                }

                return true;
            });
        });
    </script>
</body>
</html>
