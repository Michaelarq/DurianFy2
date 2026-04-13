<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Services\LayananPrediksiDurian;

class HalamanDurianController extends Controller
{
    public function __construct(
        private readonly LayananPrediksiDurian $layananPrediksi
    ) {}

    /**
     * Tampilkan halaman form upload klasifikasi.
     */
    public function tampilkanForm(): \Illuminate\View\View
    {
        return view('klasifikasi');
    }

    /**
     * Proses gambar yang diupload, kirim ke Flask API, simpan hasilnya ke session.
     */
    public function proses(Request $request): \Illuminate\Http\RedirectResponse
    {
        // Validasi input
        $request->validate([
            'model'    => 'required|string',
            'gambar'   => 'required|array|min:1|max:4',
            'gambar.*' => 'image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        $daftarGambarUrl = [];
        $fileGambarAsli  = [];

        // Simpan setiap gambar ke storage publik dan kumpulkan file aslinya
        foreach ($request->file('gambar', []) as $file) {
            if ($file && $file->isValid()) {
                $path              = $file->store('unggahan-hasil', 'public');
                $daftarGambarUrl[] = Storage::url($path);
                $fileGambarAsli[]  = $file;
            }
        }

        if (empty($fileGambarAsli)) {
            return redirect()->route('klasifikasi')
                ->with('info', 'Tidak ada gambar valid yang berhasil diproses. Silakan coba lagi.');
        }

        try {
            // Panggil LayananPrediksiDurian → Flask API → model InceptionV3
            $hasilAPI = $this->layananPrediksi->proses($fileGambarAsli, $request->model);

            // Susun data sesuai yang dibutuhkan view hasil-klasifikasi.blade.php
            $hasilAkhir = [
                'label_utama'       => $hasilAPI['label_utama'],        // ex: 'Musang King'
                'model'             => $request->model,                  // ex: 'InceptionV3'
                'confidence_utama'  => $hasilAPI['confidence'],          // ex: 98.4
                'jumlah_gambar'     => count($daftarGambarUrl),
                'probabilitas'      => $hasilAPI['probabilitas'],        // ex: ['Musang King' => 98.4, ...]
                'gambar'            => $daftarGambarUrl,                 // URL gambar yang diupload
            ];

            // Simpan ke session dan redirect ke halaman hasil
            session(['hasil_klasifikasi' => $hasilAkhir]);

            return redirect()->route('hasil');

        } catch (\Exception $e) {
            // Jika Flask mati atau error, kembalikan ke halaman upload dengan pesan error
            return redirect()->route('klasifikasi')
                ->with('info', $e->getMessage());
        }
    }

    /**
     * Tampilkan halaman hasil klasifikasi dari session.
     */
    public function tampilkanHasil(): \Illuminate\View\View|\Illuminate\Http\RedirectResponse
    {
        $hasil = session('hasil_klasifikasi');

        if (! $hasil) {
            return redirect()->route('klasifikasi')
                ->with('info', 'Silakan unggah gambar terlebih dahulu.');
        }

        return view('hasil-klasifikasi', compact('hasil'));
    }
}