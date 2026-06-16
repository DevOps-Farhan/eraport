@extends('layouts.admin-cabang')

@section('title', 'Mata Pelajaran')

@section('content')
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-slate-900">Mata Pelajaran</h1>
            <p class="mt-1 text-sm text-slate-500">Kelola daftar mata pelajaran cabang sekolah.</p>
        </div>
        <button type="button" data-modal-open="modal-tambah-mata-pelajaran" class="inline-flex items-center justify-center rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700">+ Tambah Mapel</button>
    </div>

    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <table class="min-w-full divide-y divide-slate-200">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">Kode</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">Nama</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">Kelompok</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">Status</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold uppercase text-slate-500">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($mataPelajaran as $item)
                    <tr class="hover:bg-slate-50">
                        <td class="px-4 py-3 text-sm font-medium">{{ $item->kode }}</td>
                        <td class="px-4 py-3 text-sm">{{ $item->nama }}</td>
                        <td class="px-4 py-3 text-sm">{{ $item->kelompok->label() }}</td>
                        <td class="px-4 py-3">@include('partials.badge-aktif', ['aktif' => $item->aktif])</td>
                        <td class="px-4 py-3 text-right text-sm">
                            <button type="button" class="rounded-lg px-2.5 py-1.5 text-emerald-600 hover:bg-emerald-50" data-modal-open="modal-detail-mata-pelajaran" data-mata-pelajaran='@json($item)'>Detail</button>
                            <button type="button" class="rounded-lg px-2.5 py-1.5 text-slate-600 hover:bg-slate-100" data-modal-open="modal-edit-mata-pelajaran" data-mata-pelajaran='@json($item)'>Edit</button>
                            <button type="button" class="rounded-lg px-2.5 py-1.5 text-red-600 hover:bg-red-50" data-modal-open="modal-hapus-mata-pelajaran" data-mata-pelajaran='@json($item)'>Hapus</button>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-4 py-10 text-center text-sm text-slate-500">Belum ada mata pelajaran.</td></tr>
                @endforelse
            </tbody>
        </table>
        @if ($mataPelajaran->hasPages())<div class="border-t px-4 py-3">{{ $mataPelajaran->links() }}</div>@endif
    </div>
@endsection

@push('modals')
@include('admin-cabang.data-akademik.mata-pelajaran._modals')
@endpush
