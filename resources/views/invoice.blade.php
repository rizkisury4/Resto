<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Invoice - Order {{ $order->id }}</title>
    <style>
        body{font-family: 'Courier New', monospace; max-width:360px;margin:0 auto;color:#111}
        .center{text-align:center}
        .muted{color:#666;font-size:12px}
        .line{border-top:1px solid #ddd;margin:12px 0}
        .row{display:flex;justify-content:space-between}
        .bold{font-weight:700}
        .item{margin:6px 0}
    </style>
</head>
<body>
    @unless($forPdf ?? false)
    <div style="text-align:center;margin-top:8px;margin-bottom:8px">
        <a href="/">← Home</a>
    </div>
    @endunless
    <div class="center">
        <h2 style="margin:6px 0">Restoran Nusantara</h2>
        <div class="muted">Jl. Kelapa Gading, Jakarta Utara</div>
        <div class="muted">0812-3456-7890</div>
    </div>

    <div class="line"></div>

    <div class="row">
        <div>No. Pesanan</div>
        <div>ORD/{{ $order->created_at->format('ymd') }}/{{ $order->id }}</div>
    </div>
    <div class="row">
        <div>Waktu</div>
        <div>{{ $order->created_at->format('d M Y, H:i') }}</div>
    </div>
    <div class="row">
        <div>Jenis Pesanan</div>
        <div>{{ $order->notes ?? 'Dine In / Takeaway' }}</div>
    </div>
    <div class="row">
        <div>Kasir</div>
        <div>{{ $order->cashier_name ?? 'Kasir' }}</div>
    </div>

    <div class="line"></div>

    <div class="item">
        <div class="row">
            <div>{{ $order->item_name }}</div>
            <div>Rp{{ number_format($unitPrice * $quantity,0,',','.') }}</div>
        </div>
        <div class="muted">{{ $quantity }} x Rp{{ number_format($unitPrice,0,',','.') }}</div>
    </div>

    <div class="line"></div>

    <div class="row bold">
        <div>Total</div>
        <div>Rp{{ number_format($total,0,',','.') }}</div>
    </div>

    <div class="line"></div>

    <div class="bold">Pembayaran</div>
    <div class="row">
        <div>Metode</div>
        <div>{{ $order->payment_method ?? 'Tunai' }}</div>
    </div>
    <div class="row">
        <div>Jumlah Dibayarkan</div>
        <div>Rp{{ number_format($paymentAmount,0,',','.') }}</div>
    </div>
    <div class="row">
        <div>Kembali</div>
        <div>Rp{{ number_format($change,0,',','.') }}</div>
    </div>

    <div style="height:20px"></div>
    <div class="center muted">Powered by Bistro - bistro.my.id</div>
    @unless($forPdf ?? false)
        @if($order->status === 'paid' || $order->payment_method === 'cashier')
            <div style="text-align:center;margin-top:12px">
                <a href="{{ route('orders.invoice.pdf', $order->id) }}">Download PDF</a>
            </div>
        @endif
    @endunless
</body>
</html>
