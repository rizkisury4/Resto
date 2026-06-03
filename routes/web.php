<?php

use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\OrderController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('menu');
});

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
