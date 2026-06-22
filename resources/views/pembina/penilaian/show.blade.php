@extends('layouts.pembina')

@section('title', 'Penilaian - ' . $ekstrakurikuler->nama)

@section('content')
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <div class="flex items-center gap-3">
                <a href="{{ route('pembina.penilaian.index') }}" class="rounded-full bg-slate-100 p-2 text-slate-500 transition hover:bg-slate-200 hover:text-slate-900">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                    </svg>
                </a>
                <h1 class="text-2xl font-semibold text-slate-900">{{ $ekstrakurikuler->nama }}</h1>
            </div>
            <p class="mt-1 pl-11 text-sm text-slate-500">
                Tahun Ajaran {{ $tahunAjaran->nama }}
                @if ($semesterAktif)
                    &mdash; Semester {{ $semesterAktif->jenis === 'ganjil' ? 'Ganjil' : 'Genap' }}
                @endif
            </p>
        </div>
    </div>

    @if (! $semesterAktif)
        <div class="rounded-2xl border border-yellow-200 bg-yellow-50 p-4 text-yellow-800">
            <div class="flex items-center gap-3">
                <svg class="h-5 w-5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 2.495zM10 5a.75.75 0 01.75.75v4.5a.75.75 0 01-1.5 0v-4.5A.75.75 0 0110 5zm0 10a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                </svg>
                <p class="text-sm font-medium">Tidak ada semester aktif. Anda tidak dapat mengisi penilaian saat ini.</p>
            </div>
        </div>
    @elseif ($anggotaList->isEmpty())
        <div class="rounded-2xl border border-slate-200 bg-white p-10 text-center shadow-sm">
            <h3 class="mt-4 text-sm font-medium text-slate-900">Belum ada anggota</h3>
            <p class="mt-1 text-sm text-slate-500">Silakan tambahkan anggota ekstrakurikuler terlebih dahulu pada menu Kelola Anggota.</p>
            <div class="mt-6">
                <a href="{{ route('pembina.ekstrakurikuler.show', $ekstrakurikuler) }}" class="inline-flex items-center justify-center rounded-xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700">
                    Kelola Anggota
                </a>
            </div>
        </div>
    @else
        <form action="{{ route('pembina.penilaian.store', $ekstrakurikuler) }}" method="POST">
            @csrf
            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-200 bg-slate-50 px-4 py-3">
                    <h3 class="font-medium text-slate-900">Form Penilaian ({{ $anggotaList->count() }} Siswa)</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">Nama Lengkap</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500 w-32">Nilai (0-100)</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500 w-32">Predikat</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500 min-w-[200px]">Deskripsi / Evaluasi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach ($anggotaList as $index => $anggota)
                                @php
                                    $nilaiSiswa = $nilaiList->get($anggota->siswa_id);
                                @endphp
                                <tr class="hover:bg-slate-50">
                                    <td class="px-4 py-3 text-sm font-medium text-slate-900">
                                        {{ $anggota->siswa->nama }}
                                        <div class="mt-0.5 text-xs text-slate-500">{{ $anggota->siswa->rombelSiswa->first()?->kelas->nama ?? '—' }}</div>
                                        <input type="hidden" name="nilai[{{ $index }}][siswa_id]" value="{{ $anggota->siswa_id }}">
                                    </td>
                                    <td class="px-4 py-3">
                                        <input type="number" step="0.01" min="0" max="100" name="nilai[{{ $index }}][nilai]" value="{{ old("nilai.$index.nilai", $nilaiSiswa?->nilai) }}" class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm focus:border-emerald-500 focus:ring-emerald-500" placeholder="Contoh: 85">
                                    </td>
                                    <td class="px-4 py-3">
                                        <select name="nilai[{{ $index }}][predikat]" class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm focus:border-emerald-500 focus:ring-emerald-500">
                                            <option value="">-- Pilih --</option>
                                            <option value="A" {{ old("nilai.$index.predikat", $nilaiSiswa?->predikat) == 'A' ? 'selected' : '' }}>Sangat Baik (A)</option>
                                            <option value="B" {{ old("nilai.$index.predikat", $nilaiSiswa?->predikat) == 'B' ? 'selected' : '' }}>Baik (B)</option>
                                            <option value="C" {{ old("nilai.$index.predikat", $nilaiSiswa?->predikat) == 'C' ? 'selected' : '' }}>Cukup (C)</option>
                                            <option value="D" {{ old("nilai.$index.predikat", $nilaiSiswa?->predikat) == 'D' ? 'selected' : '' }}>Kurang (D)</option>
                                        </select>
                                    </td>
                                    <td class="px-4 py-3">
                                        <textarea name="nilai[{{ $index }}][deskripsi]" rows="2" class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm focus:border-emerald-500 focus:ring-emerald-500" placeholder="Tuliskan perkembangan siswa...">{{ old("nilai.$index.deskripsi", $nilaiSiswa?->deskripsi) }}</textarea>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="border-t border-slate-200 bg-slate-50 px-4 py-4 flex justify-end">
                    <button type="submit" class="inline-flex items-center justify-center rounded-xl bg-emerald-600 px-6 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700">
                        Simpan Penilaian
                    </button>
                </div>
            </div>
        </form>
    @endif
@endsection
