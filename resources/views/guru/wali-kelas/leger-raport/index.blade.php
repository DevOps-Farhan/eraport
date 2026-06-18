@extends('layouts.guru')

@section('title', 'Leger & Cetak Raport')

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-slate-900">Leger & Cetak Raport</h1>
        <p class="mt-1 text-sm text-slate-500">Lihat leger nilai dan cetak raport siswa.</p>
    </div>

    @if (! $kelas)
        <div class="rounded-2xl border border-slate-200 bg-white px-6 py-10 text-center text-sm text-slate-600">
            Anda belum ditunjuk sebagai wali kelas di tahun ajaran aktif.
        </div>
    @elseif (! $semester)
        <div class="rounded-2xl border border-amber-200 bg-amber-50 px-6 py-10 text-center text-sm text-amber-800">
            Tidak ada semester berjalan.
        </div>
    @elseif ($siswaList->isEmpty())
        <div class="rounded-2xl border border-amber-200 bg-amber-50 px-6 py-10 text-center text-sm text-amber-800">
            Belum ada siswa di kelas.
        </div>
    @else
        <div class="mb-4 rounded-xl border border-sky-200 bg-sky-50 px-4 py-3 text-sm text-sky-800">
            Kelas: <strong>{{ $kelas->nama }}</strong> — Semester {{ $semester->jenis->label() }}
        </div>

        <div class="overflow-x-auto rounded-2xl border border-slate-200 bg-white shadow-sm">
            <table class="min-w-full divide-y divide-slate-200 text-sm">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="sticky left-0 z-10 bg-slate-50 px-4 py-3 text-left font-semibold text-slate-500">Siswa</th>
                        @foreach ($penugasanList as $penugasan)
                            <th class="min-w-[80px] px-3 py-3 text-center font-semibold text-slate-500">{{ $penugasan->mataPelajaran->nama }}</th>
                        @endforeach
                        <th class="px-4 py-3 text-right font-semibold text-slate-500">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach ($siswaList as $siswa)
                        <tr>
                            <td class="sticky left-0 z-10 bg-white px-4 py-3 font-medium">{{ $siswa->nama }}</td>
                            @foreach ($penugasanList as $penugasan)
                                @php $nilai = $leger[$siswa->id][$penugasan->id] ?? []; @endphp
                                <td class="px-3 py-3 text-center">
                                    {{ $nilai['nilai_akhir'] !== null ? number_format($nilai['nilai_akhir'], 2) : '—' }}
                                </td>
                            @endforeach
                            <td class="px-4 py-3 text-right">
                                <a href="{{ route('guru.wali-kelas.leger-raport.cetak', $siswa) }}" class="rounded-lg px-2.5 py-1.5 text-sky-600 hover:bg-sky-50" target="_blank">Cetak Raport</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
@endsection
