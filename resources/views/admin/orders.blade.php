<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Admin - Orders</title>
    <style>table{width:100%;border-collapse:collapse}th,td{padding:8px;border:1px solid #ddd}</style>
</head>
<body>
    <h2>Orders</h2>
    <p><a href="{{ route('admin.logout') }}">Logout</a></p>

    @if(session('status'))<div style="color:green">{{ session('status') }}</div>@endif

    <table>
        <thead>
            <tr><th>ID</th><th>Item</th><th>Qty</th><th>Customer</th><th>Notes</th><th>Status</th><th>Time</th><th>Action</th></tr>
        </thead>
        <tbody>
            @foreach($orders as $order)
                <tr>
                    <td>{{ $order->id }}</td>
                    <td>{{ $order->item_name }}</td>
                    <td>{{ $order->quantity }}</td>
                    <td>{{ $order->customer_name }}</td>
                    <td>{{ $order->notes }}</td>
                    <td>{{ $order->status }}</td>
                    <td>{{ $order->created_at->format('d M Y H:i') }}</td>
                    <td>
                        <form method="post" action="{{ route('admin.orders.update', $order) }}">
                            @csrf
                            @method('patch')
                            <select name="status">
                                <option value="pending" {{ $order->status=='pending'?'selected':'' }}>Pending</option>
                                <option value="on progress" {{ $order->status=='on progress'?'selected':'' }}>On Progress</option>
                                <option value="completed" {{ $order->status=='completed'?'selected':'' }}>Completed</option>
                            </select>
                            <button type="submit">Update</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
