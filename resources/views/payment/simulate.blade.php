<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Simulate Payment</title>
</head>
<body>
    <h2>Simulasi Pembayaran untuk Order #{{ $order->id }}</h2>
    <p>Menu: {{ $order->item_name }} — Jumlah: {{ $order->quantity }} — Total: Rp{{ number_format($order->total_price,0,',','.') }}</p>

    <form method="post" action="{{ route('payment.mock.pay', $order) }}">
        @csrf
        <button type="submit">Bayar Sekarang (Simulasi)</button>
    </form>

    <p>Atau kirim webhook POST ke <code>/payment/webhook</code> dengan JSON: {"order_id": {{ $order->id }}, "status":"paid"}</p>
</body>
</html>
