<aside class="fixed inset-y-0 left-0 z-30 hidden w-64 flex-col border-r border-slate-200 bg-white lg:flex">
    <div class="border-b border-slate-200 px-6 py-5">
        <p class="text-lg font-bold text-emerald-600">eRaport</p>
        <p class="mt-0.5 text-xs font-medium uppercase tracking-wide text-slate-400">Pembina Ekstrakurikuler</p>
    </div>

    <nav class="flex-1 space-y-1 overflow-y-auto px-3 py-4">
        {{-- Dashboard --}}
        <a
            href="{{ route('pembina.dashboard') }}"
            @class([
                'flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition',
                'bg-emerald-50 text-emerald-700' => request()->routeIs('pembina.dashboard'),
                'text-slate-600 hover:bg-slate-50 hover:text-slate-900' => ! request()->routeIs('pembina.dashboard'),
            ])
        >
            <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z" />
            </svg>
            Dashboard
        </a>

        {{-- Kelola Data Ekstrakurikuler --}}
        <a
            href="{{ route('pembina.ekstrakurikuler.index') }}"
            @class([
                'flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition',
                'bg-emerald-50 text-emerald-700' => request()->routeIs('pembina.ekstrakurikuler.*'),
                'text-slate-600 hover:bg-slate-50 hover:text-slate-900' => ! request()->routeIs('pembina.ekstrakurikuler.*'),
            ])
        >
            <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" />
            </svg>
            Kelola Ekstrakurikuler
        </a>

        {{-- Input Penilaian & Evaluasi --}}
        <a
            href="{{ route('pembina.penilaian.index') }}"
            @class([
                'flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition',
                'bg-emerald-50 text-emerald-700' => request()->routeIs('pembina.penilaian.*'),
                'text-slate-600 hover:bg-slate-50 hover:text-slate-900' => ! request()->routeIs('pembina.penilaian.*'),
            ])
        >
            <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M11.35 3.836c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m8.9-4.414c.376.023.75.05 1.124.08 1.131.094 1.976 1.057 1.976 2.192V16.5A2.25 2.25 0 0 1 18 18.75h-2.25m-7.5-10.5H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V18.75m-7.5-10.5h6.375c.621 0 1.125.504 1.125 1.125v9.375m-8.25-3 1.5 1.5 3-3.75" />
            </svg>
            Penilaian & Evaluasi
        </a>

        {{-- Profil Pengguna --}}
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
            Profil Pengguna
        </a>
    </nav>

    <div class="border-t border-slate-200 p-3">
        <div class="mb-3 rounded-xl bg-slate-50 px-3 py-2">
            <p class="truncate text-sm font-medium text-slate-900">{{ auth()->user()->nama ?? 'Pembina' }}</p>
            <p class="truncate text-xs text-slate-500">{{ auth()->user()->username ?? 'pembina' }}</p>
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
