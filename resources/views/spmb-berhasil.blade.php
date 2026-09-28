<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pendaftaran Diterima · SPMB Ibnu Abbas</title>
    <link href="https://fonts.googleapis.com/css2?family=Amiri:wght@700&family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        *{box-sizing:border-box}body{margin:0;background:#faf8fe;color:#1f1a2e;font:16px/1.7 'Plus Jakarta Sans',sans-serif}.top{background:#2e1766;color:#fff;padding:16px 20px;text-align:center;font-weight:700}.wrap{max-width:640px;margin:40px auto;padding:0 20px}.card{background:#fff;border:1px solid #e3dcf3;border-radius:18px;padding:28px;text-align:center}.logo{width:80px;height:80px;border-radius:20px}.ok{background:#1f8a4c;color:#fff;border-radius:50%;width:64px;height:64px;display:grid;place-items:center;font-size:30px;margin:20px auto}h1{font:700 30px 'Amiri',serif;color:#3b1f78}.details{text-align:left;background:#f6f2fd;border-radius:12px;padding:16px;overflow-wrap:anywhere}.details p{margin:4px 0}.signature{margin-top:28px}a{display:block;background:#4c2a94;color:#fff;padding:13px;border-radius:12px;text-decoration:none;margin-top:24px;font-weight:700}@media(prefers-color-scheme:dark){body{background:#15101f;color:#efeaf9}.card{background:#1f1830;border-color:#332a4b}.details{background:#251c3b}h1{color:#fff}}
    </style>
</head>
<body>
    <div class="top">Rumah Qur'an Ibnu Abbas</div>
    <main class="wrap"><div class="card">
        <img class="logo" src="{{ asset('assets/spmb/logo.jpg') }}" alt="Logo Rumah Qur'an Ibnu Abbas">
        <div class="ok">✓</div>
        <h1>Alhamdulillah, data telah kami terima</h1>
        <p>Data pembayaran formulir telah kami terima atas nama:</p>
        <div class="details">
            <p><strong>Orang tua/wali:</strong> {{ $pendaftaran->ortu }}</p>
            <p><strong>No. HP / WhatsApp:</strong> {{ $pendaftaran->wa }}</p>
            <p><strong>Calon siswa:</strong> {{ $pendaftaran->nama }}</p>
            <p><strong>Jenjang:</strong> {{ $pendaftaran->jenjang }}</p>
        </div>
        <p>Admin kami akan melakukan verifikasi dan mengirimkan formulir pendaftaran lewat WhatsApp.</p>
        <p class="signature">Ttd,<br><strong>Admin SPMB</strong></p>
        <a href="{{ route('spmb') }}">Kembali ke halaman SPMB</a>
    </div></main>
</body>
</html>
