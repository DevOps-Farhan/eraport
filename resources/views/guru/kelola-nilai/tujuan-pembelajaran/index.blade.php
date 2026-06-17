@extends('layouts.guru')

@section('title', 'Tujuan Pembelajaran')

@section('content')
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-slate-900">Tujuan Pembelajaran</h1>
            <p class="mt-1 text-sm text-slate-500">Kelola tujuan pembelajaran untuk mata pelajaran yang Anda ampu.</p>
        </div>
        @if ($penugasanAktif && $dapatDiedit)
            <button type="button" data-modal-open="modal-tambah-tp" class="inline-flex items-center justify-center rounded-xl bg-sky-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-sky-700">+ Tambah TP</button>
        @endif
    </div>

    @if ($penugasanList->isEmpty())
        <div class="rounded-2xl border border-amber-200 bg-amber-50 px-6 py-10 text-center text-sm text-amber-800">
            Belum ada penugasan mengajar untuk Anda di tahun ajaran aktif.
        </div>
    @else
        <form method="GET" class="mb-6">
            <label class="block text-sm font-medium text-slate-700">Penugasan Mengajar</label>
            <select name="penugasan" onchange="this.form.submit()" class="mt-1 w-full max-w-md rounded-xl border border-slate-300 px-3 py-2 text-sm">
                @foreach ($penugasanList as $item)
                    <option value="{{ $item->id }}" @selected($penugasanAktif?->id === $item->id)>
                        {{ $item->mataPelajaran->nama }} — {{ $item->kelas->nama }}
                    </option>
                @endforeach
            </select>
        </form>

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
    @endif
@endsection

@push('modals')
    @if ($penugasanAktif && $dapatDiedit)
        @include('guru.kelola-nilai.tujuan-pembelajaran._modals', ['penugasanAktif' => $penugasanAktif])
    @endif
@endpush
