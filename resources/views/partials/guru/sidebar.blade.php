@php
    $cabangNama = auth()->user()->cabangSekolah?->nama;
@endphp

<aside class="fixed inset-y-0 left-0 z-30 hidden w-64 flex-col border-r border-slate-200 bg-white lg:flex">
    <div class="border-b border-slate-200 px-6 py-5">
        <p class="text-lg font-bold text-sky-600">eRaport</p>
        <p class="mt-0.5 text-xs font-medium uppercase tracking-wide text-slate-400">Guru</p>
        @if ($cabangNama)
            <p class="mt-2 truncate text-xs font-medium text-sky-700">{{ $cabangNama }}</p>
        @endif
    </div>

    <nav class="flex-1 space-y-1 overflow-y-auto px-3 py-4">
        <a
            href="{{ route('guru.dashboard') }}"
            @class([
                'flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition',
                'bg-sky-50 text-sky-700' => request()->routeIs('guru.dashboard'),
                'text-slate-600 hover:bg-slate-50 hover:text-slate-900' => ! request()->routeIs('guru.dashboard'),
            ])
        >
            <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z" />
            </svg>
            Dashboard
        </a>

        <details
            class="group"
            @if (request()->routeIs('guru.kelola-nilai.*')) open @endif
        >
            <summary
                @class([
                    'flex cursor-pointer list-none items-center justify-between gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition [&::-webkit-details-marker]:hidden',
                    'bg-sky-50 text-sky-700' => request()->routeIs('guru.kelola-nilai.*'),
                    'text-slate-600 hover:bg-slate-50 hover:text-slate-900' => ! request()->routeIs('guru.kelola-nilai.*'),
                ])
            >
                <span class="flex items-center gap-3">
                    <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                    </svg>
                    Kelola Nilai
                </span>
                <svg class="h-4 w-4 shrink-0 transition group-open:rotate-180" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                </svg>
            </summary>

            <div class="ml-4 mt-1 space-y-1 border-l border-slate-200 pl-3">
                @include('partials.guru.sidebar-link', ['route' => 'guru.kelola-nilai.tujuan-pembelajaran', 'label' => 'Tujuan Pembelajaran'])
                @include('partials.guru.sidebar-link', ['route' => 'guru.kelola-nilai.input-nilai-tp', 'label' => 'Input Nilai Tujuan Pembelajaran'])
                @include('partials.guru.sidebar-link', ['route' => 'guru.kelola-nilai.nilai-ujian', 'label' => 'Nilai Ujian'])
            </div>
        </details>

        <details
            class="group"
            @if (request()->routeIs('guru.wali-kelas.*')) open @endif
        >
            <summary
                @class([
                    'flex cursor-pointer list-none items-center justify-between gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition [&::-webkit-details-marker]:hidden',
                    'bg-sky-50 text-sky-700' => request()->routeIs('guru.wali-kelas.*'),
                    'text-slate-600 hover:bg-slate-50 hover:text-slate-900' => ! request()->routeIs('guru.wali-kelas.*'),
                ])
            >
                <span class="flex items-center gap-3">
                    <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" />
                    </svg>
                    Wali Kelas
                </span>
                <svg class="h-4 w-4 shrink-0 transition group-open:rotate-180" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                </svg>
            </summary>

            <div class="ml-4 mt-1 space-y-1 border-l border-slate-200 pl-3">
                @include('partials.guru.sidebar-link', ['route' => 'guru.wali-kelas.kelas', 'label' => 'Kelola Data Kelas'])
                @include('partials.guru.sidebar-link', ['route' => 'guru.wali-kelas.absensi-catatan', 'label' => 'Rekap Absensi & Catatan'])
                @include('partials.guru.sidebar-link', ['route' => 'guru.wali-kelas.leger-raport', 'label' => 'Leger & Cetak Raport'])
            </div>
        </details>

        <a
            href="{{ route('profil.edit') }}"
            @class([
                'flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition',
                'bg-sky-50 text-sky-700' => request()->routeIs('profil.*'),
                'text-slate-600 hover:bg-slate-50 hover:text-slate-900' => ! request()->routeIs('profil.*'),
            ])
        >
            <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
            </svg>
            Profil Akun
        </a>
    </nav>

    <div class="border-t border-slate-200 p-3">
        <div class="mb-3 rounded-xl bg-slate-50 px-3 py-2">
            <p class="truncate text-sm font-medium text-slate-900">{{ auth()->user()->nama }}</p>
            <p class="truncate text-xs text-slate-500">{{ auth()->user()->username }}</p>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button
                type="submit"
                class="flex w-full items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-red-600 transition hover:bg-red-50"
            >
                <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9" />
                </svg>
                Logout
            </button>
        </form>
    </div>
</aside>
