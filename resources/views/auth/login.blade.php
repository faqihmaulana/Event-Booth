<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(to right, #c2e9fb, #a1c4fd);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', sans-serif;
        }

        .login-card {
            background: #ffffff;
            border: none;
            border-radius: 1.5rem;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
            padding: 2.5rem;
        }

        .form-control {
            padding-left: 2.5rem;
        }

        .form-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d;
        }

        .btn-primary {
            background-color: #4facfe;
            border-color: #4facfe;
        }

        .btn-primary:hover {
            background-color: #00f2fe;
            border-color: #00f2fe;
        }

        .form-check-label {
            user-select: none;
        }

        .logo-text {
            font-size: 1.5rem;
            font-weight: bold;
            color: #4facfe;
        }

        .text-decoration-none {
            color: #4facfe;
        }

        .text-decoration-none:hover {
            color: #00c3ff;
        }

        .forgot-password-link {
            font-size: 0.9rem;
            color: #6c757d;
        }

        .forgot-password-link:hover {
            color: #4facfe;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="login-card">
                <div class="text-center mb-4">
                    <div class="logo-text">🔐 Masuk Akun Anda</div>
                </div>

                {{-- Session Alerts --}}
                @if(session('message'))
                    <div class="alert alert-success">{{ session('message') }}</div>
                @endif

                @if(session('status'))
                    <div class="alert alert-success">{{ session('status') }}</div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger">
                        @foreach($errors->all() as $error)
                            {{ $error }}<br>
                        @endforeach
                        @if(session('resend_verification'))
                            <form method="POST" action="{{ route('verification.send') }}" class="mt-2">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-outline-primary">
                                    Kirim Ulang Email Verifikasi
                                </button>
                            </form>
                        @endif
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <!-- Email Input -->
                    <div class="mb-3 position-relative">
                        <i class="bi bi-envelope form-icon"></i>
                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                               name="email" value="{{ old('email') }}" required placeholder="Email">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Password Input -->
                    <div class="mb-3 position-relative">
                        <i class="bi bi-lock form-icon"></i>
                        <input type="password" class="form-control @error('password') is-invalid @enderror"
                               name="password" required placeholder="Password">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Remember Me and Forgot Password -->
                    <div class="mb-3 d-flex justify-content-between align-items-center">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="remember" name="remember">
                            <label class="form-check-label" for="remember">Ingat Saya</label>
                        </div>
                        <a href="{{ route('password.request') }}" class="text-decoration-none forgot-password-link">
                            Lupa Password?
                        </a>
                    </div>

                    <!-- Login Button -->
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Masuk</button>
                    </div>

                    <p class="mt-4 text-center">
                        Belum punya akun? <a href="{{ route('register') }}" class="text-decoration-none">Daftar Sekarang</a>
                    </p>
                </form>
            </div>
        </div>
    </div>
</div>

</body>
</html>