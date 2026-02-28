<!DOCTYPE html>
<html>
<head><style>body{font-family:sans-serif;line-height:1.6;color:#333;} .box{border:1px solid #ddd;padding:20px;max-width:500px;margin:0 auto;border-radius:8px;} .success{color:#14b8a6;font-weight:bold;}</style></head>
<body>
    <div class="box">
        <h2>Halo, {{ $user->name }}!</h2>
        <p>Terima kasih, pembayaran Anda telah <span class="success">BERHASIL</span>.</p>
        <hr>
        <h3>Detail Invoice:</h3>
        <p><strong>ID Transaksi:</strong> {{ $transaction->id }}</p>
        <p><strong>Paket Berlangganan:</strong> {{ $package->name }}</p>
        <p><strong>Total Bayar:</strong> Rp{{ number_format($transaction->amount, 0, ',', '.') }}</p>
        <p><strong>Status:</strong> Lunas</p>
        <hr>
        <p>Seluruh kuota paket Anda sudah di-reset ulang dan siap digunakan. Silakan login ke Dashboard Anda sekarang.</p>
        <p>Salam hangat,<br>Tim ScanYuk</p>
    </div>
</body>
</html>