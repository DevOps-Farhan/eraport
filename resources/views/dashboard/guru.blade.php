@extends('layouts.app')

@section('title', 'Guru')

@section('content')
    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
        <h1 class="text-xl font-semibold text-slate-900">Dashboard Guru</h1>
        <p class="mt-2 text-sm text-slate-600">
            Selamat datang, {{ auth()->user()->nama }}.
        </p>
        @if (auth()->user()->cabangSekolah)
            <p class="mt-1 text-sm text-slate-500">
                Cabang: <span class="font-medium text-slate-700">{{ auth()->user()->cabangSekolah->nama }}</span>
            </p>
        @endif
    </div>
@endsection
