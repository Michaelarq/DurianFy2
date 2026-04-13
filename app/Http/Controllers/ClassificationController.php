<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClassificationRequest;
use App\Services\LayananPrediksiDurian;

class ClassificationController extends Controller
{
    public function store(ClassificationRequest $request, LayananPrediksiDurian $layanan)
    {
        $model = $request->input('model', 'InceptionV3');

        $files = [];
        $paths = [];

        foreach ($request->file('gambar', []) as $file) {
            if ($file && $file->isValid()) {
                $paths[] = $file->store('uploads', 'public');
                $files[] = $file;
            }
        }

        try {
            if (count($files) === 1) {
                // ── Mode single: prediksi langsung ──────────────────────────────
                $hasilAPI = $layanan->proses($files, $model);

                $result = [
                    'label_utama'       => $hasilAPI['label_utama'],
                    'confidence_utama'  => $hasilAPI['confidence'],
                    'model'             => $model,
                    'jumlah_gambar'     => 1,
                    'probabilitas'      => $hasilAPI['probabilitas'],
                    'gambar'            => array_map(fn($p) => asset('storage/' . $p), $paths),
                ];

            } else {
                // ── Mode multi: rata-rata probabilitas dari semua gambar ─────────
                //
                // Strategi: kirim setiap gambar satu per satu ke Flask,
                // kumpulkan array probabilitas per gambar, lalu rata-ratakan.
                // Label pemenang = kelas dengan rata-rata probabilitas tertinggi.
                //
                // Jika layanan mendukung batch, ganti loop di bawah dengan
                // $layanan->prosesBatch($files, $model) yang mengembalikan
                // array of ['probabilitas' => [...]] per gambar.

                $semuaProbabilitas = []; // [ ['Musang King' => 80.0, 'Bawor' => 15.0, ...], ... ]

                foreach ($files as $file) {
                    $hasilSatu = $layanan->proses([$file], $model);
                    $semuaProbabilitas[] = $hasilSatu['probabilitas'];
                }

                // Hitung rata-rata per label
                $rataRata = [];
                $labelList = array_keys($semuaProbabilitas[0]); // ambil daftar label dari hasil pertama

                foreach ($labelList as $label) {
                    $total = 0;
                    foreach ($semuaProbabilitas as $prob) {
                        $total += $prob[$label] ?? 0;
                    }
                    $rataRata[$label] = $total / count($semuaProbabilitas);
                }

                // Urutkan dari tertinggi ke terendah
                arsort($rataRata);

                // Label pemenang = rata-rata tertinggi
                $labelUtama      = array_key_first($rataRata);
                $confidenceUtama = $rataRata[$labelUtama];

                $result = [
                    'label_utama'      => $labelUtama,
                    'confidence_utama' => round($confidenceUtama, 2),
                    'model'            => $model,
                    'jumlah_gambar'    => count($files),
                    'probabilitas'     => array_map(fn($v) => round($v, 2), $rataRata),
                    'gambar'           => array_map(fn($p) => asset('storage/' . $p), $paths),
                ];
            }

            session(['classification_result' => $result]);

            return redirect()->route('klasifikasi.hasil');

        } catch (\Exception $e) {
            return back()->with('info', $e->getMessage());
        }
    }

    public function hasil()
    {
        $hasil = session('classification_result');

        if (! $hasil) {
            return redirect()->route('klasifikasi')->with('info', 'Belum ada hasil klasifikasi. Silakan unggah gambar terlebih dahulu.');
        }

        return view('hasil-klasifikasi', compact('hasil'));
    }
}