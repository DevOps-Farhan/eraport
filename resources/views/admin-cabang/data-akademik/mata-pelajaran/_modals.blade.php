<div id="modal-tambah-mata-pelajaran" class="modal hidden" aria-hidden="true">
    <div class="modal-backdrop fixed inset-0 z-40 bg-slate-900/50" data-modal-close></div>
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="w-full max-w-lg rounded-2xl bg-white shadow-xl">
            <div class="border-b px-6 py-4"><h2 class="text-lg font-semibold">Tambah Mata Pelajaran</h2></div>
            <form method="POST" action="{{ route('admin-cabang.data-akademik.mata-pelajaran.store') }}" class="px-6 py-4">
                @csrf
                @include('admin-cabang.data-akademik.mata-pelajaran._form-fields', ['prefix' => 'tambah'])
                <div class="mt-6 flex justify-end gap-3 border-t pt-4">
                    <button type="button" data-modal-close class="rounded-xl border px-4 py-2 text-sm">Batal</button>
                    <button type="submit" class="rounded-xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div id="modal-edit-mata-pelajaran" class="modal hidden" aria-hidden="true">
    <div class="modal-backdrop fixed inset-0 z-40 bg-slate-900/50" data-modal-close></div>
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="w-full max-w-lg rounded-2xl bg-white shadow-xl">
            <div class="border-b px-6 py-4"><h2 class="text-lg font-semibold">Edit Mata Pelajaran</h2></div>
            <form id="form-edit-mata-pelajaran" method="POST" action="" class="px-6 py-4">
                @csrf @method('PUT')
                @include('admin-cabang.data-akademik.mata-pelajaran._form-fields', ['prefix' => 'edit'])
                <div class="mt-6 flex justify-end gap-3 border-t pt-4">
                    <button type="button" data-modal-close class="rounded-xl border px-4 py-2 text-sm">Batal</button>
                    <button type="submit" class="rounded-xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white">Perbarui</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div id="modal-detail-mata-pelajaran" class="modal hidden" aria-hidden="true">
    <div class="modal-backdrop fixed inset-0 z-40 bg-slate-900/50" data-modal-close></div>
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="w-full max-w-lg rounded-2xl bg-white shadow-xl">
            <div class="border-b px-6 py-4"><h2 class="text-lg font-semibold">Detail Mata Pelajaran</h2></div>
            <div class="px-6 py-4">
                @include('admin-cabang.data-akademik.mata-pelajaran._form-fields', ['prefix' => 'detail', 'readonly' => true])
                <div class="mt-6 flex justify-end border-t pt-4"><button type="button" data-modal-close class="rounded-xl border px-4 py-2 text-sm">Tutup</button></div>
            </div>
        </div>
    </div>
</div>
<div id="modal-hapus-mata-pelajaran" class="modal hidden" aria-hidden="true">
    <div class="modal-backdrop fixed inset-0 z-40 bg-slate-900/50" data-modal-close></div>
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="w-full max-w-md rounded-2xl bg-white shadow-xl">
            <div class="border-b px-6 py-4"><h2 class="text-lg font-semibold">Hapus Mata Pelajaran</h2></div>
            <form id="form-hapus-mata-pelajaran" method="POST" action="" class="px-6 py-4">
                @csrf @method('DELETE')
                <p class="text-sm text-slate-600">Yakin ingin menghapus <span id="hapus-mata-pelajaran-nama" class="font-semibold"></span>?</p>
                <div class="mt-6 flex justify-end gap-3 border-t pt-4">
                    <button type="button" data-modal-close class="rounded-xl border px-4 py-2 text-sm">Batal</button>
                    <button type="submit" class="rounded-xl bg-red-600 px-4 py-2 text-sm font-semibold text-white">Hapus</button>
                </div>
            </form>
        </div>
    </div>
</div>
