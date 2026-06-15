<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function show(Order $order)
    {
        // only show invoice when paid (for online payments). For cashier payments, show invoice after admin marks paid.
        if ($order->status !== 'paid' && $order->payment_method === 'debit') {
            return view('invoice-unavailable', compact('order'));
        }

        $unitPrice = $order->unit_price ?? ($order->total_price / max(1, $order->quantity));
        $quantity = $order->quantity ?? 1;
        $total = $order->total_price ?? ($unitPrice * $quantity);
        $paymentAmount = $order->payment_amount ?? $total;
        $change = $paymentAmount - $total;

        return view('invoice', compact('order', 'unitPrice', 'quantity', 'total', 'paymentAmount', 'change'));
    }

    public function pdf(Order $order)
    {
        if ($order->status !== 'paid' && $order->payment_method === 'debit') {
            return redirect()->route('orders.invoice', $order->id)->withErrors('Invoice belum tersedia sebelum pembayaran dikonfirmasi.');
        }

        // Prefer the barryvdh/laravel-dompdf facade if available
        if (class_exists('\\Barryvdh\\DomPDF\\Facade\\Pdf') || class_exists('PDF')) {
            $data = ['order' => $order, 'unitPrice' => $order->total_price / max(1, $order->quantity), 'quantity' => $order->quantity, 'total' => $order->total_price];
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('invoice', $data);

            return $pdf->download('invoice-' . $order->id . '.pdf');
        }

        return response('PDF generator not installed. Run: composer require barryvdh/laravel-dompdf', 500);
    }
}
