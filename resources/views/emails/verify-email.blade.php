<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Email - {{ config('app.name') }}</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f8f9fa;
        }
        .container {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #007bff;
            margin-bottom: 10px;
        }
        .welcome-icon {
            font-size: 60px;
            margin-bottom: 20px;
        }
        h1 {
            color: #2c3e50;
            margin-bottom: 20px;
        }
        .btn {
            display: inline-block;
            padding: 15px 30px;
            background-color: #28a745;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            margin: 20px 0;
            text-align: center;
        }
        .btn:hover {
            background-color: #218838;
        }
        .info-box {
            background-color: #e3f2fd;
            padding: 20px;
            border-radius: 5px;
            margin: 20px 0;
            border-left: 4px solid #2196f3;
        }
        .warning-box {
            background-color: #fff3cd;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
            border-left: 4px solid #ffc107;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            color: #666;
            font-size: 14px;
        }
        .url-box {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            word-break: break-all;
            font-family: monospace;
            font-size: 12px;
            color: #495057;
            margin: 10px 0;
        }
        ul {
            padding-left: 20px;
        }
        li {
            margin-bottom: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">{{ config('app.name') }}</div>
            <div class="welcome-icon">🎉</div>
            <h1>Selamat Datang, {{ $user->name }}!</h1>
        </div>

        <p><strong>Halo {{ $user->name }}! 👋</strong></p>

        <p>Terima kasih telah bergabung dengan <strong>{{ config('app.name') }}</strong>!</p>
        
        <p>Kami sangat senang Anda menjadi bagian dari komunitas kami. Untuk melengkapi pendaftaran dan mengakses semua fitur, silakan verifikasi alamat email Anda dengan mengklik tombol di bawah ini:</p>

        <div style="text-align: center;">
            <a href="{{ $url }}" class="btn">✅ Verifikasi Email Saya</a>
        </div>

        <div class="info-box">
            <h3>🔒 Mengapa perlu verifikasi?</h3>
            <ul>
                <li>Melindungi akun Anda dari penyalahgunaan</li>
                <li>Memastikan Anda mendapat notifikasi penting</li>
                <li>Mengakses semua fitur aplikasi</li>
            </ul>
        </div>

        <hr>

        <p><strong>Jika tombol di atas tidak berfungsi</strong>, salin dan tempel tautan berikut ke browser Anda:</p>
        <div class="url-box">{{ $url }}</div>

        <div class="warning-box">
            <h3>⚠️ Catatan Penting:</h3>
            <ul>
                <li>Tautan ini akan kedaluwarsa dalam <strong>60 menit</strong></li>
                <li>Jika Anda tidak mendaftar akun ini, abaikan email ini</li>
                <li>Jangan bagikan tautan ini kepada orang lain</li>
            </ul>
        </div>

        <div class="footer">
            <p><strong>Salam hangat,<br>Tim {{ config('app.name') }} 🚀</strong></p>
            <p>Email ini dikirim secara otomatis, mohon jangan membalas email ini.</p>
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html>