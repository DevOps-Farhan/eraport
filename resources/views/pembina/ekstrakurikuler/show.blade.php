@extends('layouts.pembina')

@section('title', 'Anggota Ekstrakurikuler - ' . $ekstrakurikuler->nama)

@section('content')
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <div class="flex items-center gap-3">
                <a href="{{ route('pembina.ekstrakurikuler.index') }}" class="rounded-full bg-slate-100 p-2 text-slate-500 transition hover:bg-slate-200 hover:text-slate-900">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                    </svg>
                </a>
                <h1 class="text-2xl font-semibold text-slate-900">{{ $ekstrakurikuler->nama }}</h1>
            </div>
            <p class="mt-1 pl-11 text-sm text-slate-500">Kelola anggota pada tahun ajaran {{ $tahunAjaran->nama }}.</p>
        </div>
        <button type="button" onclick="document.getElementById('modal-tambah-anggota').classList.remove('hidden')" class="inline-flex items-center justify-center rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700">
            + Tambah Anggota
        </button>
    </div>

    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="border-b border-slate-200 bg-slate-50 px-4 py-3">
            <h3 class="font-medium text-slate-900">Daftar Anggota ({{ $anggotaList->count() }} Siswa)</h3>
        </div>
        <table class="min-w-full divide-y divide-slate-200">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">Nama Lengkap</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">NIS / NISN</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">Kelas Saat Ini</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold uppercase text-slate-500">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($anggotaList as $anggota)
                    <tr class="hover:bg-slate-50">
                        <td class="px-4 py-3 text-sm font-medium text-slate-900">{{ $anggota->siswa->nama }}</td>
                        <td class="px-4 py-3 text-sm text-slate-600">{{ $anggota->siswa->nis }} / {{ $anggota->siswa->nisn }}</td>
                        <td class="px-4 py-3 text-sm text-slate-600">{{ $anggota->siswa->rombelSiswa->first()?->kelas->nama ?? '—' }}</td>
                        <td class="px-4 py-3 text-right text-sm">
                            <form action="{{ route('pembina.ekstrakurikuler.anggota.destroy', [$ekstrakurikuler, $anggota->siswa_id]) }}" method="POST" onsubmit="return confirm('Keluarkan siswa ini dari anggota ekstrakurikuler?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="rounded-lg px-2.5 py-1.5 text-red-600 transition hover:bg-red-50">Keluarkan</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-4 py-10 text-center text-sm text-slate-500">
                            Belum ada anggota pada ekstrakurikuler ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Modal Tambah Anggota -->
    <div id="modal-tambah-anggota" class="fixed inset-0 z-50 hidden overflow-y-auto bg-slate-900/50 backdrop-blur-sm" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex min-h-screen items-center justify-center p-4">
            <div class="w-full max-w-2xl rounded-2xl bg-white shadow-xl">
                <form action="{{ route('pembina.ekstrakurikuler.anggota.store', $ekstrakurikuler) }}" method="POST">
                    @csrf
                    <div class="border-b px-6 py-4 flex items-center justify-between">
                        <h2 class="text-lg font-semibold" id="modal-title">Pilih Calon Anggota</h2>
                        <button type="button" onclick="document.getElementById('modal-tambah-anggota').classList.add('hidden')" class="text-slate-400 hover:text-slate-500">
                            <span class="sr-only">Tutup</span>
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg>
                        </button>
                    </div>
                    <div class="max-h-[60vh] overflow-y-auto p-6">
                        @if($calonAnggotaList->isEmpty())
                            <p class="text-center text-sm text-slate-500">Semua siswa aktif sudah menjadi anggota ekstrakurikuler ini.</p>
                        @else
                            <div class="mb-4">
                                <input type="text" id="cari-siswa" placeholder="Cari nama atau kelas siswa..." class="w-full rounded-xl border-slate-300 px-4 py-2 text-sm focus:border-emerald-500 focus:ring-emerald-500">
                            </div>
                            <div class="space-y-2" id="daftar-calon-siswa">
                                @foreach($calonAnggotaList as $calon)
                                    <label class="flex cursor-pointer items-center gap-3 rounded-xl border border-slate-200 p-3 hover:bg-slate-50 calon-item">
                                        <input type="checkbox" name="siswa_ids[]" value="{{ $calon->id }}" class="h-4 w-4 rounded border-slate-300 text-emerald-600 focus:ring-emerald-600">
                                        <div class="flex-1">
                                            <p class="text-sm font-medium text-slate-900 nama-siswa">{{ $calon->nama }}</p>
                                            <p class="text-xs text-slate-500 kelas-siswa">Kelas: {{ $calon->rombelSiswa->first()?->kelas->nama ?? 'Belum ada kelas' }}</p>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        @endif
                    </div>
                    <div class="flex items-center justify-end gap-3 border-t bg-slate-50 px-6 py-4">
                        <button type="button" onclick="document.getElementById('modal-tambah-anggota').classList.add('hidden')" class="rounded-xl px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-100">Batal</button>
                        <button type="submit" @if($calonAnggotaList->isEmpty()) disabled @endif class="rounded-xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-emerald-700 disabled:opacity-50">Tambahkan yang Dipilih</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const searchInput = document.getElementById('cari-siswa');
                if (searchInput) {
                    searchInput.addEventListener('input', (e) => {
                        const term = e.target.value.toLowerCase();
                        document.querySelectorAll('.calon-item').forEach(item => {
                            const nama = item.querySelector('.nama-siswa').textContent.toLowerCase();
                            const kelas = item.querySelector('.kelas-siswa').textContent.toLowerCase();
                            if (nama.includes(term) || kelas.includes(term)) {
                                item.style.display = 'flex';
                            } else {
                                item.style.display = 'none';
                            }
                        });
                    });
                }
            });
        </script>
    @endpush
@endsection
