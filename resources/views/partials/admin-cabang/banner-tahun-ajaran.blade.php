@if ($tahunAjaran)
    <div class="mb-4 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
        Tahun ajaran aktif: <span class="font-semibold">{{ $tahunAjaran->nama }}</span>
    </div>
@else
    <div class="mb-4 rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800">
        Belum ada tahun ajaran aktif. Aktifkan tahun ajaran terlebih dahulu untuk mengelola data per semester.
    </div>
@endif
