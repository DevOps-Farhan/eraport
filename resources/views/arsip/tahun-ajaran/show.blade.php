@extends('layouts.app')

@section('title', 'Detail Arsip — ' . $tahunAjaran->nama)

@section('content')
    <div class="mb-6">
        <a
            href="{{ route('arsip.tahun-ajaran.index') }}"
            class="text-sm font-medium text-slate-500 transition hover:text-slate-700"
        >
            ← Kembali ke Arsip
        </a>
        <h1 class="mt-2 text-2xl font-semibold text-slate-900">{{ $tahunAjaran->nama }}</h1>
        <p class="mt-1 text-sm text-slate-500">Tampilan arsip — hanya baca.</p>
    </div>

    <div class="grid gap-6 lg:grid-cols-2">
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <h2 class="text-lg font-semibold text-slate-900">Informasi Tahun Ajaran</h2>
            <dl class="mt-4 space-y-3 text-sm">
                <div>
                    <dt class="text-slate-500">Status</dt>
                    <dd class="mt-0.5 font-medium text-slate-900">{{ $tahunAjaran->status->label() }}</dd>
                </div>
                <div>
                    <dt class="text-slate-500">Tanggal Mulai</dt>
                    <dd class="mt-0.5 font-medium text-slate-900">{{ $tahunAjaran->tanggal_mulai->format('d M Y') }}</dd>
                </div>
                <div>
                    <dt class="text-slate-500">Tanggal Selesai</dt>
                    <dd class="mt-0.5 font-medium text-slate-900">
                        {{ $tahunAjaran->tanggal_selesai?->format('d M Y') ?? '—' }}
                    </dd>
                </div>
            </dl>

            <h3 class="mt-6 text-sm font-semibold uppercase tracking-wide text-slate-500">Semester</h3>
            <div class="mt-3 space-y-2 rounded-xl bg-slate-50 p-4 text-sm text-slate-700">
                @foreach ($tahunAjaran->semester as $semester)
                    <p>
                        <span class="font-medium">Semester {{ $semester->jenis->label() }}:</span>
                        {{ $semester->status->label() }}
                        @if ($semester->tanggal_mulai)
                            <span class="text-slate-500">({{ $semester->tanggal_mulai->format('d M Y') }}
                            @if ($semester->tanggal_selesai)
                                — {{ $semester->tanggal_selesai->format('d M Y') }}
                            @endif
                            )</span>
                        @endif
                    </p>
                @endforeach
            </div>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <h2 class="text-lg font-semibold text-slate-900">Riwayat Perubahan</h2>
            @if ($logAkademik->isEmpty())
                <p class="mt-4 text-sm text-slate-500">Belum ada riwayat tercatat.</p>
            @else
                <ul class="mt-4 space-y-3">
                    @foreach ($logAkademik as $log)
                        <li class="rounded-xl border border-slate-100 bg-slate-50 px-4 py-3 text-sm">
                            <p class="font-medium text-slate-900">{{ $log->aksi->label() }}</p>
                            @if ($log->keterangan)
                                <p class="mt-0.5 text-slate-600">{{ $log->keterangan }}</p>
                            @endif
                            <p class="mt-1 text-xs text-slate-500">
                                {{ $log->pengguna?->nama ?? 'Sistem' }}
                                · {{ $log->created_at->format('d M Y H:i') }}
                            </p>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
@endsection
