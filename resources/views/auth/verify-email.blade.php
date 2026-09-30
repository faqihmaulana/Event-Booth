<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Email</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(to right, #667eea, #764ba2);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', sans-serif;
        }

        .card {
            border: none;
            border-radius: 1.25rem;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        }

        .card-header {
            background-color: transparent;
            font-weight: bold;
            font-size: 1.2rem;
            color: #444;
            border-bottom: none;
            text-align: center;
        }

        .btn-primary {
            background-color: #667eea;
            border-color: #667eea;
        }

        .btn-primary:hover {
            background-color: #5a67d8;
            border-color: #5a67d8;
        }

        .btn-link {
            color: #667eea;
        }

        .btn-link:hover {
            color: #5a67d8;
            text-decoration: underline;
        }

        .icon-email {
            font-size: 3rem;
            color: #667eea;
        }
    </style>
</head>
<body>
    <div class="container px-3">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card p-4">
                    <div class="card-header">Verifikasi Email</div>
                    <div class="card-body">
                        @if(session('message'))
                            <div class="alert alert-success">
                                {{ session('message') }}
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif
                        
                        <div class="text-center mb-4">
                            <i class="bi bi-envelope-fill icon-email mb-3"></i>
                            <h4 class="fw-semibold">Verifikasi Email Anda</h4>
                        </div>
                        
                        <p class="text-center mb-4">
                            Terima kasih telah mendaftar! Sebelum melanjutkan, silakan verifikasi alamat email Anda
                            dengan mengklik tautan yang baru saja kami kirim. Jika Anda belum menerima email tersebut,
                            kami dapat mengirimkannya kembali.
                        </p>

                        <div class="text-center mb-3">
                            <form method="POST" action="{{ route('verification.send') }}" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-primary">
                                    Kirim Ulang Email Verifikasi
                                </button>
                            </form>
                        </div>

                        <div class="text-center">
                            <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-link text-decoration-none">
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
