@extends('layouts.guru')

@section('title', 'Kelola Data Kelas')

@section('content')
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-slate-900">Kelola Data Kelas</h1>
            <p class="mt-1 text-sm text-slate-500">Kelola data siswa dan informasi kelas yang Anda wali.</p>
        </div>
        @if ($kelas)
            <button type="button" data-modal-open="modal-tambah-siswa-wali" class="inline-flex items-center justify-center rounded-xl bg-sky-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-sky-700">+ Tambah Siswa</button>
        @endif
    </div>

    @if (! $kelas)
        <div class="rounded-2xl border border-slate-200 bg-white px-6 py-10 text-center text-sm text-slate-600">
            Anda belum ditunjuk sebagai wali kelas di tahun ajaran aktif.
        </div>
    @else
        <div class="mb-4 rounded-xl border border-sky-200 bg-sky-50 px-4 py-3 text-sm text-sky-800">
            Kelas: <strong>{{ $kelas->nama }}</strong> — {{ $kelas->jurusan->nama }}
        </div>

        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">NIS</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">Nama</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">JK</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold uppercase text-slate-500">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($siswaList as $siswa)
                        <tr class="hover:bg-slate-50">
                            <td class="px-4 py-3 text-sm font-medium">{{ $siswa->nis }}</td>
                            <td class="px-4 py-3 text-sm">{{ $siswa->nama }}</td>
                            <td class="px-4 py-3 text-sm">{{ $siswa->jenis_kelamin->label() }}</td>
                            <td class="px-4 py-3 text-right text-sm">
                                <button type="button" class="rounded-lg px-2.5 py-1.5 text-slate-600 hover:bg-slate-100" data-modal-open="modal-edit-siswa-wali" data-siswa='@json($siswa)'>Edit</button>
                                <button type="button" class="rounded-lg px-2.5 py-1.5 text-red-600 hover:bg-red-50" data-modal-open="modal-hapus-siswa-wali" data-siswa='@json($siswa)'>Hapus</button>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="px-4 py-10 text-center text-sm text-slate-500">Belum ada siswa di kelas ini.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @endif
@endsection

@push('modals')
    @if ($kelas)
        @include('guru.wali-kelas.kelas._modals')
    @endif
@endpush
