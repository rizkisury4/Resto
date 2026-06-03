<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ChatbotController extends Controller
{
    public function ask(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'message' => ['required', 'string', 'max:500'],
        ]);

        $apiKey = config('services.gemini.api_key');
        $model = config('services.gemini.model', 'gemini-1.5-flash');

        if (! $apiKey) {
            return response()->json([
                'reply' => 'Chatbot belum aktif. Silakan isi GEMINI_API_KEY di file .env terlebih dahulu.',
            ], 503);
        }

        $restaurantContext = <<<'PROMPT'
Anda adalah chatbot customer service Restoran Nusantara.
Jawab dalam bahasa Indonesia yang ramah, singkat, dan hanya berdasarkan informasi restoran berikut:

Menu dan harga:
- Nasi Goreng: Rp 25.000
- Mie Goreng: Rp 22.000
- Nasi Ayam Goreng: Rp 29.000
- Ayam Bakar: Rp 32.000
- Ayam Geprek: Rp 28.000
- Sate Ayam: Rp 26.000

Promo:
- Diskon 10% untuk pembelian minimal 3 porsi.
- Gratis es teh untuk pesanan minimal Rp 75.000.
- Promo tidak dapat digabungkan dengan promo lain.

Delivery:
- Bisa delivery untuk area sekitar restoran.
- Estimasi pengantaran 30-45 menit.
- Biaya delivery dikonfirmasi setelah alamat customer diketahui.

Pembayaran:
- Tunai
- Transfer bank
- QRIS
- E-wallet

Jika customer bertanya di luar informasi ini, jawab bahwa staff restoran akan membantu konfirmasi lebih lanjut.
PROMPT;

        try {
            $promptText = $restaurantContext."\n\nPertanyaan customer: ".$validated['message'];

            // If the model looks like a Gemini model, call the v1 generateContent endpoint
            // Otherwise fall back to the v1beta2 generateText endpoint for Bison-like models.
            if (str_contains($model, 'gemini')) {
                // ensure model is in the form models/{name}
                if (! str_starts_with($model, 'models/')) {
                    $model = 'models/' . $model;
                }

                $endpoint = "https://generativelanguage.googleapis.com/v1/{$model}:generateContent";

                $response = Http::timeout(60)
                    ->withHeaders([
                        'x-goog-api-key' => $apiKey,
                        'Content-Type' => 'application/json',
                    ])
                    ->post($endpoint, [
                        'contents' => [
                            [
                                'role' => 'user',
                                'parts' => [
                                    ['text' => $promptText],
                                ],
                            ],
                        ],
                        'generationConfig' => [
                            'temperature' => 0.4,
                            'maxOutputTokens' => 512,
                        ],
                    ]);
            } else {
                $endpoint = "https://generativelanguage.googleapis.com/v1beta2/models/{$model}:generateText";

                $response = Http::timeout(60)
                    ->withHeaders([
                        'x-goog-api-key' => $apiKey,
                        'Content-Type' => 'application/json',
                    ])
                    ->post($endpoint, [
                        'prompt' => [
                            'text' => $promptText,
                        ],
                        'temperature' => 0.4,
                        'maxOutputTokens' => 512,
                    ]);
            }

            if ($response->failed()) {
                Log::error('Gemini API request failed', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);

                return response()->json([
                    'reply' => 'Maaf, chatbot sedang belum bisa menjawab. Silakan coba lagi sebentar.',
                ], 502);
            }

            $body = $response->json();

            // Gemini generateContent responses commonly include candidates[].content.parts[].text
            $reply = data_get($body, 'candidates.0.content.parts.0.text')
                ?: data_get($body, 'candidates.0.content.0.text')
                ?: data_get($body, 'candidates.0.output')
                ?: data_get($body, 'output');

            Log::debug('Gemini API response', ['body' => $body]);

            return response()->json([
                'reply' => $reply ?: 'Maaf, saya belum menemukan jawaban yang sesuai.',
            ]);
        } catch (\Throwable $error) {
            Log::error('Chatbot exception', ['message' => $error->getMessage()]);
            report($error);

            return response()->json([
                'reply' => 'Maaf, koneksi chatbot sedang bermasalah. Silakan coba lagi nanti.',
            ], 500);
        }
    }
}
