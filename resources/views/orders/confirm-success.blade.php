<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Konfirmasi Berhasil</title>
    <meta http-equiv="refresh" content="3;url=/" />
    <style>body{font-family:Arial,Helvetica,sans-serif;padding:24px}</style>
</head>
<body>
    <h2>Terima kasih — Konfirmasi diterima</h2>
    <p>Nama: {{ $order->customer_name ?? '-' }}</p>
    <p>Pilihan: {{ $order->notes ?? '-' }}</p>
    <p>Anda akan diarahkan ke beranda dalam beberapa detik. Jika tidak, klik <a href="/">Home</a>.</p>
</body>
</html>
