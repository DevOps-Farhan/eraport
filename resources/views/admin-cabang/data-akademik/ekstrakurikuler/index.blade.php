@extends('layouts.admin-cabang')

@section('title', 'Ekstrakurikuler')

@section('content')
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-slate-900">Ekstrakurikuler</h1>
            <p class="mt-1 text-sm text-slate-500">Kelola kegiatan ekstrakurikuler dan penugasan pembina per tahun ajaran.</p>
        </div>
        <button type="button" data-modal-open="modal-tambah-ekstrakurikuler" class="inline-flex items-center justify-center rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700">+ Tambah Ekstrakurikuler</button>
    </div>

    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="border-b border-slate-200 px-4 py-3"><h2 class="text-sm font-semibold text-slate-900">Daftar Ekstrakurikuler</h2></div>
        <table class="min-w-full divide-y divide-slate-200">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">Kode</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">Nama</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">Status</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold uppercase text-slate-500">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($ekstrakurikuler as $item)
                    <tr class="hover:bg-slate-50">
                        <td class="px-4 py-3 text-sm font-medium">{{ $item->kode }}</td>
                        <td class="px-4 py-3 text-sm">{{ $item->nama }}</td>
                        <td class="px-4 py-3">@include('partials.badge-aktif', ['aktif' => $item->aktif])</td>
                        <td class="px-4 py-3 text-right text-sm">
                            <button type="button" class="rounded-lg px-2.5 py-1.5 text-emerald-600 hover:bg-emerald-50" data-modal-open="modal-detail-ekstrakurikuler" data-ekstrakurikuler='@json($item)'>Detail</button>
                            <button type="button" class="rounded-lg px-2.5 py-1.5 text-slate-600 hover:bg-slate-100" data-modal-open="modal-edit-ekstrakurikuler" data-ekstrakurikuler='@json($item)'>Edit</button>
                            <button type="button" class="rounded-lg px-2.5 py-1.5 text-red-600 hover:bg-red-50" data-modal-open="modal-hapus-ekstrakurikuler" data-ekstrakurikuler='@json($item)'>Hapus</button>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="px-4 py-10 text-center text-sm text-slate-500">Belum ada ekstrakurikuler.</td></tr>
                @endforelse
            </tbody>
        </table>
        @if ($ekstrakurikuler->hasPages())<div class="border-t px-4 py-3">{{ $ekstrakurikuler->links() }}</div>@endif
    </div>

@endsection

@push('modals')
@include('admin-cabang.data-akademik.ekstrakurikuler._modals')
@endpush
