<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Kode OTP ScanYuk</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f4f7f6; margin: 0; padding: 0; }
        .container { max-width: 500px; margin: 40px auto; background: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.05); }
        .header { background-color: #0f172a; padding: 25px 20px; text-align: center; border-bottom: 4px solid #0d9488; }
        .header img { width: 45px; height: 45px; margin-bottom: 10px; }
        .header h2 { margin: 0; color: #ffffff; font-size: 24px; letter-spacing: 1px; }
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
            <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='%230d9488' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Crect width='5' height='5' x='3' y='3' rx='1'%3E%3C/rect%3E%3Crect width='5' height='5' x='16' y='3' rx='1'%3E%3C/rect%3E%3Crect width='5' height='5' x='3' y='16' rx='1'%3E%3C/rect%3E%3Cpath d='M21 16h-3a2 2 0 0 0-2 2v3'%3E%3C/path%3E%3Cpath d='M21 21v.01'%3E%3C/path%3E%3Cpath d='M12 7v3a2 2 0 0 1-2 2H7'%3E%3C/path%3E%3Cpath d='M3 12h.01'%3E%3C/path%3E%3Cpath d='M12 3h.01'%3E%3C/path%3E%3Cpath d='M12 16v.01'%3E%3C/path%3E%3Cpath d='M16 12h1'%3E%3C/path%3E%3Cpath d='M21 12v.01'%3E%3C/path%3E%3Cpath d='M12 21v-1'%3E%3C/path%3E%3C/svg%3E" alt="ScanYuk Logo">
            <h2>ScanYuk</h2>
        </div>
        
        <div class="content">
            <h3>Verifikasi Keamanan</h3>
            <p>Halo, terima kasih telah menggunakan <b>ScanYuk</b>.<br>Silakan masukkan kode OTP berikut untuk melanjutkan proses:</p>
            
            <div class="otp-box">
                {{ $otp }}
            </div>
            
            <div class="warning">
                <strong>Penting:</strong> Kode ini hanya berlaku selama 5 menit. Jangan pernah memberikan kode OTP ini kepada siapapun, termasuk tim dari ScanYuk.
            </div>
        </div>
        
        <div class="footer">
            <p>&copy; {{ date('Y') }} ScanYuk AR. Semua Hak Dilindungi.</p>
            <p>Email ini dihasilkan secara otomatis oleh sistem. Mohon untuk tidak membalas email ini.</p>
        </div>
    </div>
</body>
</html>