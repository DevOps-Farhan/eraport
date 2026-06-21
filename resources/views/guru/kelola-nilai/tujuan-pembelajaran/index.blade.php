@extends('layouts.guru')

@section('title', 'Tujuan Pembelajaran')

@section('content')
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <a href="{{ route('guru.kelola-nilai.tujuan-pembelajaran') }}" class="mb-2 inline-flex items-center gap-2 text-sm font-medium text-slate-500 transition hover:text-slate-800">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                </svg>
                Kembali
            </a>
            <h1 class="text-2xl font-semibold text-slate-900">Tujuan Pembelajaran</h1>
            <p class="mt-1 text-sm text-slate-500">Kelola tujuan pembelajaran untuk mata pelajaran yang Anda ampu.</p>
        </div>
        @if ($dapatDiedit)
            <button type="button" data-modal-open="modal-tambah-tp" class="inline-flex items-center justify-center rounded-xl bg-sky-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-sky-700">+ Tambah TP</button>
        @endif
    </div>

    <div class="mb-4 rounded-xl border border-sky-200 bg-sky-50 px-4 py-3 text-sm text-sky-800">
        Mata Pelajaran: <strong>{{ $penugasanAktif->mataPelajaran->nama }}</strong> — Kelas: <strong>{{ $penugasanAktif->kelas->nama }}</strong>
    </div>



        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">Urutan</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">Kode</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">Deskripsi Kompetensi</th>
                        @if ($dapatDiedit)
                            <th class="px-4 py-3 text-right text-xs font-semibold uppercase text-slate-500">Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($tujuanPembelajaran as $item)
                        <tr class="hover:bg-slate-50">
                            <td class="px-4 py-3 text-sm">{{ $item->urutan }}</td>
                            <td class="px-4 py-3 text-sm font-medium">{{ $item->kode }}</td>
                            <td class="px-4 py-3 text-sm">{{ $item->deskripsi_kompetensi }}</td>
                            @if ($dapatDiedit)
                                <td class="px-4 py-3 text-right text-sm">
                                    <button type="button" class="rounded-lg px-2.5 py-1.5 text-slate-600 hover:bg-slate-100" data-modal-open="modal-edit-tp" data-tp='@json($item)'>Edit</button>
                                    <button type="button" class="rounded-lg px-2.5 py-1.5 text-red-600 hover:bg-red-50" data-modal-open="modal-hapus-tp" data-tp='@json($item)'>Hapus</button>
                                </td>
                            @endif
                        </tr>
                    @empty
                        <tr><td colspan="{{ $dapatDiedit ? 4 : 3 }}" class="px-4 py-10 text-center text-sm text-slate-500">Belum ada tujuan pembelajaran.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
@endsection

@push('modals')
    @if ($dapatDiedit)
        @include('guru.kelola-nilai.tujuan-pembelajaran._modals', ['penugasanAktif' => $penugasanAktif])
    @endif
@endpush
