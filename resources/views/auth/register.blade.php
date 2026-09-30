<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>

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

        .register-card {
            background: #ffffff;
            border-radius: 1.5rem;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
            padding: 2.5rem;
            border: none;
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
    </style>
</head>
<body>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="register-card">
                <div class="text-center mb-4">
                    <div class="logo-text">📝 Daftar Akun Baru</div>
                </div>

                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <!-- Nama -->
                    <div class="mb-3 position-relative">
                        <i class="bi bi-person form-icon"></i>
                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                               name="name" value="{{ old('name') }}" required autofocus placeholder="Nama Lengkap">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div class="mb-3 position-relative">
                        <i class="bi bi-envelope form-icon"></i>
                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                               name="email" value="{{ old('email') }}" required placeholder="Email">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="mb-3 position-relative">
                        <i class="bi bi-lock form-icon"></i>
                        <input type="password" class="form-control @error('password') is-invalid @enderror"
                               name="password" required placeholder="Password">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Konfirmasi Password -->
                    <div class="mb-3 position-relative">
                        <i class="bi bi-shield-lock form-icon"></i>
                        <input type="password" class="form-control"
                               name="password_confirmation" required placeholder="Konfirmasi Password">
                    </div>

                    <!-- Role hidden -->
                    <input type="hidden" name="role_id" value="{{ $tenantRole->id }}">

                    <!-- Submit -->
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Daftar</button>
                    </div>

                    <p class="mt-4 text-center">
                        Sudah punya akun? <a href="{{ route('login') }}" class="text-decoration-none">Masuk Sekarang</a>
                    </p>
                </form>

            </div>
        </div>
    </div>
</div>

</body>
</html>