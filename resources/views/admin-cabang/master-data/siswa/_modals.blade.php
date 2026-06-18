<div id="modal-tambah-siswa" class="modal hidden" aria-hidden="true">
    <div class="modal-backdrop fixed inset-0 z-40 bg-slate-900/50" data-modal-close></div>
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="flex max-h-[90vh] w-full max-w-4xl flex-col rounded-2xl bg-white shadow-xl">
            <div class="shrink-0 border-b px-6 py-4"><h2 class="text-lg font-semibold">Tambah Siswa</h2></div>
            <form method="POST" action="{{ route('admin-cabang.master-data.siswa.store') }}" class="flex min-h-0 flex-1 flex-col">
                @csrf
                <div class="flex-1 overflow-y-auto px-6 py-4">
                    @include('admin-cabang.master-data.siswa._form-fields', ['prefix' => 'tambah'])
                </div>
                <div class="flex shrink-0 justify-end gap-3 border-t px-6 py-4">
                    <button type="button" data-modal-close class="rounded-xl border px-4 py-2 text-sm">Batal</button>
                    <button type="submit" class="rounded-xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div id="modal-edit-siswa" class="modal hidden" aria-hidden="true">
    <div class="modal-backdrop fixed inset-0 z-40 bg-slate-900/50" data-modal-close></div>
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="flex max-h-[90vh] w-full max-w-4xl flex-col rounded-2xl bg-white shadow-xl">
            <div class="shrink-0 border-b px-6 py-4"><h2 class="text-lg font-semibold">Edit Siswa</h2></div>
            <form id="form-edit-siswa" method="POST" action="" class="flex min-h-0 flex-1 flex-col">
                @csrf @method('PUT')
                <div class="flex-1 overflow-y-auto px-6 py-4">
                    @include('admin-cabang.master-data.siswa._form-fields', ['prefix' => 'edit'])
                </div>
                <div class="flex shrink-0 justify-end gap-3 border-t px-6 py-4">
                    <button type="button" data-modal-close class="rounded-xl border px-4 py-2 text-sm">Batal</button>
                    <button type="submit" class="rounded-xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white">Perbarui</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div id="modal-detail-siswa" class="modal hidden" aria-hidden="true">
    <div class="modal-backdrop fixed inset-0 z-40 bg-slate-900/50" data-modal-close></div>
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="flex max-h-[90vh] w-full max-w-4xl flex-col rounded-2xl bg-white shadow-xl">
            <div class="shrink-0 border-b px-6 py-4"><h2 class="text-lg font-semibold">Detail Siswa</h2></div>
            <div class="flex min-h-0 flex-1 flex-col">
                <div class="flex-1 overflow-y-auto px-6 py-4">
                    @include('admin-cabang.master-data.siswa._form-fields', ['prefix' => 'detail', 'readonly' => true])
                </div>
                <div class="flex shrink-0 justify-end border-t px-6 py-4">
                    <button type="button" data-modal-close class="rounded-xl border px-4 py-2 text-sm">Tutup</button>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="modal-hapus-siswa" class="modal hidden" aria-hidden="true">
    <div class="modal-backdrop fixed inset-0 z-40 bg-slate-900/50" data-modal-close></div>
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="w-full max-w-md rounded-2xl bg-white shadow-xl">
            <div class="border-b px-6 py-4"><h2 class="text-lg font-semibold">Hapus Siswa</h2></div>
            <form id="form-hapus-siswa" method="POST" action="" class="px-6 py-4">
                @csrf @method('DELETE')
                <p class="text-sm text-slate-600">Yakin ingin menghapus siswa <span id="hapus-siswa-nama" class="font-semibold"></span>?</p>
                <div class="mt-6 flex justify-end gap-3 border-t pt-4">
                    <button type="button" data-modal-close class="rounded-xl border px-4 py-2 text-sm">Batal</button>
                    <button type="submit" class="rounded-xl bg-red-600 px-4 py-2 text-sm font-semibold text-white">Hapus</button>
                </div>
            </form>
        </div>
    </div>
</div>
@if ($tahunAjaran)
<div id="modal-rombel-siswa" class="modal hidden" aria-hidden="true">
    <div class="modal-backdrop fixed inset-0 z-40 bg-slate-900/50" data-modal-close></div>
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="w-full max-w-md rounded-2xl bg-white shadow-xl">
            <div class="border-b px-6 py-4"><h2 class="text-lg font-semibold">Tempatkan ke Kelas</h2></div>
            <form id="form-rombel-siswa" method="POST" action="" class="px-6 py-4">
                @csrf
                <input type="hidden" name="tahun_ajaran_id" value="{{ $tahunAjaran->id }}">
                <p class="mb-4 text-sm text-slate-600">Siswa: <span id="rombel-siswa-nama" class="font-semibold text-slate-900"></span></p>
                <label for="rombel-kelas_id" class="mb-1 block text-sm font-medium text-slate-700">Kelas</label>
                <select id="rombel-kelas_id" name="kelas_id" required class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm">
                    <option value="">Pilih kelas</option>
                    @foreach ($kelasList as $kelas)
                        <option value="{{ $kelas->id }}">{{ $kelas->nama }}</option>
                    @endforeach
                </select>
                <div class="mt-6 flex justify-end gap-3 border-t pt-4">
                    <button type="button" data-modal-close class="rounded-xl border px-4 py-2 text-sm">Batal</button>
                    <button type="submit" class="rounded-xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
