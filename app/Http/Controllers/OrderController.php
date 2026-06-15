<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

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

    public function index()
    {
        $orders = Order::latest()->get();

        return view('orders', compact('orders'));
    }

    public function checkout(Request $request)
    {
        $item_name = $request->input('item_name');
        $quantity = $request->input('quantity');
        $customer_name = $request->input('customer_name');
        $notes = $request->input('notes');

        $validated = $request->validate([
            'item_name' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1|max:20',
            'customer_name' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:500',
        ]);

        $price = $this->menuPrices[$item_name] ?? 0;
        $total = $price * $quantity;

        session([
            'order_data' => [
                'item_name' => $item_name,
                'quantity' => $quantity,
                'customer_name' => $customer_name,
                'notes' => $notes,
                'total_price' => $total,
            ]
        ]);

        return view('checkout', [
            'item_name' => $item_name,
            'quantity' => $quantity,
            'customer_name' => $customer_name,
            'notes' => $notes,
            'total_price' => $total,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'item_name' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1|max:20',
            'customer_name' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:500',
            'payment_method' => 'required|in:debit,cashier',
            'total_price' => 'required|numeric|min:1000',
        ]);

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

        // For cashier payment, show a simple confirmation page (or you can show invoice after admin marks paid)
        return redirect('/')->with('success', 'Pesanan diterima. Silakan bayar di kasir.');
    }
}
