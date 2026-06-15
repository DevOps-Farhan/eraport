@extends('layouts.admin-pusat')

@section('title', $judul)

@section('content')
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-slate-900">Kelola {{ $judul }}</h1>
            <p class="mt-1 text-sm text-slate-500">Tambah, ubah, dan kelola pengguna {{ strtolower($judul) }} di seluruh cabang.</p>
        </div>
        <button
            type="button"
            data-modal-open="modal-tambah-pengguna"
            class="inline-flex items-center justify-center rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700"
        >
            + Tambah {{ $judul }}
        </button>
    </div>

    <div class="mb-6 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
        <form method="GET" action="{{ route($routeName) }}" class="flex flex-col gap-3 sm:flex-row">
            <div class="flex-1">
                <label for="cari" class="sr-only">Cari pengguna</label>
                <input
                    id="cari"
                    name="cari"
                    type="search"
                    value="{{ $cari }}"
                    placeholder="Cari berdasarkan username, nama, atau email..."
                    class="block w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200"
                >
            </div>
            <div class="flex gap-2">
                <button
                    type="submit"
                    class="rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-indigo-700"
                >
                    Cari
                </button>
                @if ($cari !== '')
                    <a
                        href="{{ route($routeName) }}"
                        class="rounded-xl border border-slate-300 px-4 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-50"
                    >
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Username</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Nama</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Email</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Cabang</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Status</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-slate-500">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($pengguna as $item)
                        <tr class="hover:bg-slate-50">
                            <td class="whitespace-nowrap px-4 py-3 text-sm font-medium text-slate-900">{{ $item->username }}</td>
                            <td class="px-4 py-3 text-sm text-slate-700">{{ $item->nama }}</td>
                            <td class="px-4 py-3 text-sm text-slate-600">{{ $item->email ?? '—' }}</td>
                            <td class="px-4 py-3 text-sm text-slate-600">{{ $item->cabangSekolah?->nama ?? '—' }}</td>
                            <td class="px-4 py-3">
                                @if ($item->aktif)
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
                                        data-modal-open="modal-detail-pengguna"
                                        data-pengguna='@json($item->loadMissing('cabangSekolah'))'
                                        data-tipe="{{ $tipe }}"
                                    >
                                        Detail
                                    </button>
                                    <button
                                        type="button"
                                        class="rounded-lg px-2.5 py-1.5 text-slate-600 transition hover:bg-slate-100"
                                        data-modal-open="modal-edit-pengguna"
                                        data-pengguna='@json($item->loadMissing('cabangSekolah'))'
                                        data-tipe="{{ $tipe }}"
                                    >
                                        Edit
                                    </button>
                                    <button
                                        type="button"
                                        class="rounded-lg px-2.5 py-1.5 text-red-600 transition hover:bg-red-50"
                                        data-modal-open="modal-hapus-pengguna"
                                        data-pengguna='@json($item)'
                                        data-tipe="{{ $tipe }}"
                                    >
                                        Hapus
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-10 text-center text-sm text-slate-500">
                                @if ($cari !== '')
                                    Tidak ditemukan pengguna dengan kata kunci "{{ $cari }}".
                                @else
                                    Belum ada data {{ strtolower($judul) }}.
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($pengguna->hasPages())
            <div class="border-t border-slate-200 px-4 py-3">
                {{ $pengguna->links() }}
            </div>
        @endif
    </div>
@endsection

@push('modals')
    {{-- Modal Tambah --}}
    <div id="modal-tambah-pengguna" class="modal hidden" aria-hidden="true">
        <div class="modal-backdrop fixed inset-0 z-40 bg-slate-900/50" data-modal-close></div>
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="max-h-[90vh] w-full max-w-lg overflow-y-auto rounded-2xl bg-white shadow-xl" role="dialog" aria-modal="true">
                <div class="border-b border-slate-200 px-6 py-4">
                    <h2 class="text-lg font-semibold text-slate-900">Tambah {{ $judul }}</h2>
                </div>
                <form method="POST" action="{{ route('admin-pusat.pengguna.'.$tipe.'.store') }}" class="px-6 py-4">
                    @csrf
                    @include('admin-pusat.pengguna._form-fields', ['prefix' => 'tambah'])
                    <div class="mt-6 flex justify-end gap-3 border-t border-slate-200 pt-4">
                        <button type="button" data-modal-close class="rounded-xl border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">Batal</button>
                        <button type="submit" class="rounded-xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal Edit --}}
    <div id="modal-edit-pengguna" class="modal hidden" aria-hidden="true">
        <div class="modal-backdrop fixed inset-0 z-40 bg-slate-900/50" data-modal-close></div>
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="max-h-[90vh] w-full max-w-lg overflow-y-auto rounded-2xl bg-white shadow-xl" role="dialog" aria-modal="true">
                <div class="border-b border-slate-200 px-6 py-4">
                    <h2 class="text-lg font-semibold text-slate-900">Edit {{ $judul }}</h2>
                </div>
                <form id="form-edit-pengguna" method="POST" action="" class="px-6 py-4">
                    @csrf
                    @method('PUT')
                    @include('admin-pusat.pengguna._form-fields', ['prefix' => 'edit', 'passwordOptional' => true])
                    <div class="mt-6 flex justify-end gap-3 border-t border-slate-200 pt-4">
                        <button type="button" data-modal-close class="rounded-xl border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">Batal</button>
                        <button type="submit" class="rounded-xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700">Perbarui</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal Detail --}}
    <div id="modal-detail-pengguna" class="modal hidden" aria-hidden="true">
        <div class="modal-backdrop fixed inset-0 z-40 bg-slate-900/50" data-modal-close></div>
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="max-h-[90vh] w-full max-w-lg overflow-y-auto rounded-2xl bg-white shadow-xl" role="dialog" aria-modal="true">
                <div class="border-b border-slate-200 px-6 py-4">
                    <h2 class="text-lg font-semibold text-slate-900">Detail {{ $judul }}</h2>
                </div>
                <div class="px-6 py-4">
                    @include('admin-pusat.pengguna._form-fields', ['prefix' => 'detail', 'readonly' => true, 'showPassword' => false])
                    <div class="mt-6 flex justify-end border-t border-slate-200 pt-4">
                        <button type="button" data-modal-close class="rounded-xl border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Hapus --}}
    <div id="modal-hapus-pengguna" class="modal hidden" aria-hidden="true">
        <div class="modal-backdrop fixed inset-0 z-40 bg-slate-900/50" data-modal-close></div>
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="w-full max-w-md rounded-2xl bg-white shadow-xl" role="dialog" aria-modal="true">
                <div class="border-b border-slate-200 px-6 py-4">
                    <h2 class="text-lg font-semibold text-slate-900">Hapus Pengguna</h2>
                </div>
                <form id="form-hapus-pengguna" method="POST" action="" class="px-6 py-4">
                    @csrf
                    @method('DELETE')
                    <p class="text-sm text-slate-600">
                        Yakin ingin menghapus pengguna <span id="hapus-pengguna-nama" class="font-semibold text-slate-900"></span>?
                        Tindakan ini tidak dapat dibatalkan.
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
                document.getElementById('modal-tambah-pengguna')?.classList.remove('hidden');
                document.body.classList.add('overflow-hidden');
            });
        </script>
    @endpush
@endif
