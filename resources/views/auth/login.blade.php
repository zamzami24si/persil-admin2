<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Bina Desa</title>

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

        .login-wrapper {
            width: 100%;
            max-width: 1000px;
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 15px 30px rgba(0,0,0,0.15);
            display: flex;
            min-height: 550px;
        }

        /* Left Panel */
        .left-panel {
            flex: 1;
            background: linear-gradient(135deg, var(--primary) 0%, #1a252f 100%);
            color: white;
            padding: 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .logo-section {
            text-align: center;
            margin-bottom: 30px;
        }

        .logo-icon {
            font-size: 3.5rem;
            color: var(--accent);
            margin-bottom: 15px;
        }

        .logo-title {
            font-size: 2.2rem;
            font-weight: 700;
            margin: 0;
        }

        .logo-subtitle {
            font-size: 1rem;
            opacity: 0.9;
            margin-top: 5px;
        }

        .features-list {
            list-style: none;
            padding: 0;
            margin: 30px 0;
        }

        .features-list li {
            margin-bottom: 20px;
            display: flex;
            align-items: flex-start;
        }

        .features-list i {
            color: var(--accent);
            font-size: 1.3rem;
            margin-right: 15px;
            margin-top: 3px;
            min-width: 25px;
        }

        .feature-title {
            font-weight: 600;
            margin-bottom: 3px;
        }

        .feature-desc {
            font-size: 0.85rem;
            opacity: 0.9;
        }

        .version-info {
            text-align: center;
            margin-top: 20px;
            font-size: 0.8rem;
            opacity: 0.7;
        }

        /* Right Panel */
        .right-panel {
            flex: 1;
            padding: 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .login-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .login-header h2 {
            color: var(--primary);
            font-weight: 600;
            margin-bottom: 8px;
        }

        .login-header p {
            color: #666;
            font-size: 0.95rem;
        }

        /* Form */
        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            color: #333;
            font-weight: 500;
            margin-bottom: 6px;
            font-size: 0.9rem;
        }

        .input-group {
            border-radius: 8px;
            overflow: hidden;
            border: 1px solid #ddd;
        }

        .input-group:focus-within {
            border-color: var(--secondary);
            box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.25);
        }

        .input-group-text {
            background: #f8f9fa;
            border: none;
            padding: 10px 12px;
            color: #666;
        }

        .form-control {
            border: none;
            padding: 10px 12px;
            font-size: 0.95rem;
        }

        .btn-login {
            background: linear-gradient(135deg, var(--secondary) 0%, var(--accent) 100%);
            color: white;
            border: none;
            padding: 12px;
            border-radius: 8px;
            font-weight: 600;
            width: 100%;
            margin-top: 10px;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 15px rgba(52, 152, 219, 0.3);
        }

        .form-check-label {
            font-size: 0.9rem;
            color: #666;
        }

        .forgot-link {
            color: var(--secondary);
            font-size: 0.9rem;
            text-decoration: none;
        }

        .forgot-link:hover {
            text-decoration: underline;
        }

        .register-section {
            text-align: center;
            margin-top: 25px;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }

        .register-section p {
            color: #666;
            font-size: 0.9rem;
            margin-bottom: 10px;
        }

        .btn-register {
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

        .btn-register:hover {
            background: var(--secondary);
            color: white;
        }

        .footer-links {
            text-align: center;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            font-size: 0.8rem;
            color: #666;
        }

        .footer-links a {
            color: var(--secondary);
            text-decoration: none;
            margin: 0 8px;
        }

        .footer-links a:hover {
            text-decoration: underline;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .login-wrapper {
                flex-direction: column;
                max-width: 450px;
            }

            .left-panel, .right-panel {
                padding: 30px;
            }

            .logo-title {
                font-size: 1.8rem;
            }

            .logo-icon {
                font-size: 3rem;
            }
        }
    </style>
</head>
<body>
    <div class="login-wrapper">
        <!-- Left Panel: Info -->
        <div class="left-panel">
            <div class="logo-section">
                <div class="logo-icon">
                    <i class="fas fa-landmark"></i>
                </div>
                <h1 class="logo-title">BINA DESA</h1>
                <p class="logo-subtitle">Sistem Administrasi Pertanahan</p>
            </div>

            <ul class="features-list">
                <li>
                    <i class="fas fa-map-marked-alt"></i>
                    <div>
                        <div class="feature-title">Manajemen Persil</div>
                        <div class="feature-desc">Kelola data tanah, pemilik, dan batas wilayah</div>
                    </div>
                </li>
                <li>
                    <i class="fas fa-file-contract"></i>
                    <div>
                        <div class="feature-title">Dokumen Legal</div>
                        <div class="feature-desc">Arsip dokumen kepemilikan dan sertifikat</div>
                    </div>
                </li>
                <li>
                    <i class="fas fa-users"></i>
                    <div>
                        <div class="feature-title">Data Warga</div>
                        <div class="feature-desc">Database lengkap informasi penduduk</div>
                    </div>
                </li>
                <li>
                    <i class="fas fa-balance-scale"></i>
                    <div>
                        <div class="feature-title">Resolusi Sengketa</div>
                        <div class="feature-desc">Tracking dan penyelesaian sengketa tanah</div>
                    </div>
                </li>
            </ul>

            <div class="version-info">
                <i class="fas fa-info-circle"></i> Versi 2.0.0 |
                <i class="fas fa-shield-alt"></i> Sistem Terenkripsi
            </div>
        </div>

        <!-- Right Panel: Login Form -->
        <div class="right-panel">
            <div class="login-header">
                <h2>Masuk ke Sistem</h2>
                <p>Masukkan kredensial Anda untuk mengakses dashboard</p>
            </div>

            <!-- Error/Success Messages -->
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Login Form -->
            <form action="{{ route('auth.login') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="email" class="form-label">Email Anda</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-envelope"></i>
                        </span>
                        <input type="email"
                               name="email"
                               class="form-control"
                               placeholder="example@company.com"
                               id="email"
                               value="{{ old('email') }}"
                               required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-lock"></i>
                        </span>
                        <input type="password"
                               name="password"
                               class="form-control"
                               placeholder="Password"
                               id="password"
                               required>
                        <button class="input-group-text" type="button" id="togglePassword">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember">
                        <label class="form-check-label" for="remember">
                            Ingat saya
                        </label>
                    </div>
                    <a href="#" class="forgot-link">Lupa password?</a>
                </div>

                <button type="submit" class="btn-login">
                    <i class="fas fa-sign-in-alt me-2"></i> Masuk
                </button>
            </form>

            <!-- Register Link -->
            <div class="register-section">
                <p>Belum punya akun?</p>
                <a href="{{ route('auth.register') }}" class="btn-register">
                    Daftar Akun Baru
                </a>
            </div>

            <!-- Footer Links -->
            <div class="footer-links">
                <p>&copy; 2025 Bina Desa - Sistem Administrasi Pertanahan</p>
                <p>
                    <a href="{{ url('/') }}">Beranda</a> |
                    <a href="#">Panduan</a> |
                    <a href="#">Kontak</a> |
                    <a href="#">Kebijakan Privasi</a>
                </p>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Auto focus email
            document.getElementById('email').focus();

            // Toggle password
            const passwordInput = document.getElementById('password');
            const toggleBtn = document.getElementById('togglePassword');

            if (passwordInput && toggleBtn) {
                toggleBtn.addEventListener('click', function() {
                    const type = passwordInput.type === 'password' ? 'text' : 'password';
                    passwordInput.type = type;
                    this.innerHTML = type === 'password' ?
                        '<i class="fas fa-eye"></i>' :
                        '<i class="fas fa-eye-slash"></i>';
                });
            }

            // Simple validation
            const form = document.querySelector('form');
            form.addEventListener('submit', function(e) {
                const email = document.getElementById('email').value;
                const password = document.getElementById('password').value;

                if (!email || !password) {
                    e.preventDefault();
                    alert('Harap lengkapi email dan password!');
                    return false;
                }

                return true;
            });
        });
    </script>
</body>
</html>
