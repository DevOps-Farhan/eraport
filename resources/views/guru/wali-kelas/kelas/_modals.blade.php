<div id="modal-tambah-siswa-wali" class="modal hidden" aria-hidden="true">
    <div class="modal-backdrop fixed inset-0 z-40 bg-slate-900/50" data-modal-close></div>
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="flex max-h-[90vh] w-full max-w-4xl flex-col rounded-2xl bg-white shadow-xl">
            <div class="shrink-0 border-b px-6 py-4"><h2 class="text-lg font-semibold">Tambah Siswa</h2></div>
            <form method="POST" action="{{ route('guru.wali-kelas.kelas.store-siswa', $kelas) }}" class="flex min-h-0 flex-1 flex-col">
                @csrf
                <div class="flex-1 overflow-y-auto px-6 py-4">
                    @include('admin-cabang.master-data.siswa._form-fields', ['prefix' => 'wali-tambah', 'showAktif' => false])
                </div>
                <div class="flex shrink-0 justify-end gap-2 border-t px-6 py-4">
                    <button type="button" data-modal-close class="rounded-xl border px-4 py-2 text-sm">Batal</button>
                    <button type="submit" class="rounded-xl bg-sky-600 px-4 py-2 text-sm font-semibold text-white">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="modal-edit-siswa-wali" class="modal hidden" aria-hidden="true">
    <div class="modal-backdrop fixed inset-0 z-40 bg-slate-900/50" data-modal-close></div>
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="flex max-h-[90vh] w-full max-w-4xl flex-col rounded-2xl bg-white shadow-xl">
            <div class="shrink-0 border-b px-6 py-4"><h2 class="text-lg font-semibold">Edit Siswa</h2></div>
            <form method="POST" action="" class="flex min-h-0 flex-1 flex-col" data-form-edit-siswa-wali data-kelas-id="{{ $kelas->route_key }}">
                @csrf @method('PUT')
                <div class="flex-1 overflow-y-auto px-6 py-4">
                    @include('admin-cabang.master-data.siswa._form-fields', ['prefix' => 'wali-edit', 'showAktif' => false])
                </div>
                <div class="flex shrink-0 justify-end gap-2 border-t px-6 py-4">
                    <button type="button" data-modal-close class="rounded-xl border px-4 py-2 text-sm">Batal</button>
                    <button type="submit" class="rounded-xl bg-sky-600 px-4 py-2 text-sm font-semibold text-white">Perbarui</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="modal-hapus-siswa-wali" class="modal hidden" aria-hidden="true">
    <div class="modal-backdrop fixed inset-0 z-40 bg-slate-900/50" data-modal-close></div>
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="w-full max-w-md rounded-2xl bg-white shadow-xl">
            <div class="border-b px-6 py-4"><h2 class="text-lg font-semibold">Hapus dari Kelas</h2></div>
            <form method="POST" action="" class="px-6 py-4" data-form-hapus-siswa-wali data-kelas-id="{{ $kelas->route_key }}">
                @csrf @method('DELETE')
                <p class="text-sm text-slate-600">Yakin ingin menghapus <strong data-siswa-nama></strong> dari kelas?</p>
                <div class="mt-6 flex justify-end gap-2 border-t pt-4">
                    <button type="button" data-modal-close class="rounded-xl border px-4 py-2 text-sm">Batal</button>
                    <button type="submit" class="rounded-xl bg-red-600 px-4 py-2 text-sm font-semibold text-white">Hapus</button>
                </div>
            </form>
        </div>
    </div>
</div>
