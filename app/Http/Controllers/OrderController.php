<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;

class OrderController extends Controller
{
    private $menuPrices = [
        'Nasi Goreng' => 25000,
        'Mie Goreng' => 22000,
        'Nasi Ayam Goreng' => 29000,
        'Ayam Bakar' => 32000,
        'Ayam Geprek' => 28000,
        'Sate Ayam' => 26000,
    ];

    public function start()
    {
        if (session()->has('customer_context')) {
            return redirect()->route('menu');
        }

        return view('start-order');
    }

    public function startOrder(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'service_type' => 'required|in:dine_in,takeaway',
        ]);

        session([
            'customer_context' => [
                'customer_name' => trim($validated['customer_name']),
                'service_type' => $validated['service_type'],
            ],
        ]);

        return redirect()->route('menu');
    }

    public function resetOrderSession()
    {
        session()->forget(['customer_context', 'order_data']);

        return redirect()->route('order.start');
    }

    public function menu()
    {
        $customerContext = session('customer_context');

        if (! $customerContext) {
            return redirect()->route('order.start');
        }

        // load menu items from DB if available
        $menuItems = [];
        if (class_exists(\App\Models\MenuItem::class)) {
            $menuItems = \App\Models\MenuItem::where('is_active', true)->orderBy('name')->get();
        }

        return view('menu', compact('customerContext', 'menuItems'));
    }

    public function index()
    {
        $customerContext = session('customer_context');

        if (! $customerContext) {
            return redirect()->route('order.start');
        }

        $orders = Order::where('customer_name', $customerContext['customer_name'])
            ->latest()
            ->get();

        return view('orders', [
            'orders' => $orders,
            'customerContext' => $customerContext,
        ]);
    }

    public function checkout(Request $request)
    {
        $customerContext = session('customer_context');

        if (! $customerContext) {
            return redirect()->route('order.start');
        }

        $customer_name = $customerContext['customer_name'];
        $service_type = $customerContext['service_type'];

        // Support both single-item form (legacy) and multi-item 'items' array
        if ($request->has('items')) {
            $items = $request->input('items');

            $rules = [
                'items' => 'required|array|min:1',
            ];

            // allow menu names from DB if MenuItem model exists
            $validNames = array_keys($this->menuPrices);
            if (class_exists(\App\Models\MenuItem::class)) {
                $dbNames = \App\Models\MenuItem::where('is_active', true)->pluck('name')->toArray();
                if (! empty($dbNames)) {
                    $validNames = $dbNames;
                }
            }

            foreach ($items as $i => $it) {
                $rules["items.$i.item_name"] = ['required', 'string', Rule::in($validNames)];
                $rules["items.$i.quantity"] = 'required|integer|min:1|max:20';
                $rules["items.$i.notes"] = 'nullable|string|max:500';
            }

            $validated = $request->validate($rules);

            $cleanItems = [];
            $total = 0;
            foreach ($validated['items'] as $it) {
                $name = $it['item_name'];
                $qty = (int) $it['quantity'];
                // resolve price from DB menu if available
                if (class_exists(\App\Models\MenuItem::class)) {
                    $menuRow = \App\Models\MenuItem::where('name', $name)->first();
                    $price = $menuRow ? (float) $menuRow->price : ($this->menuPrices[$name] ?? 0);
                } else {
                    $price = $this->menuPrices[$name] ?? 0;
                }
                $subtotal = $price * $qty;
                $cleanItems[] = [
                    'item_name' => $name,
                    'quantity' => $qty,
                    'unit_price' => $price,
                    'notes' => $it['notes'] ?? null,
                    'subtotal' => $subtotal,
                ];
                $total += $subtotal;
            }

            session([
                'order_data' => [
                    'items' => $cleanItems,
                    'customer_name' => $customer_name,
                    'service_type' => $service_type,
                    'total_price' => $total,
                ]
            ]);

            return view('checkout', [
                'items' => $cleanItems,
                'customer_name' => $customer_name,
                'service_type' => $service_type,
                'total_price' => $total,
            ]);
        }

        // Legacy single-item handling
        $item_name = $request->input('item_name');
        $quantity = $request->input('quantity');
        $notes = $request->input('notes');

        $validNames = array_keys($this->menuPrices);
        if (class_exists(\App\Models\MenuItem::class)) {
            $dbNames = \App\Models\MenuItem::where('is_active', true)->pluck('name')->toArray();
            if (! empty($dbNames)) {
                $validNames = $dbNames;
            }
        }

        $validated = $request->validate([
            'item_name' => ['required', 'string', Rule::in($validNames)],
            'quantity' => 'required|integer|min:1|max:20',
            'notes' => 'nullable|string|max:500',
        ]);

        $item_name = $validated['item_name'];
        $quantity = $validated['quantity'];
        if (class_exists(\App\Models\MenuItem::class)) {
            $menuRow = \App\Models\MenuItem::where('name', $item_name)->first();
            $price = $menuRow ? (float) $menuRow->price : ($this->menuPrices[$item_name] ?? 0);
        } else {
            $price = $this->menuPrices[$item_name] ?? 0;
        }
        $total = $price * $quantity;

        session([
            'order_data' => [
                'item_name' => $item_name,
                'quantity' => $quantity,
                'customer_name' => $customer_name,
                'service_type' => $service_type,
                'notes' => $notes,
                'total_price' => $total,
            ]
        ]);

        return view('checkout', [
            'item_name' => $item_name,
            'quantity' => $quantity,
            'customer_name' => $customer_name,
            'service_type' => $service_type,
            'notes' => $notes,
            'total_price' => $total,
        ]);
    }

    public function store(Request $request)
    {
        $customerContext = session('customer_context');

        if (! $customerContext) {
            return redirect()->route('order.start');
        }

        // If checkout used multi-item cart, use session data
        $orderData = session('order_data');

        if (! empty($orderData) && ! empty($orderData['items'])) {
            $request->validate([
                'payment_method' => 'required|in:debit,cashier',
            ]);

            $items = $orderData['items'];
            $totalPrice = $orderData['total_price'] ?? 0;
            $serviceLabel = $customerContext['service_type'] === 'dine_in' ? 'Makan di sini' : 'Takeaway';

            $notesParts = [];
            foreach ($items as $it) {
                $notesParts[] = $it['item_name'] . ' x' . $it['quantity'] . ($it['notes'] ? ' (' . $it['notes'] . ')' : '');
            }

            $generalNotes = trim((string) $request->input('notes', ''));
            $notesText = $serviceLabel . ' - ' . implode(' ; ', $notesParts);
            if ($generalNotes !== '') {
                $notesText .= ' | Catatan: ' . $generalNotes;
            }

            $orderAttributes = [
                'item_name' => count($items) > 1 ? 'Beberapa item' : $items[0]['item_name'],
                'quantity' => array_sum(array_column($items, 'quantity')),
                'customer_name' => $customerContext['customer_name'],
                'notes' => $notesText,
                'payment_method' => $request->input('payment_method'),
                'total_price' => $totalPrice,
            ];

            if (Schema::hasColumn('orders', 'items')) {
                $orderAttributes['items'] = $items;
            }

            $order = Order::create($orderAttributes);
            // if a cashier created this (admin POS) allow recording cashier_name via request
            if ($request->filled('cashier_name') && Schema::hasColumn('orders', 'cashier_name')) {
                $order->cashier_name = $request->input('cashier_name');
                $order->save();
            }
        } else {
            $validated = $request->validate([
                'item_name' => 'required|string',
                'quantity' => 'required|integer|min:1|max:20',
                'notes' => 'nullable|string|max:500',
                'payment_method' => 'required|in:debit,cashier',
                'total_price' => 'required|numeric|min:1000',
            ]);

            $validated['customer_name'] = $customerContext['customer_name'];
            $generalNotes = trim((string) $request->input('notes', ''));
            // resolve unit price from DB if MenuItem exists
            if (class_exists(\App\Models\MenuItem::class)) {
                $menuRow = \App\Models\MenuItem::where('name', $validated['item_name'])->first();
                $unitPrice = $menuRow ? (float)$menuRow->price : ($this->menuPrices[$validated['item_name']] ?? 0);
            } else {
                $unitPrice = $this->menuPrices[$validated['item_name']] ?? 0;
            }

            $validated['total_price'] = $unitPrice * $validated['quantity'];
            $serviceLabel = $customerContext['service_type'] === 'dine_in' ? 'Makan di sini' : 'Takeaway';
            $validated['notes'] = trim($serviceLabel . ($validated['notes'] ? ' - ' . $validated['notes'] : '') . ($generalNotes !== '' ? ' | Catatan: ' . $generalNotes : ''));

            $order = Order::create($validated);
            // ensure single-item orders also store items array for consistency
            if (Schema::hasColumn('orders', 'items')) {
                $order->items = [[
                    'item_name' => $validated['item_name'],
                    'quantity' => $validated['quantity'],
                    'unit_price' => $unitPrice,
                    'notes' => $validated['notes'] ?? null,
                    'subtotal' => $validated['quantity'] * $unitPrice,
                ]];
                $order->save();
            }
        }

        // set status depending on payment method
        $paymentMethod = $request->input('payment_method');
        if ($paymentMethod === 'debit') {
            $order->status = 'pending_payment';
        } else {
            $order->status = 'pending';
        }
        // total_price already set on create
        $order->save();

        session()->forget('order_data');

        // If payment is online (debit) redirect to mock payment gateway simulation
        if ($paymentMethod === 'debit') {
            return redirect()->route('payment.simulate', $order->id);
        }

        // For cashier payment, redirect to waiting page where customer waits for admin approval after paying at cashier
        return redirect()->route('orders.waiting', $order->id)->with('success', 'Pesanan diterima. Silakan bayar di kasir.');
    }

    public function waiting(Order $order)
    {
        $this->authorizeCustomerOrder($order);

        return view('orders.waiting', compact('order'));
    }

    public function status(Order $order)
    {
        $this->authorizeCustomerOrder($order);

        return response()->json(['status' => $order->status]);
    }

    public function confirmForm(Order $order)
    {
        $this->authorizeCustomerOrder($order);

        return redirect()->route('orders.invoice', $order->id);
    }

    public function confirmSubmit(Request $request, Order $order)
    {
        $this->authorizeCustomerOrder($order);

        $validated = $request->validate([
            'customer_name' => 'nullable|string|max:255',
            'dine_type' => 'required|in:dine_in,takeaway',
        ]);

        $order->customer_name = $validated['customer_name'] ?? $order->customer_name;
        $order->notes = $validated['dine_type'] === 'dine_in' ? ($order->notes ?? 'Makan di sini') : ($order->notes ?? 'Takeaway');
        $order->save();

        return redirect()->route('orders.invoice', $order->id);
    }

    private function authorizeCustomerOrder(Order $order): void
    {
        $customerContext = session('customer_context');

        if (! $customerContext || $order->customer_name !== $customerContext['customer_name']) {
            abort(403, 'Pesanan ini bukan milik customer yang sedang aktif.');
        }
    }
}
