<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice - {{ $booking->order_id }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #3B82F6;
            padding-bottom: 20px;
        }
        .header h1 {
            color: #3B82F6;
            margin: 0;
            font-size: 28px;
        }
        .header p {
            margin: 5px 0 0 0;
            color: #666;
        }
        .status-badge {
            background: #10B981;
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            display: inline-block;
            font-size: 12px;
            font-weight: bold;
            margin: 15px 0;
        }
        .info-section {
            margin-bottom: 25px;
        }
        .info-box {
            background: #F8FAFC;
            border: 1px solid #E2E8F0;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
        }
        .info-box h3 {
            margin: 0 0 10px 0;
            color: #1E40AF;
            font-size: 14px;
            font-weight: bold;
        }
        .info-row {
            margin-bottom: 5px;
            font-size: 12px;
        }
        .info-row strong {
            display: inline-block;
            width: 120px;
            color: #374151;
        }
        .booth-info {
            background: #EFF6FF;
            border-left: 4px solid #3B82F6;
        }
        .company-info {
            background: #F0FDF4;
            border-left: 4px solid #10B981;
        }
        .payment-section {
            border: 2px solid #3B82F6;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            margin: 25px 0;
        }
        .payment-section h3 {
            margin: 0 0 15px 0;
            color: #1E40AF;
        }
        .total-amount {
            font-size: 24px;
            font-weight: bold;
            color: #10B981;
            margin: 10px 0;
        }
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 11px;
            color: #666;
            border-top: 1px solid #E2E8F0;
            padding-top: 20px;
        }
        .description-box {
            background: #FFF7ED;
            border-left: 4px solid #F59E0B;
            padding: 15px;
            margin: 15px 0;
        }
        .description-box h3 {
            margin: 0 0 8px 0;
            color: #92400E;
            font-size: 14px;
        }
        .description-box p {
            margin: 0;
            font-size: 12px;
            line-height: 1.4;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>INVOICE BOOKING BOOTH</h1>
        <p>EventKu - Platform Booking Booth Terpercaya</p>
        <div class="status-badge">LUNAS</div>
        <p style="font-size: 12px; margin-top: 10px;">
            Invoice ID: {{ $booking->order_id ?: '#' . $booking->id }}
        </p>
    </div>

    <div class="info-section">
        <div class="info-box company-info">
            <h3>INFORMASI PRODUK</h3>
            <div class="info-row">
                <strong>Nama Produk:</strong> {{ $booking->company_name }}
            </div>
            <div class="info-row">
                <strong>Contact Person:</strong> {{ $booking->contact_person }}
            </div>
            <div class="info-row">
                <strong>Telepon:</strong> {{ $booking->phone }}
            </div>
            <div class="info-row">
                <strong>Email:</strong> {{ $booking->email }}
            </div>
        </div>

        <div class="info-box booth-info">
            <h3>DETAIL BOOTH</h3>
            <div class="info-row">
                <strong>ID Booth:</strong> {{ $booking->booth->booth_id }}
            </div>
            <div class="info-row">
                <strong>Nama Booth:</strong> {{ $booking->booth->booth_name }}
            </div>
            <div class="info-row">
                <strong>Section:</strong> {{ $booking->booth->section }}
            </div>
            <div class="info-row">
                <strong>Tanggal Booking:</strong> {{ \Carbon\Carbon::parse($booking->booking_date)->format('d F Y') }}
            </div>
        </div>

        @if($booking->description)
        <div class="description-box">
            <h3>DESKRIPSI</h3>
            <p>{{ $booking->description }}</p>
        </div>
        @endif
    </div>

    <div class="payment-section">
        <h3>DETAIL PEMBAYARAN</h3>
        <div style="border-bottom: 1px dashed #ccc; padding-bottom: 10px; margin-bottom: 10px;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <span>Biaya Booth {{ $booking->booth->booth_id }}</span>
                <span>Rp {{ number_format($booking->total_price, 0, ',', '.') }}</span>
            </div>
        </div>
        <div class="total-amount">
            TOTAL: Rp {{ number_format($booking->total_price, 0, ',', '.') }}
        </div>
        <p style="margin: 5px 0; font-size: 12px; color: #666;">
            Tanggal Pembayaran: {{ \Carbon\Carbon::parse($booking->updated_at)->format('d F Y, H:i') }} WIB
        </p>
    </div>

    <div class="footer">
        <p><strong>Terima kasih atas kepercayaan Anda!</strong></p>
        <p>Invoice ini dibuat secara otomatis pada {{ \Carbon\Carbon::now()->format('d F Y, H:i') }} WIB</p>
        <p>Untuk pertanyaan lebih lanjut, silakan hubungi tim customer service kami.</p>
        <br>
        <p style="font-size: 10px;">© {{ date('Y') }} EventKu. All rights reserved.</p>
    </div>
</body>
</html>