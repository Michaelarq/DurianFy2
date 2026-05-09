@extends('layouts.aplikasi')

@section('judul', 'Klasifikasi - DurianFy')

@section('konten')
    {{-- Memaksa browser mengamankan semua request (Penting untuk Railway) --}}
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">

    {{-- Navbar --}}
    <nav class="fixed top-0 w-full z-50 bg-white/70 backdrop-blur-xl shadow-sm shadow-emerald-900/5 transition-all duration-200">
        <div class="flex justify-between items-center h-20 px-6 lg:px-8 max-w-7xl mx-auto">
            <a href="{{ route('beranda') }}" class="flex items-center">
                <img src="{{ asset('gambar/Logo Durianfy.png') }}" alt="DurianFy" class="h-10 w-auto">
            </a>

            <div class="hidden md:flex items-center space-x-8 font-headline tracking-tight font-semibold">
                <a class="text-slate-600 hover:text-emerald-600 transition-colors" href="{{ route('beranda') }}">Home</a>
                <a class="text-emerald-700 border-b-2 border-emerald-500 pb-1" href="{{ route('klasifikasi') }}">Klasifikasi</a>
                <a class="text-slate-600 hover:text-emerald-600 transition-colors" href="{{ route('varietas') }}">Tentang</a>
            </div>

            <div class="flex items-center gap-4">
                <button class="p-2 hover:bg-emerald-50/50 rounded-xl transition-all active:scale-95 duration-200 ease-in-out" type="button">
                    <span class="material-symbols-outlined text-emerald-600">notifications</span>
                </button>
                <div class="w-10 h-10 rounded-full bg-surface-container overflow-hidden">
                    <img alt="Avatar pengguna" class="w-full h-full object-cover"
                        src="https://lh3.googleusercontent.com/aida-public/AB6AXuBD1pclx46soispQLdv5cVwHe0Fl64exvpv80U-yHUlRbVr0cuYPKejdnrfjFGVL0Fi4cZqsMWRHY9MCZ8ox51d3AhQUwSQfsRp0B_qG7ngvzGpojWdf_A7hwEDg6VIk9PQGTHYHKvPK3_AR11OaUQP_T4Xk1RyR5q2B-boANyuRmHbAoDVvWFvF_mynNLplV6tv4xN0Sju-GIoYri661Wh7OahjmV89rJqXh_2BQ8OnpwLlDFRRh8MIXh7Bqx4RQz4nS_LWCSKsiKG">
                </div>
            </div>
        </div>
    </nav>

    {{-- ===================== MODAL KAMERA ===================== --}}
    <div id="modal-kamera" class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/80 backdrop-blur-sm p-4">
        <div class="bg-white rounded-[32px] overflow-hidden w-full max-w-2xl shadow-2xl">
            {{-- Header modal --}}
            <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
                <div>
                    <h3 class="font-headline font-bold text-xl text-on-surface" id="modal-kamera-judul">Ambil Foto</h3>
                    <p class="text-sm text-slate-500" id="modal-kamera-subjudul">Arahkan kamera ke durian lalu tekan tombol ambil foto</p>
                </div>
                <button type="button" id="tombol-tutup-modal"
                    class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 hover:bg-slate-200 transition-colors">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>

            {{-- Video feed --}}
            <div class="relative bg-black aspect-[4/3]">
                <video id="video-kamera" class="w-full h-full object-cover" autoplay playsinline muted></video>

                {{-- Overlay panduan --}}
                <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                    <div class="w-64 h-64 border-2 border-white/50 rounded-[32px] relative">
                        <span class="absolute top-0 left-0 w-8 h-8 border-t-4 border-l-4 border-emerald-400 rounded-tl-[20px]"></span>
                        <span class="absolute top-0 right-0 w-8 h-8 border-t-4 border-r-4 border-emerald-400 rounded-tr-[20px]"></span>
                        <span class="absolute bottom-0 left-0 w-8 h-8 border-b-4 border-l-4 border-emerald-400 rounded-bl-[20px]"></span>
                        <span class="absolute bottom-0 right-0 w-8 h-8 border-b-4 border-r-4 border-emerald-400 rounded-br-[20px]"></span>
                    </div>
                </div>

                {{-- Label perspektif --}}
                <div class="absolute top-4 left-4">
                    <span id="label-perspektif-aktif" class="px-3 py-1 bg-emerald-500 text-white text-xs font-bold rounded-full tracking-wider uppercase shadow"></span>
                </div>

                {{-- Indikator progres slot --}}
                <div id="indikator-slot" class="absolute bottom-4 left-0 right-0 flex justify-center gap-2 hidden">
                    @for ($i = 0; $i < 4; $i++)
                        <div class="slot-dot w-3 h-3 rounded-full bg-white/30 transition-all duration-300" data-slot="{{ $i }}"></div>
                    @endfor
                </div>
            </div>

            {{-- Footer modal --}}
            <div class="px-6 py-5 flex items-center justify-between gap-4 bg-white">
                <div class="text-sm text-slate-500">
                    <span id="teks-status-kamera">Kamera siap</span>
                </div>

                <div class="flex items-center gap-3">
                    <button type="button" id="tombol-ganti-kamera"
                        class="w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center text-slate-600 hover:bg-slate-200 transition-colors">
                        <span class="material-symbols-outlined">flip_camera_ios</span>
                    </button>

                    <button type="button" id="tombol-ambil-foto"
                        class="w-16 h-16 rounded-full bg-emerald-500 hover:bg-emerald-600 flex items-center justify-center text-white shadow-lg shadow-emerald-500/30 transition-all active:scale-95">
                        <span class="material-symbols-outlined text-3xl">photo_camera</span>
                    </button>

                    <canvas id="canvas-kamera" class="hidden"></canvas>
                </div>

                <div class="text-sm font-medium text-emerald-700" id="teks-sisa-slot"></div>
            </div>
        </div>
    </div>

    {{-- ===================== MAIN ===================== --}}
    <main class="pt-32 pb-20 px-6 lg:px-8 max-w-7xl mx-auto">
        <header class="mb-12">
            <h1 class="font-headline text-4xl md:text-5xl font-extrabold tracking-tight text-on-surface mb-3">
                Klasifikasi Durian
            </h1>
            <p class="text-on-surface-variant text-lg max-w-3xl">
                Pilih mode upload yang paling nyaman, lalu unggah foto durian dari galeri atau kamera untuk dilakukan klasifikasi menggunakan model InceptionV3.
            </p>
        </header>

        {{-- Menampilkan Error Validasi Laravel --}}
        @if ($errors->any())
            <div class="mb-8 rounded-2xl border border-red-200 bg-red-50 px-5 py-4 text-red-800 shadow-sm">
                <div class="flex items-center gap-2 mb-2 font-bold">
                    <span class="material-symbols-outlined">error</span>
                    Terjadi Kesalahan:
                </div>
                <ul class="list-disc pl-8 text-sm space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('info'))
            <div class="mb-8 rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-emerald-800">
                {{ session('info') }}
            </div>
        @endif

        <form action="{{ route('klasifikasi.proses') }}" method="POST" enctype="multipart/form-data" id="form-klasifikasi">
            @csrf

            <input type="hidden" name="mode_upload" id="mode_upload" value="single">
            <input type="file" id="gambar-kirim" name="gambar[]" accept="image/*" class="hidden" multiple>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
                {{-- ===== KIRI ===== --}}
                <div class="lg:col-span-7">
                    <section class="bg-white rounded-[28px] p-6 md:p-8 shadow-halus mb-8">
                        <div class="flex items-center justify-between gap-4 flex-wrap">
                            <div>
                                <p class="text-xs font-bold uppercase tracking-[0.2em] text-emerald-700 mb-2">Mode Upload</p>
                                <h2 class="font-headline text-2xl font-bold text-on-surface">Pilih Cara Input Gambar</h2>
                            </div>
                            <div class="text-sm text-slate-500">Single = 1 gambar, Multi = 2–4 gambar</div>
                        </div>

                        <div class="grid sm:grid-cols-2 gap-4 mt-6">
                            <button type="button" id="tombol-mode-single"
                                class="mode-card border-2 border-emerald-500 bg-emerald-50 rounded-3xl p-5 text-left transition-all">
                                <div class="flex items-start gap-4">
                                    <div class="w-12 h-12 rounded-2xl bg-emerald-600 text-white flex items-center justify-center shrink-0">
                                        <span class="material-symbols-outlined">image</span>
                                    </div>
                                    <div>
                                        <h3 class="font-headline text-xl font-bold">1 Gambar</h3>
                                        <p class="text-sm text-slate-600 mt-1">Cocok untuk klasifikasi cepat.</p>
                                    </div>
                                </div>
                            </button>

                            <button type="button" id="tombol-mode-multi"
                                class="mode-card border-2 border-slate-200 bg-white rounded-3xl p-5 text-left transition-all">
                                <div class="flex items-start gap-4">
                                    <div class="w-12 h-12 rounded-2xl bg-slate-100 text-emerald-700 flex items-center justify-center shrink-0">
                                        <span class="material-symbols-outlined">view_module</span>
                                    </div>
                                    <div>
                                        <h3 class="font-headline text-xl font-bold">Multi Sudut</h3>
                                        <p class="text-sm text-slate-600 mt-1">Unggah 2–4 gambar sisi berbeda.</p>
                                    </div>
                                </div>
                            </button>
                        </div>
                    </section>

                    {{-- ===== PANEL SINGLE ===== --}}
                    <section id="panel-single" class="bg-white rounded-[28px] p-6 md:p-8 shadow-halus">
                        <div class="flex items-center justify-between gap-4 flex-wrap mb-6">
                            <div>
                                <p class="text-xs font-bold uppercase tracking-[0.2em] text-emerald-700 mb-2">Upload Tunggal</p>
                                <h2 class="font-headline text-2xl font-bold">Unggah 1 Foto Durian</h2>
                            </div>
                            <span class="text-sm text-slate-500">Maksimal 1 gambar</span>
                        </div>

                        <div class="grid sm:grid-cols-2 gap-4 mb-6">
                            <label for="input-single-galeri"
                                class="cursor-pointer rounded-3xl border-2 border-dashed border-emerald-200 bg-emerald-50/60 p-5 hover:bg-emerald-50 transition-colors">
                                <div class="flex items-center gap-4">
                                    <div class="w-14 h-14 rounded-2xl bg-white flex items-center justify-center text-emerald-700 shadow-sm">
                                        <span class="material-symbols-outlined text-3xl">collections</span>
                                    </div>
                                    <div>
                                        <h3 class="font-headline font-bold text-lg">Pilih dari Galeri</h3>
                                        <p class="text-sm text-slate-600">Ambil 1 gambar dari perangkat.</p>
                                    </div>
                                </div>
                            </label>

                            <button type="button" id="tombol-kamera-single"
                                class="rounded-3xl border-2 border-dashed border-yellow-200 bg-yellow-50/70 p-5 hover:bg-yellow-50 transition-colors text-left w-full">
                                <div class="flex items-center gap-4">
                                    <div class="w-14 h-14 rounded-2xl bg-white flex items-center justify-center text-yellow-700 shadow-sm">
                                        <span class="material-symbols-outlined text-3xl">photo_camera</span>
                                    </div>
                                    <div>
                                        <h3 class="font-headline font-bold text-lg">Dari Kamera</h3>
                                        <p class="text-sm text-slate-600">Foto langsung pakai kamera.</p>
                                    </div>
                                </div>
                            </button>

                            <input type="file" id="input-single-galeri" accept="image/*" class="hidden">
                            <input type="file" id="input-single-kamera-fallback" accept="image/*" capture="environment" class="hidden">
                        </div>

                        <div>
                            <div class="flex items-center justify-between mb-3">
                                <h3 class="font-headline text-xl font-bold">Preview</h3>
                                <span class="text-sm text-slate-500">Jumlah gambar: <strong id="jumlah-single">0</strong>/1</span>
                            </div>

                            <div id="preview-single"
                                class="aspect-[4/3] rounded-[28px] border-2 border-dashed border-slate-200 bg-slate-50 flex flex-col items-center justify-center text-center p-6 overflow-hidden">
                                <div class="w-16 h-16 rounded-full bg-white flex items-center justify-center text-emerald-700 shadow-sm mb-4">
                                    <span class="material-symbols-outlined text-3xl">image</span>
                                </div>
                                <h4 class="font-headline text-lg font-bold mb-1">Belum ada gambar</h4>
                                <p class="text-sm text-slate-500">Silakan pilih 1 foto dari galeri atau kamera.</p>
                            </div>
                        </div>
                    </section>

                    {{-- ===== PANEL MULTI ===== --}}
                    <section id="panel-multi" class="bg-white rounded-[28px] p-6 md:p-8 shadow-halus hidden">
                        <div class="flex items-center justify-between gap-4 flex-wrap mb-6">
                            <div>
                                <p class="text-xs font-bold uppercase tracking-[0.2em] text-emerald-700 mb-2">Upload Multi Sudut</p>
                                <h2 class="font-headline text-2xl font-bold">Unggah 2–4 Foto</h2>
                            </div>
                            <span class="text-sm text-slate-500">Bisa 2, 3, atau 4 gambar</span>
                        </div>

                        <div class="grid sm:grid-cols-2 gap-4 mb-6">
                            <label for="input-multi-galeri"
                                class="cursor-pointer rounded-3xl border-2 border-dashed border-emerald-200 bg-emerald-50/60 p-5 hover:bg-emerald-50 transition-colors">
                                <div class="flex items-center gap-4">
                                    <div class="w-14 h-14 rounded-2xl bg-white flex items-center justify-center text-emerald-700 shadow-sm">
                                        <span class="material-symbols-outlined text-3xl">collections</span>
                                    </div>
                                    <div>
                                        <h3 class="font-headline font-bold text-lg">Tambah dari Galeri</h3>
                                        <p class="text-sm text-slate-600">Pilih beberapa gambar sekaligus.</p>
                                    </div>
                                </div>
                            </label>

                            <button type="button" id="tombol-kamera-multi"
                                class="rounded-3xl border-2 border-dashed border-yellow-200 bg-yellow-50/70 p-5 hover:bg-yellow-50 transition-colors text-left w-full">
                                <div class="flex items-center gap-4">
                                    <div class="w-14 h-14 rounded-2xl bg-white flex items-center justify-center text-yellow-700 shadow-sm">
                                        <span class="material-symbols-outlined text-3xl">camera_enhance</span>
                                    </div>
                                    <div>
                                        <h3 class="font-headline font-bold text-lg">Foto 4 Sisi Berurutan</h3>
                                        <p class="text-sm text-slate-600">Kamera akan memandu tiap sudut.</p>
                                    </div>
                                </div>
                            </button>

                            <input type="file" id="input-multi-galeri" accept="image/*" multiple class="hidden">
                            <input type="file" id="input-multi-kamera-fallback" accept="image/*" capture="environment" class="hidden">
                        </div>

                        <div class="flex items-center justify-between mb-3">
                            <h3 class="font-headline text-xl font-bold">Preview Multi Sudut</h3>
                            <span class="text-sm text-slate-500">Jumlah gambar: <strong id="jumlah-multi">0</strong>/4</span>
                        </div>

                        <div id="preview-multi" class="grid grid-cols-2 gap-4"></div>

                        <div class="mt-4 text-sm text-slate-500">
                            Saran: gunakan minimal 2 sudut agar ciri kulit durian lebih lengkap terlihat.
                        </div>
                    </section>
                </div>

                {{-- ===== KANAN ===== --}}
                <div class="lg:col-span-5 flex flex-col gap-8">
                    <section class="bg-white p-8 rounded-[28px] shadow-halus flex flex-col gap-8">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-[0.2em] text-on-surface-variant mb-3">
                                Model Klasifikasi
                            </label>
                            <div class="relative">
                                <select name="model" class="w-full bg-slate-100 border-none rounded-full px-6 py-4 text-on-surface font-semibold appearance-none focus:ring-2 focus:ring-emerald-400">
                                    <option value="InceptionV3">InceptionV3 (Recommended)</option>
                                </select>
                                <span class="material-symbols-outlined absolute right-6 top-1/2 -translate-y-1/2 pointer-events-none">expand_more</span>
                            </div>
                        </div>

                        <div class="rounded-3xl bg-slate-50 p-5 border border-slate-100">
                            <h3 class="font-headline font-bold text-lg mb-2">Ringkasan Input</h3>
                            <div class="space-y-2 text-sm text-slate-600">
                                <div class="flex justify-between gap-4">
                                    <span>Mode aktif</span>
                                    <strong id="teks-mode-aktif">1 Gambar</strong>
                                </div>
                                <div class="flex justify-between gap-4">
                                    <span>Total gambar siap kirim</span>
                                    <strong id="teks-total-gambar">0</strong>
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-col gap-4">
                            <button type="submit" id="tombol-submit"
                                class="w-full signature-gradient text-white py-5 rounded-2xl font-headline font-bold text-lg hover:opacity-90 transition-opacity active:scale-[0.98] duration-200 shadow-lg shadow-emerald-900/10">
                                Mulai Klasifikasi
                            </button>
                            <button type="button" id="tombol-reset"
                                class="w-full bg-yellow-100 text-yellow-900 py-4 rounded-2xl font-headline font-bold hover:bg-yellow-200 transition-colors active:scale-[0.98] duration-200">
                                Reset Foto
                            </button>
                        </div>
                    </section>
                </div>
            </div>
        </form>
    </main>

    <footer class="w-full border-t border-slate-200/50 bg-slate-50">
        <div class="py-12 px-6 lg:px-8 max-w-7xl mx-auto flex flex-col md:flex-row justify-between items-center gap-6 font-inter text-sm leading-relaxed">
            <div class="flex flex-col gap-2">
                <div class="text-lg font-bold text-slate-900">DurianFy</div>
                <div class="text-slate-500">© 2026 DurianFy. Website klasifikasi varietas durian berbasis InceptionV3.</div>
            </div>
        </div>
    </footer>

    {{-- ===================== SCRIPT ===================== --}}
    <script>
    (function () {
        'use strict';

        const tombolModeSingle   = document.getElementById('tombol-mode-single');
        const tombolModeMulti    = document.getElementById('tombol-mode-multi');
        const panelSingle        = document.getElementById('panel-single');
        const panelMulti         = document.getElementById('panel-multi');

        const inputSingleGaleri  = document.getElementById('input-single-galeri');
        const inputSingleFallback= document.getElementById('input-single-kamera-fallback');
        const inputMultiGaleri   = document.getElementById('input-multi-galeri');
        const inputMultiFallback = document.getElementById('input-multi-kamera-fallback');

        const tombolKameraSingle = document.getElementById('tombol-kamera-single');
        const tombolKameraMulti  = document.getElementById('tombol-kamera-multi');

        const inputMode          = document.getElementById('mode_upload');
        const previewSingle      = document.getElementById('preview-single');
        const previewMulti       = document.getElementById('preview-multi');
        const jumlahSingle       = document.getElementById('jumlah-single');
        const jumlahMulti        = document.getElementById('jumlah-multi');
        const teksModeAktif      = document.getElementById('teks-mode-aktif');
        const teksTotalGambar    = document.getElementById('teks-total-gambar');
        const tombolReset        = document.getElementById('tombol-reset');
        const tombolSubmit       = document.getElementById('tombol-submit');

        const modalKamera        = document.getElementById('modal-kamera');
        const videoEl            = document.getElementById('video-kamera');
        const canvasEl           = document.getElementById('canvas-kamera');
        const tombolAmbilFoto    = document.getElementById('tombol-ambil-foto');
        const tombolTutupModal   = document.getElementById('tombol-tutup-modal');
        const tombolGantiKamera  = document.getElementById('tombol-ganti-kamera');
        const modalJudul         = document.getElementById('modal-kamera-judul');
        const modalSubjudul      = document.getElementById('modal-kamera-subjudul');
        const labelPerspektif    = document.getElementById('label-perspektif-aktif');
        const indikatorSlot      = document.getElementById('indikator-slot');
        const teksSisaSlot       = document.getElementById('teks-sisa-slot');
        const teksStatusKamera   = document.getElementById('teks-status-kamera');

        let fileSingle    = [];
        let fileMulti     = [];
        let streamAktif   = null;
        let facingMode    = 'environment';  
        let modeKamera    = 'single';       
        let slotMultiTarget = 0;            

        const LABEL_SLOT = ['Tampak Depan', 'Samping Kiri', 'Samping Kanan', 'Tampak Atas'];

        function isMobile() {
            return /Mobi|Android|iPhone|iPad|iPod/i.test(navigator.userAgent);
        }

        function perbaruiTotalGambar() {
            teksTotalGambar.textContent = inputMode.value === 'single' ? fileSingle.length : fileMulti.length;
        }

        async function kompresGambarPintar(file) {
            return new Promise((resolve) => {
                const reader = new FileReader();
                reader.readAsDataURL(file);
                reader.onload = (event) => {
                    const img = new Image();
                    img.src = event.target.result;
                    img.onload = () => {
                        const canvas = document.createElement('canvas');
                        let width = img.width;
                        let height = img.height;
                        const MAX_SIZE = 1024; 

                        if (width > height) {
                            if (width > MAX_SIZE) { height *= MAX_SIZE / width; width = MAX_SIZE; }
                        } else {
                            if (height > MAX_SIZE) { width *= MAX_SIZE / height; height = MAX_SIZE; }
                        }

                        canvas.width = width;
                        canvas.height = height;
                        const ctx = canvas.getContext('2d');
                        ctx.drawImage(img, 0, 0, width, height);

                        canvas.toBlob((blob) => {
                            resolve(new File([blob], `durianfy_${Date.now()}.jpg`, { type: 'image/jpeg', lastModified: Date.now() }));
                        }, 'image/jpeg', 0.80);
                    };
                };
            });
        }

        function renderPreviewSingle() {
            jumlahSingle.textContent = fileSingle.length;
            if (fileSingle.length === 0) {
                previewSingle.innerHTML = `
                    <div class="w-16 h-16 rounded-full bg-white flex items-center justify-center text-emerald-700 shadow-sm mb-4">
                        <span class="material-symbols-outlined text-3xl">image</span>
                    </div>
                    <h4 class="font-headline text-lg font-bold mb-1">Belum ada gambar</h4>
                    <p class="text-sm text-slate-500">Silakan pilih 1 foto dari galeri atau kamera.</p>`;
                return;
            }
            const reader = new FileReader();
            reader.onload = e => {
                previewSingle.innerHTML = `<img src="${e.target.result}" alt="Preview gambar" class="w-full h-full object-cover rounded-[24px]">`;
            };
            reader.readAsDataURL(fileSingle[0]);
        }

        function renderPreviewMulti() {
            jumlahMulti.textContent = fileMulti.length;
            previewMulti.innerHTML = '';
            for (let i = 0; i < 4; i++) {
                const div = document.createElement('div');
                div.className = 'aspect-square rounded-[24px] overflow-hidden border-2 border-dashed border-slate-200 bg-slate-50 relative group';

                if (fileMulti[i]) {
                    const reader = new FileReader();
                    const idx = i;
                    reader.onload = e => {
                        div.innerHTML = `
                            <img src="${e.target.result}" alt="Preview ${idx + 1}" class="w-full h-full object-cover">
                            <div class="absolute inset-0 bg-black/0 group-hover:bg-black/30 transition-colors flex items-center justify-center">
                                <button type="button" class="opacity-0 group-hover:opacity-100 transition-opacity bg-red-500 text-white rounded-full w-9 h-9 flex items-center justify-center shadow-lg" onclick="hapusSlotMulti(${idx})">
                                    <span class="material-symbols-outlined text-lg">delete</span>
                                </button>
                            </div>
                            <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/60 to-transparent px-3 py-2">
                                <p class="text-white text-xs font-bold">${LABEL_SLOT[idx]}</p>
                            </div>`;
                    };
                    reader.readAsDataURL(fileMulti[i]);
                } else {
                    div.innerHTML = `
                        <div class="w-full h-full flex flex-col items-center justify-center text-center p-4">
                            <div class="w-12 h-12 rounded-full bg-white flex items-center justify-center text-emerald-700 shadow-sm mb-3">
                                <span class="material-symbols-outlined">photo_camera</span>
                            </div>
                            <h4 class="font-headline font-bold text-sm">Slot ${i + 1}</h4>
                            <p class="text-xs text-slate-500 mt-1">${LABEL_SLOT[i]}</p>
                        </div>`;
                }
                previewMulti.appendChild(div);
            }
        }

        window.hapusSlotMulti = function(idx) {
            fileMulti.splice(idx, 1);
            renderPreviewMulti();
            perbaruiTotalGambar();
        };

        function updateIndikatorSlot(slotAktif) {
            const dots = indikatorSlot.querySelectorAll('.slot-dot');
            dots.forEach((dot, i) => {
                dot.classList.remove('bg-white', 'bg-emerald-400', 'bg-white/30', 'scale-125');
                if (i < fileMulti.length) dot.classList.add('bg-emerald-400');
                else if (i === slotAktif) dot.classList.add('bg-white', 'scale-125');
                else dot.classList.add('bg-white/30');
            });
        }

        async function bukaKamera(mode, slotTarget = 0) {
            modeKamera = mode;
            slotMultiTarget = slotTarget;

            if (mode === 'single') {
                modalJudul.textContent = 'Ambil Foto Durian';
                modalSubjudul.textContent = 'Arahkan kamera ke durian lalu tekan tombol ambil foto';
                labelPerspektif.textContent = 'Foto Utama';
                indikatorSlot.classList.add('hidden');
                teksSisaSlot.textContent = '';
            } else {
                modalJudul.textContent = `Foto ${LABEL_SLOT[slotTarget]}`;
                modalSubjudul.textContent = `Pengambilan ${slotTarget + 1} dari 4 sudut`;
                labelPerspektif.textContent = LABEL_SLOT[slotTarget];
                indikatorSlot.classList.remove('hidden');
                updateIndikatorSlot(slotTarget);
                teksSisaSlot.textContent = `Slot ${slotTarget + 1}/4`;
            }

            modalKamera.classList.remove('hidden');
            modalKamera.classList.add('flex');
            await mulaiStream();
        }

        async function mulaiStream() {
            try {
                hentikanStream();
                teksStatusKamera.textContent = 'Menghubungkan kamera...';
                const constraints = { video: { facingMode: facingMode, width: { ideal: 1024 }, height: { ideal: 1024 } }, audio: false };
                streamAktif = await navigator.mediaDevices.getUserMedia(constraints);
                videoEl.srcObject = streamAktif;
                teksStatusKamera.textContent = 'Kamera siap';
            } catch (err) {
                console.error('Kamera error:', err);
                tutupModal();
                if (modeKamera === 'single') inputSingleFallback.click();
                else inputMultiFallback.click();
            }
        }

        function hentikanStream() {
            if (streamAktif) {
                streamAktif.getTracks().forEach(t => t.stop());
                streamAktif = null;
                videoEl.srcObject = null;
            }
        }

        function tutupModal() {
            hentikanStream();
            modalKamera.classList.add('hidden');
            modalKamera.classList.remove('flex');
        }

        function ambilFotoDariVideo() {
            let w = videoEl.videoWidth || 640;
            let h = videoEl.videoHeight || 480;
            const MAX = 1024;
            if (w > h && w > MAX) { h *= MAX / w; w = MAX; }
            else if (h > MAX) { w *= MAX / h; h = MAX; }

            canvasEl.width = w;
            canvasEl.height = h;

            const ctx = canvasEl.getContext('2d');
            ctx.drawImage(videoEl, 0, 0, w, h);

            canvasEl.toBlob(blob => {
                if (!blob) return;
                const file = new File([blob], `durianfy_kamera_${Date.now()}.jpg`, { type: 'image/jpeg' });

                const flash = document.createElement('div');
                flash.className = 'absolute inset-0 bg-white opacity-80 transition-opacity duration-200';
                videoEl.parentElement.appendChild(flash);
                setTimeout(() => flash.remove(), 250);

                if (modeKamera === 'single') {
                    fileSingle = [file];
                    renderPreviewSingle();
                    perbaruiTotalGambar();
                    tutupModal();
                } else {
                    if (slotMultiTarget < fileMulti.length) fileMulti[slotMultiTarget] = file;
                    else fileMulti.push(file);
                    
                    renderPreviewMulti();
                    perbaruiTotalGambar();

                    const berikutnya = slotMultiTarget + 1;
                    if (berikutnya >= 4 || fileMulti.length >= 4) {
                        tutupModal();
                    } else {
                        slotMultiTarget = berikutnya;
                        modalJudul.textContent = `Foto ${LABEL_SLOT[slotMultiTarget]}`;
                        modalSubjudul.textContent = `Pengambilan ${slotMultiTarget + 1} dari 4 sudut`;
                        labelPerspektif.textContent = LABEL_SLOT[slotMultiTarget];
                        teksSisaSlot.textContent = `Slot ${slotMultiTarget + 1}/4`;
                        updateIndikatorSlot(slotMultiTarget);
                    }
                }
            }, 'image/jpeg', 0.85); 
        }

        tombolGantiKamera.addEventListener('click', () => {
            facingMode = facingMode === 'environment' ? 'user' : 'environment';
            mulaiStream();
        });

        tombolKameraSingle.addEventListener('click', async () => {
            isMobile() ? inputSingleFallback.click() : await bukaKamera('single');
        });

        tombolKameraMulti.addEventListener('click', async () => {
            if (isMobile()) {
                inputMultiFallback.click();
            } else {
                if (fileMulti.length >= 4) return;
                await bukaKamera('multi', fileMulti.length);
            }
        });

        tombolAmbilFoto.addEventListener('click', ambilFotoDariVideo);
        tombolTutupModal.addEventListener('click', tutupModal);

        async function prosesUploadGaleriSingle(input) {
            if (input.files.length > 0) {
                tombolSubmit.innerHTML = '<span class="material-symbols-outlined animate-spin align-middle mr-2">sync</span> Memproses Foto...';
                tombolSubmit.disabled = true;

                const compressedFile = await kompresGambarPintar(input.files[0]);
                fileSingle = [compressedFile];
                
                renderPreviewSingle();
                perbaruiTotalGambar();
                
                tombolSubmit.innerHTML = 'Mulai Klasifikasi';
                tombolSubmit.disabled = false;
                input.value = '';
            }
        }

        async function prosesUploadGaleriMulti(input) {
            if (input.files.length > 0) {
                tombolSubmit.innerHTML = '<span class="material-symbols-outlined animate-spin align-middle mr-2">sync</span> Memproses Foto...';
                tombolSubmit.disabled = true;

                const filesArray = Array.from(input.files);
                for (let file of filesArray) {
                    if (fileMulti.length < 4) {
                        const compressedFile = await kompresGambarPintar(file);
                        fileMulti.push(compressedFile);
                    }
                }
                
                renderPreviewMulti();
                perbaruiTotalGambar();
                
                tombolSubmit.innerHTML = 'Mulai Klasifikasi';
                tombolSubmit.disabled = false;
                input.value = '';
            }
        }

        inputSingleFallback.addEventListener('change', function () { prosesUploadGaleriSingle(this); });
        inputSingleGaleri.addEventListener('change', function () { prosesUploadGaleriSingle(this); });
        inputMultiFallback.addEventListener('change', function () { prosesUploadGaleriMulti(this); });
        inputMultiGaleri.addEventListener('change', function () { prosesUploadGaleriMulti(this); });

        function aktifkanMode(mode) {
            inputMode.value = mode;
            if (mode === 'single') {
                panelSingle.classList.remove('hidden');
                panelMulti.classList.add('hidden');
                tombolModeSingle.classList.add('border-emerald-500', 'bg-emerald-50');
                tombolModeSingle.classList.remove('border-slate-200', 'bg-white');
                tombolModeMulti.classList.remove('border-emerald-500', 'bg-emerald-50');
                tombolModeMulti.classList.add('border-slate-200', 'bg-white');
                teksModeAktif.textContent = '1 Gambar';
            } else {
                panelMulti.classList.remove('hidden');
                panelSingle.classList.add('hidden');
                tombolModeMulti.classList.add('border-emerald-500', 'bg-emerald-50');
                tombolModeMulti.classList.remove('border-slate-200', 'bg-white');
                tombolModeSingle.classList.remove('border-emerald-500', 'bg-emerald-50');
                tombolModeSingle.classList.add('border-slate-200', 'bg-white');
                teksModeAktif.textContent = 'Multi Sudut';
            }
            perbaruiTotalGambar();
        }

        tombolModeSingle.addEventListener('click', () => aktifkanMode('single'));
        tombolModeMulti.addEventListener('click',  () => aktifkanMode('multi'));

        tombolReset.addEventListener('click', () => {
            fileSingle = []; fileMulti  = [];
            inputSingleGaleri.value = ''; inputSingleFallback.value = '';
            inputMultiGaleri.value = ''; inputMultiFallback.value = '';
            renderPreviewSingle(); renderPreviewMulti();
            aktifkanMode('single');
        });

        // ── Submit normal agar halaman hasil tidak kacau ──
        const formKlasifikasi = document.getElementById('form-klasifikasi');
        const gambarKirim = document.getElementById('gambar-kirim');

        formKlasifikasi.addEventListener('submit', function(e) {
            e.preventDefault();

            const daftar = inputMode.value === 'single' ? fileSingle : fileMulti;

            if (daftar.length === 0) {
                alert('Tunggu dulu! Kamu belum memilih foto durian.');
                return;
            }

            if (inputMode.value === 'multi' && daftar.length < 2) {
                alert('Mode Multi Sudut membutuhkan minimal 2 gambar.');
                return;
            }

            if (daftar.length > 4) {
                alert('Maksimal 4 gambar yang dapat diklasifikasikan.');
                return;
            }

            /*
             * Masukkan file hasil preview JS ke input file final.
             * Jadi Laravel tetap menerima field gambar[] secara normal.
             */
            const dataTransfer = new DataTransfer();

            daftar.forEach((file) => {
                dataTransfer.items.add(file);
            });

            gambarKirim.files = dataTransfer.files;

            /*
             * Disable input lain agar tidak dobel terkirim.
             * Input gambar-kirim tetap aktif karena itu yang dikirim ke Laravel.
             */
            [
                inputSingleGaleri,
                inputSingleFallback,
                inputMultiGaleri,
                inputMultiFallback
            ].forEach((input) => {
                if (input) {
                    input.disabled = true;
                }
            });

            tombolSubmit.innerHTML =
                '<span class="material-symbols-outlined animate-spin align-middle mr-2">sync</span> AI Sedang Bekerja...';

            tombolSubmit.disabled = true;

            /*
             * Submit form secara normal.
             * Browser akan render hasil-klasifikasi.blade.php dengan CSS/layout normal.
             */
            formKlasifikasi.submit();
        });

        renderPreviewSingle(); renderPreviewMulti(); aktifkanMode('single');

    })();
    </script>
@endsection