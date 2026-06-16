<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class AdminPaymentController extends Controller
{
    public function index()
    {
        // show orders awaiting confirmation/payment at cashier
        $pendingPayments = Order::where('payment_method', 'cashier')->whereIn('status', ['pending', 'pending_payment'])->orderBy('created_at', 'desc')->get();

        return view('admin.payments', compact('pendingPayments'));
    }

    public function confirm(Order $order)
    {
        $order->status = 'paid';
        $order->save();

        return redirect()->route('admin.payments.index')->with('status', 'Pembayaran dikonfirmasi untuk order '.$order->id);
    }
}
