@if (isset($tahunAjaranAktif) && $tahunAjaranAktif)
    @php
        $semesterBerjalan = $tahunAjaranAktif->semesterBerjalan;
    @endphp
    <div class="mb-6 flex flex-wrap items-center gap-3 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm">
        <span class="font-medium text-emerald-800">Konteks Aktif:</span>
        <span class="rounded-full bg-white px-3 py-1 font-semibold text-emerald-700">{{ $tahunAjaranAktif->nama }}</span>
        @if ($semesterBerjalan)
            <span class="text-emerald-700">Semester {{ $semesterBerjalan->jenis->label() }}</span>
        @else
            <span class="text-emerald-600">Tidak ada semester berjalan</span>
        @endif
    </div>
@else
    <div class="mb-6 rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800">
        Belum ada tahun ajaran aktif.
        <a href="{{ route('admin-cabang.data-akademik.tahun-ajaran.index') }}" class="font-medium underline">Aktifkan tahun ajaran</a>
        untuk mulai mengelola data akademik.
    </div>
@endif

