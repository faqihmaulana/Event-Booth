<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>

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

        .reset-card {
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

        .password-strength {
            font-size: 0.8rem;
            margin-top: 0.25rem;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="reset-card">
                <div class="text-center mb-4">
                    <div class="logo-text">🔒 Reset Password</div>
                    <p class="info-text mt-3">
                        Masukkan password baru untuk akun Anda
                    </p>
                </div>

                {{-- Error Messages --}}
                @if($errors->any())
                    <div class="alert alert-danger">
                        @foreach($errors->all() as $error)
                            {{ $error }}<br>
                        @endforeach
                    </div>
                @endif

                <form method="POST" action="{{ route('password.update') }}">
                    @csrf

                    <!-- Hidden Token and Email -->
                    <input type="hidden" name="token" value="{{ $token }}">

                    <!-- Email Input (Read Only) -->
                    <div class="mb-3 position-relative">
                        <i class="bi bi-envelope form-icon"></i>
                        <input type="email" class="form-control" name="email" 
                               value="{{ $email ?? old('email') }}" readonly placeholder="Email">
                    </div>

                    <!-- New Password -->
                    <div class="mb-3 position-relative">
                        <i class="bi bi-lock form-icon"></i>
                        <input type="password" class="form-control @error('password') is-invalid @enderror"
                               name="password" required placeholder="Password Baru" id="password">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="password-strength text-muted">
                            Password minimal 8 karakter
                        </div>
                    </div>

                    <!-- Confirm Password -->
                    <div class="mb-4 position-relative">
                        <i class="bi bi-shield-lock form-icon"></i>
                        <input type="password" class="form-control"
                               name="password_confirmation" required placeholder="Konfirmasi Password Baru" id="password_confirmation">
                        <div id="password-match" class="password-strength" style="display: none;"></div>
                    </div>

                    <!-- Submit Button -->
                    <div class="d-grid mb-3">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-2"></i>Reset Password
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

<script>
// Password confirmation check
document.getElementById('password_confirmation').addEventListener('input', function() {
    const password = document.getElementById('password').value;
    const confirmPassword = this.value;
    const matchDiv = document.getElementById('password-match');
    
    if (confirmPassword.length > 0) {
        matchDiv.style.display = 'block';
        if (password === confirmPassword) {
            matchDiv.textContent = '✓ Password cocok';
            matchDiv.className = 'password-strength text-success';
        } else {
            matchDiv.textContent = '✗ Password tidak cocok';
            matchDiv.className = 'password-strength text-danger';
        }
    } else {
        matchDiv.style.display = 'none';
    }
});
</script>

</body>
</html>