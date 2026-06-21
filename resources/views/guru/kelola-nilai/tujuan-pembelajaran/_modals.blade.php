@php use App\Enums\JenisKelamin; @endphp

<div id="modal-tambah-tp" class="modal hidden fixed inset-0 z-50 items-center justify-center bg-slate-900/50 p-4" role="dialog">
    <div class="w-full max-w-lg rounded-2xl bg-white p-6 shadow-xl">
        <h2 class="text-lg font-semibold text-slate-900">Tambah Tujuan Pembelajaran</h2>
        <form method="POST" action="{{ route('guru.kelola-nilai.tujuan-pembelajaran.store', $penugasanAktif) }}" class="mt-4 space-y-4">
            @csrf
            <input type="hidden" name="penugasan_mengajar_id" value="{{ $penugasanAktif->id }}">
            <div>
                <label class="block text-sm font-medium text-slate-700">Kode TP</label>
                <input type="text" name="kode" required maxlength="20" class="mt-1 w-full rounded-xl border border-slate-300 px-3 py-2 text-sm" placeholder="3.1">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700">Deskripsi Kompetensi</label>
                <textarea name="deskripsi_kompetensi" required rows="3" class="mt-1 w-full rounded-xl border border-slate-300 px-3 py-2 text-sm" placeholder="Siswa mampu menjelaskan..."></textarea>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700">Urutan</label>
                <input type="number" name="urutan" required min="1" value="1" class="mt-1 w-full rounded-xl border border-slate-300 px-3 py-2 text-sm">
            </div>
            <div class="flex justify-end gap-2">
                <button type="button" data-modal-close class="rounded-xl px-4 py-2 text-sm text-slate-600 hover:bg-slate-100">Batal</button>
                <button type="submit" class="rounded-xl bg-sky-600 px-4 py-2 text-sm font-semibold text-white hover:bg-sky-700">Simpan</button>
            </div>
        </form>
    </div>
</div>

<div id="modal-edit-tp" class="modal hidden fixed inset-0 z-50 items-center justify-center bg-slate-900/50 p-4" role="dialog">
    <div class="w-full max-w-lg rounded-2xl bg-white p-6 shadow-xl">
        <h2 class="text-lg font-semibold text-slate-900">Edit Tujuan Pembelajaran</h2>
        <form method="POST" action="" class="mt-4 space-y-4" data-form-edit-tp>
            @csrf
            @method('PUT')
            <div>
                <label class="block text-sm font-medium text-slate-700">Kode TP</label>
                <input type="text" name="kode" required maxlength="20" class="mt-1 w-full rounded-xl border border-slate-300 px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700">Deskripsi Kompetensi</label>
                <textarea name="deskripsi_kompetensi" required rows="3" class="mt-1 w-full rounded-xl border border-slate-300 px-3 py-2 text-sm"></textarea>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700">Urutan</label>
                <input type="number" name="urutan" required min="1" class="mt-1 w-full rounded-xl border border-slate-300 px-3 py-2 text-sm">
            </div>
            <div class="flex justify-end gap-2">
                <button type="button" data-modal-close class="rounded-xl px-4 py-2 text-sm text-slate-600 hover:bg-slate-100">Batal</button>
                <button type="submit" class="rounded-xl bg-sky-600 px-4 py-2 text-sm font-semibold text-white hover:bg-sky-700">Simpan</button>
            </div>
        </form>
    </div>
</div>

<div id="modal-hapus-tp" class="modal hidden fixed inset-0 z-50 items-center justify-center bg-slate-900/50 p-4" role="dialog">
    <div class="w-full max-w-md rounded-2xl bg-white p-6 shadow-xl">
        <h2 class="text-lg font-semibold text-slate-900">Hapus Tujuan Pembelajaran</h2>
        <p class="mt-2 text-sm text-slate-600">Yakin ingin menghapus TP <strong data-tp-kode></strong>?</p>
        <form method="POST" action="" class="mt-4 flex justify-end gap-2" data-form-hapus-tp>
            @csrf
            @method('DELETE')
            <button type="button" data-modal-close class="rounded-xl px-4 py-2 text-sm text-slate-600 hover:bg-slate-100">Batal</button>
            <button type="submit" class="rounded-xl bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-700">Hapus</button>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const baseUrl = @json(url('/guru/kelola-nilai/tujuan-pembelajaran'));

    document.querySelectorAll('[data-modal-open="modal-edit-tp"]').forEach(btn => {
        btn.addEventListener('click', () => {
            const tp = JSON.parse(btn.dataset.tp);
            const form = document.querySelector('[data-form-edit-tp]');
            form.action = `${baseUrl}/${tp.route_key}`;
            form.querySelector('[name=kode]').value = tp.kode;
            form.querySelector('[name=deskripsi_kompetensi]').value = tp.deskripsi_kompetensi;
            form.querySelector('[name=urutan]').value = tp.urutan;
        });
    });

    document.querySelectorAll('[data-modal-open="modal-hapus-tp"]').forEach(btn => {
        btn.addEventListener('click', () => {
            const tp = JSON.parse(btn.dataset.tp);
            const form = document.querySelector('[data-form-hapus-tp]');
            form.action = `${baseUrl}/${tp.route_key}`;
            document.querySelector('[data-tp-kode]').textContent = tp.kode;
        });
    });
});
</script>
@endpush
