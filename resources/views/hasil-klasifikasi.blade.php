@extends('layouts.aplikasi')

@section('judul', 'Hasil Klasifikasi - DurianFy')

@section('konten')
@php
    $hasil = $hasil ?? [
        'label_utama' => 'Musang King',
        'model' => 'InceptionV3',
        'confidence_utama' => 98.4,
        'jumlah_gambar' => 3,
        'probabilitas' => [
            'Musang King' => 98.4,
            'Bawor' => 1.2,
            'Duri Hitam' => 0.3,
            'Jenis Lainnya' => 0.1,
        ],
        'gambar' => [],
    ];

    $infoVarietas = [
        'Musang King' => [
            'karakteristik' => 'Warna daging kuning pekat, tekstur creamy, dan rasa manis pahit yang seimbang.',
            'asal' => 'Varietas ini dikenal luas dari Malaysia dan sering menjadi durian premium di pasaran.',
        ],
        'Bawor' => [
            'karakteristik' => 'Ukuran buah cenderung besar, daging tebal, dan rasa manis legit.',
            'asal' => 'Bawor dikenal sebagai salah satu varietas unggulan lokal Indonesia.',
        ],
        'Duri Hitam' => [
            'karakteristik' => 'Kulit cenderung lebih gelap, daging lembut, dan rasa kaya.',
            'asal' => 'Varietas premium yang dikenal juga dengan nama Black Thorn.',
        ],
        'Jenis Lainnya' => [
            'karakteristik' => 'Karakter visual tidak dominan ke salah satu kelas utama.',
            'asal' => 'Kategori ini digunakan untuk durian di luar kelas target utama.',
        ],
    ];

    $detail       = $infoVarietas[$hasil['label_utama']] ?? $infoVarietas['Jenis Lainnya'];
    $gambar       = $hasil['gambar'] ?? [];
    $jumlahGambar = count($gambar);

    // Tentukan layout grid gambar
    // 1 gambar  → full width
    // 2 gambar  → 2 kolom sejajar
    // 3 gambar  → 1 besar atas + 2 kecil bawah
    // 4 gambar  → 2x2 grid
@endphp

    {{-- Navbar --}}
    <nav class="fixed top-0 w-full z-50 bg-white/70 backdrop-blur-xl shadow-sm shadow-emerald-900/5">
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
                <button class="p-2 text-emerald-600 hover:bg-emerald-50/50 rounded-xl transition-all active:scale-95 duration-200 ease-in-out" type="button">
                    <span class="material-symbols-outlined">notifications</span>
                </button>
                <div class="w-10 h-10 rounded-full bg-surface-container overflow-hidden">
                    <img alt="User Avatar" class="w-full h-full object-cover"
                        src="https://lh3.googleusercontent.com/aida-public/AB6AXuACpoCHFSxvhiHAoPQ-EHb_JKjEN4fZ6nEJVTl7CXNgUfIPTQnH-ALl2KVPVy3OOYAL3856j5_TWYhSyMsdnH3JUnOuzgX87vq2nk4h6CxRKEzXgKmrX2eNvet9BFKT6GqWxrCseyYRje5gi-qTIXR6z1--_wJ-JGNBTsN7hEJhmHRDXfue4R3nqlfh6GLiXsfcRKdFsB2KSR7mx7yFg7ERNNkI5hk294oG_BIVIsvKn8AM2cw0D67JC5ZNzGT_j07mf3hIKraLx3v6">
                </div>
            </div>
        </div>
    </nav>

    <main class="pt-32 pb-20 px-6 lg:px-8 max-w-7xl mx-auto">
        {{-- Header --}}
        <header class="mb-12">
            <div class="flex items-center gap-3 mb-4 flex-wrap">
                <span class="px-3 py-1 bg-emerald-100 text-emerald-800 rounded-full text-xs font-bold tracking-widest uppercase">
                    Analisis Selesai
                </span>
                <span class="h-1 w-1 rounded-full bg-slate-300"></span>
                <span class="text-slate-500 text-sm font-medium">Model: {{ $hasil['model'] }}</span>
                @if ($jumlahGambar > 1)
                    <span class="h-1 w-1 rounded-full bg-slate-300"></span>
                    <span class="text-slate-500 text-sm font-medium">
                        Rata-rata dari {{ $jumlahGambar }} foto
                    </span>
                @endif
            </div>

            <h1 class="font-headline text-4xl md:text-6xl font-extrabold text-on-surface tracking-tight mb-2">
                Hasil Prediksi:
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary to-emerald-400">
                    {{ $hasil['label_utama'] }}
                </span>
            </h1>
        </header>

        <div class="grid grid-cols-1 md:grid-cols-12 gap-8 items-start">
            {{-- ===== KOLOM KIRI ===== --}}
            <div class="md:col-span-5 space-y-8">
                {{-- Ringkasan --}}
                <div class="bg-white p-8 rounded-[28px] shadow-halus ring-1 ring-emerald-900/5">
                    <h2 class="font-headline text-2xl font-bold mb-6 flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">analytics</span>
                        Ringkasan Hasil
                    </h2>

                    <div class="space-y-6">
                        <div class="flex justify-between items-end gap-6">
                            <div>
                                <p class="text-sm font-medium text-slate-500 uppercase tracking-wider mb-1">
                                    @if ($jumlahGambar > 1)
                                        Confidence (Rata-rata)
                                    @else
                                        Confidence Score
                                    @endif
                                </p>
                                <p class="text-4xl font-black text-primary tracking-tighter">
                                    {{ number_format($hasil['confidence_utama'], 1) }}%
                                </p>
                            </div>

                            <div class="text-right">
                                <p class="text-sm font-medium text-slate-500 uppercase tracking-wider mb-1">Total Gambar</p>
                                <p class="text-xl font-bold text-on-surface">
                                    {{ $hasil['jumlah_gambar'] }} Foto
                                </p>
                            </div>
                        </div>

                        <div class="h-2 w-full bg-slate-100 rounded-full overflow-hidden">
                            <div class="h-full bg-gradient-to-r from-primary to-emerald-400 rounded-full transition-all duration-700"
                                 style="width: {{ min($hasil['confidence_utama'], 100) }}%"></div>
                        </div>

                        @if ($jumlahGambar > 1)
                            <div class="rounded-2xl bg-emerald-50 border border-emerald-100 px-4 py-3 flex items-start gap-3 text-sm text-emerald-800">
                                <span class="material-symbols-outlined text-emerald-600 shrink-0 mt-0.5" style="font-size:18px">info</span>
                                <span>Skor ini merupakan <strong>rata-rata probabilitas</strong> dari {{ $jumlahGambar }} foto yang diunggah, sehingga prediksi lebih stabil dan representatif.</span>
                            </div>
                        @endif

                        <div class="grid grid-cols-2 gap-4 pt-4 border-t border-slate-100">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg bg-slate-100 flex items-center justify-center text-primary">
                                    <span class="material-symbols-outlined">memory</span>
                                </div>
                                <div>
                                    <p class="text-[10px] font-bold text-slate-500 uppercase tracking-tighter">Model</p>
                                    <p class="text-sm font-semibold">{{ $hasil['model'] }}</p>
                                </div>
                            </div>

                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg bg-slate-100 flex items-center justify-center text-primary">
                                    <span class="material-symbols-outlined">category</span>
                                </div>
                                <div>
                                    <p class="text-[10px] font-bold text-slate-500 uppercase tracking-tighter">Varietas</p>
                                    <p class="text-sm font-semibold">{{ $hasil['label_utama'] }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Distribusi Probabilitas --}}
                <div class="bg-slate-50 p-8 rounded-[28px]">
                    <h3 class="font-headline text-xl font-bold mb-2">Distribusi Probabilitas</h3>
                    @if ($jumlahGambar > 1)
                        <p class="text-xs text-slate-500 mb-5">Nilai rata-rata dari {{ $jumlahGambar }} foto</p>
                    @else
                        <div class="mb-5"></div>
                    @endif

                    <div class="space-y-5">
                        @foreach ($hasil['probabilitas'] as $label => $nilai)
                            <div class="space-y-2">
                                <div class="flex justify-between text-sm font-bold">
                                    <span class="flex items-center gap-1.5">
                                        @if ($label === $hasil['label_utama'])
                                            <span class="w-2 h-2 rounded-full bg-primary inline-block"></span>
                                        @else
                                            <span class="w-2 h-2 rounded-full bg-slate-300 inline-block"></span>
                                        @endif
                                        {{ $label }}
                                    </span>
                                    <span class="{{ $label === $hasil['label_utama'] ? 'text-primary' : 'text-slate-500' }}">
                                        {{ number_format($nilai, 1) }}%
                                    </span>
                                </div>

                                <div class="h-3 w-full bg-white rounded-full overflow-hidden p-0.5">
                                    <div class="h-full rounded-full transition-all duration-700 {{ $label === $hasil['label_utama'] ? 'bg-primary' : 'bg-slate-300' }}"
                                         style="width: {{ min($nilai, 100) }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- CTA --}}
                <div class="flex flex-col gap-4">
                    <a href="{{ route('varietas', ['aktif' => \Illuminate\Support\Str::slug($hasil['label_utama'])]) }}"
                       class="w-full py-5 bg-gradient-to-br from-primary to-emerald-400 text-white rounded-[24px] font-headline font-bold text-lg shadow-xl shadow-emerald-900/10 hover:opacity-95 transition-all flex items-center justify-center gap-2 active:scale-[0.98]">
                        Lihat Informasi Varietas
                        <span class="material-symbols-outlined">arrow_forward</span>
                    </a>

                    <div class="flex gap-4">
                        <a href="{{ route('klasifikasi') }}"
                           class="flex-1 py-4 bg-yellow-100 text-yellow-900 rounded-[24px] font-bold flex items-center justify-center gap-2 hover:opacity-90 transition-all active:scale-[0.98]">
                            <span class="material-symbols-outlined">refresh</span>
                            Coba Lagi
                        </a>

                        <a href="{{ route('klasifikasi') }}"
                           class="flex-1 py-4 bg-white text-slate-600 rounded-[24px] font-bold flex items-center justify-center gap-2 hover:bg-slate-50 transition-all active:scale-[0.98] border border-slate-200">
                            <span class="material-symbols-outlined">sentiment_dissatisfied</span>
                            Tidak Puas
                        </a>
                    </div>
                </div>
            </div>

            {{-- ===== KOLOM KANAN ===== --}}
            <div class="md:col-span-7">

                {{-- ── Grid foto adaptif ── --}}
                @if ($jumlahGambar === 0)
                    {{-- Tidak ada gambar --}}
                    <div class="aspect-[16/9] rounded-[28px] border-2 border-dashed border-slate-200 bg-slate-50 flex flex-col items-center justify-center text-slate-400">
                        <span class="material-symbols-outlined text-5xl mb-3">image</span>
                        <p class="font-semibold">Belum ada gambar</p>
                    </div>

                @elseif ($jumlahGambar === 1)
                    {{-- 1 gambar → full --}}
                    <div class="aspect-[16/9] rounded-[28px] overflow-hidden shadow-2xl shadow-emerald-900/10 relative bg-slate-100">
                        <img src="{{ $gambar[0] }}" alt="Gambar durian" class="w-full h-full object-cover">
                        <div class="absolute top-4 right-4 bg-primary text-white p-2 rounded-lg shadow-lg">
                            <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">check_circle</span>
                        </div>
                        <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/50 to-transparent px-5 py-4">
                            <p class="text-white text-sm font-bold">Foto Utama</p>
                        </div>
                    </div>

                @elseif ($jumlahGambar === 2)
                    {{-- 2 gambar → 2 kolom --}}
                    <div class="grid grid-cols-2 gap-4">
                        @php $labelSlot = ['Tampak Depan', 'Samping Kiri']; @endphp
                        @foreach ($gambar as $idx => $src)
                            <div class="aspect-square rounded-[24px] overflow-hidden shadow-lg ring-4 ring-white relative bg-slate-100">
                                <img src="{{ $src }}" alt="Gambar durian {{ $idx + 1 }}" class="w-full h-full object-cover">
                                <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/60 to-transparent px-3 py-2">
                                    <p class="text-white text-xs font-bold">{{ $labelSlot[$idx] ?? 'Foto '.($idx+1) }}</p>
                                </div>
                                @if ($idx === 0)
                                    <div class="absolute top-3 right-3 bg-primary text-white p-1.5 rounded-lg shadow">
                                        <span class="material-symbols-outlined text-sm" style="font-variation-settings: 'FILL' 1;">check_circle</span>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>

                @elseif ($jumlahGambar === 3)
                    {{-- 3 gambar → 1 besar atas + 2 kecil bawah --}}
                    @php $labelSlot = ['Tampak Depan', 'Samping Kiri', 'Samping Kanan']; @endphp
                    <div class="flex flex-col gap-4">
                        {{-- Foto utama --}}
                        <div class="aspect-[16/9] rounded-[28px] overflow-hidden shadow-2xl shadow-emerald-900/10 relative bg-slate-100">
                            <img src="{{ $gambar[0] }}" alt="Foto utama durian" class="w-full h-full object-cover">
                            <div class="absolute top-4 right-4 bg-primary text-white p-2 rounded-lg shadow-lg">
                                <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">check_circle</span>
                            </div>
                            <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/60 to-transparent px-5 py-4">
                                <p class="text-white text-sm font-bold">{{ $labelSlot[0] }}</p>
                            </div>
                        </div>
                        {{-- 2 thumbnail --}}
                        <div class="grid grid-cols-2 gap-4">
                            @for ($i = 1; $i <= 2; $i++)
                                <div class="aspect-square rounded-[24px] overflow-hidden ring-4 ring-white shadow-lg relative bg-slate-100">
                                    <img src="{{ $gambar[$i] }}" alt="Gambar durian {{ $i + 1 }}" class="w-full h-full object-cover">
                                    <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/60 to-transparent px-3 py-2">
                                        <p class="text-white text-xs font-bold">{{ $labelSlot[$i] }}</p>
                                    </div>
                                </div>
                            @endfor
                        </div>
                    </div>

                @else
                    {{-- 4 gambar → 1 besar atas + 3 thumbnail bawah --}}
                    @php $labelSlot = ['Tampak Depan', 'Samping Kiri', 'Samping Kanan', 'Tampak Atas']; @endphp
                    <div class="flex flex-col gap-4">
                        {{-- Foto utama --}}
                        <div class="aspect-[16/9] rounded-[28px] overflow-hidden shadow-2xl shadow-emerald-900/10 relative bg-slate-100">
                            <img src="{{ $gambar[0] }}" alt="Foto utama durian" class="w-full h-full object-cover">
                            <div class="absolute top-4 right-4 bg-primary text-white p-2 rounded-lg shadow-lg">
                                <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">check_circle</span>
                            </div>
                            <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/60 to-transparent px-5 py-4">
                                <p class="text-white text-sm font-bold">{{ $labelSlot[0] }}</p>
                            </div>
                        </div>
                        {{-- 3 thumbnail --}}
                        <div class="grid grid-cols-3 gap-4">
                            @for ($i = 1; $i <= 3; $i++)
                                <div class="aspect-square rounded-[24px] overflow-hidden ring-4 ring-white shadow-lg relative bg-slate-100">
                                    <img src="{{ $gambar[$i] }}" alt="Gambar durian {{ $i + 1 }}" class="w-full h-full object-cover">
                                    <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/60 to-transparent px-3 py-2">
                                        <p class="text-white text-xs font-bold">{{ $labelSlot[$i] }}</p>
                                    </div>
                                </div>
                            @endfor
                        </div>
                    </div>
                @endif

                {{-- Info varietas --}}
                <div class="mt-8 grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div class="p-6 bg-emerald-50 rounded-[24px] border border-emerald-100">
                        <div class="flex items-center gap-3 mb-3 text-emerald-700">
                            <span class="material-symbols-outlined">eco</span>
                            <span class="font-bold text-sm uppercase tracking-wide">Karakteristik</span>
                        </div>
                        <p class="text-emerald-900/80 leading-relaxed text-sm">{{ $detail['karakteristik'] }}</p>
                    </div>

                    <div class="p-6 bg-slate-50 rounded-[24px] border border-slate-100">
                        <div class="flex items-center gap-3 mb-3 text-slate-700">
                            <span class="material-symbols-outlined">location_on</span>
                            <span class="font-bold text-sm uppercase tracking-wide">Asal / Informasi</span>
                        </div>
                        <p class="text-slate-900/80 leading-relaxed text-sm">{{ $detail['asal'] }}</p>
                    </div>
                </div>
            </div>
        </div>
    </main>

    {{-- Footer --}}
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