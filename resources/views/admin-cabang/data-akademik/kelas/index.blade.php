@extends('layouts.admin-cabang')

@section('title', 'Kelas')

@section('content')
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-slate-900">Kelas</h1>
            <p class="mt-1 text-sm text-slate-500">Kelola kelas per tahun ajaran aktif.</p>
        </div>
        @if ($tahunAjaran)
            <button type="button" data-modal-open="modal-tambah-kelas" class="inline-flex items-center justify-center rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700">+ Tambah Kelas</button>
        @endif
    </div>

    @include('partials.admin-cabang.banner-tahun-ajaran', ['tahunAjaran' => $tahunAjaran])

    @if ($tahunAjaran)
        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">Nama</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">Jurusan</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">Fase</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">Tingkat</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">Wali Kelas</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold uppercase text-slate-500">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($kelas as $item)
                        <tr class="hover:bg-slate-50">
                            <td class="px-4 py-3 text-sm font-medium">{{ $item->nama }}</td>
                            <td class="px-4 py-3 text-sm">{{ $item->jurusan->nama }}</td>
                            <td class="px-4 py-3 text-sm font-medium">{{ $item->fase->label() }}</td>
                            <td class="px-4 py-3 text-sm">{{ $item->tingkat->label() }}</td>
                            <td class="px-4 py-3 text-sm">{{ $item->waliKelas?->nama ?? '—' }}</td>
                            <td class="px-4 py-3 text-right text-sm">
                                <button type="button" class="rounded-lg px-2.5 py-1.5 text-emerald-600 hover:bg-emerald-50" data-modal-open="modal-detail-kelas" data-kelas='@json($item->loadMissing(["jurusan","waliKelas"]))'>Detail</button>
                                <button type="button" class="rounded-lg px-2.5 py-1.5 text-slate-600 hover:bg-slate-100" data-modal-open="modal-edit-kelas" data-kelas='@json($item->loadMissing(["jurusan","waliKelas"]))'>Edit</button>
                                <button type="button" class="rounded-lg px-2.5 py-1.5 text-red-600 hover:bg-red-50" data-modal-open="modal-hapus-kelas" data-kelas='@json($item)'>Hapus</button>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-4 py-10 text-center text-sm text-slate-500">Belum ada kelas untuk tahun ajaran ini.</td></tr>
                    @endforelse
                </tbody>
            </table>
            @if ($kelas->hasPages())<div class="border-t px-4 py-3">{{ $kelas->links() }}</div>@endif
        </div>
    @endif
@endsection

@push('modals')
@include('admin-cabang.data-akademik.kelas._modals', ['jurusanList' => $jurusanList, 'guruList' => $guruList])
@endpush
