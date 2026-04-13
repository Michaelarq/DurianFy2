@extends('layouts.aplikasi')

@section('judul', 'Beranda - DurianFy')

@section('konten')
    {{-- Navbar --}}
    <nav class="fixed top-0 w-full z-50 bg-white/70 backdrop-blur-xl shadow-sm shadow-emerald-900/5 transition-all duration-200">
        <div class="flex justify-between items-center h-20 px-6 lg:px-8 max-w-7xl mx-auto">
        <a href="{{ route('beranda') }}" class="flex items-center">
            <img src="{{ asset('gambar/Logo Durianfy.png') }}" alt="DurianFy" class="h-10 w-auto">
        </a>

            <div class="hidden md:flex items-center space-x-8 font-headline tracking-tight font-semibold">
                <a class="text-emerald-700 border-b-2 border-emerald-500 pb-1" href="{{ route('beranda') }}">
                    Home
                </a>
                <a class="text-slate-600 hover:text-emerald-600 transition-colors" href="{{ route('klasifikasi') }}">
                    Klasifikasi
                </a>
                <a class="text-slate-600 hover:text-emerald-600 transition-colors" href="{{ route('varietas') }}">
                    Tentang
                </a>
            </div>

            <div class="flex items-center gap-4">
                <button class="p-2 hover:bg-emerald-50/50 rounded-xl transition-all active:scale-95 duration-200 ease-in-out" type="button">
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

    {{-- Hero Section --}}
    <section class="relative pt-32 pb-20 overflow-hidden min-h-screen flex items-center">
        <div class="max-w-7xl mx-auto px-6 lg:px-8 grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
            <div class="lg:col-span-6 z-10">
                <div class="inline-flex items-center px-4 py-2 bg-secondary-fixed/60 rounded-full mb-6">
                    <span class="material-symbols-outlined text-secondary mr-2" style="font-variation-settings: 'FILL' 1;">temp_preferences_custom</span>
                    <span class="text-sm font-bold text-emerald-700 uppercase tracking-widest">AI Powered Agriculture</span>
                </div>

                <h1 class="font-headline text-5xl lg:text-7xl font-extrabold text-on-surface leading-[1.05] tracking-tight mb-6">
                    Kenali Varietas Durian dengan <span class="text-primary">Lebih Cepat</span>
                </h1>

                <p class="text-lg lg:text-xl text-on-surface-variant leading-relaxed max-w-2xl mb-8">
                    DurianFy membantu pengguna mengidentifikasi varietas durian melalui citra kulit buah menggunakan model
                    <span class="font-semibold text-primary">InceptionV3</span>.
                    Sistem dirancang sederhana, cepat, dan mudah digunakan oleh pengguna umum.
                </p>

                <div class="flex flex-wrap gap-4">
                    <a href="{{ route('klasifikasi') }}" class="signature-gradient text-white px-7 py-4 rounded-2xl font-headline font-semibold shadow-halus hover:scale-[1.02] transition-transform">
                        Mulai Klasifikasi
                    </a>

                    <a href="{{ route('varietas') }}" class="bg-white text-slate-700 px-7 py-4 rounded-2xl font-headline font-semibold border border-slate-200 hover:border-emerald-200 hover:text-emerald-700 transition-colors">
                        Lihat Informasi
                    </a>
                </div>
            </div>

            <div class="lg:col-span-6 relative">
                <div class="grid grid-cols-2 gap-4 relative z-10">
                    <div class="col-span-2">
                        <div class="rounded-[28px] overflow-hidden shadow-halus bg-white border border-white/80">
                            <img
                                 alt="Durian hero"
                                 class="w-full h-[340px] object-cover"
                                 src="{{ asset('gambar/beranda/hdurian.jpg') }}"
                            >
                        </div>
                    </div>

                    <div class="glass-card rounded-[24px] p-5 shadow-kaca border border-white/60">
                        <span class="text-[11px] font-bold uppercase tracking-[0.2em] text-emerald-700">AI Result</span>

                        <div class="mt-4 flex items-center gap-4">
                            <div class="w-12 h-12 bg-primary rounded-lg flex items-center justify-center text-white">
                                <span class="material-symbols-outlined">psychology</span>
                            </div>
                            <div>
                                <h3 class="font-headline font-bold text-xl text-on-surface">Musang King</h3>
                                <p class="text-xs text-on-surface-variant">Durio zibethinus D197</p>
                            </div>
                        </div>

                        <div class="mt-4 inline-flex items-center rounded-full bg-emerald-50 text-emerald-700 px-3 py-1 text-xs font-bold">
                            98.4% CONFIDENCE
                        </div>
                    </div>

                    <div class="aspect-square rounded-[24px] bg-primary/5 overflow-hidden shadow-sm">
                        <img
                            alt="Close up durian"
                            class="w-full h-full object-cover"
                            src="{{ asset('gambar/beranda/kartu-hasil.jpg') }}"
                        >
                    </div>
                </div>

                <div class="absolute -top-10 -right-10 w-64 h-64 bg-primary-container/30 rounded-full blur-3xl -z-10"></div>
                <div class="absolute -bottom-10 -left-10 w-48 h-48 bg-secondary-container/30 rounded-full blur-2xl -z-10"></div>
            </div>
        </div>
    </section>

    {{-- Bagaimana Ini Bekerja --}}
    <section class="py-24 bg-surface-container-low">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="text-center mb-20">
                <h2 class="font-headline text-4xl font-extrabold mb-4 tracking-tight">Bagaimana Ini Bekerja</h2>
                <div class="w-20 h-1.5 bg-primary mx-auto rounded-full"></div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
                <div class="group">
                    <div class="mb-8 relative">
                        <div class="w-20 h-20 rounded-xl bg-surface-container-lowest flex items-center justify-center group-hover:bg-primary transition-colors duration-300">
                            <span class="material-symbols-outlined text-3xl text-primary group-hover:text-on-primary transition-colors">upload_file</span>
                        </div>
                        <span class="absolute -top-4 -right-2 text-6xl font-black text-on-surface/5">01</span>
                    </div>
                    <h3 class="font-headline text-2xl font-bold mb-4">Upload</h3>
                    <p class="text-on-surface-variant leading-relaxed">
                        Unggah foto durian dari galeri atau ambil langsung menggunakan kamera perangkat.
                    </p>
                </div>

                <div class="group">
                    <div class="mb-8 relative">
                        <div class="w-20 h-20 rounded-xl bg-surface-container-lowest flex items-center justify-center group-hover:bg-primary transition-colors duration-300">
                            <span class="material-symbols-outlined text-3xl text-primary group-hover:text-on-primary transition-colors">model_training</span>
                        </div>
                        <span class="absolute -top-4 -right-2 text-6xl font-black text-on-surface/5">02</span>
                    </div>
                    <h3 class="font-headline text-2xl font-bold mb-4">AI Analysis</h3>
                    <p class="text-on-surface-variant leading-relaxed">
                        Model InceptionV3 memproses ciri visual untuk mengidentifikasi varietas durian secara instan.
                    </p>
                </div>

                <div class="group">
                    <div class="mb-8 relative">
                        <div class="w-20 h-20 rounded-xl bg-surface-container-lowest flex items-center justify-center group-hover:bg-primary transition-colors duration-300">
                            <span class="material-symbols-outlined text-3xl text-primary group-hover:text-on-primary transition-colors">check_circle</span>
                        </div>
                        <span class="absolute -top-4 -right-2 text-6xl font-black text-on-surface/5">03</span>
                    </div>
                    <h3 class="font-headline text-2xl font-bold mb-4">Hasil</h3>
                    <p class="text-on-surface-variant leading-relaxed">
                        Sistem menampilkan hasil klasifikasi lengkap dengan confidence score dan informasi varietas.
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- Keunggulan --}}
    <section class="py-24 bg-surface">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-20 items-center">
                <div>
                    <h2 class="font-headline text-4xl font-extrabold mb-8 leading-tight tracking-tight">
                        Keunggulan Teknologi <br>
                        <span class="text-primary">DurianFy</span>
                    </h2>

                    <div class="space-y-6">
                        <div class="flex gap-5 p-6 rounded-2xl bg-surface-container-low hover:bg-surface-container transition-colors">
                            <div class="shrink-0 w-12 h-12 rounded-xl bg-primary/10 flex items-center justify-center">
                                <span class="material-symbols-outlined text-primary">bolt</span>
                            </div>
                            <div>
                                <h4 class="font-headline font-bold text-xl mb-1">Cepat</h4>
                                <p class="text-on-surface-variant leading-relaxed">
                                    Hasil klasifikasi dapat ditampilkan dengan cepat setelah gambar diunggah.
                                </p>
                            </div>
                        </div>

                        <div class="flex gap-5 p-6 rounded-2xl bg-surface-container-low hover:bg-surface-container transition-colors">
                            <div class="shrink-0 w-12 h-12 rounded-xl bg-primary/10 flex items-center justify-center">
                                <span class="material-symbols-outlined text-primary">verified</span>
                            </div>
                            <div>
                                <h4 class="font-headline font-bold text-xl mb-1">Praktis</h4>
                                <p class="text-on-surface-variant leading-relaxed">
                                    Dapat digunakan oleh pengguna umum tanpa perlu pengetahuan teknis yang rumit.
                                </p>
                            </div>
                        </div>

                        <div class="flex gap-5 p-6 rounded-2xl bg-surface-container-low hover:bg-surface-container transition-colors">
                            <div class="shrink-0 w-12 h-12 rounded-xl bg-primary/10 flex items-center justify-center">
                                <span class="material-symbols-outlined text-primary">psychiatry</span>
                            </div>
                            <div>
                                <h4 class="font-headline font-bold text-xl mb-1">Sesuai Penelitian</h4>
                                <p class="text-on-surface-variant leading-relaxed">
                                    Menggunakan pendekatan CNN dengan arsitektur InceptionV3 sesuai rancangan pada penelitian.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <img
                        src="{{ asset('gambar/beranda/hdurian2.jpg') }}"
                        alt="Durian 1"
                        class="rounded-[24px] h-56 md:h-64 w-full object-cover shadow-sm"
                    >

                    <img
                        src="{{ asset('gambar/beranda/hdurian3.jpg') }}"
                        alt="Durian 2"
                        class="rounded-[24px] h-72 md:h-80 w-full object-cover shadow-sm mt-8"
                    >

                    <img
                        src="{{ asset('gambar/beranda/hdurian4.jpg') }}"
                        alt="Durian 3"
                        class="rounded-[24px] h-72 md:h-80 w-full object-cover shadow-sm -mt-8"
                    >

                    <img
                        src="{{ asset('gambar/beranda/hdurian5.jpg') }}"
                        alt="Durian 4"
                        class="rounded-[24px] h-56 md:h-64 w-full object-cover shadow-sm"
                    >
                </div>
            </div>
        </div>
    </section>

    {{-- Footer --}}
    <footer class="w-full border-t border-slate-200/50 bg-slate-50">
        <div class="py-12 px-6 lg:px-8 max-w-7xl mx-auto flex flex-col md:flex-row justify-between items-center gap-6">
            <div class="flex flex-col items-center md:items-start">
                <div class="text-lg font-bold text-slate-900 mb-2 font-headline">DurianFy</div>
                <p class="font-inter text-sm leading-relaxed text-slate-500">
                    © 2026 DurianFy. Sistem klasifikasi varietas durian berbasis InceptionV3.
                </p>
            </div>

            <div class="flex flex-wrap justify-center gap-8">
                <a class="font-inter text-sm text-slate-500 hover:text-emerald-500 hover:underline decoration-emerald-500/30 underline-offset-4 transition-opacity" href="{{ route('beranda') }}">
                    Beranda
                </a>
                <a class="font-inter text-sm text-slate-500 hover:text-emerald-500 hover:underline decoration-emerald-500/30 underline-offset-4 transition-opacity" href="{{ route('klasifikasi') }}">
                    Klasifikasi
                </a>
                <a class="font-inter text-sm text-slate-500 hover:text-emerald-500 hover:underline decoration-emerald-500/30 underline-offset-4 transition-opacity" href="{{ route('varietas') }}">
                    Informasi
                </a>
            </div>

            <div class="flex gap-4">
                <a class="w-10 h-10 rounded-full bg-white flex items-center justify-center text-slate-400 hover:text-primary transition-all shadow-sm" href="#">
                    <span class="material-symbols-outlined">share</span>
                </a>
                <a class="w-10 h-10 rounded-full bg-white flex items-center justify-center text-slate-400 hover:text-primary transition-all shadow-sm" href="#">
                    <span class="material-symbols-outlined">language</span>
                </a>
            </div>
        </div>
    </footer>
@endsection