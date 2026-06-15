@extends('layouts.admin-pusat')

@section('title', $judul)

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-slate-900">Kelola {{ $judul }}</h1>
        <p class="mt-1 text-sm text-slate-500">Daftar pengguna dengan peran {{ strtolower($judul) }} di seluruh cabang.</p>
    </div>

    <div class="mb-6 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
        <form method="GET" action="{{ route($routeName) }}" class="flex flex-col gap-3 sm:flex-row">
            <div class="flex-1">
                <label for="cari" class="sr-only">Cari pengguna</label>
                <input
                    id="cari"
                    name="cari"
                    type="search"
                    value="{{ $cari }}"
                    placeholder="Cari berdasarkan username, nama, atau email..."
                    class="block w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200"
                >
            </div>
            <div class="flex gap-2">
                <button
                    type="submit"
                    class="rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-indigo-700"
                >
                    Cari
                </button>
                @if ($cari !== '')
                    <a
                        href="{{ route($routeName) }}"
                        class="rounded-xl border border-slate-300 px-4 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-50"
                    >
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Username</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Nama</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Email</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Cabang</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($pengguna as $item)
                        <tr class="hover:bg-slate-50">
                            <td class="whitespace-nowrap px-4 py-3 text-sm font-medium text-slate-900">{{ $item->username }}</td>
                            <td class="px-4 py-3 text-sm text-slate-700">{{ $item->nama }}</td>
                            <td class="px-4 py-3 text-sm text-slate-600">{{ $item->email ?? '—' }}</td>
                            <td class="px-4 py-3 text-sm text-slate-600">{{ $item->cabangSekolah?->nama ?? '—' }}</td>
                            <td class="px-4 py-3">
                                @if ($item->aktif)
                                    <span class="inline-flex rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-700">Aktif</span>
                                @else
                                    <span class="inline-flex rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-medium text-slate-600">Nonaktif</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-10 text-center text-sm text-slate-500">
                                @if ($cari !== '')
                                    Tidak ditemukan pengguna dengan kata kunci "{{ $cari }}".
                                @else
                                    Belum ada data {{ strtolower($judul) }}.
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($pengguna->hasPages())
            <div class="border-t border-slate-200 px-4 py-3">
                {{ $pengguna->links() }}
            </div>
        @endif
    </div>
@endsection
