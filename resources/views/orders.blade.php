<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Daftar Pesanan</title>
    <style>
        body {
            margin: 0;
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            background: #fff7f0;
            color: #2d2a26;
        }
        .page {
            max-width: 980px;
            margin: 0 auto;
            padding: 24px;
        }
        header {
            text-align: center;
            padding: 28px 16px;
        }
        h1 {
            margin: 0;
            font-size: clamp(2rem, 3vw, 3.5rem);
            color: #b53f2e;
        }
        .back-link {
            display: inline-block;
            margin-top: 16px;
            color: #ffffff;
            background: #b53f2e;
            padding: 10px 18px;
            border-radius: 999px;
            text-decoration: none;
            font-weight: 600;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 24px;
            background: #fff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 20px 50px rgba(181, 63, 46, 0.12);
        }
        th, td {
            padding: 16px 14px;
            text-align: left;
            border-bottom: 1px solid #f0e2dd;
        }
        th {
            background: #ffe8e0;
            color: #8f2f1d;
            font-weight: 700;
        }
        tbody tr:last-child td {
            border-bottom: none;
        }
        .empty {
            text-align: center;
            padding: 42px 16px;
            color: #6d6057;
            background: #fff5f0;
            border-radius: 0 0 16px 16px;
        }
    </style>
</head>
<body>
    <div class="page">
        <header>
            <h1>Daftar Pesanan</h1>
            @isset($customerContext)
                <p style="margin:10px 0 0; color:#6d6057;">Menampilkan pesanan untuk {{ $customerContext['customer_name'] }}.</p>
            @endisset
            <a class="back-link" href="{{ route('menu') }}">Kembali ke Menu</a>
        </header>

        @if ($orders->isEmpty())
            <div class="empty">Belum ada pesanan masuk.</div>
        @else
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Menu</th>
                        <th>Jumlah</th>
                        <th>Total</th>
                        <th>Nama</th>
                        <th>Pembayaran</th>
                        <th>Status</th>
                        <th>Waktu</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orders as $order)
                        <tr>
                            <td>{{ $order->id }}</td>
                            <td>{{ $order->item_name }}</td>
                            <td>{{ $order->quantity }}</td>
                            <td>Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                            <td>{{ $order->customer_name ?: '-' }}</td>
                            <td>
                                <span style="padding: 4px 12px; border-radius: 12px; font-size: 0.85rem; font-weight: 600; {{ $order->payment_method === 'debit' ? 'background: #e3f2fd; color: #1565c0;' : 'background: #fff3e0; color: #e65100;' }}">
                                    {{ $order->payment_method === 'debit' ? '💳 Debit' : '💰 Kasir' }}
                                </span>
                            </td>
                            <td>
                                <span style="padding: 4px 12px; border-radius: 12px; font-size: 0.85rem; font-weight: 600; {{ $order->status === 'completed' ? 'background: #e8f5e9; color: #2e7d32;' : ($order->status === 'paid' ? 'background: #fff3e0; color: #e65100;' : 'background: #f3e5f5; color: #6a1b9a;') }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                            <td>{{ $order->created_at->format('d M Y H:i') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</body>
</html>
