@extends('layouts.admin-pusat')

@section('title', 'Dashboard')

@section('content')
    <div class="mb-8">
        <h1 class="text-2xl font-semibold text-slate-900">Dashboard</h1>
        <p class="mt-2 text-slate-600">
            Selamat datang kembali, <span class="font-medium text-slate-900">{{ auth()->user()->nama }}</span>.
            Kelola seluruh cabang sekolah dan pengguna dari panel ini.
        </p>
    </div>

    <div class="grid gap-4 sm:grid-cols-2">
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-500">Total Cabang Sekolah</p>
                    <p class="mt-2 text-3xl font-bold text-slate-900">{{ $jumlahCabang }}</p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008Zm0 3h.008v.008h-.008v-.008Zm0 3h.008v.008h-.008v-.008Z" />
                    </svg>
                </div>
            </div>
            <a href="{{ route('admin-pusat.cabang-sekolah.index') }}" class="mt-4 inline-flex text-sm font-medium text-indigo-600 hover:text-indigo-700">
                Kelola cabang →
            </a>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-500">Total Pengguna</p>
                    <p class="mt-2 text-3xl font-bold text-slate-900">{{ $jumlahPengguna }}</p>
                    <p class="mt-1 text-xs text-slate-400">Tidak termasuk admin pusat</p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex flex-wrap gap-4 text-sm">
                <a href="{{ route('admin-pusat.pengguna.admin-cabang') }}" class="font-medium text-indigo-600 hover:text-indigo-700">Admin Cabang →</a>
                <a href="{{ route('admin-pusat.pengguna.guru') }}" class="font-medium text-indigo-600 hover:text-indigo-700">Guru →</a>
                <a href="{{ route('admin-pusat.pengguna.pembina') }}" class="font-medium text-indigo-600 hover:text-indigo-700">Pembina →</a>
            </div>
        </div>
    </div>
@endsection
