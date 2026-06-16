<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Admin - Orders</title>
    <style>table{width:100%;border-collapse:collapse}th,td{padding:8px;border:1px solid #ddd}</style>
</head>
<body>
    <h2>Admin POS Dashboard</h2>
    <p><a href="{{ route('admin.logout') }}">Logout</a></p>

    @if(session('status'))<div style="color:green">{{ session('status') }}</div>@endif

    <div style="display:grid; grid-template-columns: 220px 1fr 420px; gap:18px; align-items:start;">
        <aside style="background:#fff; border:1px solid #eee; padding:14px; border-radius:10px;">
            <h3 style="margin-top:0;">Navigation</h3>
            <nav style="display:flex; flex-direction:column; gap:8px;">
                <a href="{{ route('admin.dashboard') }}" style="display:block; padding:8px 10px; border-radius:8px; text-decoration:none; color:#333; background:#fff;">Home - Laporan Penjualan</a>
                <a href="{{ route('admin.payments.index') }}" style="display:block; padding:8px 10px; border-radius:8px; text-decoration:none; color:#333; background:#fff;">Laporan Pembayaran</a>
                <a href="{{ route('admin.orders.index') }}" style="display:block; padding:8px 10px; border-radius:8px; text-decoration:none; color:#333; background:#fff;">Order Makanan</a>
                <a href="{{ route('admin.menu.index') }}" style="display:block; padding:8px 10px; border-radius:8px; text-decoration:none; color:#333; background:#fff;">Daftar Makanan</a>
            </nav>
        </aside>

        <section style="background:#fff; border:1px solid #eee; padding:14px; border-radius:10px;">
            <h3 style="margin-top:0;">Keranjang POS</h3>
            <form id="posForm" method="POST" action="{{ route('admin.orders.pos.create') }}">
                @csrf
                <div id="cartItems" style="display:grid; gap:10px;">
                    <!-- rows added by JS -->
                </div>

                <div style="display:flex; justify-content:space-between; align-items:center; margin-top:12px;">
                    <div style="font-weight:700">Total: <span id="totalDisplay">Rp 0</span></div>
                    <div style="display:flex; gap:8px; align-items:center;">
                        <select name="payment_method" id="payment_method" style="padding:8px 10px; border-radius:8px;">
                            <option value="cashier">Bayar di Kasir</option>
                            <option value="debit">Kartu Debit</option>
                        </select>
                        <button type="submit" style="padding:10px 14px; border-radius:8px; background:#2b6f4a; color:#fff; border:none; cursor:pointer;">Proses (POS)</button>
                    </div>
                </div>
            </form>
        </section>

        <aside style="background:#fff; border:1px solid #eee; padding:14px; border-radius:10px;">
            <h3 style="margin-top:0;">Pesanan Aktif</h3>
            <div style="max-height:68vh; overflow:auto;">
                <table style="width:100%; border-collapse:collapse">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Item</th>
                            <th>Qty</th>
                            <th>Notes</th>
                            <th>Status</th>
                            <th>ETA</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($foods as $order)
                            <tr style="border-bottom:1px solid #f2f2f2">
                                <td>{{ $order->id }}</td>
                                <td style="max-width:160px">{{ $order->item_name }}</td>
                                <td>{{ $order->quantity }}</td>
                                <td style="max-width:420px;">
                                    {{ $order->notes }}
                                    @if(!empty($order->items) && is_array($order->items))
                                        <div style="margin-top:6px; font-size:0.9rem; color:#444">
                                            <strong>Items:</strong>
                                            <ul style="margin:6px 0 0 18px; padding:0;">
                                                @foreach($order->items as $it)
                                                    <li>{{ $it['item_name'] }} x{{ $it['quantity'] }} — Rp{{ number_format($it['subtotal'] ?? ($it['unit_price']*$it['quantity']),0,',','.') }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
                                </td>
                                <td>{{ $order->status }}</td>
                                <td>{{ $order->eta ? $order->eta->format('H:i') . ' (' . $order->estimated_prep_minutes . 'm)' : '-' }}</td>
                                <td style="white-space:nowrap">
                                    <form method="post" action="{{ route('admin.orders.update', $order) }}" style="display:inline-block">
                                        @csrf
                                        @method('patch')
                                        <select name="status">
                                            <option value="pending" {{ $order->status=='pending'?'selected':'' }}>Pending</option>
                                            <option value="pending_payment" {{ $order->status=='pending_payment'?'selected':'' }}>Pending Payment</option>
                                            <option value="paid" {{ $order->status=='paid'?'selected':'' }}>Paid</option>
                                            <option value="on progress" {{ $order->status=='on progress'?'selected':'' }}>On Progress</option>
                                            <option value="completed" {{ $order->status=='completed'?'selected':'' }}>Completed</option>
                                        </select>
                                        <button type="submit">Update</button>
                                    </form>
                                    <a href="{{ route('admin.orders.receipt', $order) }}" style="margin-left:8px; display:inline-block; padding:6px 8px; background:#f3f3f3; border-radius:6px; text-decoration:none; color:#333">Cetak Struk</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </aside>
    </div>

    <script>
        (function(){
            const menuButtons = document.querySelectorAll('.menu-add');
            const cart = document.getElementById('cartItems');
            const totalDisplay = document.getElementById('totalDisplay');
            let cartState = [];

            function formatRupiah(v){ return 'Rp ' + v.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.'); }

            function renderCart(){
                cart.innerHTML = '';
                let total = 0;
                cartState.forEach((it, idx)=>{
                    const row = document.createElement('div');
                    row.style.display='flex'; row.style.gap='8px'; row.style.alignItems='center';
                    row.innerHTML = `
                        <input type="hidden" name="items[${idx}][item_name]" value="${it.item_name}" />
                        <input type="hidden" name="items[${idx}][notes]" value="${it.notes||''}" />
                        <div style="flex:1">${it.item_name}<div style="font-size:0.85rem;color:#666">${formatRupiah(it.unit_price)}</div></div>
                        <div><input type="number" min="1" value="${it.quantity}" data-idx="${idx}" class="qty-input" style="width:70px; padding:6px;" /></div>
                        <div style="width:110px; text-align:right">${formatRupiah(it.unit_price * it.quantity)}</div>
                        <div><button type="button" data-idx="${idx}" class="remove-btn" style="background:transparent;border:none;color:#b53f2e;cursor:pointer">Hapus</button></div>
                    `;
                    cart.appendChild(row);
                    total += it.unit_price * it.quantity;
                });

                totalDisplay.textContent = formatRupiah(total);

                // attach events
                cart.querySelectorAll('.qty-input').forEach(inp=>{
                    inp.addEventListener('change', function(){
                        const i = parseInt(this.dataset.idx);
                        const v = Math.max(1, parseInt(this.value)||1);
                        cartState[i].quantity = v;
                        renderCart();
                    });
                });
                cart.querySelectorAll('.remove-btn').forEach(btn=>{
                    btn.addEventListener('click', function(){
                        const i = parseInt(this.dataset.idx);
                        cartState.splice(i,1);
                        renderCart();
                    });
                });
            }

            menuButtons.forEach(btn=>{
                btn.addEventListener('click', function(){
                    const name = this.dataset.name;
                    const price = parseInt(this.dataset.price);
                    // if exists, increment qty
                    const found = cartState.find(it=>it.item_name===name);
                    if(found){ found.quantity++; }
                    else { cartState.push({ item_name: name, unit_price: price, quantity:1 }); }
                    renderCart();
                });
            });

            // initial empty cart
            renderCart();
        })();
    </script>
</body>
</html>
