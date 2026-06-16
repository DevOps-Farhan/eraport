@php
    $cabangNama = auth()->user()->cabangSekolah?->nama;
@endphp

<aside class="fixed inset-y-0 left-0 z-30 hidden w-64 flex-col border-r border-slate-200 bg-white lg:flex">
    <div class="border-b border-slate-200 px-6 py-5">
        <p class="text-lg font-bold text-emerald-600">eRaport</p>
        <p class="mt-0.5 text-xs font-medium uppercase tracking-wide text-slate-400">Admin Cabang</p>
        @if ($cabangNama)
            <p class="mt-2 truncate text-xs font-medium text-emerald-700">{{ $cabangNama }}</p>
        @endif
    </div>

    <nav class="flex-1 space-y-1 overflow-y-auto px-3 py-4">
        {{-- Dashboard --}}
        <a
            href="{{ route('admin-cabang.dashboard') }}"
            @class([
                'flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition',
                'bg-emerald-50 text-emerald-700' => request()->routeIs('admin-cabang.dashboard'),
                'text-slate-600 hover:bg-slate-50 hover:text-slate-900' => ! request()->routeIs('admin-cabang.dashboard'),
            ])
        >
            <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z" />
            </svg>
            Dashboard
        </a>

        {{-- Data Akademik --}}
        <details
            class="group"
            @if (request()->routeIs('admin-cabang.data-akademik.*')) open @endif
        >
            <summary
                @class([
                    'flex cursor-pointer list-none items-center justify-between gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition [&::-webkit-details-marker]:hidden',
                    'bg-emerald-50 text-emerald-700' => request()->routeIs('admin-cabang.data-akademik.*'),
                    'text-slate-600 hover:bg-slate-50 hover:text-slate-900' => ! request()->routeIs('admin-cabang.data-akademik.*'),
                ])
            >
                <span class="flex items-center gap-3">
                    <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443m-7.007 11.55A5.981 5.981 0 0 0 6.75 15.75v-1.5" />
                    </svg>
                    Data Akademik
                </span>
                <svg class="h-4 w-4 shrink-0 transition group-open:rotate-180" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                </svg>
            </summary>

            <div class="ml-4 mt-1 space-y-1 border-l border-slate-200 pl-3">
                @include('partials.admin-cabang.sidebar-link', ['route' => 'admin-cabang.data-akademik.tahun-ajaran.index', 'label' => 'Tahun Ajaran'])
                @include('partials.admin-cabang.sidebar-link', ['route' => 'admin-cabang.data-akademik.jurusan', 'label' => 'Jurusan'])
                @include('partials.admin-cabang.sidebar-link', ['route' => 'admin-cabang.data-akademik.kelas', 'label' => 'Kelas'])
                @include('partials.admin-cabang.sidebar-link', ['route' => 'admin-cabang.data-akademik.mata-pelajaran', 'label' => 'Mata Pelajaran'])
                @include('partials.admin-cabang.sidebar-link', ['route' => 'admin-cabang.data-akademik.ekstrakurikuler', 'label' => 'Ekstrakurikuler'])
                @include('partials.admin-cabang.sidebar-link', ['route' => 'admin-cabang.data-akademik.penugasan-mengajar', 'label' => 'Penugasan Mengajar'])
            </div>
        </details>

        {{-- Master Data --}}
        <details
            class="group"
            @if (request()->routeIs('admin-cabang.master-data.*')) open @endif
        >
            <summary
                @class([
                    'flex cursor-pointer list-none items-center justify-between gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition [&::-webkit-details-marker]:hidden',
                    'bg-emerald-50 text-emerald-700' => request()->routeIs('admin-cabang.master-data.*'),
                    'text-slate-600 hover:bg-slate-50 hover:text-slate-900' => ! request()->routeIs('admin-cabang.master-data.*'),
                ])
            >
                <span class="flex items-center gap-3">
                    <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375m16.5 0v3.75m-16.5-3.75v3.75m16.5 0v3.75C20.25 16.153 16.556 18 12 18s-8.25-1.847-8.25-4.125v-3.75m16.5 0c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125" />
                    </svg>
                    Master Data
                </span>
                <svg class="h-4 w-4 shrink-0 transition group-open:rotate-180" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                </svg>
            </summary>

            <div class="ml-4 mt-1 space-y-1 border-l border-slate-200 pl-3">
                @include('partials.admin-cabang.sidebar-link', ['route' => 'admin-cabang.master-data.guru', 'label' => 'Guru'])
                @include('partials.admin-cabang.sidebar-link', ['route' => 'admin-cabang.master-data.pembina', 'label' => 'Pembina Ekstrakurikuler'])
                @include('partials.admin-cabang.sidebar-link', ['route' => 'admin-cabang.master-data.siswa', 'label' => 'Siswa'])
            </div>
        </details>

        {{-- Profil Akun --}}
        <a
            href="{{ route('profil.edit') }}"
            @class([
                'flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition',
                'bg-emerald-50 text-emerald-700' => request()->routeIs('profil.*'),
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
