<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class GeminiChatController extends Controller
{
    public function sendMessage(Request $request)
    {
        // Validasi input wajib ada teks pesan
        $request->validate([
            'message' => 'required|string',
        ]);

        $userMessage = $request->input('message');
        $apiKey = env('GEMINI_API_KEY');

        // Pastikan API Key sudah diset di .env
        if (!$apiKey) {
            return response()->json(['reply' => 'API Key Gemini belum dikonfigurasi.'], 500);
        }

        try {
            // Memanggil API Gemini versi terbaru (Gemini 2.5 Flash)
            // URL endpoint resmi menggunakan v1beta
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key={$apiKey}", [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $userMessage]
                        ]
                    ]
                ]
            ]);

            if ($response->successful()) {
                $data = $response->json();
                // Mengambil teks jawaban dari struktur JSON Gemini
                $replyText = $data['candidates'][0]['content']['parts'][0]['text'] ?? 'Maaf, saya tidak mengerti.';
                
                return response()->json(['reply' => $replyText]);
            }

            return response()->json(['reply' => 'Gagal terhubung ke Gemini API.'], $response->status());

        } catch (\Exception $e) {
            return response()->json(['reply' => 'Terjadi kesalahan sistem: ' . $e->getMessage()], 500);
        }
    }
}