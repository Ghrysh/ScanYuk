<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: sans-serif; line-height: 1.6; color: #333; }
        .box { border: 1px solid #ddd; padding: 20px; border-radius: 8px; max-width: 600px; margin: 0 auto; }
        h2 { color: #0d9488; margin-top: 0; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        td { padding: 10px; border-bottom: 1px solid #eee; }
        td:first-child { font-weight: bold; width: 35%; color: #555; }
        .message-box { background: #f9f9f9; padding: 15px; border-radius: 5px; font-style: italic; }
    </style>
</head>
<body>
    <div class="box">
        <h2>Pesan Baru dari Contact Sales</h2>
        <p>Ada perusahaan baru yang tertarik menggunakan ScanYuk. Berikut detailnya:</p>
        
        <table>
            <tr><td>Nama:</td><td>{{ $data['name'] }}</td></tr>
            <tr><td>Perusahaan:</td><td>{{ $data['company'] }}</td></tr>
            <tr><td>Email:</td><td>{{ $data['email'] }}</td></tr>
            <tr><td>Industri:</td><td>{{ $data['industry'] }}</td></tr>
            <tr><td>Estimasi Volume:</td><td>{{ $data['volume'] }}</td></tr>
        </table>

        <p><strong>Pesan:</strong></p>
        <div class="message-box">
            {!! nl2br(e($data['message'])) !!}
        </div>
    </div>
</body>
</html>