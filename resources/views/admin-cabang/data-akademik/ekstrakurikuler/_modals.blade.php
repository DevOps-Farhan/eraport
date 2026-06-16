<div id="modal-tambah-ekstrakurikuler" class="modal hidden" aria-hidden="true">
    <div class="modal-backdrop fixed inset-0 z-40 bg-slate-900/50" data-modal-close></div>
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="w-full max-w-lg rounded-2xl bg-white shadow-xl">
            <div class="border-b px-6 py-4"><h2 class="text-lg font-semibold">Tambah Ekstrakurikuler</h2></div>
            <form method="POST" action="{{ route('admin-cabang.data-akademik.ekstrakurikuler.store') }}" class="px-6 py-4">
                @csrf
                @include('admin-cabang.data-akademik.ekstrakurikuler._form-fields', ['prefix' => 'tambah'])
                <div class="mt-6 flex justify-end gap-3 border-t pt-4">
                    <button type="button" data-modal-close class="rounded-xl border px-4 py-2 text-sm">Batal</button>
                    <button type="submit" class="rounded-xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div id="modal-edit-ekstrakurikuler" class="modal hidden" aria-hidden="true">
    <div class="modal-backdrop fixed inset-0 z-40 bg-slate-900/50" data-modal-close></div>
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="w-full max-w-lg rounded-2xl bg-white shadow-xl">
            <div class="border-b px-6 py-4"><h2 class="text-lg font-semibold">Edit Ekstrakurikuler</h2></div>
            <form id="form-edit-ekstrakurikuler" method="POST" action="" class="px-6 py-4">
                @csrf @method('PUT')
                @include('admin-cabang.data-akademik.ekstrakurikuler._form-fields', ['prefix' => 'edit'])
                <div class="mt-6 flex justify-end gap-3 border-t pt-4">
                    <button type="button" data-modal-close class="rounded-xl border px-4 py-2 text-sm">Batal</button>
                    <button type="submit" class="rounded-xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white">Perbarui</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div id="modal-detail-ekstrakurikuler" class="modal hidden" aria-hidden="true">
    <div class="modal-backdrop fixed inset-0 z-40 bg-slate-900/50" data-modal-close></div>
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="w-full max-w-lg rounded-2xl bg-white shadow-xl">
            <div class="border-b px-6 py-4"><h2 class="text-lg font-semibold">Detail Ekstrakurikuler</h2></div>
            <div class="px-6 py-4">
                @include('admin-cabang.data-akademik.ekstrakurikuler._form-fields', ['prefix' => 'detail', 'readonly' => true])
                <div class="mt-6 flex justify-end border-t pt-4"><button type="button" data-modal-close class="rounded-xl border px-4 py-2 text-sm">Tutup</button></div>
            </div>
        </div>
    </div>
</div>
<div id="modal-hapus-ekstrakurikuler" class="modal hidden" aria-hidden="true">
    <div class="modal-backdrop fixed inset-0 z-40 bg-slate-900/50" data-modal-close></div>
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="w-full max-w-md rounded-2xl bg-white shadow-xl">
            <div class="border-b px-6 py-4"><h2 class="text-lg font-semibold">Hapus Ekstrakurikuler</h2></div>
            <form id="form-hapus-ekstrakurikuler" method="POST" action="" class="px-6 py-4">
                @csrf @method('DELETE')
                <p class="text-sm text-slate-600">Yakin ingin menghapus <span id="hapus-ekstrakurikuler-nama" class="font-semibold"></span>?</p>
                <div class="mt-6 flex justify-end gap-3 border-t pt-4">
                    <button type="button" data-modal-close class="rounded-xl border px-4 py-2 text-sm">Batal</button>
                    <button type="submit" class="rounded-xl bg-red-600 px-4 py-2 text-sm font-semibold text-white">Hapus</button>
                </div>
            </form>
        </div>
    </div>
</div>
