<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class LayananPrediksiDurian
{
    /**
     * Mapping label dari Python (snake_case) ke nama tampilan (Title Case).
     * Sesuai dengan CLASS_NAMES di app.py: ['bawor', 'blackthorn', 'jenis_lainnya', 'musang_king']
     */
    private array $labelMap = [
        'bawor'         => 'Bawor',
        'blackthorn'    => 'Duri Hitam',
        'jenis_lainnya' => 'Jenis Lainnya',
        'musang_king'   => 'Musang King',
    ];

    public function proses(array $semuaGambar, string $model): array
    {
        // Ambil URL Flask API dari .env, default ke 127.0.0.1:5000
        $apiUrl = rtrim(env('FLASK_API_URL', 'http://127.0.0.1:5000'), '/') . '/predict';

        // Ambil gambar pertama dari array untuk dikirim ke Python
        $fileGambar = $semuaGambar[0];

        try {
            Log::info('DurianFy: Mengirim gambar ke Flask API', [
                'url'      => $apiUrl,
                'filename' => $fileGambar->getClientOriginalName(),
                'model'    => $model,
            ]);

            // Kirim HTTP POST request ke Flask
            $response = Http::timeout(300)->attach(
                'image',                              // Key HARUS 'image' sesuai app.py
                file_get_contents($fileGambar->getRealPath()),
                $fileGambar->getClientOriginalName()
            )->post($apiUrl);

            // Jika berhasil terhubung dan mendapat respons sukses
            if ($response->successful()) {
                $data = $response->json();

                if (isset($data['success']) && $data['success'] === true) {
                    // Label mentah dari Python, misal: 'musang_king'
                    $labelMentah = $data['predicted_class'];

                    // Konversi ke nama tampilan, misal: 'Musang King'
                    $labelTampil = $this->labelMap[$labelMentah] ?? ucfirst(str_replace('_', ' ', $labelMentah));

                    // Konversi semua key di all_scores ke nama tampilan juga
                    $probabilitas = [];
                    foreach ($data['all_scores'] as $key => $nilai) {
                        $keyTampil = $this->labelMap[$key] ?? ucfirst(str_replace('_', ' ', $key));
                        $probabilitas[$keyTampil] = $nilai;
                    }

                    Log::info('DurianFy: Prediksi berhasil', [
                        'label'      => $labelTampil,
                        'confidence' => $data['confidence'],
                    ]);

                    return [
                        'label_utama'    => $labelTampil,
                        'confidence'     => $data['confidence'],
                        'probabilitas'   => $probabilitas,
                        'model_digunakan'=> $model,
                    ];
                }
            }

            // Jika terhubung tapi Python mengembalikan error
            Log::error('DurianFy: Error dari API Python', [
                'status' => $response->status(),
                'body'   => $response->body(),
            ]);

            throw new \Exception('API Python gagal memproses gambar. Response: ' . $response->body());

        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            // Khusus error koneksi (server Python mati, salah IP, dll)
            Log::error('DurianFy: Gagal terhubung ke Flask API', ['error' => $e->getMessage()]);

            throw new \Exception(
                'Gagal terhubung ke server AI. Pastikan server Python (Flask) sudah berjalan ' .
                'dan URL di file .env sudah benar. Detail: ' . $e->getMessage()
            );

        } catch (\Exception $e) {
            Log::error('DurianFy: Exception tidak terduga', ['error' => $e->getMessage()]);
            throw $e;
        }
    }
}