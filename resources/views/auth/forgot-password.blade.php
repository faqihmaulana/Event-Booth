<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password</title>

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

        .forgot-card {
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

        .btn-secondary {
            background-color: #6c757d;
            border-color: #6c757d;
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

        .info-text {
            color: #666;
            font-size: 0.9rem;
            line-height: 1.5;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="forgot-card">
                <div class="text-center mb-4">
                    <div class="logo-text">🔐 Lupa Password</div>
                    <p class="info-text mt-3">
                        Masukkan email Anda dan kami akan mengirimkan link untuk reset password
                    </p>
                </div>

                {{-- Session Status --}}
                @if(session('status'))
                    <div class="alert alert-success">{{ session('status') }}</div>
                @endif

                {{-- Error Messages --}}
                @if($errors->any())
                    <div class="alert alert-danger">
                        @foreach($errors->all() as $error)
                            {{ $error }}<br>
                        @endforeach
                    </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}">
                    @csrf

                    <!-- Email Input -->
                    <div class="mb-4 position-relative">
                        <i class="bi bi-envelope form-icon"></i>
                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                               name="email" value="{{ old('email') }}" required autofocus placeholder="Email">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <div class="d-grid mb-3">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-send me-2"></i>Kirim Link Reset Password
                        </button>
                    </div>

                    <!-- Back to Login -->
                    <div class="d-grid">
                        <a href="{{ route('login') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left me-2"></i>Kembali ke Login
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

</body>
</html>