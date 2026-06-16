@extends('layouts.admin-cabang')

@section('title', $judul)

@section('content')
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-slate-900">Kelola {{ $judul }}</h1>
            <p class="mt-1 text-sm text-slate-500">Tambah, ubah, dan kelola {{ strtolower($judul) }} di cabang Anda.</p>
        </div>
        <button type="button" data-modal-open="modal-tambah-pengguna-cabang" class="inline-flex items-center justify-center rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700">
            + Tambah {{ $judul }}
        </button>
    </div>

    <div class="mb-6 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
        <form method="GET" action="{{ route($routeName) }}" class="flex flex-col gap-3 sm:flex-row">
            <div class="flex-1">
                <input name="cari" type="search" value="{{ $cari }}" placeholder="Cari username, nama, atau email..." class="block w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm">
            </div>
            <div class="flex gap-2">
                <button type="submit" class="rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-emerald-700">Cari</button>
                @if ($cari !== '')
                    <a href="{{ route($routeName) }}" class="rounded-xl border border-slate-300 px-4 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-50">Reset</a>
                @endif
            </div>
        </form>
    </div>

    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <table class="min-w-full divide-y divide-slate-200">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">Username</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">Nama</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">Email</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">Status</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold uppercase text-slate-500">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($pengguna as $item)
                    <tr class="hover:bg-slate-50">
                        <td class="px-4 py-3 text-sm font-medium">{{ $item->username }}</td>
                        <td class="px-4 py-3 text-sm">{{ $item->nama }}</td>
                        <td class="px-4 py-3 text-sm">{{ $item->email ?? '—' }}</td>
                        <td class="px-4 py-3">@include('partials.badge-aktif', ['aktif' => $item->aktif])</td>
                        <td class="px-4 py-3 text-right text-sm">
                            <button type="button" class="rounded-lg px-2.5 py-1.5 text-emerald-600 hover:bg-emerald-50" data-modal-open="modal-detail-pengguna-cabang" data-pengguna-cabang='@json($item)' data-tipe="{{ $tipe }}">Detail</button>
                            <button type="button" class="rounded-lg px-2.5 py-1.5 text-slate-600 hover:bg-slate-100" data-modal-open="modal-edit-pengguna-cabang" data-pengguna-cabang='@json($item)' data-tipe="{{ $tipe }}">Edit</button>
                            <button type="button" class="rounded-lg px-2.5 py-1.5 text-red-600 hover:bg-red-50" data-modal-open="modal-hapus-pengguna-cabang" data-pengguna-cabang='@json($item)' data-tipe="{{ $tipe }}">Hapus</button>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-4 py-10 text-center text-sm text-slate-500">Belum ada data {{ strtolower($judul) }}.</td></tr>
                @endforelse
            </tbody>
        </table>
        @if ($pengguna->hasPages())<div class="border-t px-4 py-3">{{ $pengguna->links() }}</div>@endif
    </div>
@endsection

@push('modals')
    <div id="modal-tambah-pengguna-cabang" class="modal hidden" aria-hidden="true">
        <div class="modal-backdrop fixed inset-0 z-40 bg-slate-900/50" data-modal-close></div>
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="max-h-[90vh] w-full max-w-lg overflow-y-auto rounded-2xl bg-white shadow-xl">
                <div class="border-b px-6 py-4"><h2 class="text-lg font-semibold">Tambah {{ $judul }}</h2></div>
                <form method="POST" action="{{ route('admin-cabang.master-data.'.$tipe.'.store') }}" class="px-6 py-4">
                    @csrf
                    @include('admin-cabang.master-data.pengguna._form-fields', ['prefix' => 'tambah'])
                    <div class="mt-6 flex justify-end gap-3 border-t pt-4">
                        <button type="button" data-modal-close class="rounded-xl border px-4 py-2 text-sm">Batal</button>
                        <button type="submit" class="rounded-xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div id="modal-edit-pengguna-cabang" class="modal hidden" aria-hidden="true">
        <div class="modal-backdrop fixed inset-0 z-40 bg-slate-900/50" data-modal-close></div>
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="max-h-[90vh] w-full max-w-lg overflow-y-auto rounded-2xl bg-white shadow-xl">
                <div class="border-b px-6 py-4"><h2 class="text-lg font-semibold">Edit {{ $judul }}</h2></div>
                <form id="form-edit-pengguna-cabang" method="POST" action="" class="px-6 py-4">
                    @csrf @method('PUT')
                    @include('admin-cabang.master-data.pengguna._form-fields', ['prefix' => 'edit', 'passwordOptional' => true])
                    <div class="mt-6 flex justify-end gap-3 border-t pt-4">
                        <button type="button" data-modal-close class="rounded-xl border px-4 py-2 text-sm">Batal</button>
                        <button type="submit" class="rounded-xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white">Perbarui</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div id="modal-detail-pengguna-cabang" class="modal hidden" aria-hidden="true">
        <div class="modal-backdrop fixed inset-0 z-40 bg-slate-900/50" data-modal-close></div>
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="max-h-[90vh] w-full max-w-lg overflow-y-auto rounded-2xl bg-white shadow-xl">
                <div class="border-b px-6 py-4"><h2 class="text-lg font-semibold">Detail {{ $judul }}</h2></div>
                <div class="px-6 py-4">
                    @include('admin-cabang.master-data.pengguna._form-fields', ['prefix' => 'detail', 'readonly' => true, 'showPassword' => false])
                    <div class="mt-6 flex justify-end border-t pt-4"><button type="button" data-modal-close class="rounded-xl border px-4 py-2 text-sm">Tutup</button></div>
                </div>
            </div>
        </div>
    </div>
    <div id="modal-hapus-pengguna-cabang" class="modal hidden" aria-hidden="true">
        <div class="modal-backdrop fixed inset-0 z-40 bg-slate-900/50" data-modal-close></div>
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="w-full max-w-md rounded-2xl bg-white shadow-xl">
                <div class="border-b px-6 py-4"><h2 class="text-lg font-semibold">Hapus Pengguna</h2></div>
                <form id="form-hapus-pengguna-cabang" method="POST" action="" class="px-6 py-4">
                    @csrf @method('DELETE')
                    <p class="text-sm text-slate-600">Yakin ingin menghapus <span id="hapus-pengguna-cabang-nama" class="font-semibold"></span>?</p>
                    <div class="mt-6 flex justify-end gap-3 border-t pt-4">
                        <button type="button" data-modal-close class="rounded-xl border px-4 py-2 text-sm">Batal</button>
                        <button type="submit" class="rounded-xl bg-red-600 px-4 py-2 text-sm font-semibold text-white">Hapus</button>
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
                document.getElementById('modal-tambah-pengguna-cabang')?.classList.remove('hidden');
                document.body.classList.add('overflow-hidden');
            });
        </script>
    @endpush
@endif
