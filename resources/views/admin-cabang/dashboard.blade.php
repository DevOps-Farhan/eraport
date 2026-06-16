@extends('layouts.admin-cabang')

@section('title', 'Dashboard')

@section('content')
    <div class="mb-8">
        <h1 class="text-2xl font-semibold text-slate-900">Dashboard</h1>
        <p class="mt-2 text-slate-600">
            Selamat datang, <span class="font-medium text-slate-900">{{ auth()->user()->nama }}</span>.
        </p>
        @if (auth()->user()->cabangSekolah)
            <p class="mt-1 text-sm text-slate-500">
                Cabang: <span class="font-medium text-emerald-700">{{ auth()->user()->cabangSekolah->nama }}</span>
            </p>
        @endif
    </div>

    <div class="grid gap-4 sm:grid-cols-2">
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <h2 class="text-sm font-medium text-slate-500">Data Akademik</h2>
            <p class="mt-2 text-sm text-slate-600">Kelola tahun ajaran, jurusan, kelas, mata pelajaran, dan penugasan mengajar.</p>
            <a href="{{ route('admin-cabang.data-akademik.tahun-ajaran.index') }}" class="mt-4 inline-flex text-sm font-medium text-emerald-600 hover:text-emerald-700">
                Buka data akademik →
            </a>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <h2 class="text-sm font-medium text-slate-500">Master Data</h2>
            <p class="mt-2 text-sm text-slate-600">Kelola data guru, pembina ekstrakurikuler, dan siswa di cabang Anda.</p>
            <a href="{{ route('admin-cabang.master-data.guru') }}" class="mt-4 inline-flex text-sm font-medium text-emerald-600 hover:text-emerald-700">
                Buka master data →
            </a>
        </div>
    </div>
@endsection
