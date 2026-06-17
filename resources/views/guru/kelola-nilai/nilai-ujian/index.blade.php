@extends('layouts.guru')

@section('title', 'Nilai Ujian')

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-slate-900">Nilai Ujian</h1>
        <p class="mt-1 text-sm text-slate-500">Input dan kelola nilai ujian mata pelajaran.</p>
        <p class="mt-2 text-xs text-slate-400">Formula: Rata-rata TP × 60% + Nilai Ujian × 40% = Nilai Akhir Raport</p>
    </div>

    @if ($penugasanList->isEmpty())
        <div class="rounded-2xl border border-amber-200 bg-amber-50 px-6 py-10 text-center text-sm text-amber-800">
            Belum ada penugasan mengajar untuk Anda di tahun ajaran aktif.
        </div>
    @else
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($penugasanList as $item)
                <a href="{{ route('guru.kelola-nilai.nilai-ujian.show', $item) }}" class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:border-sky-300 hover:shadow-md">
                    <p class="font-semibold text-slate-900">{{ $item->mataPelajaran->nama }}</p>
                    <p class="mt-1 text-sm text-slate-500">{{ $item->kelas->nama }}</p>
                    <p class="mt-3 text-sm font-medium text-sky-600">Input nilai ujian →</p>
                </a>
            @endforeach
        </div>
    @endif
@endsection
