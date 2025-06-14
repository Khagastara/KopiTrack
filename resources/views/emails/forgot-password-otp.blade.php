<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - KopiTrack</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .logo {
            font-size: 32px;
            font-weight: bold;
            color: #8B4513;
            margin-bottom: 10px;
        }
        .otp-code {
            background-color: #f8f9fa;
            border: 2px dashed #8B4513;
            padding: 20px;
            text-align: center;
            margin: 25px 0;
            border-radius: 8px;
        }
        .otp-number {
            font-size: 36px;
            font-weight: bold;
            color: #8B4513;
            letter-spacing: 8px;
            margin: 10px 0;
        }
        .warning {
            background-color: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            margin: 20px 0;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            font-size: 14px;
            color: #666;
            text-align: center;
        }
        .button {
            display: inline-block;
            padding: 12px 25px;
            background-color: #8B4513;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 15px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">☕ KopiTrack</div>
            <h2 style="color: #333; margin: 0;">Reset Password</h2>
        </div>

        <p>Halo <strong>{{ $username }}</strong>,</p>

        <p>Kami menerima permintaan untuk mereset password akun KopiTrack Anda. Gunakan kode OTP berikut untuk melanjutkan proses reset password:</p>

        <div class="otp-code">
            <p style="margin: 0; font-size: 16px; color: #666;">Kode OTP Anda:</p>
            <div class="otp-number">{{ $otp }}</div>
            <p style="margin: 0; font-size: 14px; color: #666;">Berlaku selama 10 menit</p>
        </div>

        <div class="warning">
            <strong>⚠️ Penting:</strong>
            <ul style="margin: 10px 0;">
                <li>Jangan bagikan kode ini kepada siapa pun</li>
                <li>Kode ini akan kedaluwarsa dalam 10 menit</li>
                <li>Jika Anda tidak meminta reset password, abaikan email ini</li>
            </ul>
        </div>

        <p>Jika Anda mengalami kesulitan, silakan hubungi tim support kami.</p>

        <div class="footer">
            <p><strong>Tim KopiTrack</strong></p>
            <p style="font-size: 12px; color: #999;">
                Email ini dikirim secara otomatis, mohon tidak membalas email ini.
            </p>
            <p style="font-size: 12px; color: #999;">
                © {{ date('Y') }} KopiTrack. All rights reserved.
            </p>
        </div>
    </div>
</body>
</html>
