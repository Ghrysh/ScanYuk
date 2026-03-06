<!DOCTYPE html>
<html>
<head>
    <title>Reset Password ScanYuk</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f8fafc; padding: 40px 0; margin: 0;">
    <div style="max-w: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);">
        <div style="background-color: #14b8a6; padding: 30px; text-align: center;">
            <h1 style="color: #ffffff; margin: 0; font-size: 24px; letter-spacing: 2px;">SCANYUK</h1>
        </div>
        <div style="padding: 40px 30px;">
            <h2 style="color: #0f172a; margin-top: 0; font-size: 20px;">Halo!</h2>
            <p style="color: #475569; font-size: 16px; line-height: 1.6;">
                Kami menerima permintaan untuk mereset password akun ScanYuk Anda. Silakan klik tombol di bawah ini untuk membuat password baru:
            </p>
            <div style="text-align: center; margin: 30px 0;">
                <a href="{{ route('password.reset', ['token' => $token, 'email' => $email]) }}" style="background-color: #14b8a6; color: #ffffff; padding: 14px 28px; text-decoration: none; border-radius: 8px; font-weight: bold; font-size: 16px; display: inline-block;">Reset Password Sekarang</a>
            </div>
            <p style="color: #475569; font-size: 14px; line-height: 1.6;">
                Jika Anda tidak pernah meminta reset password, abaikan email ini. Tautan ini akan kadaluarsa dalam 60 menit.
            </p>
        </div>
        <div style="background-color: #f1f5f9; padding: 20px; text-align: center; border-top: 1px solid #e2e8f0;">
            <p style="color: #94a3b8; font-size: 12px; margin: 0;">&copy; {{ date('Y') }} ScanYuk. Hak cipta dilindungi.</p>
        </div>
    </div>
</body>
</html>