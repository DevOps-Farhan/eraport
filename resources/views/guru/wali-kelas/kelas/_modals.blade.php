@php use App\Enums\JenisKelamin; @endphp

<div id="modal-tambah-siswa-wali" class="modal hidden" aria-hidden="true">
    <div class="modal-backdrop fixed inset-0 z-40 bg-slate-900/50" data-modal-close></div>
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="w-full max-w-lg rounded-2xl bg-white shadow-xl">
            <div class="border-b px-6 py-4"><h2 class="text-lg font-semibold">Tambah Siswa</h2></div>
            <form method="POST" action="{{ route('guru.wali-kelas.kelas.store-siswa') }}" class="px-6 py-4 space-y-3">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-slate-700">NIS</label>
                    <input type="text" name="nis" required maxlength="30" class="mt-1 w-full rounded-xl border border-slate-300 px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700">NISN</label>
                    <input type="text" name="nisn" maxlength="30" class="mt-1 w-full rounded-xl border border-slate-300 px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700">Nama</label>
                    <input type="text" name="nama" required maxlength="100" class="mt-1 w-full rounded-xl border border-slate-300 px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700">Jenis Kelamin</label>
                    <select name="jenis_kelamin" required class="mt-1 w-full rounded-xl border border-slate-300 px-3 py-2 text-sm">
                        @foreach (JenisKelamin::cases() as $jk)
                            <option value="{{ $jk->value }}">{{ $jk->label() }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700">Tanggal Lahir</label>
                    <input type="date" name="tanggal_lahir" class="mt-1 w-full rounded-xl border border-slate-300 px-3 py-2 text-sm">
                </div>
                <div class="flex justify-end gap-2 border-t pt-4">
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
        <div class="w-full max-w-lg rounded-2xl bg-white shadow-xl">
            <div class="border-b px-6 py-4"><h2 class="text-lg font-semibold">Edit Siswa</h2></div>
            <form method="POST" action="" class="px-6 py-4 space-y-3" data-form-edit-siswa-wali>
                @csrf @method('PUT')
                <div>
                    <label class="block text-sm font-medium text-slate-700">NIS</label>
                    <input type="text" name="nis" required maxlength="30" class="mt-1 w-full rounded-xl border border-slate-300 px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700">NISN</label>
                    <input type="text" name="nisn" maxlength="30" class="mt-1 w-full rounded-xl border border-slate-300 px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700">Nama</label>
                    <input type="text" name="nama" required maxlength="100" class="mt-1 w-full rounded-xl border border-slate-300 px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700">Jenis Kelamin</label>
                    <select name="jenis_kelamin" required class="mt-1 w-full rounded-xl border border-slate-300 px-3 py-2 text-sm">
                        @foreach (JenisKelamin::cases() as $jk)
                            <option value="{{ $jk->value }}">{{ $jk->label() }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700">Tanggal Lahir</label>
                    <input type="date" name="tanggal_lahir" class="mt-1 w-full rounded-xl border border-slate-300 px-3 py-2 text-sm">
                </div>
                <div class="flex justify-end gap-2 border-t pt-4">
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
            <form method="POST" action="" class="px-6 py-4" data-form-hapus-siswa-wali>
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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const baseUrl = @json(url('/guru/wali-kelas/kelas/siswa'));

    document.querySelectorAll('[data-modal-open="modal-edit-siswa-wali"]').forEach(btn => {
        btn.addEventListener('click', () => {
            const siswa = JSON.parse(btn.dataset.siswa);
            const form = document.querySelector('[data-form-edit-siswa-wali]');
            form.action = `${baseUrl}/${siswa.id}`;
            form.querySelector('[name=nis]').value = siswa.nis;
            form.querySelector('[name=nisn]').value = siswa.nisn || '';
            form.querySelector('[name=nama]').value = siswa.nama;
            form.querySelector('[name=jenis_kelamin]').value = siswa.jenis_kelamin;
            form.querySelector('[name=tanggal_lahir]').value = siswa.tanggal_lahir ? siswa.tanggal_lahir.substring(0, 10) : '';
        });
    });

    document.querySelectorAll('[data-modal-open="modal-hapus-siswa-wali"]').forEach(btn => {
        btn.addEventListener('click', () => {
            const siswa = JSON.parse(btn.dataset.siswa);
            document.querySelector('[data-form-hapus-siswa-wali]').action = `${baseUrl}/${siswa.id}`;
            document.querySelector('[data-siswa-nama]').textContent = siswa.nama;
        });
    });
});
</script>
@endpush
