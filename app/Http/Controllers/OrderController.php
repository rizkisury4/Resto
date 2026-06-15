<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
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

        return view('menu', compact('customerContext'));
    }

    public function index()
    {
        $orders = Order::latest()->get();

        return view('orders', compact('orders'));
    }

    public function checkout(Request $request)
    {
        $customerContext = session('customer_context');

        if (! $customerContext) {
            return redirect()->route('order.start');
        }

        $item_name = $request->input('item_name');
        $quantity = $request->input('quantity');
        $customer_name = $customerContext['customer_name'];
        $service_type = $customerContext['service_type'];
        $notes = $request->input('notes');

        $validated = $request->validate([
            'item_name' => ['required', 'string', Rule::in(array_keys($this->menuPrices))],
            'quantity' => 'required|integer|min:1|max:20',
            'notes' => 'nullable|string|max:500',
        ]);

        $item_name = $validated['item_name'];
        $quantity = $validated['quantity'];
        $price = $this->menuPrices[$item_name];
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

        $validated = $request->validate([
            'item_name' => ['required', 'string', Rule::in(array_keys($this->menuPrices))],
            'quantity' => 'required|integer|min:1|max:20',
            'notes' => 'nullable|string|max:500',
            'payment_method' => 'required|in:debit,cashier',
            'total_price' => 'required|numeric|min:1000',
        ]);

        $validated['customer_name'] = $customerContext['customer_name'];
        $validated['total_price'] = $this->menuPrices[$validated['item_name']] * $validated['quantity'];
        $serviceLabel = $customerContext['service_type'] === 'dine_in' ? 'Makan di sini' : 'Takeaway';
        $validated['notes'] = trim($serviceLabel . ($validated['notes'] ? ' - ' . $validated['notes'] : ''));

        $order = Order::create($validated);

        // set status depending on payment method
        if ($validated['payment_method'] === 'debit') {
            // requires payment confirmation (simulate gateway)
            $order->status = 'pending_payment';
        } else {
            $order->status = 'pending';
        }
        $order->total_price = $validated['total_price'];
        $order->save();

        session()->forget('order_data');

        // If payment is online (debit) redirect to mock payment gateway simulation
        if ($validated['payment_method'] === 'debit') {
            return redirect()->route('payment.simulate', $order->id);
        }

        // For cashier payment, redirect to waiting page where customer waits for admin approval after paying at cashier
        return redirect()->route('orders.waiting', $order->id)->with('success', 'Pesanan diterima. Silakan bayar di kasir.');
    }

    public function waiting(Order $order)
    {
        return view('orders.waiting', compact('order'));
    }

    public function status(Order $order)
    {
        return response()->json(['status' => $order->status]);
    }

    public function confirmForm(Order $order)
    {
        return view('orders.confirm', compact('order'));
    }

    public function confirmSubmit(Request $request, Order $order)
    {
        $validated = $request->validate([
            'customer_name' => 'nullable|string|max:255',
            'dine_type' => 'required|in:dine_in,takeaway',
        ]);

        $order->customer_name = $validated['customer_name'] ?? $order->customer_name;
        $order->notes = $validated['dine_type'] === 'dine_in' ? ($order->notes ?? 'Makan di sini') : ($order->notes ?? 'Takeaway');
        $order->save();

        return view('orders.confirm-success', ['order' => $order]);
    }
}
