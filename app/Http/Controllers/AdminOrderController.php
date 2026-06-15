<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class AdminOrderController extends Controller
{
    public function index()
    {
        $orders = Order::orderBy('created_at', 'desc')->get();

        // Split into two lists: pembayaran (orders with payment_method cashier or pending_payment) and makanan (all orders)
        $payments = $orders->filter(function($o){
            return $o->payment_method === 'cashier' || $o->status === 'pending_payment' || $o->status === 'pending';
        });

        $foods = $orders;

        return view('admin.orders', compact('payments', 'foods'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate(['status' => 'required|in:pending,pending_payment,on progress,paid,completed']);

        $order->status = $request->status;
        $order->save();

        // if admin approves payment (set to paid) and payment_method == cashier, we can trigger invoice PDF availability
        if ($request->status === 'paid' && $order->payment_method === 'cashier') {
            // nothing extra needed: customer waiting page polls and will redirect to invoice
        }

        return redirect()->back()->with('status', 'Order status updated');
    }
}
