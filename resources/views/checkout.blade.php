<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Checkout - Restoran Nusantara</title>
    <style>
        body {
            margin: 0;
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            background: #fff7f0;
            color: #2d2a26;
        }
        .page {
            max-width: 700px;
            margin: 0 auto;
            padding: 24px;
        }
        header {
            text-align: center;
            padding: 28px 16px;
        }
        h1 {
            margin: 0;
            font-size: clamp(1.8rem, 3vw, 2.8rem);
            color: #b53f2e;
        }
        .back-link {
            display: inline-block;
            margin-top: 12px;
            color: #b53f2e;
            text-decoration: none;
            font-weight: 600;
        }
        .checkout-box {
            background: #fff;
            border: 1px solid rgba(181, 63, 46, 0.2);
            border-radius: 20px;
            padding: 28px;
            box-shadow: 0 20px 50px rgba(181, 63, 46, 0.12);
        }
        .order-summary {
            padding: 18px;
            background: rgba(181, 63, 46, 0.08);
            border-radius: 14px;
            margin-bottom: 24px;
        }
        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            font-size: 0.95rem;
        }
        .summary-row strong {
            color: #2d2a26;
        }
        .summary-row.total {
            font-size: 1.3rem;
            font-weight: 700;
            color: #b53f2e;
            border-top: 1px solid rgba(181, 63, 46, 0.3);
            padding-top: 12px;
            margin-top: 12px;
        }
        .payment-section h2 {
            margin: 0 0 16px;
            color: #8f2f1d;
            font-size: 1.2rem;
        }
        .payment-options {
            display: grid;
            gap: 14px;
            margin-bottom: 24px;
        }
        .payment-option {
            display: flex;
            align-items: center;
            padding: 16px 18px;
            border: 2px solid #dcd6d2;
            border-radius: 14px;
            cursor: pointer;
            transition: all 0.2s;
            background: #fff;
        }
        .payment-option:hover {
            border-color: #b53f2e;
            background: rgba(181, 63, 46, 0.04);
        }
        .payment-option input[type="radio"] {
            margin: 0 12px 0 0;
            width: 20px;
            height: 20px;
            cursor: pointer;
        }
        .payment-option input[type="radio"]:checked + label {
            font-weight: 700;
            color: #b53f2e;
        }
        .payment-option label {
            flex: 1;
            cursor: pointer;
            margin: 0;
            display: flex;
            flex-direction: column;
        }
        .payment-option .method-name {
            font-weight: 600;
            color: #2d2a26;
        }
        .payment-option .method-desc {
            font-size: 0.85rem;
            color: #6d6057;
            margin-top: 4px;
        }
        .button-group {
            display: flex;
            gap: 12px;
            justify-content: flex-end;
        }
        .btn {
            padding: 14px 28px;
            border-radius: 14px;
            font-weight: 700;
            border: none;
            cursor: pointer;
            transition: all 0.2s;
        }
        .btn-primary {
            background: #b53f2e;
            color: #fff;
        }
        .btn-primary:hover {
            background: #8f2f1d;
        }
        .btn-secondary {
            background: #dcd6d2;
            color: #2d2a26;
        }
        .btn-secondary:hover {
            background: #c9c1bb;
        }
        form {
            display: contents;
        }
    </style>
</head>
<body>
    <div class="page">
        <header>
            <h1>Checkout Pesanan</h1>
            <a class="back-link" href="{{ route('menu') }}">← Kembali ke Menu</a>
        </header>

        <div class="checkout-box">
            <div class="order-summary">
                <div class="summary-row">
                    <span>Menu:</span>
                    <strong>{{ $item_name }}</strong>
                </div>
                <div class="summary-row">
                    <span>Jumlah:</span>
                    <strong>{{ $quantity }} Porsi</strong>
                </div>
                @if ($customer_name)
                    <div class="summary-row">
                        <span>Nama:</span>
                        <strong>{{ $customer_name }}</strong>
                    </div>
                @endif
                <div class="summary-row">
                    <span>Tipe Pesanan:</span>
                    <strong>{{ $service_type === 'dine_in' ? 'Makan di sini' : 'Takeaway' }}</strong>
                </div>
                @if ($notes)
                    <div class="summary-row">
                        <span>Catatan:</span>
                        <strong>{{ $notes }}</strong>
                    </div>
                @endif
                <div class="summary-row total">
                    <span>Total Harga:</span>
                    <span>Rp {{ number_format($total_price, 0, ',', '.') }}</span>
                </div>
            </div>

            <form action="{{ route('order.store') }}" method="POST">
                @csrf
                <input type="hidden" name="item_name" value="{{ $item_name }}" />
                <input type="hidden" name="quantity" value="{{ $quantity }}" />
                <input type="hidden" name="customer_name" value="{{ $customer_name }}" />
                <input type="hidden" name="service_type" value="{{ $service_type }}" />
                <input type="hidden" name="notes" value="{{ $notes }}" />
                <input type="hidden" name="total_price" value="{{ $total_price }}" />

                <div class="payment-section">
                    <h2>Pilih Metode Pembayaran</h2>
                    <div class="payment-options">
                        <div class="payment-option">
                            <input type="radio" id="cashier" name="payment_method" value="cashier" checked />
                            <label for="cashier">
                                <span class="method-name">💰 Bayar di Kasir</span>
                                <span class="method-desc">Bayar langsung di kasir ketika pesanan siap</span>
                            </label>
                        </div>

                        <div class="payment-option">
                            <input type="radio" id="debit" name="payment_method" value="debit" />
                            <label for="debit">
                                <span class="method-name">💳 Kartu Debit</span>
                                <span class="method-desc">Pembayaran via kartu debit (API belum tersedia)</span>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="button-group">
                    <a href="{{ route('menu') }}" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary">Proses Pesanan</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
