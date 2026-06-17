@extends('layouts.guru')

@section('title', 'Nilai Ujian')

@section('content')
    <div class="mb-6">
        <a href="{{ route('guru.kelola-nilai.nilai-ujian') }}" class="text-sm text-sky-600 hover:text-sky-700">← Kembali</a>
        <h1 class="mt-2 text-2xl font-semibold text-slate-900">{{ $penugasan->mataPelajaran->nama }} — {{ $penugasan->kelas->nama }}</h1>
        <p class="mt-1 text-sm text-slate-500">Nilai akhir = Rata TP × 60% + Ujian × 40%</p>
    </div>

    @if ($siswaList->isEmpty())
        <div class="rounded-2xl border border-amber-200 bg-amber-50 px-6 py-10 text-center text-sm text-amber-800">
            Belum ada siswa aktif di kelas ini.
        </div>
    @else
        <form method="POST" action="{{ route('guru.kelola-nilai.nilai-ujian.store', $penugasan) }}">
            @csrf
            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <table class="min-w-full divide-y divide-slate-200 text-sm">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-4 py-3 text-left font-semibold text-slate-500">Siswa</th>
                            <th class="px-4 py-3 text-center font-semibold text-slate-500">Rata TP</th>
                            <th class="px-4 py-3 text-center font-semibold text-slate-500">Nilai Ujian</th>
                            <th class="px-4 py-3 text-center font-semibold text-slate-500">Nilai Akhir</th>
                            <th class="px-4 py-3 text-center font-semibold text-slate-500">Predikat</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($siswaList as $siswa)
                            @php $data = $ringkasan[$siswa->id] ?? []; @endphp
                            <tr>
                                <td class="px-4 py-3 font-medium">{{ $siswa->nama }}</td>
                                <td class="px-4 py-3 text-center">{{ $data['rata_tp'] !== null ? number_format($data['rata_tp'], 2) : '—' }}</td>
                                <td class="px-4 py-3">
                                    <input
                                        type="number"
                                        name="nilai[{{ $siswa->id }}]"
                                        value="{{ $nilaiExisting->get($siswa->id)?->nilai }}"
                                        min="0"
                                        max="100"
                                        step="0.01"
                                        @disabled(! $dapatDiedit)
                                        class="mx-auto block w-24 rounded-lg border border-slate-300 px-2 py-1.5 text-center"
                                    >
                                </td>
                                <td class="px-4 py-3 text-center font-semibold">{{ $data['nilai_akhir'] !== null ? number_format($data['nilai_akhir'], 2) : '—' }}</td>
                                <td class="px-4 py-3 text-center">{{ $data['predikat']?->value ?? '—' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if ($dapatDiedit)
                <div class="mt-4 flex justify-end">
                    <button type="submit" class="rounded-xl bg-sky-600 px-6 py-2.5 text-sm font-semibold text-white hover:bg-sky-700">Simpan Nilai Ujian</button>
                </div>
            @endif
        </form>
    @endif
@endsection
