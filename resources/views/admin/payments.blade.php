<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Admin - Laporan Pembayaran</title>
</head>
<body>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}" />
    <script src="{{ asset('js/app.js') }}" defer></script>

    <h2>Laporan Pembayaran (Konfirmasi Kasir)</h2>
    <p><a href="{{ route('admin.dashboard') }}">Kembali</a> | <a href="{{ route('admin.logout') }}">Logout</a></p>

    @if(session('status'))<div style="color:green">{{ session('status') }}</div>@endif

    <table style="width:100%;border-collapse:collapse"><thead><tr><th>ID</th><th>Total</th><th>Metode</th><th>Status</th><th>Aksi</th></tr></thead>
        <tbody>
            @foreach($pendingPayments as $p)
                <tr>
                    <td>{{ $p->id }}</td>
                    <td>Rp {{ number_format($p->total_price,0,',','.') }}</td>
                    <td>{{ $p->payment_method }}</td>
                    <td>{{ $p->status }}</td>
                    <td>
                        <form method="post" action="{{ route('admin.payments.confirm', $p) }}">
                            @csrf
                            <button type="submit" style="padding:6px 8px; border-radius:6px; background:#2b6f4a; color:#fff; border:none">Konfirmasi Bayar</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
