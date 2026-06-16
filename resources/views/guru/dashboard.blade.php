@extends('layouts.guru')

@section('title', 'Dashboard')

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-slate-900">Dashboard Guru</h1>
        <p class="mt-1 text-sm text-slate-500">
            Selamat datang, {{ auth()->user()->nama }}.
        </p>
    </div>

    <div class="grid gap-4 sm:grid-cols-2">
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <h2 class="text-sm font-medium text-slate-500">Kelola Nilai</h2>
            <p class="mt-2 text-sm text-slate-600">Input tujuan pembelajaran, nilai TP, dan nilai ujian untuk mata pelajaran yang diajar.</p>
            <a href="{{ route('guru.kelola-nilai.tujuan-pembelajaran') }}" class="mt-4 inline-flex text-sm font-medium text-sky-600 hover:text-sky-700">
                Buka kelola nilai →
            </a>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <h2 class="text-sm font-medium text-slate-500">Wali Kelas</h2>
            <p class="mt-2 text-sm text-slate-600">Kelola data kelas, absensi, catatan, leger, dan cetak raport sebagai wali kelas.</p>
            <a href="{{ route('guru.wali-kelas.kelas') }}" class="mt-4 inline-flex text-sm font-medium text-sky-600 hover:text-sky-700">
                Buka wali kelas →
            </a>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:col-span-2">
            <h2 class="text-sm font-medium text-slate-500">Arsip Tahun Ajaran</h2>
            <p class="mt-2 text-sm text-slate-600">Lihat data tahun ajaran yang sudah ditutup (hanya baca).</p>
            <a href="{{ route('arsip.tahun-ajaran.index') }}" class="mt-4 inline-flex text-sm font-medium text-sky-600 hover:text-sky-700">
                Buka arsip →
            </a>
        </div>
    </div>
@endsection
