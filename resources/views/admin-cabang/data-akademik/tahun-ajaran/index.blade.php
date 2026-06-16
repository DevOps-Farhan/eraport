@extends('layouts.admin-cabang')

@section('title', 'Tahun Ajaran')

@section('content')
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-slate-900">Tahun Ajaran</h1>
            <p class="mt-1 text-sm text-slate-500">Kelola siklus tahun ajaran, semester ganjil & genap cabang sekolah.</p>
        </div>
        <button
            type="button"
            data-modal-open="modal-tambah-tahun-ajaran"
            class="inline-flex items-center justify-center rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700"
        >
            + Tambah Tahun Ajaran
        </button>
    </div>

    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Nama</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Periode</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Status</th>
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
                            <td class="px-4 py-3">
                                @include('admin-cabang.data-akademik.tahun-ajaran._badge-status', ['status' => $item->status])
                            </td>
                            <td class="px-4 py-3 text-sm text-slate-600">
                                @if ($item->isDraft())
                                    <span class="text-slate-400">Belum diaktifkan</span>
                                @else
                                    <div class="space-y-1">
                                        @if ($ganjil)
                                            <p>Ganjil: <span class="font-medium">{{ $ganjil->status->label() }}</span></p>
                                        @endif
                                        @if ($genap)
                                            <p>Genap: <span class="font-medium">{{ $genap->status->label() }}</span></p>
                                        @endif
                                    </div>
                                @endif
                            </td>
                            <td class="whitespace-nowrap px-4 py-3 text-right text-sm">
                                <div class="flex flex-wrap items-center justify-end gap-2">
                                    <button
                                        type="button"
                                        class="rounded-lg px-2.5 py-1.5 text-emerald-600 transition hover:bg-emerald-50"
                                        data-modal-open="modal-detail-tahun-ajaran"
                                        data-tahun-ajaran='@json($item->loadMissing(['semester', 'logAkademik.pengguna']))'
                                    >
                                        Detail
                                    </button>

                                    @if ($item->isDraft())
                                        <button
                                            type="button"
                                            class="rounded-lg px-2.5 py-1.5 text-slate-600 transition hover:bg-slate-100"
                                            data-modal-open="modal-edit-tahun-ajaran"
                                            data-tahun-ajaran='@json($item)'
                                        >
                                            Edit
                                        </button>
                                        @include('admin-cabang.data-akademik.tahun-ajaran._tombol-konfirmasi', [
                                            'action' => route('admin-cabang.data-akademik.tahun-ajaran.aktifkan', $item),
                                            'judul' => 'Aktifkan Tahun Ajaran',
                                            'pesan' => "Yakin ingin mengaktifkan {$item->nama}? Semester ganjil akan dimulai dan tahun ajaran ini menjadi konteks aktif cabang.",
                                            'submitLabel' => 'Ya, Aktifkan',
                                            'submitClass' => 'bg-emerald-600 hover:bg-emerald-700',
                                            'label' => 'Aktifkan',
                                            'buttonClass' => 'rounded-lg px-2.5 py-1.5 font-medium text-emerald-700 transition hover:bg-emerald-50',
                                        ])
                                        <button
                                            type="button"
                                            class="rounded-lg px-2.5 py-1.5 text-red-600 transition hover:bg-red-50"
                                            data-modal-open="modal-hapus-tahun-ajaran"
                                            data-tahun-ajaran='@json($item)'
                                        >
                                            Hapus
                                        </button>
                                    @endif

                                    @if ($item->isAktif())
                                        @if ($ganjil?->status === \App\Enums\StatusSemester::Berjalan)
                                            @include('admin-cabang.data-akademik.tahun-ajaran._tombol-konfirmasi', [
                                                'action' => route('admin-cabang.data-akademik.tahun-ajaran.pindah-genap', $item),
                                                'judul' => 'Mulai Semester Genap',
                                                'pesan' => "Semester ganjil akan ditutup dan semester genap {$item->nama} akan dimulai. Lanjutkan?",
                                                'submitLabel' => 'Ya, Mulai Genap',
                                                'submitClass' => 'bg-blue-600 hover:bg-blue-700',
                                                'label' => 'Mulai Genap',
                                                'buttonClass' => 'rounded-lg px-2.5 py-1.5 font-medium text-blue-700 transition hover:bg-blue-50',
                                            ])
                                        @endif
                                        @if ($genap?->status === \App\Enums\StatusSemester::Berjalan)
                                            @include('admin-cabang.data-akademik.tahun-ajaran._tombol-konfirmasi', [
                                                'action' => route('admin-cabang.data-akademik.tahun-ajaran.akhiri-genap', $item),
                                                'judul' => 'Akhiri Semester Genap',
                                                'pesan' => "Semester genap {$item->nama} akan diselesaikan. Setelah ini Anda dapat menutup tahun ajaran.",
                                                'submitLabel' => 'Ya, Akhiri Genap',
                                                'submitClass' => 'bg-amber-600 hover:bg-amber-700',
                                                'label' => 'Akhiri Genap',
                                                'buttonClass' => 'rounded-lg px-2.5 py-1.5 font-medium text-amber-700 transition hover:bg-amber-50',
                                            ])
                                            @include('admin-cabang.data-akademik.tahun-ajaran._tombol-konfirmasi', [
                                                'action' => route('admin-cabang.data-akademik.tahun-ajaran.kembalikan-ganjil', $item),
                                                'judul' => 'Kembalikan ke Semester Ganjil',
                                                'pesan' => "Semester genap akan dibatalkan dan semester ganjil {$item->nama} dibuka kembali. Hanya tersedia jika belum ada data akademik di genap.",
                                                'submitLabel' => 'Ya, Kembalikan',
                                                'submitClass' => 'bg-violet-600 hover:bg-violet-700',
                                                'label' => 'Batalkan Genap',
                                                'buttonClass' => 'rounded-lg px-2.5 py-1.5 font-medium text-violet-700 transition hover:bg-violet-50',
                                            ])
                                        @endif
                                        @if ($genap?->status === \App\Enums\StatusSemester::Selesai)
                                            @include('admin-cabang.data-akademik.tahun-ajaran._tombol-konfirmasi', [
                                                'action' => route('admin-cabang.data-akademik.tahun-ajaran.buka-kembali-genap', $item),
                                                'judul' => 'Buka Kembali Semester Genap',
                                                'pesan' => "Semester genap {$item->nama} akan dibuka kembali untuk melanjutkan input data.",
                                                'submitLabel' => 'Ya, Buka Kembali',
                                                'submitClass' => 'bg-violet-600 hover:bg-violet-700',
                                                'label' => 'Buka Genap',
                                                'buttonClass' => 'rounded-lg px-2.5 py-1.5 font-medium text-violet-700 transition hover:bg-violet-50',
                                            ])
                                            @include('admin-cabang.data-akademik.tahun-ajaran._tombol-konfirmasi', [
                                                'action' => route('admin-cabang.data-akademik.tahun-ajaran.tutup', $item),
                                                'judul' => 'Tutup Tahun Ajaran',
                                                'pesan' => "Tahun ajaran {$item->nama} akan ditutup dan diarsipkan. Tindakan ini tidak dapat dibatalkan kecuali dengan membuka kembali TA.",
                                                'submitLabel' => 'Ya, Tutup TA',
                                                'submitClass' => 'bg-slate-700 hover:bg-slate-800',
                                                'label' => 'Tutup TA',
                                                'buttonClass' => 'rounded-lg px-2.5 py-1.5 font-medium text-slate-700 transition hover:bg-slate-100',
                                            ])
                                        @endif
                                    @endif

                                    @if ($item->isDitutup())
                                        @include('admin-cabang.data-akademik.tahun-ajaran._tombol-konfirmasi', [
                                            'action' => route('admin-cabang.data-akademik.tahun-ajaran.buka-kembali', $item),
                                            'judul' => 'Buka Kembali Tahun Ajaran',
                                            'pesan' => "Tahun ajaran {$item->nama} akan diaktifkan kembali dengan semester genap berjalan. Pastikan tidak ada TA lain yang sedang aktif.",
                                            'submitLabel' => 'Ya, Buka Kembali',
                                            'submitClass' => 'bg-indigo-600 hover:bg-indigo-700',
                                            'label' => 'Buka Kembali',
                                            'buttonClass' => 'rounded-lg px-2.5 py-1.5 font-medium text-indigo-700 transition hover:bg-indigo-50',
                                        ])
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-10 text-center text-sm text-slate-500">
                                Belum ada tahun ajaran. Klik "Tambah Tahun Ajaran" untuk memulai.
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

@push('modals')
    <div id="modal-tambah-tahun-ajaran" class="modal hidden" aria-hidden="true">
        <div class="modal-backdrop fixed inset-0 z-40 bg-slate-900/50" data-modal-close></div>
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="w-full max-w-lg rounded-2xl bg-white shadow-xl" role="dialog" aria-modal="true">
                <div class="border-b border-slate-200 px-6 py-4">
                    <h2 class="text-lg font-semibold text-slate-900">Tambah Tahun Ajaran</h2>
                </div>
                <form method="POST" action="{{ route('admin-cabang.data-akademik.tahun-ajaran.store') }}" class="px-6 py-4">
                    @csrf
                    @include('admin-cabang.data-akademik.tahun-ajaran._form-fields', ['prefix' => 'tambah'])
                    <div class="mt-6 flex justify-end gap-3 border-t border-slate-200 pt-4">
                        <button type="button" data-modal-close class="rounded-xl border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">Batal</button>
                        <button type="submit" class="rounded-xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="modal-edit-tahun-ajaran" class="modal hidden" aria-hidden="true">
        <div class="modal-backdrop fixed inset-0 z-40 bg-slate-900/50" data-modal-close></div>
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="w-full max-w-lg rounded-2xl bg-white shadow-xl" role="dialog" aria-modal="true">
                <div class="border-b border-slate-200 px-6 py-4">
                    <h2 class="text-lg font-semibold text-slate-900">Edit Tahun Ajaran</h2>
                </div>
                <form id="form-edit-tahun-ajaran" method="POST" action="" class="px-6 py-4">
                    @csrf
                    @method('PUT')
                    @include('admin-cabang.data-akademik.tahun-ajaran._form-fields', ['prefix' => 'edit'])
                    <div class="mt-6 flex justify-end gap-3 border-t border-slate-200 pt-4">
                        <button type="button" data-modal-close class="rounded-xl border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">Batal</button>
                        <button type="submit" class="rounded-xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700">Perbarui</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="modal-detail-tahun-ajaran" class="modal hidden" aria-hidden="true">
        <div class="modal-backdrop fixed inset-0 z-40 bg-slate-900/50" data-modal-close></div>
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="w-full max-w-lg rounded-2xl bg-white shadow-xl" role="dialog" aria-modal="true">
                <div class="border-b border-slate-200 px-6 py-4">
                    <h2 class="text-lg font-semibold text-slate-900">Detail Tahun Ajaran</h2>
                </div>
                <div class="px-6 py-4">
                    @include('admin-cabang.data-akademik.tahun-ajaran._form-fields', ['prefix' => 'detail', 'readonly' => true])
                    <div id="detail-semester-list" class="mt-4 space-y-2 rounded-xl bg-slate-50 p-4 text-sm text-slate-700"></div>
                    <div id="detail-log-list" class="mt-4 space-y-2"></div>
                    <div class="mt-6 flex justify-end border-t border-slate-200 pt-4">
                        <button type="button" data-modal-close class="rounded-xl border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="modal-hapus-tahun-ajaran" class="modal hidden" aria-hidden="true">
        <div class="modal-backdrop fixed inset-0 z-40 bg-slate-900/50" data-modal-close></div>
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="w-full max-w-md rounded-2xl bg-white shadow-xl" role="dialog" aria-modal="true">
                <div class="border-b border-slate-200 px-6 py-4">
                    <h2 class="text-lg font-semibold text-slate-900">Hapus Tahun Ajaran</h2>
                </div>
                <form id="form-hapus-tahun-ajaran" method="POST" action="" class="px-6 py-4">
                    @csrf
                    @method('DELETE')
                    <p class="text-sm text-slate-600">
                        Yakin ingin menghapus tahun ajaran <span id="hapus-tahun-ajaran-nama" class="font-semibold text-slate-900"></span>?
                    </p>
                    <div class="mt-6 flex justify-end gap-3 border-t border-slate-200 pt-4">
                        <button type="button" data-modal-close class="rounded-xl border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">Batal</button>
                        <button type="submit" class="rounded-xl bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-700">Hapus</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @include('admin-cabang.data-akademik.tahun-ajaran._modal-konfirmasi')
@endpush

@if ($errors->any())
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                document.getElementById('modal-tambah-tahun-ajaran')?.classList.remove('hidden');
                document.body.classList.add('overflow-hidden');
            });
        </script>
    @endpush
@endif
