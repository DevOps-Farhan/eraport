@extends('layouts.app')

@section('title', 'Admin Pusat')

@section('content')
    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
        <h1 class="text-xl font-semibold text-slate-900">Dashboard Admin Pusat</h1>
        <p class="mt-2 text-sm text-slate-600">
            Selamat datang, {{ auth()->user()->nama }}. Anda mengelola seluruh cabang sekolah.
        </p>
    </div>
@endsection
