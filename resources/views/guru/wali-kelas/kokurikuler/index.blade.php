@extends('layouts.guru')

@section('title', 'Kokurikuler - ' . $kelas->nama)

@section('content')
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-slate-900">Kokurikuler</h1>
            <p class="mt-1 text-sm text-slate-500">Kelas {{ $kelas->nama }} • Fase {{ $kelas->fase }}</p>
        </div>
        <a href="{{ route('guru.wali-kelas.kokurikuler') }}" class="inline-flex items-center justify-center rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 shadow-sm transition hover:bg-slate-50">
            Kembali
        </a>
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
        <form method="POST" action="{{ route('guru.wali-kelas.kokurikuler.store', $kelas) }}">
            @csrf
            <div class="overflow-x-auto rounded-2xl border border-slate-200 bg-white shadow-sm">
                <table class="min-w-full divide-y divide-slate-200 text-sm">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-4 py-3 text-center w-12 font-semibold text-slate-500">No</th>
                            <th class="px-4 py-3 text-left font-semibold text-slate-500">NIS</th>
                            <th class="px-4 py-3 text-left font-semibold text-slate-500">Nama Siswa</th>
                            <th class="min-w-[240px] px-4 py-3 text-left font-semibold text-slate-500">Capaian Kokurikuler</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($siswaList as $index => $siswa)
                            @php
                                $koku = $kokurikuler->get($siswa->id);
                            @endphp
                            <tr>
                                <td class="px-4 py-3 text-center text-slate-500">{{ $index + 1 }}</td>
                                <td class="px-4 py-3 text-slate-500">{{ $siswa->nis }}</td>
                                <td class="px-4 py-3 font-medium text-slate-900">{{ $siswa->nama }}</td>
                                <td class="px-4 py-2">
                                    <textarea name="capaian[{{ $siswa->id }}]" rows="2" @disabled(! $dapatDiedit) class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500" placeholder="Opsional...">{{ $koku?->capaian_kokurikuler }}</textarea>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if ($dapatDiedit)
                <div class="mt-4 flex justify-end">
                    <button type="submit" class="rounded-xl bg-sky-600 px-6 py-2.5 text-sm font-semibold text-white hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:ring-offset-2">Simpan Capaian</button>
                </div>
            @endif
        </form>
    @endif
@endsection
