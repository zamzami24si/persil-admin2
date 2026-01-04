<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'Bina Desa - Login')</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Google Fonts - PASTIKAN MENGGUNAKAN FONT YANG SUPPORT KARAKTER INDONESIA -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">

    <!-- Custom CSS -->
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #3498db;
            --accent-color: #27ae60;
            --light-color: #ecf0f1;
            --dark-color: #2c3e50;
        }

        /* Reset encoding dan font */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', 'Inter', sans-serif;
        }

        html, body {
            height: 100%;
            width: 100%;
        }

        body {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            font-family: 'Poppins', 'Inter', sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 20px;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        /* Pastikan semua teks menggunakan font yang benar */
        h1, h2, h3, h4, h5, h6, p, span, div, a, button, input, label, select, textarea {
            font-family: 'Poppins', 'Inter', sans-serif;
        }

        .login-wrapper {
            width: 100%;
            max-width: 1200px;
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
            overflow: hidden;
            display: flex;
            min-height: 600px;
        }

        .login-left {
            flex: 1;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--dark-color) 100%);
            color: white;
            padding: 50px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .login-left::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" preserveAspectRatio="none" opacity="0.1"><path d="M0,0 L100,0 L100,100 Z" fill="white"/></svg>');
            background-size: cover;
        }

        .logo-container {
            text-align: center;
            margin-bottom: 30px;
            position: relative;
            z-index: 1;
        }

        .logo-icon {
            font-size: 4rem;
            color: var(--accent-color);
            margin-bottom: 15px;
        }

        .system-name {
            font-size: 2.5rem;
            font-weight: 700;
            margin: 0;
            color: white;
            letter-spacing: 1px;
        }

        .system-subtitle {
            font-size: 1.2rem;
            color: var(--light-color);
            font-weight: 300;
            margin-top: 5px;
        }

        .features-list {
            list-style: none;
            padding: 0;
            margin: 40px 0;
            position: relative;
            z-index: 1;
        }

        .features-list li {
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            font-size: 1.1rem;
        }

        .features-list i {
            color: var(--accent-color);
            font-size: 1.5rem;
            margin-right: 15px;
            width: 30px;
        }

        .login-right {
            flex: 1;
            padding: 50px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .login-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .login-header h2 {
            color: var(--dark-color);
            font-weight: 600;
            margin-bottom: 10px;
        }

        .login-header p {
            color: #666;
            font-size: 1rem;
        }

        /* Form styling */
        .form-group {
            margin-bottom: 25px;
        }

        .form-label {
            color: var(--dark-color);
            font-weight: 500;
            margin-bottom: 8px;
            font-size: 0.95rem;
            display: block;
        }

        .input-group {
            border-radius: 10px;
            overflow: hidden;
            border: 1px solid #ddd;
            transition: all 0.3s;
        }

        .input-group:focus-within {
            border-color: var(--secondary-color);
            box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.25);
        }

        .input-group-text {
            background-color: #f8f9fa;
            border: none;
            padding: 12px 15px;
            color: #666;
        }

        .form-control {
            border: none;
            padding: 12px 15px;
            font-size: 1rem;
            font-family: 'Inter', sans-serif;
        }

        .form-control:focus {
            box-shadow: none;
        }

        .btn-login {
            background: linear-gradient(135deg, var(--secondary-color) 0%, var(--accent-color) 100%);
            border: none;
            color: white;
            padding: 14px;
            border-radius: 10px;
            font-weight: 600;
            font-size: 1.1rem;
            width: 100%;
            transition: all 0.3s;
            margin-top: 10px;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(52, 152, 219, 0.3);
        }

        .footer-links {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            color: #666;
            font-size: 0.9rem;
        }

        .footer-links p {
            margin-bottom: 10px;
        }

        .footer-links a {
            color: var(--secondary-color);
            text-decoration: none;
            margin: 0 10px;
            font-size: 0.9rem;
        }

        .footer-links a:hover {
            text-decoration: underline;
        }

        .version-info {
            text-align: center;
            margin-top: 20px;
            font-size: 0.8rem;
            color: rgba(255, 255, 255, 0.7);
            position: relative;
            z-index: 1;
        }

        /* Responsive */
        @media (max-width: 992px) {
            .login-wrapper {
                flex-direction: column;
                max-width: 500px;
            }

            .login-left, .login-right {
                padding: 30px;
            }
        }

        @media (max-width: 576px) {
            .login-left, .login-right {
                padding: 20px;
            }

            .system-name {
                font-size: 2rem;
            }

            .logo-icon {
                font-size: 3rem;
            }
        }
    </style>

    @yield('styles')
</head>
<body>
    <div class="login-wrapper">
        <!-- Left Side: Branding & Info -->
        <div class="login-left">
            <div class="logo-container">
                <div class="logo-icon">
                    <i class="fas fa-landmark"></i>
                </div>
                <h1 class="system-name">BINA DESA</h1>
                <p class="system-subtitle">Sistem Administrasi Pertanahan</p>
            </div>

            <ul class="features-list">
                <li>
                    <i class="fas fa-map-marked-alt"></i>
                    <div>
                        <strong>Manajemen Persil</strong>
                        <p style="font-size: 0.9rem; margin-top: 5px; opacity: 0.9;">Kelola data tanah, pemilik, dan batas wilayah</p>
                    </div>
                </li>
                <li>
                    <i class="fas fa-file-contract"></i>
                    <div>
                        <strong>Dokumen Legal</strong>
                        <p style="font-size: 0.9rem; margin-top: 5px; opacity: 0.9;">Arsip dokumen kepemilikan dan sertifikat</p>
                    </div>
                </li>
                <li>
                    <i class="fas fa-users"></i>
                    <div>
                        <strong>Data Warga</strong>
                        <p style="font-size: 0.9rem; margin-top: 5px; opacity: 0.9;">Database lengkap informasi penduduk</p>
                    </div>
                </li>
                <li>
                    <i class="fas fa-balance-scale"></i>
                    <div>
                        <strong>Resolusi Sengketa</strong>
                        <p style="font-size: 0.9rem; margin-top: 5px; opacity: 0.9;">Tracking dan penyelesaian sengketa tanah</p>
                    </div>
                </li>
            </ul>

            <div class="version-info">
                <i class="fas fa-info-circle"></i>
                Versi 2.0.0 |
                <i class="fas fa-shield-alt"></i>
                Sistem Terenkripsi
            </div>
        </div>

        <!-- Right Side: Login Form -->
        <div class="login-right">
            @yield('content')

            <div class="footer-links">
                <p>&copy; {{ date('Y') }} <strong>Bina Desa</strong> - Sistem Administrasi Pertanahan</p>
                <p>
                    <a href="{{ url('/') }}"><i class="fas fa-home"></i> Beranda</a> |
                    <a href="#"><i class="fas fa-question-circle"></i> Panduan</a> |
                    <a href="#"><i class="fas fa-envelope"></i> Kontak</a> |
                    <a href="#"><i class="fas fa-lock"></i> Kebijakan Privasi</a>
                </p>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Custom JS -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Auto focus on email input
            const emailInput = document.getElementById('email');
            if (emailInput) {
                emailInput.focus();
            }

            // Toggle password visibility
            const passwordInput = document.getElementById('password');
            const toggleBtn = document.getElementById('togglePassword');

            if (passwordInput && toggleBtn) {
                toggleBtn.addEventListener('click', function() {
                    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                    passwordInput.setAttribute('type', type);
                    this.innerHTML = type === 'password' ?
                        '<i class="fas fa-eye"></i>' :
                        '<i class="fas fa-eye-slash"></i>';
                });
            }
        });
    </script>

    @yield('scripts')
</body>
</html>
