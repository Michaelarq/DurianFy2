<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
     * Proses gambar yang diupload, kirim ke API Hugging Face,
     * lalu tampilkan hasil klasifikasi beserta preview gambar.
     */
    public function proses(Request $request): \Illuminate\View\View|\Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'model'    => 'required|string',
            'gambar'   => 'required|array|min:1|max:4',
            'gambar.*' => 'image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        $fileGambarAsli = [];
        $daftarGambarPreview = [];

        foreach ($request->file('gambar', []) as $file) {
            if ($file && $file->isValid()) {
                $fileGambarAsli[] = $file;

                /*
                 * Preview gambar tanpa storage permanen.
                 * Cocok untuk Vercel karena file tidak disimpan ke public/storage.
                 */
                $mimeType = $file->getMimeType();
                $base64 = base64_encode(file_get_contents($file->getRealPath()));

                $daftarGambarPreview[] = "data:{$mimeType};base64,{$base64}";
            }
        }

        if (empty($fileGambarAsli)) {
            return redirect()->route('klasifikasi')
                ->with('info', 'Tidak ada gambar valid yang berhasil diproses. Silakan coba lagi.');
        }

        try {
            /*
             * Kirim gambar ke API Hugging Face.
             * LayananPrediksiDurian akan memanggil FLASK_API_URL/predict.
             */
            $hasilAPI = $this->layananPrediksi->proses($fileGambarAsli, $request->model);

            $hasil = [
                'label_utama'       => $hasilAPI['label_utama'],
                'model'             => $request->model,
                'confidence_utama'  => $hasilAPI['confidence'],
                'jumlah_gambar'     => count($fileGambarAsli),
                'probabilitas'      => $hasilAPI['probabilitas'],
                'gambar'            => $daftarGambarPreview,
            ];

            /*
             * Simpan hasil ke session tanpa gambar agar session tidak terlalu besar.
             * Gambar hanya tampil pada response POST saat ini.
             */
            $hasilUntukSession = $hasil;
            $hasilUntukSession['gambar'] = [];

            session(['hasil_klasifikasi' => $hasilUntukSession]);

            /*
             * Penting:
             * Return view langsung agar browser render halaman hasil secara normal.
             */
            return view('hasil-klasifikasi', compact('hasil'));

        } catch (\Exception $e) {
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