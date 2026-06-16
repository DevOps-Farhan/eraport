@extends('layouts.app')

@section('title', 'Arsip Tahun Ajaran')

@section('content')
    <div class="mb-6">
        <a
            href="{{ route($dashboardRoute) }}"
            class="text-sm font-medium text-slate-500 transition hover:text-slate-700"
        >
            ← Kembali ke Dashboard
        </a>
        <h1 class="mt-2 text-2xl font-semibold text-slate-900">Arsip Tahun Ajaran</h1>
        <p class="mt-1 text-sm text-slate-500">Lihat data tahun ajaran yang sudah ditutup (hanya baca).</p>
    </div>

    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Nama</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Periode</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Semester</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-slate-500">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($tahunAjaran as $item)
                        @php
                            $ganjil = $item->semesterGanjil();
                            $genap = $item->semesterGenap();
                        @endphp
                        <tr class="hover:bg-slate-50">
                            <td class="whitespace-nowrap px-4 py-3 text-sm font-medium text-slate-900">{{ $item->nama }}</td>
                            <td class="px-4 py-3 text-sm text-slate-600">
                                {{ $item->tanggal_mulai->format('d M Y') }}
                                @if ($item->tanggal_selesai)
                                    — {{ $item->tanggal_selesai->format('d M Y') }}
                                @endif
                            </td>
                            <td class="px-4 py-3 text-sm text-slate-600">
                                <div class="space-y-1">
                                    @if ($ganjil)
                                        <p>Ganjil: <span class="font-medium">{{ $ganjil->status->label() }}</span></p>
                                    @endif
                                    @if ($genap)
                                        <p>Genap: <span class="font-medium">{{ $genap->status->label() }}</span></p>
                                    @endif
                                </div>
                            </td>
                            <td class="whitespace-nowrap px-4 py-3 text-right text-sm">
                                <a
                                    href="{{ route('arsip.tahun-ajaran.show', $item) }}"
                                    class="rounded-lg px-2.5 py-1.5 font-medium text-indigo-600 transition hover:bg-indigo-50"
                                >
                                    Lihat Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-10 text-center text-sm text-slate-500">
                                Belum ada tahun ajaran yang diarsipkan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($tahunAjaran->hasPages())
            <div class="border-t border-slate-200 px-4 py-3">
                {{ $tahunAjaran->links() }}
            </div>
        @endif
    </div>
@endsection
