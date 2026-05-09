@extends('layouts.aplikasi')

@section('judul', 'Hasil Klasifikasi - DurianFy')

@section('konten')
@php
    use Illuminate\Support\Str;

    $hasil = $hasil ?? session('hasil_klasifikasi') ?? [
        'label_utama' => 'Jenis Lainnya',
        'model' => 'InceptionV3',
        'confidence_utama' => 0,
        'jumlah_gambar' => 0,
        'probabilitas' => [
            'Bawor' => 0,
            'Duri Hitam' => 0,
            'Jenis Lainnya' => 0,
            'Musang King' => 0,
        ],
        'gambar' => [],
    ];

    $labelUtama = $hasil['label_utama'] ?? 'Jenis Lainnya';
    $model = $hasil['model'] ?? 'InceptionV3';
    $confidence = (float) ($hasil['confidence_utama'] ?? 0);
    $probabilitas = $hasil['probabilitas'] ?? [];
    $gambar = $hasil['gambar'] ?? [];
    $jumlahGambar = count($gambar) > 0 ? count($gambar) : (int) ($hasil['jumlah_gambar'] ?? 0);

    $infoVarietas = [
        'Musang King' => [
            'karakteristik' => 'Warna daging kuning pekat, tekstur creamy, serta rasa manis pahit yang seimbang.',
            'asal' => 'Varietas ini dikenal luas sebagai salah satu durian premium dengan karakter rasa yang kuat.',
        ],
        'Bawor' => [
            'karakteristik' => 'Ukuran buah cenderung besar, daging tebal, dan rasa manis legit.',
            'asal' => 'Bawor dikenal sebagai salah satu varietas durian unggulan lokal Indonesia.',
        ],
        'Duri Hitam' => [
            'karakteristik' => 'Kulit cenderung lebih gelap, daging lembut, dan rasa yang kaya.',
            'asal' => 'Duri Hitam atau Black Thorn termasuk varietas premium dengan karakter visual yang khas.',
        ],
        'Jenis Lainnya' => [
            'karakteristik' => 'Karakter visual gambar tidak dominan mengarah pada salah satu kelas utama.',
            'asal' => 'Kategori ini digunakan untuk gambar durian di luar kelas target utama atau gambar dengan ciri yang kurang spesifik.',
        ],
    ];

    $detail = $infoVarietas[$labelUtama] ?? $infoVarietas['Jenis Lainnya'];
@endphp

    {{-- Navbar --}}
    <nav class="fixed top-0 w-full z-50 bg-white/80 backdrop-blur-xl border-b border-slate-100 shadow-sm">
        <div class="flex justify-between items-center h-20 px-6 lg:px-8 max-w-7xl mx-auto">
            <a href="{{ route('beranda') }}" class="flex items-center">
                <img src="{{ asset('gambar/Logo Durianfy.png') }}" alt="DurianFy" class="h-10 w-auto">
            </a>

            <div class="hidden md:flex items-center gap-8 font-headline tracking-tight font-semibold">
                <a class="text-slate-600 hover:text-emerald-600 transition-colors" href="{{ route('beranda') }}">Home</a>
                <a class="text-emerald-700 border-b-2 border-emerald-500 pb-1" href="{{ route('klasifikasi') }}">Klasifikasi</a>
                <a class="text-slate-600 hover:text-emerald-600 transition-colors" href="{{ route('varietas') }}">Tentang</a>
            </div>

            <div class="flex items-center gap-4">
                <button type="button" class="p-2 text-emerald-600 hover:bg-emerald-50 rounded-xl transition-all">
                    <span class="material-symbols-outlined">notifications</span>
                </button>

                <div class="w-10 h-10 rounded-full bg-emerald-50 overflow-hidden">
                    <img
                        alt="Avatar pengguna"
                        class="w-full h-full object-cover"
                        src="https://lh3.googleusercontent.com/aida-public/AB6AXuBD1pclx46soispQLdv5cVwHe0Fl64exvpv80U-yHUlRbVr0cuYPKejdnrfjFGVL0Fi4cZqsMWRHY9MCZ8ox51d3AhQUwSQfsRp0B_qG7ngvzGpojWdf_A7hwEDg6VIk9PQGTHYHKvPK3_AR11OaUQP_T4Xk1RyR5q2B-boANyuRmHbAoDVvWFvF_mynNLplV6tv4xN0Sju-GIoYri661Wh7OahjmV89rJqXh_2BQ8OnpwLlDFRRh8MIXh7Bqx4RQz4nS_LWCSKsiKG"
                    >
                </div>
            </div>
        </div>
    </nav>

    <main class="pt-32 pb-20 px-6 lg:px-8 max-w-7xl mx-auto">
        {{-- Header --}}
        <header class="mb-10">
            <div class="flex flex-wrap items-center gap-3 mb-4">
                <span class="px-3 py-1 rounded-full bg-emerald-100 text-emerald-800 text-xs font-bold tracking-widest uppercase">
                    Analisis Selesai
                </span>
                <span class="hidden sm:inline-block h-1 w-1 rounded-full bg-slate-300"></span>
                <span class="text-slate-500 text-sm font-medium">Model: {{ $model }}</span>
                @if ($jumlahGambar > 1)
                    <span class="hidden sm:inline-block h-1 w-1 rounded-full bg-slate-300"></span>
                    <span class="text-slate-500 text-sm font-medium">{{ $jumlahGambar }} foto dianalisis</span>
                @endif
            </div>

            <h1 class="font-headline text-4xl md:text-6xl font-extrabold tracking-tight text-slate-950 leading-tight">
                Hasil Prediksi:
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-700 to-emerald-400">
                    {{ $labelUtama }}
                </span>
            </h1>
        </header>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            {{-- KOLOM KIRI --}}
            <section class="lg:col-span-5 space-y-6">
                {{-- Ringkasan --}}
                <div class="bg-white rounded-[28px] shadow-xl shadow-slate-200/60 border border-slate-100 p-6 md:p-8">
                    <h2 class="font-headline text-2xl font-bold mb-6 flex items-center gap-2 text-slate-950">
                        <span class="material-symbols-outlined text-emerald-600">analytics</span>
                        Ringkasan Hasil
                    </h2>

                    <div class="grid grid-cols-2 gap-5 mb-6">
                        <div>
                            <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">
                                Confidence Score
                            </p>
                            <p class="text-4xl font-black text-emerald-700 tracking-tight">
                                {{ number_format($confidence, 1) }}%
                            </p>
                        </div>

                        <div class="text-right">
                            <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">
                                Total Gambar
                            </p>
                            <p class="text-2xl font-black text-slate-950">
                                {{ $jumlahGambar }} Foto
                            </p>
                        </div>
                    </div>

                    <div class="h-3 w-full bg-slate-100 rounded-full overflow-hidden mb-6">
                        <div
                            class="h-full bg-gradient-to-r from-emerald-700 to-emerald-400 rounded-full"
                            style="width: {{ min($confidence, 100) }}%">
                        </div>
                    </div>

                    <div class="grid sm:grid-cols-2 gap-4 pt-5 border-t border-slate-100">
                        <div class="flex items-center gap-3">
                            <div class="w-11 h-11 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center shrink-0">
                                <span class="material-symbols-outlined">memory</span>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">Model</p>
                                <p class="text-sm font-bold text-slate-950">{{ $model }}</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            <div class="w-11 h-11 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center shrink-0">
                                <span class="material-symbols-outlined">category</span>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">Varietas</p>
                                <p class="text-sm font-bold text-slate-950">{{ $labelUtama }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Distribusi Probabilitas --}}
                <div class="bg-white rounded-[28px] shadow-xl shadow-slate-200/60 border border-slate-100 p-6 md:p-8">
                    <div class="mb-6">
                        <h3 class="font-headline text-2xl font-bold text-slate-950">
                            Distribusi Probabilitas
                        </h3>
                        @if ($jumlahGambar > 1)
                            <p class="text-sm text-slate-500 mt-1">
                                Nilai probabilitas merupakan hasil rata-rata dari {{ $jumlahGambar }} foto.
                            </p>
                        @endif
                    </div>

                    <div class="space-y-5">
                        @forelse ($probabilitas as $label => $nilai)
                            @php
                                $nilaiFloat = (float) $nilai;
                                $isWinner = $label === $labelUtama;
                            @endphp

                            <div>
                                <div class="flex justify-between items-center gap-4 mb-2">
                                    <div class="flex items-center gap-2 min-w-0">
                                        <span class="w-2.5 h-2.5 rounded-full {{ $isWinner ? 'bg-emerald-600' : 'bg-slate-300' }} shrink-0"></span>
                                        <span class="font-bold text-sm {{ $isWinner ? 'text-slate-950' : 'text-slate-600' }}">
                                            {{ $label }}
                                        </span>
                                    </div>
                                    <span class="font-black text-sm {{ $isWinner ? 'text-emerald-700' : 'text-slate-500' }}">
                                        {{ number_format($nilaiFloat, 1) }}%
                                    </span>
                                </div>

                                <div class="h-3 w-full bg-slate-100 rounded-full overflow-hidden">
                                    <div
                                        class="h-full rounded-full {{ $isWinner ? 'bg-emerald-600' : 'bg-slate-300' }}"
                                        style="width: {{ min($nilaiFloat, 100) }}%">
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-sm text-slate-500">
                                Data probabilitas belum tersedia.
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- Aksi --}}
                <div class="grid sm:grid-cols-2 gap-4">
                    <a
                        href="{{ route('varietas', ['aktif' => Str::slug($labelUtama)]) }}"
                        class="inline-flex items-center justify-center gap-2 rounded-2xl bg-emerald-600 px-5 py-4 text-white font-bold hover:bg-emerald-700 transition-colors text-center"
                    >
                        Lihat Informasi
                        <span class="material-symbols-outlined">arrow_forward</span>
                    </a>

                    <a
                        href="{{ route('klasifikasi') }}"
                        class="inline-flex items-center justify-center gap-2 rounded-2xl bg-yellow-100 px-5 py-4 text-yellow-900 font-bold hover:bg-yellow-200 transition-colors text-center"
                    >
                        <span class="material-symbols-outlined">refresh</span>
                        Coba Lagi
                    </a>
                </div>
            </section>

            {{-- KOLOM KANAN --}}
            <section class="lg:col-span-7 space-y-6">
                {{-- Gambar --}}
                <div class="bg-white rounded-[28px] shadow-xl shadow-slate-200/60 border border-slate-100 p-5 md:p-6">
                    <div class="flex items-center justify-between gap-4 mb-5">
                        <div>
                            <h2 class="font-headline text-2xl font-bold text-slate-950">
                                Gambar yang Dianalisis
                            </h2>
                            <p class="text-sm text-slate-500 mt-1">
                                Foto yang diunggah pengguna untuk proses klasifikasi.
                            </p>
                        </div>
                    </div>

                    @if (count($gambar) === 0)
                        <div class="aspect-[16/9] rounded-[24px] border-2 border-dashed border-slate-200 bg-slate-50 flex flex-col items-center justify-center text-center text-slate-400 p-8">
                            <span class="material-symbols-outlined text-6xl mb-3">image</span>
                            <p class="font-bold text-slate-500">Belum ada gambar</p>
                            <p class="text-sm mt-1 max-w-md">
                                Gambar tidak tersimpan permanen. Silakan klasifikasi ulang untuk melihat preview gambar.
                            </p>
                        </div>
                    @elseif (count($gambar) === 1)
                        <div class="aspect-[16/9] rounded-[24px] overflow-hidden bg-slate-100 relative">
                            <img src="{{ $gambar[0] }}" alt="Gambar durian" class="w-full h-full object-cover">
                            <div class="absolute top-4 right-4 rounded-full bg-emerald-600 text-white w-10 h-10 flex items-center justify-center shadow-lg">
                                <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">check_circle</span>
                            </div>
                            <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/60 to-transparent px-5 py-4">
                                <p class="text-white text-sm font-bold">Foto Utama</p>
                            </div>
                        </div>
                    @else
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            @foreach ($gambar as $idx => $src)
                                @php
                                    $labelSlot = ['Tampak Depan', 'Samping Kiri', 'Samping Kanan', 'Tampak Atas'][$idx] ?? 'Foto ' . ($idx + 1);
                                @endphp

                                <div class="aspect-square rounded-[24px] overflow-hidden bg-slate-100 relative">
                                    <img src="{{ $src }}" alt="Gambar durian {{ $idx + 1 }}" class="w-full h-full object-cover">
                                    <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/60 to-transparent px-4 py-3">
                                        <p class="text-white text-xs font-bold">{{ $labelSlot }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                {{-- Informasi Varietas --}}
                <div class="grid md:grid-cols-2 gap-6">
                    <div class="bg-emerald-50 rounded-[24px] border border-emerald-100 p-6">
                        <div class="flex items-center gap-3 mb-3 text-emerald-700">
                            <span class="material-symbols-outlined">eco</span>
                            <span class="font-bold text-sm uppercase tracking-wide">Karakteristik</span>
                        </div>
                        <p class="text-emerald-950/80 leading-relaxed text-sm">
                            {{ $detail['karakteristik'] }}
                        </p>
                    </div>

                    <div class="bg-slate-50 rounded-[24px] border border-slate-100 p-6">
                        <div class="flex items-center gap-3 mb-3 text-slate-700">
                            <span class="material-symbols-outlined">location_on</span>
                            <span class="font-bold text-sm uppercase tracking-wide">Asal / Informasi</span>
                        </div>
                        <p class="text-slate-900/80 leading-relaxed text-sm">
                            {{ $detail['asal'] }}
                        </p>
                    </div>
                </div>

                {{-- Catatan --}}
                <div class="rounded-[24px] bg-white border border-slate-100 p-6 text-sm text-slate-600 leading-relaxed">
                    <div class="flex items-start gap-3">
                        <span class="material-symbols-outlined text-emerald-600 shrink-0">info</span>
                        <p>
                            Hasil prediksi ditentukan berdasarkan probabilitas tertinggi dari model InceptionV3.
                            Nilai confidence menunjukkan tingkat keyakinan model terhadap kelas yang dipilih.
                        </p>
                    </div>
                </div>
            </section>
        </div>
    </main>

    <footer class="w-full border-t border-slate-200/50 bg-slate-50">
        <div class="py-12 px-6 lg:px-8 max-w-7xl mx-auto flex flex-col md:flex-row justify-between items-center gap-6">
            <div class="flex flex-col items-center md:items-start">
                <div class="text-lg font-bold text-slate-900 mb-2">DurianFy</div>
                <p class="font-inter text-sm leading-relaxed text-slate-500 text-center md:text-left">
                    © 2026 DurianFy. Sistem klasifikasi varietas durian berbasis InceptionV3.
                </p>
            </div>

            <div class="flex flex-wrap justify-center gap-6 font-inter text-sm leading-relaxed">
                <a class="text-slate-500 hover:text-emerald-500 transition-opacity" href="{{ route('beranda') }}">Beranda</a>
                <a class="text-slate-500 hover:text-emerald-500 transition-opacity" href="{{ route('klasifikasi') }}">Klasifikasi</a>
                <a class="text-slate-500 hover:text-emerald-500 transition-opacity" href="{{ route('varietas') }}">Informasi</a>
            </div>
        </div>
    </footer>
@endsection
