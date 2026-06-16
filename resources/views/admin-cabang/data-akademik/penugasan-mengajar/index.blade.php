@extends('layouts.admin-cabang')

@section('title', 'Penugasan Mengajar')

@section('content')
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-slate-900">Penugasan Mengajar</h1>
            <p class="mt-1 text-sm text-slate-500">Atur guru ke mata pelajaran dan kelas pada tahun ajaran aktif.</p>
        </div>
        @if ($tahunAjaran && $guruList->isNotEmpty() && $mataPelajaranList->isNotEmpty() && $kelasList->isNotEmpty())
            <button type="button" data-modal-open="modal-tambah-penugasan" class="inline-flex items-center justify-center rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700">+ Tambah Penugasan</button>
        @endif
    </div>

    @include('partials.admin-cabang.banner-tahun-ajaran', ['tahunAjaran' => $tahunAjaran])

    @if ($tahunAjaran)
        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">Guru</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">Mata Pelajaran</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">Kelas</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold uppercase text-slate-500">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($penugasan as $item)
                        <tr class="hover:bg-slate-50">
                            <td class="px-4 py-3 text-sm">{{ $item->guru->nama }}</td>
                            <td class="px-4 py-3 text-sm">{{ $item->mataPelajaran->nama }}</td>
                            <td class="px-4 py-3 text-sm">{{ $item->kelas->nama }}</td>
                            <td class="px-4 py-3 text-right text-sm">
                                <form method="POST" action="{{ route('admin-cabang.data-akademik.penugasan-mengajar.destroy', $item) }}" onsubmit="return confirm('Hapus penugasan ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="rounded-lg px-2.5 py-1.5 text-red-600 hover:bg-red-50">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="px-4 py-10 text-center text-sm text-slate-500">Belum ada penugasan mengajar.</td></tr>
                    @endforelse
                </tbody>
            </table>
            @if ($penugasan->hasPages())<div class="border-t px-4 py-3">{{ $penugasan->links() }}</div>@endif
        </div>
    @endif
@endsection

@push('modals')
@if ($tahunAjaran)
<div id="modal-tambah-penugasan" class="modal hidden" aria-hidden="true">
    <div class="modal-backdrop fixed inset-0 z-40 bg-slate-900/50" data-modal-close></div>
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="w-full max-w-lg rounded-2xl bg-white shadow-xl">
            <div class="border-b px-6 py-4"><h2 class="text-lg font-semibold">Tambah Penugasan Mengajar</h2></div>
            <form method="POST" action="{{ route('admin-cabang.data-akademik.penugasan-mengajar.store') }}" class="px-6 py-4">
                @csrf
                <input type="hidden" name="tahun_ajaran_id" value="{{ $tahunAjaran->id }}">
                <div class="space-y-4">
                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-700">Guru</label>
                        <select name="guru_id" required class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm">
                            <option value="">Pilih guru</option>
                            @foreach ($guruList as $guru)
                                <option value="{{ $guru->id }}">{{ $guru->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-700">Mata Pelajaran</label>
                        <select name="mata_pelajaran_id" required class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm">
                            <option value="">Pilih mapel</option>
                            @foreach ($mataPelajaranList as $mapel)
                                <option value="{{ $mapel->id }}">{{ $mapel->kode }} — {{ $mapel->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-700">Kelas</label>
                        <select name="kelas_id" required class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm">
                            <option value="">Pilih kelas</option>
                            @foreach ($kelasList as $kelas)
                                <option value="{{ $kelas->id }}">{{ $kelas->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="mt-6 flex justify-end gap-3 border-t pt-4">
                    <button type="button" data-modal-close class="rounded-xl border px-4 py-2 text-sm">Batal</button>
                    <button type="submit" class="rounded-xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endpush
