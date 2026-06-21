@extends('layouts.guru')

@section('title', 'Rekap Absensi & Catatan')

@section('content')
    <div class="mb-6">
        <a href="{{ route('guru.wali-kelas.absensi-catatan') }}" class="mb-2 inline-flex items-center gap-2 text-sm font-medium text-slate-500 transition hover:text-slate-800">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
            </svg>
            Kembali
        </a>
        <h1 class="text-2xl font-semibold text-slate-900">Rekap Absensi dan Catatan</h1>
        <p class="mt-1 text-sm text-slate-500">Rekap kehadiran siswa dan catatan wali kelas akhir semester.</p>
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
            Belum ada siswa di kelas {{ $kelas->nama }}.
        </div>
    @else
        <form method="POST" action="{{ route('guru.wali-kelas.absensi-catatan.store', $kelas) }}">
            @csrf
            <div class="overflow-x-auto rounded-2xl border border-slate-200 bg-white shadow-sm">
                <table class="min-w-full divide-y divide-slate-200 text-sm">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-4 py-3 text-left font-semibold text-slate-500">Siswa</th>
                            <th class="px-3 py-3 text-center font-semibold text-slate-500">Sakit</th>
                            <th class="px-3 py-3 text-center font-semibold text-slate-500">Izin</th>
                            <th class="px-3 py-3 text-center font-semibold text-slate-500">Alpha</th>
                            <th class="min-w-[240px] px-4 py-3 text-left font-semibold text-slate-500">Catatan Akhir Semester</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($siswaList as $siswa)
                            @php
                                $abs = $absensi->get($siswa->id);
                                $cat = $catatan->get($siswa->id);
                            @endphp
                            <tr>
                                <td class="px-4 py-3 font-medium">{{ $siswa->nama }}</td>
                                <td class="px-2 py-2">
                                    <input type="number" name="absensi[{{ $siswa->id }}][sakit]" value="{{ $abs?->sakit ?? 0 }}" min="0" @disabled(! $dapatDiedit) class="mx-auto block w-16 rounded-lg border border-slate-300 px-2 py-1 text-center">
                                </td>
                                <td class="px-2 py-2">
                                    <input type="number" name="absensi[{{ $siswa->id }}][izin]" value="{{ $abs?->izin ?? 0 }}" min="0" @disabled(! $dapatDiedit) class="mx-auto block w-16 rounded-lg border border-slate-300 px-2 py-1 text-center">
                                </td>
                                <td class="px-2 py-2">
                                    <input type="number" name="absensi[{{ $siswa->id }}][alpha]" value="{{ $abs?->alpha ?? 0 }}" min="0" @disabled(! $dapatDiedit) class="mx-auto block w-16 rounded-lg border border-slate-300 px-2 py-1 text-center">
                                </td>
                                <td class="px-4 py-2">
                                    <textarea name="catatan[{{ $siswa->id }}]" rows="2" @disabled(! $dapatDiedit) class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">{{ $cat?->catatan }}</textarea>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if ($dapatDiedit)
                <div class="mt-4 flex justify-end">
                    <button type="submit" class="rounded-xl bg-sky-600 px-6 py-2.5 text-sm font-semibold text-white hover:bg-sky-700">Simpan</button>
                </div>
            @endif
        </form>
    @endif
@endsection
