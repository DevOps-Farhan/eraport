@extends('layouts.guest')

@section('title', 'Halaman Tidak Ditemukan - 404')

@section('content')
<div class="flex min-h-screen items-center justify-center bg-slate-50 px-4 py-12 sm:px-6 lg:px-8">
    <div class="w-full max-w-md space-y-8 text-center">
        <div>
            <h1 class="text-9xl font-extrabold tracking-widest text-indigo-600 drop-shadow-sm">404</h1>
            <div class="absolute inset-0 flex items-center justify-center pointer-events-none opacity-20">
                <div class="w-64 h-64 bg-indigo-400 rounded-full blur-3xl mix-blend-multiply"></div>
                <div class="w-64 h-64 bg-purple-400 rounded-full blur-3xl mix-blend-multiply ml-12"></div>
            </div>
            <div class="relative mt-8">
                <h2 class="text-2xl font-bold text-slate-900 md:text-3xl">Halaman Tidak Ditemukan</h2>
                <p class="mt-4 text-base text-slate-600">
                    Maaf, kami tidak dapat menemukan halaman yang Anda cari. Halaman mungkin telah dihapus, namanya diubah, atau sementara tidak tersedia.
                </p>
            </div>
        </div>

        <div class="mt-8">
            <a href="{{ url('/') }}" class="inline-flex items-center justify-center rounded-lg border border-transparent bg-indigo-600 px-6 py-3 text-base font-medium text-white shadow-sm transition-all hover:bg-indigo-700 hover:shadow-md hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                <svg class="mr-2 -ml-1 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali ke Beranda
            </a>
        </div>
    </div>
</div>
@endsection
