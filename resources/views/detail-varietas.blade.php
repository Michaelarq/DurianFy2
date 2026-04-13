@extends('layouts.aplikasi')

@section('judul', 'Detail Varietas - DurianFy')

@section('konten')
<div class="max-w-5xl mx-auto px-6 py-12">
    <div class="bg-white rounded-3xl shadow-lembut overflow-hidden">
        <div class="bg-gradient-to-r from-emerald-700 to-emerald-500 px-8 py-12 text-white">
            <p class="uppercase tracking-[0.2em] text-sm font-bold mb-3">Detail Varietas</p>
            <h1 class="font-headline text-5xl font-extrabold mb-3">{{ $varietas['nama'] }}</h1>
            <p class="text-emerald-50">{{ $varietas['latin'] }}</p>
        </div>

        <div class="p-8">
            <div class="mb-8">
                <h2 class="font-headline text-2xl font-bold mb-3">Deskripsi Singkat</h2>
                <p class="text-textsoft leading-relaxed">{{ $varietas['deskripsi'] }}</p>
            </div>

            <div class="mb-8">
                <h2 class="font-headline text-2xl font-bold mb-4">Karakteristik</h2>
                <div class="grid md:grid-cols-2 gap-4">
                    @foreach ($varietas['ciri'] as $ciri)
                        <div class="rounded-2xl bg-surface p-4 border border-slate-100">
                            {{ $ciri }}
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="flex flex-wrap gap-4">
                <a href="{{ route('klasifikasi') }}" class="gradien-utama text-white px-8 py-4 rounded-2xl font-headline font-semibold">
                    Klasifikasi Lagi
                </a>

                <a href="{{ route('beranda') }}" class="bg-white border border-slate-200 px-8 py-4 rounded-2xl font-headline font-semibold text-slate-700">
                    Kembali ke Beranda
                </a>
            </div>
        </div>
    </div>
</div>
@endsection