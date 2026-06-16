<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Receipt - Order {{ $order->id }}</title>
    <style>
        body{font-family:Arial,Helvetica,sans-serif;max-width:320px;margin:0 auto;color:#111;padding:12px}
        .center{text-align:center}
        .muted{color:#666;font-size:12px}
        .line{border-top:1px solid #ddd;margin:12px 0}
        .row{display:flex;justify-content:space-between}
        .bold{font-weight:700}
        .item{margin:6px 0}
        @media print{ .no-print{display:none} }
    </style>
</head>
<body>
    <div class="center">
        <h2 style="margin:6px 0">Restoran Nusantara</h2>
        <div class="muted">Struk POS</div>
    </div>

    <div class="line"></div>

    <div class="row"><div>No. Pesanan</div><div>ORD/{{ $order->created_at->format('ymd') }}/{{ $order->id }}</div></div>
    <div class="row"><div>Waktu</div><div>{{ $order->created_at->format('d M Y, H:i') }}</div></div>
    <div class="row"><div>Kasir</div><div>{{ $order->cashier_name ?? 'Kasir' }}</div></div>

    <div class="line"></div>

    @if(!empty($order->items) && is_array($order->items))
        @foreach($order->items as $it)
            <div class="item">
                <div class="row"><div>{{ $it['item_name'] }} x{{ $it['quantity'] }}</div><div>Rp{{ number_format($it['subtotal'] ?? ($it['unit_price']*$it['quantity']),0,',','.') }}</div></div>
                @if(!empty($it['notes']))<div class="muted">Catatan: {{ $it['notes'] }}</div>@endif
            </div>
        @endforeach
    @else
        <div class="item">
            <div class="row"><div>{{ $order->item_name }}</div><div>Rp{{ number_format($order->total_price,0,',','.') }}</div></div>
            <div class="muted">{{ $order->notes }}</div>
        </div>
    @endif

    <div class="line"></div>
    <div class="row bold"><div>Total</div><div>Rp{{ number_format($order->total_price,0,',','.') }}</div></div>

    <div class="line"></div>
    <div class="center muted no-print"><button onclick="window.print()" style="padding:8px 12px;border-radius:8px;border:none;background:#b53f2e;color:#fff;cursor:pointer">Cetak</button></div>
</body>
</html>