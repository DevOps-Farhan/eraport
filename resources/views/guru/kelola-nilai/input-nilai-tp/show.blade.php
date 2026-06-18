@extends('layouts.guru')

@section('title', 'Input Nilai TP')

@section('content')
    <div class="mb-6">
        <a href="{{ route('guru.kelola-nilai.input-nilai-tp') }}" class="text-sm text-sky-600 hover:text-sky-700">← Kembali</a>
        <h1 class="mt-2 text-2xl font-semibold text-slate-900">{{ $penugasan->mataPelajaran->nama }} — {{ $penugasan->kelas->nama }}</h1>
        <p class="mt-1 text-sm text-slate-500">Input nilai 0–100 per tujuan pembelajaran. Kolom pencapaian dihasilkan otomatis dari TP tertinggi dan terendah.</p>
    </div>

    @if ($tujuanPembelajaran->isEmpty())
        <div class="rounded-2xl border border-amber-200 bg-amber-50 px-6 py-10 text-center text-sm text-amber-800">
            Belum ada tujuan pembelajaran. <a href="{{ route('guru.kelola-nilai.tujuan-pembelajaran', ['penugasan' => $penugasan->getRouteKey()]) }}" class="font-medium underline">Buat TP terlebih dahulu</a>.
        </div>
    @elseif ($siswaList->isEmpty())
        <div class="rounded-2xl border border-amber-200 bg-amber-50 px-6 py-10 text-center text-sm text-amber-800">
            Belum ada siswa aktif di kelas ini.
        </div>
    @else
        <form method="POST" action="{{ route('guru.kelola-nilai.input-nilai-tp.store', $penugasan) }}">
            @csrf
            <div class="overflow-x-auto rounded-2xl border border-slate-200 bg-white shadow-sm">
                <table class="min-w-full divide-y divide-slate-200 text-sm">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="sticky left-0 z-10 bg-slate-50 px-4 py-3 text-left font-semibold text-slate-500">Siswa</th>
                            @foreach ($tujuanPembelajaran as $tp)
                                <th class="min-w-[100px] px-3 py-3 text-center font-semibold text-slate-500" title="{{ $tp->deskripsi_kompetensi }}">{{ $tp->deskripsi_kompetensi }}</th>
                            @endforeach
                            <th class="min-w-[280px] px-4 py-3 text-left font-semibold text-slate-500">Pencapaian</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($siswaList as $siswa)
                            <tr>
                                <td class="sticky left-0 z-10 bg-white px-4 py-2 font-medium">{{ $siswa->nama }}</td>
                                @foreach ($tujuanPembelajaran as $tp)
                                    @php
                                        $key = $siswa->id.'_'.$tp->id;
                                        $existing = $nilaiExisting->get($key);
                                    @endphp
                                    <td class="px-2 py-2">
                                        <input
                                            type="number"
                                            name="nilai[{{ $key }}]"
                                            value="{{ $existing?->nilai }}"
                                            min="0"
                                            max="100"
                                            step="0.01"
                                            @disabled(! $dapatDiedit)
                                            class="w-full rounded-lg border border-slate-300 px-2 py-1.5 text-center text-sm"
                                        >
                                    </td>
                                @endforeach
                                <td class="px-4 py-2 text-xs leading-relaxed text-slate-600 whitespace-pre-line">{{ $pencapaianExisting->get($siswa->id)?->pencapaian ?? '—' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if ($dapatDiedit)
                <div class="mt-4 flex justify-end">
                    <button type="submit" class="rounded-xl bg-sky-600 px-6 py-2.5 text-sm font-semibold text-white hover:bg-sky-700">Simpan Nilai</button>
                </div>
            @endif
        </form>
    @endif
@endsection
