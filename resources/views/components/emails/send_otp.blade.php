<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kode Verifikasi OTP</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9fafb;
            color: #333;
            padding: 20px;
        }
        .email-container {
            background-color: #ffffff;
            border-radius: 8px;
            padding: 30px;
            max-width: 600px;
            margin: auto;
            box-shadow: 0 0 10px rgba(0,0,0,0.05);
        }
        h2 {
            color: #2c3e50;
        }
        .otp-code {
            font-size: 2rem;
            font-weight: bold;
            color: #1a73e8;
            margin: 20px 0;
        }
        p {
            line-height: 1.6;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <h2>Halo, {{ $name }}!</h2>
        <p>Untuk melanjutkan proses verifikasi akun Anda, silakan gunakan kode OTP berikut:</p>
        <div class="otp-code">{{ $otp }}</div>
        <p>Kode ini berlaku selama 5 menit. Jangan bagikan kode ini kepada siapa pun.</p>
        <p>Jika Anda tidak meminta kode ini, abaikan saja email ini.</p>
        <p>Salam hangat,<br><strong>LinmIdDev</strong></p>
    </div>
</body>
</html>
