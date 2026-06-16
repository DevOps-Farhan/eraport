@extends('layouts.admin-cabang')

@section('title', 'Jurusan')

@section('content')
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-slate-900">Jurusan</h1>
            <p class="mt-1 text-sm text-slate-500">Kelola program keahlian / jurusan di cabang sekolah.</p>
        </div>
        <button type="button" data-modal-open="modal-tambah-jurusan" class="inline-flex items-center justify-center rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700">
            + Tambah Jurusan
        </button>
    </div>

    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Kode</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Nama</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Status</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-slate-500">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($jurusan as $item)
                        <tr class="hover:bg-slate-50">
                            <td class="whitespace-nowrap px-4 py-3 text-sm font-medium text-slate-900">{{ $item->kode }}</td>
                            <td class="px-4 py-3 text-sm text-slate-700">{{ $item->nama }}</td>
                            <td class="px-4 py-3">@include('partials.badge-aktif', ['aktif' => $item->aktif])</td>
                            <td class="whitespace-nowrap px-4 py-3 text-right text-sm">
                                <div class="flex items-center justify-end gap-2">
                                    <button type="button" class="rounded-lg px-2.5 py-1.5 text-emerald-600 transition hover:bg-emerald-50" data-modal-open="modal-detail-jurusan" data-jurusan='@json($item)'>Detail</button>
                                    <button type="button" class="rounded-lg px-2.5 py-1.5 text-slate-600 transition hover:bg-slate-100" data-modal-open="modal-edit-jurusan" data-jurusan='@json($item)'>Edit</button>
                                    <button type="button" class="rounded-lg px-2.5 py-1.5 text-red-600 transition hover:bg-red-50" data-modal-open="modal-hapus-jurusan" data-jurusan='@json($item)'>Hapus</button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="px-4 py-10 text-center text-sm text-slate-500">Belum ada jurusan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($jurusan->hasPages())
            <div class="border-t border-slate-200 px-4 py-3">{{ $jurusan->links() }}</div>
        @endif
    </div>
@endsection

@push('modals')
    <div id="modal-tambah-jurusan" class="modal hidden" aria-hidden="true">
        <div class="modal-backdrop fixed inset-0 z-40 bg-slate-900/50" data-modal-close></div>
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="w-full max-w-lg rounded-2xl bg-white shadow-xl">
                <div class="border-b border-slate-200 px-6 py-4"><h2 class="text-lg font-semibold text-slate-900">Tambah Jurusan</h2></div>
                <form method="POST" action="{{ route('admin-cabang.data-akademik.jurusan.store') }}" class="px-6 py-4">
                    @csrf
                    @include('admin-cabang.data-akademik.jurusan._form-fields', ['prefix' => 'tambah'])
                    <div class="mt-6 flex justify-end gap-3 border-t border-slate-200 pt-4">
                        <button type="button" data-modal-close class="rounded-xl border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">Batal</button>
                        <button type="submit" class="rounded-xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div id="modal-edit-jurusan" class="modal hidden" aria-hidden="true">
        <div class="modal-backdrop fixed inset-0 z-40 bg-slate-900/50" data-modal-close></div>
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="w-full max-w-lg rounded-2xl bg-white shadow-xl">
                <div class="border-b border-slate-200 px-6 py-4"><h2 class="text-lg font-semibold text-slate-900">Edit Jurusan</h2></div>
                <form id="form-edit-jurusan" method="POST" action="" class="px-6 py-4">
                    @csrf @method('PUT')
                    @include('admin-cabang.data-akademik.jurusan._form-fields', ['prefix' => 'edit'])
                    <div class="mt-6 flex justify-end gap-3 border-t border-slate-200 pt-4">
                        <button type="button" data-modal-close class="rounded-xl border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">Batal</button>
                        <button type="submit" class="rounded-xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700">Perbarui</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div id="modal-detail-jurusan" class="modal hidden" aria-hidden="true">
        <div class="modal-backdrop fixed inset-0 z-40 bg-slate-900/50" data-modal-close></div>
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="w-full max-w-lg rounded-2xl bg-white shadow-xl">
                <div class="border-b border-slate-200 px-6 py-4"><h2 class="text-lg font-semibold text-slate-900">Detail Jurusan</h2></div>
                <div class="px-6 py-4">
                    @include('admin-cabang.data-akademik.jurusan._form-fields', ['prefix' => 'detail', 'readonly' => true])
                    <div class="mt-6 flex justify-end border-t border-slate-200 pt-4">
                        <button type="button" data-modal-close class="rounded-xl border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="modal-hapus-jurusan" class="modal hidden" aria-hidden="true">
        <div class="modal-backdrop fixed inset-0 z-40 bg-slate-900/50" data-modal-close></div>
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="w-full max-w-md rounded-2xl bg-white shadow-xl">
                <div class="border-b border-slate-200 px-6 py-4"><h2 class="text-lg font-semibold text-slate-900">Hapus Jurusan</h2></div>
                <form id="form-hapus-jurusan" method="POST" action="" class="px-6 py-4">
                    @csrf @method('DELETE')
                    <p class="text-sm text-slate-600">Yakin ingin menghapus jurusan <span id="hapus-jurusan-nama" class="font-semibold text-slate-900"></span>?</p>
                    <div class="mt-6 flex justify-end gap-3 border-t border-slate-200 pt-4">
                        <button type="button" data-modal-close class="rounded-xl border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">Batal</button>
                        <button type="submit" class="rounded-xl bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-700">Hapus</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endpush
