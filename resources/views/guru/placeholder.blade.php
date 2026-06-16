@extends('layouts.guru')

@section('title', $judul)

@section('content')
    <div class="rounded-2xl border border-dashed border-slate-300 bg-white p-8 text-center shadow-sm">
        <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-sky-50 text-sky-600">
            <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17 17.25 21A2.652 2.652 0 0 0 21 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766M11.42 15.17l-4.655 5.653a2.548 2.548 0 1 1-3.586-3.586l6.837-5.672m8.338-2.15-.665-1.214a.75.75 0 0 0-1.036-.226l-1.17.877a.75.75 0 0 0-.226 1.036l.665 1.214a.75.75 0 0 0 1.036.226l1.17-.877a.75.75 0 0 0 .226-1.036Z" />
            </svg>
        </div>
        <h1 class="text-xl font-semibold text-slate-900">{{ $judul }}</h1>
        <p class="mx-auto mt-2 max-w-md text-sm text-slate-500">{{ $deskripsi }}</p>
        <span class="mt-4 inline-flex rounded-full bg-amber-50 px-3 py-1 text-xs font-medium text-amber-700">
            Halaman dalam pengembangan
        </span>
    </div>
@endsection
