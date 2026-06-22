@extends('layouts.guru')

@section('title', 'Kokurikuler')

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-slate-900">Pilih Kelas Kokurikuler</h1>
        <p class="mt-1 text-sm text-slate-500">Pilih kelas perwalian Anda untuk mengelola kokurikuler.</p>
    </div>

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
        @forelse ($kelasList as $kelas)
            <a
                href="{{ route('guru.wali-kelas.kokurikuler.show', $kelas) }}"
                class="group block rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:border-sky-300 hover:shadow-md"
            >
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-semibold text-slate-900 group-hover:text-sky-700">{{ $kelas->nama }}</h2>
                        <p class="mt-1 text-sm text-slate-500">{{ $kelas->jurusan->nama ?? 'Tanpa Jurusan' }} • Fase {{ $kelas->fase }} • {{ $kelas->rombel_siswa_count ?? $kelas->rombelSiswa()->where('status', \App\Enums\StatusRombel::Aktif)->count() }} Siswa</p>
                    </div>
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-sky-50 text-sky-600 transition group-hover:bg-sky-100 group-hover:text-sky-700">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                        </svg>
                    </div>
                </div>
            </a>
        @empty
            <div class="col-span-full rounded-2xl border border-dashed border-slate-300 p-8 text-center">
                <svg class="mx-auto h-10 w-10 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <p class="mt-4 text-sm font-medium text-slate-900">Belum Ada Kelas</p>
                <p class="mt-1 text-sm text-slate-500">Anda belum ditugaskan sebagai wali kelas.</p>
            </div>
        @endforelse
    </div>
@endsection
