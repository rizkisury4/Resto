<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Schema;

class AdminOrderController extends Controller
{
    public function index()
    {
        // menu prices and estimated prep times (minutes)
        $menuPrices = [
            'Nasi Goreng' => 25000,
            'Mie Goreng' => 22000,
            'Nasi Ayam Goreng' => 29000,
            'Ayam Bakar' => 32000,
            'Ayam Geprek' => 28000,
            'Sate Ayam' => 26000,
        ];

        $menuPrep = [
            'Nasi Goreng' => 8,
            'Mie Goreng' => 7,
            'Nasi Ayam Goreng' => 12,
            'Ayam Bakar' => 15,
            'Ayam Geprek' => 10,
            'Sate Ayam' => 9,
        ];

        $orders = Order::orderBy('created_at', 'desc')->get();

        // annotate orders with estimate ready time (ETA)
        $orders->transform(function($o) use ($menuPrep) {
            $baseMinutes = 0;
            // if items JSON present, use it for prep time calculation
            if (!empty($o->items) && is_array($o->items)) {
                $items = $o->items;
                foreach ($items as $it) {
                    $name = $it['item_name'] ?? null;
                    $qty = isset($it['quantity']) ? (int)$it['quantity'] : 1;
                    if ($name && isset($menuPrep[$name])) {
                        $baseMinutes += $menuPrep[$name] * $qty;
                    }
                }
            } elseif (!empty($o->notes) && strpos($o->notes, ' - ') !== false) {
                // notes format: "Makan di sini - Item xQ (note) ; Item2 xQ"
                $parts = explode(' - ', $o->notes, 2);
                $itemsPart = $parts[1] ?? '';
                $items = explode(' ; ', $itemsPart);
                foreach ($items as $it) {
                    if (preg_match('/^(.*?) x(\d+)/', trim($it), $m)) {
                        $name = trim($m[1]);
                        $qty = (int)$m[2];
                        if (isset($menuPrep[$name])) {
                            $baseMinutes += $menuPrep[$name] * $qty;
                        }
                    }
                }
            } else {
                // fallback: use quantity and item_name
                $name = $o->item_name;
                $qty = $o->quantity ?? 1;
                if (isset($menuPrep[$name])) {
                    $baseMinutes += $menuPrep[$name] * $qty;
                }
            }

            $o->estimated_prep_minutes = $baseMinutes;
            $o->eta = $o->created_at? $o->created_at->addMinutes($baseMinutes) : null;
            return $o;
        });

        $payments = $orders->filter(function($o){
            return $o->payment_method === 'cashier' || $o->status === 'pending_payment' || $o->status === 'pending';
        });

        $foods = $orders;

        return view('admin.orders', compact('payments', 'foods', 'menuPrices'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate(['status' => 'required|string']);

        $order->status = $request->status;
        $order->save();

        return redirect()->back()->with('status', 'Order status updated');
    }

    public function posCreate(Request $request)
    {
        $menuPrices = [
            'Nasi Goreng' => 25000,
            'Mie Goreng' => 22000,
            'Nasi Ayam Goreng' => 29000,
            'Ayam Bakar' => 32000,
            'Ayam Geprek' => 28000,
            'Sate Ayam' => 26000,
        ];

        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.item_name' => 'required|string',
            'items.*.quantity' => 'required|integer|min:1',
            'payment_method' => 'required|in:cashier,debit',
        ]);

        $items = $request->input('items');
        $total = 0;
        $notesParts = [];
        $totalQty = 0;
        foreach ($items as $it) {
            $name = $it['item_name'];
            $qty = (int) $it['quantity'];
            $price = $menuPrices[$name] ?? 0;
            $subtotal = $price * $qty;
            $total += $subtotal;
            $totalQty += $qty;
            $notesParts[] = $name . ' x' . $qty . (!empty($it['notes'])? ' (' . $it['notes'] .')': '');
        }

        $serviceLabel = 'POS Sale';
        $notes = $serviceLabel . ' - ' . implode(' ; ', $notesParts);

        $orderAttributes = [
            'item_name' => count($items) > 1 ? 'Beberapa item' : $items[0]['item_name'],
            'quantity' => $totalQty,
            'customer_name' => 'POS',
            'notes' => $notes,
            'payment_method' => $request->input('payment_method'),
            'status' => $request->input('payment_method') === 'cashier' ? 'paid' : 'pending_payment',
            'total_price' => $total,
        ];

        if (Schema::hasColumn('orders', 'items')) {
            $orderAttributes['items'] = $items;
        }

        if (Schema::hasColumn('orders', 'cashier_name')) {
            $orderAttributes['cashier_name'] = auth()->user()->name ?? null;
        }

        $order = Order::create($orderAttributes);

        return redirect()->route('admin.orders.index')->with('status', 'POS order created (ID: '.$order->id.')');
    }

    public function receipt(Order $order)
    {
        // admin can view/print receipt regardless of customer context
        return view('admin.receipt', ['order' => $order]);
    }
}
