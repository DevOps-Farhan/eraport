@extends('layouts.admin-cabang')

@section('title', 'Siswa')

@section('content')
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-slate-900">Siswa</h1>
            <p class="mt-1 text-sm text-slate-500">Kelola data siswa dan penempatan kelas (rombel) tahun ajaran aktif.</p>
        </div>
        <button type="button" data-modal-open="modal-tambah-siswa" class="inline-flex items-center justify-center rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700">+ Tambah Siswa</button>
    </div>

    @include('partials.admin-cabang.banner-tahun-ajaran', ['tahunAjaran' => $tahunAjaran])

    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <table class="min-w-full divide-y divide-slate-200">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">NIS</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">Nama</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">JK</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">Kelas (TA Aktif)</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">Status</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold uppercase text-slate-500">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($siswa as $item)
                    @php $rombel = $item->rombelSiswa->first(); @endphp
                    <tr class="hover:bg-slate-50">
                        <td class="px-4 py-3 text-sm font-medium">{{ $item->nis }}</td>
                        <td class="px-4 py-3 text-sm">{{ $item->nama }}</td>
                        <td class="px-4 py-3 text-sm">{{ $item->jenis_kelamin->label() }}</td>
                        <td class="px-4 py-3 text-sm">{{ $rombel?->kelas?->nama ?? '—' }}</td>
                        <td class="px-4 py-3">@include('partials.badge-aktif', ['aktif' => $item->aktif])</td>
                        <td class="px-4 py-3 text-right text-sm">
                            <button type="button" class="rounded-lg px-2.5 py-1.5 text-emerald-600 hover:bg-emerald-50" data-modal-open="modal-detail-siswa" data-siswa='@json($item->loadMissing(["rombelSiswa.kelas"]))'>Detail</button>
                            <button type="button" class="rounded-lg px-2.5 py-1.5 text-slate-600 hover:bg-slate-100" data-modal-open="modal-edit-siswa" data-siswa='@json($item)'>Edit</button>
                            @if ($tahunAjaran && $kelasList->isNotEmpty())
                                <button type="button" class="rounded-lg px-2.5 py-1.5 text-blue-600 hover:bg-blue-50" data-modal-open="modal-rombel-siswa" data-siswa='@json($item->loadMissing(["rombelSiswa"]))'>Tempatkan</button>
                            @endif
                            <button type="button" class="rounded-lg px-2.5 py-1.5 text-red-600 hover:bg-red-50" data-modal-open="modal-hapus-siswa" data-siswa='@json($item)'>Hapus</button>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-4 py-10 text-center text-sm text-slate-500">Belum ada siswa.</td></tr>
                @endforelse
            </tbody>
        </table>
        @if ($siswa->hasPages())<div class="border-t px-4 py-3">{{ $siswa->links() }}</div>@endif
    </div>
@endsection

@push('modals')
@include('admin-cabang.master-data.siswa._modals', ['kelasList' => $kelasList, 'tahunAjaran' => $tahunAjaran])
@endpush
