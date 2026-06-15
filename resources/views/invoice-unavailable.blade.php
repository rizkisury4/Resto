<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Invoice Belum Tersedia</title>
</head>
<body>
    <p style="margin-bottom:8px"><a href="/">← Home</a></p>
    <h2>Invoice belum tersedia untuk Order #{{ $order->id }}</h2>
    <p>Status saat ini: {{ $order->status }}</p>
    <p>Invoice akan tersedia setelah pembayaran dikonfirmasi.</p>
    <p>Untuk pengujian lokal, Anda dapat melakukan simulasi pembayaran di halaman ini:</p>
    <p><a href="{{ route('payment.simulate', $order) }}">Simulasi Pembayaran</a></p>
</body>
</html>
