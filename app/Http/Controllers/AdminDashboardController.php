<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $today = now()->startOfDay();
        $salesToday = Order::where('created_at', '>=', $today)->sum('total_price');
        $ordersToday = Order::where('created_at', '>=', $today)->count();

        $recentOrders = Order::orderBy('created_at', 'desc')->limit(10)->get();

        return view('admin.dashboard', compact('salesToday', 'ordersToday', 'recentOrders'));
    }
}
