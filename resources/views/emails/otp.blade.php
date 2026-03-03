<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Kode OTP ScanYuk</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f4f7f6; margin: 0; padding: 0; }
        .container { max-width: 500px; margin: 40px auto; background: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.05); }
        .header { background-color: #0f172a; padding: 25px 20px; text-align: center; border-bottom: 4px solid #0d9488; }
        .header h2 { margin: 0; color: #0d9488; font-size: 28px; font-weight: bold; letter-spacing: 1px; }
        .content { padding: 40px 30px; text-align: center; color: #334155; }
        .content h3 { margin-top: 0; color: #0f172a; font-size: 20px; }
        .content p { line-height: 1.6; font-size: 15px; }
        .otp-box { background-color: #f8fafc; border: 2px dashed #0d9488; color: #0f172a; font-size: 36px; font-weight: 800; letter-spacing: 8px; padding: 20px; margin: 30px auto; width: fit-content; border-radius: 8px; }
        .warning { background-color: #fffbeb; color: #b45309; padding: 12px; border-radius: 6px; font-size: 13px; margin-top: 20px; text-align: left; border-left: 4px solid #f59e0b; }
        .footer { background-color: #f8fafc; padding: 20px; text-align: center; font-size: 12px; color: #64748b; border-top: 1px solid #e2e8f0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>ScanYuk</h2>
        </div>
        
        <div class="content">
            <h3>Verifikasi Keamanan</h3>
            <p>Halo, terima kasih telah menggunakan <b>ScanYuk</b>.<br>Silakan masukkan kode OTP berikut untuk melanjutkan proses:</p>
            
            <div class="otp-box">
                {{ $otp }}
            </div>
            
            <div class="warning">
                <strong>Penting:</strong> Kode ini hanya berlaku selama 5 menit. Jangan berikan kode OTP ini kepada siapapun.
            </div>
        </div>
        
        <div class="footer">
            <p>&copy; {{ date('Y') }} ScanYuk AR. Semua Hak Dilindungi.</p>
        </div>
    </div>
</body>
</html>