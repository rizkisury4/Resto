<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    // Show a simple mock payment page where customer can click to 'pay'
    public function simulatePage(Order $order)
    {
        $this->authorizeCustomerOrder($order);

        return view('payment.simulate', compact('order'));
    }

    // Simulate payment (would be called by form on simulate page)
    public function mockPay(Request $request, Order $order)
    {
        $this->authorizeCustomerOrder($order);

        $order->status = 'paid';
        $order->save();

        return redirect()->route('orders.invoice', $order->id)->with('success', 'Pembayaran berhasil (simulasi).');
    }

    // Webhook-style endpoint to accept external payment notifications
    public function webhook(Request $request)
    {
        $orderId = $request->input('order_id');
        $status = $request->input('status');

        if (! $orderId || ! $status) {
            return response()->json(['error' => 'missing order_id or status'], 400);
        }

        $order = Order::find($orderId);
        if (! $order) {
            return response()->json(['error' => 'order not found'], 404);
        }

        $order->status = $status;
        $order->save();

        return response()->json(['ok' => true]);
    }

    private function authorizeCustomerOrder(Order $order): void
    {
        $customerContext = session('customer_context');

        if (! $customerContext || $order->customer_name !== $customerContext['customer_name']) {
            abort(403, 'Pesanan ini bukan milik customer yang sedang aktif.');
        }
    }
}
