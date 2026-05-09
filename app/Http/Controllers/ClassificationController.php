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

        foreach ($request->file('gambar', []) as $file) {
            if ($file && $file->isValid()) {
                $files[] = $file;
            }
        }

        if (empty($files)) {
            return back()->with('info', 'Tidak ada gambar valid yang berhasil diproses. Silakan coba lagi.');
        }

        try {
            if (count($files) === 1) {
                $hasilAPI = $layanan->proses($files, $model);

                $result = [
                    'label_utama'       => $hasilAPI['label_utama'],
                    'confidence_utama'  => $hasilAPI['confidence'],
                    'model'             => $model,
                    'jumlah_gambar'     => 1,
                    'probabilitas'      => $hasilAPI['probabilitas'],
                    'gambar'            => [],
                ];

            } else {
                $semuaProbabilitas = [];

                foreach ($files as $file) {
                    $hasilSatu = $layanan->proses([$file], $model);
                    $semuaProbabilitas[] = $hasilSatu['probabilitas'];
                }

                $rataRata = [];
                $labelList = array_keys($semuaProbabilitas[0]);

                foreach ($labelList as $label) {
                    $total = 0;

                    foreach ($semuaProbabilitas as $prob) {
                        $total += $prob[$label] ?? 0;
                    }

                    $rataRata[$label] = $total / count($semuaProbabilitas);
                }

                arsort($rataRata);

                $labelUtama = array_key_first($rataRata);
                $confidenceUtama = $rataRata[$labelUtama];

                $result = [
                    'label_utama'      => $labelUtama,
                    'confidence_utama' => round($confidenceUtama, 2),
                    'model'            => $model,
                    'jumlah_gambar'    => count($files),
                    'probabilitas'     => array_map(fn($v) => round($v, 2), $rataRata),
                    'gambar'           => [],
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
            return redirect()->route('klasifikasi')
                ->with('info', 'Belum ada hasil klasifikasi. Silakan unggah gambar terlebih dahulu.');
        }

        return view('hasil-klasifikasi', compact('hasil'));
    }
}