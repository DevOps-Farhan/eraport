@extends('layouts.admin-pusat')

@section('title', 'Kelola Cabang Sekolah')

@section('content')
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-slate-900">Kelola Semua Cabang</h1>
            <p class="mt-1 text-sm text-slate-500">Tambah, ubah, dan lihat detail cabang sekolah.</p>
        </div>
        <button
            type="button"
            data-modal-open="modal-tambah-cabang"
            class="inline-flex items-center justify-center rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700"
        >
            + Tambah Cabang
        </button>
    </div>

    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Kode</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Nama</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Kepala Sekolah</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Pengguna</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Status</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-slate-500">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($cabangSekolah as $cabang)
                        <tr class="hover:bg-slate-50">
                            <td class="whitespace-nowrap px-4 py-3 text-sm font-medium text-slate-900">{{ $cabang->kode }}</td>
                            <td class="px-4 py-3 text-sm text-slate-700">{{ $cabang->nama }}</td>
                            <td class="px-4 py-3 text-sm text-slate-700">{{ $cabang->kepala_sekolah }}</td>
                            <td class="px-4 py-3 text-sm text-slate-600">{{ $cabang->pengguna_count }}</td>
                            <td class="px-4 py-3">
                                @if ($cabang->aktif)
                                    <span class="inline-flex rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-700">Aktif</span>
                                @else
                                    <span class="inline-flex rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-medium text-slate-600">Nonaktif</span>
                                @endif
                            </td>
                            <td class="whitespace-nowrap px-4 py-3 text-right text-sm">
                                <div class="flex items-center justify-end gap-2">
                                    <button
                                        type="button"
                                        class="rounded-lg px-2.5 py-1.5 text-indigo-600 transition hover:bg-indigo-50"
                                        data-modal-open="modal-detail-cabang"
                                        data-cabang='@json($cabang)'
                                    >
                                        Detail
                                    </button>
                                    <button
                                        type="button"
                                        class="rounded-lg px-2.5 py-1.5 text-slate-600 transition hover:bg-slate-100"
                                        data-modal-open="modal-edit-cabang"
                                        data-cabang='@json($cabang)'
                                    >
                                        Edit
                                    </button>
                                    <button
                                        type="button"
                                        class="rounded-lg px-2.5 py-1.5 text-red-600 transition hover:bg-red-50"
                                        data-modal-open="modal-hapus-cabang"
                                        data-cabang='@json($cabang)'
                                    >
                                        Hapus
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-10 text-center text-sm text-slate-500">
                                Belum ada cabang sekolah. Klik "Tambah Cabang" untuk menambahkan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($cabangSekolah->hasPages())
            <div class="border-t border-slate-200 px-4 py-3">
                {{ $cabangSekolah->links() }}
            </div>
        @endif
    </div>
@endsection

@push('modals')
    {{-- Modal Tambah --}}
    <div id="modal-tambah-cabang" class="modal hidden" aria-hidden="true">
        <div class="modal-backdrop fixed inset-0 z-40 bg-slate-900/50" data-modal-close></div>
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="w-full max-w-lg rounded-2xl bg-white shadow-xl flex flex-col max-h-[95vh] sm:max-h-[90vh]" role="dialog" aria-modal="true">
                <div class="border-b border-slate-200 px-6 py-4 shrink-0">
                    <h2 class="text-lg font-semibold text-slate-900">Tambah Cabang Sekolah</h2>
                </div>
                <form method="POST" action="{{ route('admin-pusat.cabang-sekolah.store') }}" class="flex flex-col flex-1 min-h-0">
                    @csrf
                    <div class="overflow-y-auto px-6 py-4 flex-1">
                        @include('admin-pusat.cabang-sekolah._form-fields', ['prefix' => 'tambah'])
                    </div>
                    <div class="shrink-0 flex justify-end gap-3 border-t border-slate-200 px-6 py-4">
                        <button type="button" data-modal-close class="rounded-xl border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">Batal</button>
                        <button type="submit" class="rounded-xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal Edit --}}
    <div id="modal-edit-cabang" class="modal hidden" aria-hidden="true">
        <div class="modal-backdrop fixed inset-0 z-40 bg-slate-900/50" data-modal-close></div>
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="w-full max-w-lg rounded-2xl bg-white shadow-xl flex flex-col max-h-[95vh] sm:max-h-[90vh]" role="dialog" aria-modal="true">
                <div class="border-b border-slate-200 px-6 py-4 shrink-0">
                    <h2 class="text-lg font-semibold text-slate-900">Edit Cabang Sekolah</h2>
                </div>
                <form id="form-edit-cabang" method="POST" action="" class="flex flex-col flex-1 min-h-0">
                    @csrf
                    @method('PUT')
                    <div class="overflow-y-auto px-6 py-4 flex-1">
                        @include('admin-pusat.cabang-sekolah._form-fields', ['prefix' => 'edit', 'readonly' => false])
                    </div>
                    <div class="shrink-0 flex justify-end gap-3 border-t border-slate-200 px-6 py-4">
                        <button type="button" data-modal-close class="rounded-xl border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">Batal</button>
                        <button type="submit" class="rounded-xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700">Perbarui</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal Detail --}}
    <div id="modal-detail-cabang" class="modal hidden" aria-hidden="true">
        <div class="modal-backdrop fixed inset-0 z-40 bg-slate-900/50" data-modal-close></div>
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="w-full max-w-lg rounded-2xl bg-white shadow-xl flex flex-col max-h-[95vh] sm:max-h-[90vh]" role="dialog" aria-modal="true">
                <div class="border-b border-slate-200 px-6 py-4 shrink-0">
                    <h2 class="text-lg font-semibold text-slate-900">Detail Cabang Sekolah</h2>
                </div>
                <div class="flex flex-col flex-1 min-h-0">
                    <div class="overflow-y-auto px-6 py-4 flex-1">
                        @include('admin-pusat.cabang-sekolah._form-fields', ['prefix' => 'detail', 'readonly' => true])
                    </div>
                    <div class="shrink-0 flex justify-end border-t border-slate-200 px-6 py-4">
                        <button type="button" data-modal-close class="rounded-xl border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Hapus --}}
    <div id="modal-hapus-cabang" class="modal hidden" aria-hidden="true">
        <div class="modal-backdrop fixed inset-0 z-40 bg-slate-900/50" data-modal-close></div>
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="w-full max-w-md rounded-2xl bg-white shadow-xl" role="dialog" aria-modal="true">
                <div class="border-b border-slate-200 px-6 py-4">
                    <h2 class="text-lg font-semibold text-slate-900">Hapus Cabang Sekolah</h2>
                </div>
                <form id="form-hapus-cabang" method="POST" action="" class="px-6 py-4">
                    @csrf
                    @method('DELETE')
                    <p class="text-sm text-slate-600">
                        Yakin ingin menghapus cabang <span id="hapus-cabang-nama" class="font-semibold text-slate-900"></span>?
                        Cabang yang masih memiliki pengguna tidak dapat dihapus.
                    </p>
                    <div class="mt-6 flex justify-end gap-3 border-t border-slate-200 pt-4">
                        <button type="button" data-modal-close class="rounded-xl border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">Batal</button>
                        <button type="submit" class="rounded-xl bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-700">Hapus</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endpush

@if ($errors->any())
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                document.getElementById('modal-tambah-cabang')?.classList.remove('hidden');
                document.body.classList.add('overflow-hidden');
            });
        </script>
    @endpush
@endif
