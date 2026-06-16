@extends('layouts.admin-cabang')

@section('title', 'Ekstrakurikuler')

@section('content')
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-slate-900">Ekstrakurikuler</h1>
            <p class="mt-1 text-sm text-slate-500">Kelola kegiatan ekstrakurikuler dan penugasan pembina per tahun ajaran.</p>
        </div>
        <button type="button" data-modal-open="modal-tambah-ekstrakurikuler" class="inline-flex items-center justify-center rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700">+ Tambah Ekstrakurikuler</button>
    </div>

    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="border-b border-slate-200 px-4 py-3"><h2 class="text-sm font-semibold text-slate-900">Daftar Ekstrakurikuler</h2></div>
        <table class="min-w-full divide-y divide-slate-200">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">Kode</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">Nama</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">Status</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold uppercase text-slate-500">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($ekstrakurikuler as $item)
                    <tr class="hover:bg-slate-50">
                        <td class="px-4 py-3 text-sm font-medium">{{ $item->kode }}</td>
                        <td class="px-4 py-3 text-sm">{{ $item->nama }}</td>
                        <td class="px-4 py-3">@include('partials.badge-aktif', ['aktif' => $item->aktif])</td>
                        <td class="px-4 py-3 text-right text-sm">
                            <button type="button" class="rounded-lg px-2.5 py-1.5 text-emerald-600 hover:bg-emerald-50" data-modal-open="modal-detail-ekstrakurikuler" data-ekstrakurikuler='@json($item)'>Detail</button>
                            <button type="button" class="rounded-lg px-2.5 py-1.5 text-slate-600 hover:bg-slate-100" data-modal-open="modal-edit-ekstrakurikuler" data-ekstrakurikuler='@json($item)'>Edit</button>
                            <button type="button" class="rounded-lg px-2.5 py-1.5 text-red-600 hover:bg-red-50" data-modal-open="modal-hapus-ekstrakurikuler" data-ekstrakurikuler='@json($item)'>Hapus</button>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="px-4 py-10 text-center text-sm text-slate-500">Belum ada ekstrakurikuler.</td></tr>
                @endforelse
            </tbody>
        </table>
        @if ($ekstrakurikuler->hasPages())<div class="border-t px-4 py-3">{{ $ekstrakurikuler->links() }}</div>@endif
    </div>

    <div class="mt-8">
        <h2 class="text-lg font-semibold text-slate-900">Penugasan Pembina</h2>
        <p class="mt-1 text-sm text-slate-500">Tetapkan pembina untuk setiap ekstrakurikuler pada tahun ajaran aktif.</p>
    </div>

    @include('partials.admin-cabang.banner-tahun-ajaran', ['tahunAjaran' => $tahunAjaran])

    @if ($tahunAjaran && $pembinaList->isNotEmpty() && $ekstraAktifList->isNotEmpty())
        <div class="mb-4 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
            <form method="POST" action="{{ route('admin-cabang.data-akademik.ekstrakurikuler.penugasan.store') }}" class="flex flex-col gap-3 sm:flex-row sm:items-end">
                @csrf
                <input type="hidden" name="tahun_ajaran_id" value="{{ $tahunAjaran->id }}">
                <div class="flex-1">
                    <label class="mb-1 block text-sm font-medium text-slate-700">Ekstrakurikuler</label>
                    <select name="ekstrakurikuler_id" required class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm">
                        <option value="">Pilih ekstrakurikuler</option>
                        @foreach ($ekstraAktifList as $ekstra)
                            <option value="{{ $ekstra->id }}">{{ $ekstra->kode }} — {{ $ekstra->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex-1">
                    <label class="mb-1 block text-sm font-medium text-slate-700">Pembina</label>
                    <select name="pembina_id" required class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm">
                        <option value="">Pilih pembina</option>
                        @foreach ($pembinaList as $pembina)
                            <option value="{{ $pembina->id }}">{{ $pembina->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-emerald-700">Tambah Penugasan</button>
            </form>
        </div>
    @endif

    @if ($tahunAjaran)
        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">Ekstrakurikuler</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">Pembina</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold uppercase text-slate-500">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($penugasan as $item)
                        <tr class="hover:bg-slate-50">
                            <td class="px-4 py-3 text-sm">{{ $item->ekstrakurikuler->nama }}</td>
                            <td class="px-4 py-3 text-sm">{{ $item->pembina->nama }}</td>
                            <td class="px-4 py-3 text-right text-sm">
                                <form method="POST" action="{{ route('admin-cabang.data-akademik.ekstrakurikuler.penugasan.destroy', $item) }}" onsubmit="return confirm('Hapus penugasan pembina ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="rounded-lg px-2.5 py-1.5 text-red-600 hover:bg-red-50">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="px-4 py-10 text-center text-sm text-slate-500">Belum ada penugasan pembina.</td></tr>
                    @endforelse
                </tbody>
            </table>
            @if ($penugasan->hasPages())<div class="border-t px-4 py-3">{{ $penugasan->links() }}</div>@endif
        </div>
    @endif
@endsection

@push('modals')
@include('admin-cabang.data-akademik.ekstrakurikuler._modals')
@endpush
