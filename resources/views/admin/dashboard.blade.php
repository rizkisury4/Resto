<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Admin - Dashboard</title>
</head>
<body>
    <h2>Laporan Penjualan</h2>
    <p><a href="{{ route('admin.logout') }}">Logout</a></p>

    <div style="display:flex; gap:18px; align-items:flex-start">
        <div style="flex:1; background:#fff; border:1px solid #eee; padding:12px; border-radius:8px;">
            <h3>Ringkasan Hari Ini</h3>
            <div>Total Penjualan: Rp {{ number_format($salesToday,0,',','.') }}</div>
            <div>Jumlah Pesanan: {{ $ordersToday }}</div>
        </div>

        <div style="flex:2; background:#fff; border:1px solid #eee; padding:12px; border-radius:8px;">
            <h3>Pesanan Terbaru</h3>
            <table style="width:100%;border-collapse:collapse"><thead><tr><th>ID</th><th>Total</th><th>Status</th><th>Waktu</th></tr></thead>
                <tbody>
                    @foreach($recentOrders as $o)
                        <tr>
                            <td>{{ $o->id }}</td>
                            <td>Rp {{ number_format($o->total_price,0,',','.') }}</td>
                            <td>{{ $o->status }}</td>
                            <td>{{ $o->created_at }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
