<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Konfirmasi Data Pelanggan</title>
</head>
<body>
    <h2>Konfirmasi Sebelum Kembali ke Home</h2>
    <form method="post" action="{{ route('orders.confirm.submit', $order) }}">
        @csrf
        <div>
            <label>Nama Pelanggan (opsional)</label><br>
            <input type="text" name="customer_name" value="{{ old('customer_name', $order->customer_name) }}" />
        </div>
        <div>
            <label>Ingin makan dimana?</label><br>
            <label><input type="radio" name="dine_type" value="dine_in" {{ old('dine_type')=='dine_in' ? 'checked' : '' }} /> Makan di sini</label><br>
            <label><input type="radio" name="dine_type" value="takeaway" {{ old('dine_type')=='takeaway' ? 'checked' : '' }} /> Take away</label>
        </div>
        <div style="margin-top:12px">
            <button type="submit">Kembali ke Home</button>
        </div>
    </form>
</body>
</html>
