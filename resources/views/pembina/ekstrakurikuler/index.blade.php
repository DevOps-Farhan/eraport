@extends('layouts.pembina')

@section('title', 'Kelola Ekstrakurikuler')

@section('content')
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-slate-900">Kelola Ekstrakurikuler</h1>
            <p class="mt-1 text-sm text-slate-500">Pilih ekstrakurikuler yang Anda bina untuk mengelola anggotanya.</p>
        </div>
    </div>

    @if ($ekstrakurikulerList->isEmpty())
        <div class="rounded-2xl border border-slate-200 bg-white p-10 text-center shadow-sm">
            <svg class="mx-auto h-12 w-12 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.182 15.182a4.5 4.5 0 0 1-6.364 0M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0ZM9.75 9.75c0 .414-.168.75-.375.75S9 10.164 9 9.75 9.168 9 9.375 9s.375.336.375.75Zm-.375 0h.008v.015h-.008V9.75Zm5.625 0c0 .414-.168.75-.375.75s-.375-.336-.375-.75.168-.75.375-.75.375.336.375.75Zm-.375 0h.008v.015h-.008V9.75Z" />
            </svg>
            <h3 class="mt-4 text-sm font-medium text-slate-900">Belum ada tugas pembina</h3>
            <p class="mt-1 text-sm text-slate-500">Anda belum ditugaskan untuk membina ekstrakurikuler apapun pada tahun ajaran ini.</p>
        </div>
    @else
        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($ekstrakurikulerList as $ekstrakurikuler)
                <div class="flex flex-col overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition hover:shadow-md">
                    <div class="flex-1 p-6">
                        <div class="flex items-start justify-between">
                            <div>
                                <h3 class="text-lg font-semibold text-slate-900">{{ $ekstrakurikuler->nama }}</h3>
                                <p class="mt-1 text-sm font-medium text-slate-500">Kode: {{ $ekstrakurikuler->kode }}</p>
                            </div>
                            <span class="inline-flex items-center rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-medium text-emerald-700">
                                {{ $ekstrakurikuler->anggota_count }} Anggota
                            </span>
                        </div>
                    </div>
                    <div class="border-t border-slate-100 bg-slate-50 px-6 py-4">
                        <a href="{{ route('pembina.ekstrakurikuler.show', $ekstrakurikuler) }}" class="inline-flex w-full items-center justify-center rounded-xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700">
                            Kelola Anggota
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
@endsection
