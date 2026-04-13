@extends('layouts.aplikasi')

@section('judul', 'Informasi Varietas - DurianFy')

@section('konten')
@php
    $aktif = request('aktif', 'musang-king');

    $varietas = [
        [
            'slug' => 'musang-king',
            'nama' => 'Musang King',
            'kode' => 'Durio zibethinus D197',
            'gambar' => asset('gambar/varietas/musang-king.jpg'),
            'deskripsi' => 'Musang King dikenal sebagai salah satu varietas durian premium dengan warna daging kuning pekat, tekstur lembut, dan rasa manis pahit yang khas.',
            'ciri' => [
                'Daging buah cenderung tebal dan creamy',
                'Warna daging kuning pekat',
                'Rasa manis dengan sedikit pahit',
                'Aroma kuat dan khas',
            ],
            'spesifikasi' => [
                'Bentuk buah' => 'Bulat hingga lonjong',
                'Warna kulit' => 'Hijau kekuningan',
                'Karakter rasa' => 'Manis pahit',
                'Kesan umum' => 'Premium dan kuat',
            ],
        ],
        [
            'slug' => 'bawor',
            'nama' => 'Bawor',
            'kode' => 'Varietas Lokal Unggulan',
            'gambar' => asset('gambar/varietas/bawor.jpg'),
            'deskripsi' => 'Bawor merupakan varietas lokal yang populer karena ukuran buahnya cenderung besar, daging relatif tebal, dan rasa manis legit.',
            'ciri' => [
                'Ukuran buah sering terlihat besar',
                'Daging buah tebal',
                'Rasa manis legit',
                'Cocok dikenal sebagai varietas lokal unggulan',
            ],
            'spesifikasi' => [
                'Bentuk buah' => 'Cenderung besar',
                'Warna kulit' => 'Hijau hingga hijau kecokelatan',
                'Karakter rasa' => 'Manis legit',
                'Kesan umum' => 'Lokal, besar, berdaging tebal',
            ],
        ],
        [
            'slug' => 'duri-hitam',
            'nama' => 'Duri Hitam',
            'kode' => 'Black Thorn / Duri Hitam',
            'gambar' => asset('gambar/varietas/duri-hitam.jpg'),
            'deskripsi' => 'Duri Hitam dikenal sebagai varietas premium dengan tampilan kulit yang lebih gelap pada beberapa kondisi, tekstur lembut, dan cita rasa yang kaya.',
            'ciri' => [
                'Kulit cenderung lebih gelap dibanding beberapa varietas lain',
                'Daging buah lembut',
                'Rasa kaya dan khas',
                'Termasuk varietas premium',
            ],
            'spesifikasi' => [
                'Bentuk buah' => 'Bulat hingga lonjong',
                'Warna kulit' => 'Hijau gelap hingga kecokelatan',
                'Karakter rasa' => 'Kaya dan lembut',
                'Kesan umum' => 'Premium dan khas',
            ],
        ],
    ];
@endphp

    {{-- Navbar --}}
    <nav class="fixed top-0 w-full z-50 bg-white/70 backdrop-blur-xl shadow-sm shadow-emerald-900/5">
        <div class="flex justify-between items-center h-20 px-6 lg:px-8 max-w-7xl mx-auto">
            <a href="{{ route('beranda') }}" class="flex items-center">
                <img src="{{ asset('gambar/Logo Durianfy.png') }}" alt="DurianFy" class="h-10 w-auto">
            </a>

            <div class="hidden md:flex gap-8 items-center font-headline tracking-tight font-semibold">
                <a class="text-slate-600 hover:text-emerald-600 transition-colors" href="{{ route('beranda') }}">
                    Home
                </a>
                <a class="text-slate-600 hover:text-emerald-600 transition-colors" href="{{ route('klasifikasi') }}">
                    Klasifikasi
                </a>
                <a class="text-emerald-700 border-b-2 border-emerald-500 pb-1" href="{{ route('varietas') }}">
                    Tentang
                </a>
            </div>

            <div class="flex items-center gap-4">
                <button class="p-2 hover:bg-emerald-50/50 rounded-xl transition-all active:scale-95 duration-200" type="button">
                    <span class="material-symbols-outlined text-emerald-600">notifications</span>
                </button>

                <div class="w-10 h-10 rounded-full bg-surface-container overflow-hidden">
                    <img
                        alt="Avatar pengguna"
                        class="w-full h-full object-cover"
                        src="https://lh3.googleusercontent.com/aida-public/AB6AXuBD1pclx46soispQLdv5cVwHe0Fl64exvpv80U-yHUlRbVr0cuYPKejdnrfjFGVL0Fi4cZqsMWRHY9MCZ8ox51d3AhQUwSQfsRp0B_qG7ngvzGpojWdf_A7hwEDg6VIk9PQGTHYHKvPK3_AR11OaUQP_T4Xk1RyR5q2B-boANyuRmHbAoDVvWFvF_mynNLplV6tv4xN0Sju-GIoYri661Wh7OahjmV89rJqXh_2BQ8OnpwLlDFRRh8MIXh7Bqx4RQz4nS_LWCSKsiKG"
                    >
                </div>
            </div>
        </div>
    </nav>

    <main class="pt-20">
        {{-- Hero --}}
        <section class="relative min-h-[620px] flex items-center overflow-hidden">
            <div class="absolute inset-0 z-0">
                <img
                    class="w-full h-full object-cover"
                    src="{{ asset('gambar/varietas/hero-varietas.jpg') }}"
                    alt="Hero varietas durian"
                >
                <div class="absolute inset-0 bg-gradient-to-r from-surface via-surface/90 to-surface/20"></div>
            </div>

            <div class="relative z-10 max-w-7xl mx-auto px-6 lg:px-8 w-full">
                <a href="{{ route('hasil') }}"
                   class="inline-flex items-center gap-2 text-primary font-semibold mb-8 hover:gap-3 transition-all group">
                    <span class="material-symbols-outlined">arrow_back</span>
                    <span>Kembali ke Hasil Klasifikasi</span>
                </a>

                <div class="max-w-3xl">
                    <span class="inline-block text-xs font-bold tracking-[0.2em] text-primary uppercase mb-4">
                        Informasi Varietas
                    </span>

                    <h1 class="text-5xl lg:text-7xl font-black font-headline tracking-tight text-on-surface mb-6 leading-tight">
                        Kenali 3 Varietas <span class="text-primary">Durian Utama</span>
                    </h1>

                    <p class="text-lg lg:text-xl text-on-surface-variant leading-relaxed mb-8 max-w-2xl">
                        Halaman ini berisi informasi umum mengenai tiga varietas utama pada sistem DurianFy,
                        yaitu Musang King, Bawor, dan Duri Hitam, agar pengguna dapat memahami ciri dasar
                        dari masing-masing durian.
                    </p>

                    <div class="flex flex-wrap gap-4">
                        <a href="#daftar-varietas"
                           class="signature-gradient text-white px-8 py-4 rounded-2xl font-headline font-bold shadow-halus">
                            Lihat Varietas
                        </a>

                        <a href="{{ route('klasifikasi') }}"
                           class="bg-white text-slate-700 px-8 py-4 rounded-2xl font-headline font-bold border border-slate-200">
                            Klasifikasi Sekarang
                        </a>
                    </div>
                </div>
            </div>
        </section>

        {{-- Ringkasan 3 varietas --}}
        <section id="daftar-varietas" class="max-w-7xl mx-auto px-6 lg:px-8 py-24">
            <div class="flex flex-col md:flex-row justify-between items-end mb-14 gap-4">
                <div>
                    <h2 class="text-4xl font-black font-headline tracking-tight">Varietas Utama</h2>
                    <p class="text-slate-500 mt-3">Tiga varietas utama yang digunakan pada sistem klasifikasi.</p>
                </div>
                <div class="text-sm text-slate-500">Musang King • Bawor • Duri Hitam</div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach ($varietas as $item)
                    <a href="#{{ $item['slug'] }}"
                       class="group bg-white rounded-[28px] overflow-hidden shadow-halus border transition-all hover:-translate-y-1 {{ $aktif === $item['slug'] ? 'border-emerald-400 ring-2 ring-emerald-100' : 'border-slate-100' }}">
                        <div class="aspect-[4/3] overflow-hidden bg-slate-100">
                            <img src="{{ $item['gambar'] }}" alt="{{ $item['nama'] }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        </div>

                        <div class="p-6">
                            <div class="inline-flex px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 text-xs font-bold tracking-wider uppercase mb-4">
                                Varietas Utama
                            </div>
                            <h3 class="font-headline text-2xl font-bold mb-2">{{ $item['nama'] }}</h3>
                            <p class="text-sm text-slate-500 mb-4">{{ $item['kode'] }}</p>
                            <p class="text-slate-600 leading-relaxed text-sm">
                                {{ $item['deskripsi'] }}
                            </p>
                        </div>
                    </a>
                @endforeach
            </div>
        </section>

        {{-- Detail tiap varietas --}}
        <section class="bg-surface-container-low py-24">
            <div class="max-w-7xl mx-auto px-6 lg:px-8 space-y-20">
                @foreach ($varietas as $item)
                    <div id="{{ $item['slug'] }}" class="grid lg:grid-cols-2 gap-12 items-center scroll-mt-28">
                        <div class="{{ $loop->iteration % 2 === 0 ? 'lg:order-2' : '' }}">
                            <div class="relative group">
                                <div class="absolute -inset-4 bg-primary-container/10 rounded-[32px] blur-2xl"></div>
                                <div class="relative aspect-[4/3] rounded-[32px] overflow-hidden shadow-2xl bg-slate-100">
                                    <img src="{{ $item['gambar'] }}" alt="{{ $item['nama'] }}" class="w-full h-full object-cover">
                                </div>
                            </div>
                        </div>

                        <div class="{{ $loop->iteration % 2 === 0 ? 'lg:order-1' : '' }}">
                            <div class="inline-flex px-3 py-1 rounded-full bg-white text-emerald-700 text-xs font-bold tracking-wider uppercase mb-4">
                                {{ $item['nama'] }}
                            </div>

                            <h3 class="text-4xl lg:text-5xl font-black font-headline tracking-tight mb-4">
                                {{ $item['nama'] }}
                            </h3>

                            <p class="text-slate-600 text-lg leading-relaxed mb-8">
                                {{ $item['deskripsi'] }}
                            </p>

                            <div class="grid sm:grid-cols-2 gap-4 mb-8">
                                @foreach ($item['ciri'] as $ciri)
                                    <div class="bg-white rounded-2xl p-4 border border-slate-100">
                                        <div class="flex items-start gap-3">
                                            <span class="material-symbols-outlined text-primary mt-0.5">check_circle</span>
                                            <p class="text-sm text-slate-700 leading-relaxed">{{ $ciri }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="bg-white rounded-[28px] p-6 shadow-sm ring-1 ring-slate-100">
                                <h4 class="font-headline text-xl font-bold mb-5">Informasi Umum</h4>

                                <div class="space-y-3">
                                    @foreach ($item['spesifikasi'] as $label => $nilai)
                                        <div class="flex items-center justify-between p-4 bg-slate-50 rounded-2xl gap-4">
                                            <span class="text-slate-500 font-medium">{{ $label }}</span>
                                            <span class="text-slate-900 font-bold text-right">{{ $nilai }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>

        {{-- CTA bawah --}}
        <section class="max-w-7xl mx-auto px-6 lg:px-8 py-24">
            <div class="rounded-[36px] editorial-gradient p-10 lg:p-14 text-white">
                <div class="grid lg:grid-cols-2 gap-8 items-center">
                    <div>
                        <p class="text-sm uppercase tracking-[0.25em] font-bold text-white/80 mb-4">DurianFy</p>
                        <h2 class="text-4xl lg:text-5xl font-black font-headline tracking-tight mb-4">
                            Sudah mengenal varietasnya?
                        </h2>
                        <p class="text-white/85 text-lg leading-relaxed max-w-2xl">
                            Sekarang lanjutkan ke proses klasifikasi untuk mencoba identifikasi varietas durian
                            secara otomatis menggunakan model InceptionV3.
                        </p>
                    </div>

                    <div class="flex lg:justify-end">
                        <a href="{{ route('klasifikasi') }}"
                           class="inline-flex items-center gap-3 bg-white text-primary px-8 py-4 rounded-2xl font-headline font-bold text-lg shadow-lg">
                            Mulai Klasifikasi
                            <span class="material-symbols-outlined">arrow_forward</span>
                        </a>
                    </div>
                </div>
            </div>
        </section>
    </main>

    {{-- Footer --}}
    <footer class="w-full border-t border-slate-200/50 bg-slate-50">
        <div class="py-12 px-6 lg:px-8 max-w-7xl mx-auto flex flex-col md:flex-row justify-between items-center gap-6">
            <div class="flex flex-col gap-2">
                <span class="text-lg font-bold text-slate-900 font-headline">DurianFy</span>
                <p class="font-inter text-sm leading-relaxed text-slate-500">
                    © 2026 DurianFy. Sistem klasifikasi varietas durian berbasis InceptionV3.
                </p>
            </div>

            <div class="flex flex-wrap justify-center gap-8">
                <a class="font-inter text-sm text-slate-500 hover:text-emerald-500 transition-opacity" href="{{ route('beranda') }}">Beranda</a>
                <a class="font-inter text-sm text-slate-500 hover:text-emerald-500 transition-opacity" href="{{ route('klasifikasi') }}">Klasifikasi</a>
                <a class="font-inter text-sm text-slate-500 hover:text-emerald-500 transition-opacity" href="{{ route('varietas') }}">Informasi</a>
            </div>
        </div>
    </footer>
@endsection