<?php

use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\InvoiceController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AdminOrderController;
use App\Http\Controllers\PaymentController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [OrderController::class, 'start'])->name('order.start');
Route::post('/start-order', [OrderController::class, 'startOrder'])->name('order.start.submit');
Route::post('/reset-order-session', [OrderController::class, 'resetOrderSession'])->name('order.session.reset');
Route::get('/menu', [OrderController::class, 'menu'])->name('menu');

Route::post('/order/checkout', [OrderController::class, 'checkout'])->name('order.checkout');
Route::post('/order', [OrderController::class, 'store'])->name('order.store');
Route::get('/orders', [OrderController::class, 'index'])->name('order.index');
Route::post('/chatbot/ask', [ChatbotController::class, 'ask'])->name('chatbot.ask');

// Local test route (no CSRF) to verify Gemini integration — remove in production
Route::get('/chatbot/test', function (Request $request) {
    $request->merge(['message' => 'Halo, tolong uji koneksi chatbot.']);

    return app(\App\Http\Controllers\ChatbotController::class)->ask($request);
});

// Return available models for the configured API key (local dev helper)
Route::get('/chatbot/models', function () {
    $apiKey = config('services.gemini.api_key');
    if (! $apiKey) {
        return response()->json(['error' => 'No API key configured'], 400);
    }

    $resp = Http::withHeaders(['x-goog-api-key' => $apiKey])->get('https://generativelanguage.googleapis.com/v1/models');

    return response()->json($resp->json());
});

Route::get('orders/{order}/invoice', [InvoiceController::class, 'show'])->name('orders.invoice');
Route::get('orders/{order}/invoice/pdf', [InvoiceController::class, 'pdf'])->name('orders.invoice.pdf');
Route::get('orders/{order}/waiting', [\App\Http\Controllers\OrderController::class, 'waiting'])->name('orders.waiting');
Route::get('orders/{order}/status', [\App\Http\Controllers\OrderController::class, 'status'])->name('orders.status');
Route::get('orders/{order}/confirm', [\App\Http\Controllers\OrderController::class, 'confirmForm'])->name('orders.confirm');
Route::post('orders/{order}/confirm', [\App\Http\Controllers\OrderController::class, 'confirmSubmit'])->name('orders.confirm.submit');

// Admin routes (simple session-based auth)
Route::get('admin/login', [AdminAuthController::class, 'showLogin'])->name('admin.login');
Route::post('admin/login', [AdminAuthController::class, 'login'])->name('admin.login.post');
Route::get('admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

Route::middleware(['admin.auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [\App\Http\Controllers\AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::patch('orders/{order}', [AdminOrderController::class, 'updateStatus'])->name('orders.update');
    Route::post('orders/pos', [AdminOrderController::class, 'posCreate'])->name('orders.pos.create');
    Route::get('orders/{order}/receipt', [AdminOrderController::class, 'receipt'])->name('orders.receipt');
    
    // Payments reporting / confirmation
    Route::get('payments', [\App\Http\Controllers\AdminPaymentController::class, 'index'])->name('payments.index');
    Route::post('payments/{order}/confirm', [\App\Http\Controllers\AdminPaymentController::class, 'confirm'])->name('payments.confirm');

    // Menu management
    Route::get('menu-items', [\App\Http\Controllers\AdminMenuController::class, 'index'])->name('menu.index');
    Route::post('menu-items', [\App\Http\Controllers\AdminMenuController::class, 'store'])->name('menu.store');
    Route::delete('menu-items/{menuItem}', [\App\Http\Controllers\AdminMenuController::class, 'destroy'])->name('menu.destroy');
});

// Mock payment routes for simulation
Route::get('payment/mock/{order}', [PaymentController::class, 'simulatePage'])->name('payment.simulate');
Route::post('payment/mock/{order}', [PaymentController::class, 'mockPay'])->name('payment.mock.pay');
Route::post('payment/webhook', [PaymentController::class, 'webhook'])->name('payment.webhook');
