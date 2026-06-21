@extends('layouts.guru')

@section('title', 'Tujuan Pembelajaran')

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-slate-900">Tujuan Pembelajaran</h1>
        <p class="mt-1 text-sm text-slate-500">Pilih mata pelajaran yang Anda ampu untuk mengelola tujuan pembelajaran.</p>
    </div>

    @if ($penugasanList->isEmpty())
        <div class="rounded-2xl border border-amber-200 bg-amber-50 px-6 py-10 text-center text-sm text-amber-800">
            Belum ada penugasan mengajar untuk Anda di tahun ajaran aktif.
        </div>
    @else
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($penugasanList as $penugasan)
                <div class="flex flex-col rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition hover:shadow-md">
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-slate-900">{{ $penugasan->mataPelajaran->nama }}</h3>
                        <p class="mt-1 text-sm font-medium text-slate-600">Kelas {{ $penugasan->kelas->nama }}</p>
                    </div>
                    <div class="mt-6">
                        <a href="{{ route('guru.kelola-nilai.tujuan-pembelajaran.show', $penugasan) }}" class="inline-flex w-full justify-center rounded-xl bg-sky-50 px-4 py-2.5 text-sm font-semibold text-sky-700 transition hover:bg-sky-100">
                            Kelola Tujuan Pembelajaran
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
@endsection
