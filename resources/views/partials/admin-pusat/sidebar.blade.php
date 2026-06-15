<aside class="fixed inset-y-0 left-0 z-30 hidden w-64 flex-col border-r border-slate-200 bg-white lg:flex">
    <div class="border-b border-slate-200 px-6 py-5">
        <p class="text-lg font-bold text-indigo-600">eRaport</p>
        <p class="mt-0.5 text-xs font-medium uppercase tracking-wide text-slate-400">Admin Pusat</p>
    </div>

    <nav class="flex-1 space-y-1 overflow-y-auto px-3 py-4">
        <a
            href="{{ route('admin-pusat.dashboard') }}"
            @class([
                'flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition',
                'bg-indigo-50 text-indigo-700' => request()->routeIs('admin-pusat.dashboard'),
                'text-slate-600 hover:bg-slate-50 hover:text-slate-900' => ! request()->routeIs('admin-pusat.dashboard'),
            ])
        >
            <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z" />
            </svg>
            Dashboard
        </a>

        <a
            href="{{ route('admin-pusat.cabang-sekolah.index') }}"
            @class([
                'flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition',
                'bg-indigo-50 text-indigo-700' => request()->routeIs('admin-pusat.cabang-sekolah.*'),
                'text-slate-600 hover:bg-slate-50 hover:text-slate-900' => ! request()->routeIs('admin-pusat.cabang-sekolah.*'),
            ])
        >
            <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008Zm0 3h.008v.008h-.008v-.008Zm0 3h.008v.008h-.008v-.008Z" />
            </svg>
            Kelola Semua Cabang
        </a>

        <details
            class="group"
            @if (request()->routeIs('admin-pusat.pengguna.*')) open @endif
        >
            <summary
                @class([
                    'flex cursor-pointer list-none items-center justify-between gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition [&::-webkit-details-marker]:hidden',
                    'bg-indigo-50 text-indigo-700' => request()->routeIs('admin-pusat.pengguna.*'),
                    'text-slate-600 hover:bg-slate-50 hover:text-slate-900' => ! request()->routeIs('admin-pusat.pengguna.*'),
                ])
            >
                <span class="flex items-center gap-3">
                    <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                    </svg>
                    Kelola Pengguna
                </span>
                <svg class="h-4 w-4 shrink-0 transition group-open:rotate-180" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                </svg>
            </summary>

            <div class="ml-4 mt-1 space-y-1 border-l border-slate-200 pl-3">
                <a
                    href="{{ route('admin-pusat.pengguna.guru') }}"
                    @class([
                        'block rounded-lg px-3 py-2 text-sm transition',
                        'bg-indigo-50 font-medium text-indigo-700' => request()->routeIs('admin-pusat.pengguna.guru'),
                        'text-slate-600 hover:bg-slate-50 hover:text-slate-900' => ! request()->routeIs('admin-pusat.pengguna.guru'),
                    ])
                >
                    Guru
                </a>
                <a
                    href="{{ route('admin-pusat.pengguna.pembina') }}"
                    @class([
                        'block rounded-lg px-3 py-2 text-sm transition',
                        'bg-indigo-50 font-medium text-indigo-700' => request()->routeIs('admin-pusat.pengguna.pembina'),
                        'text-slate-600 hover:bg-slate-50 hover:text-slate-900' => ! request()->routeIs('admin-pusat.pengguna.pembina'),
                    ])
                >
                    Pembina
                </a>
            </div>
        </details>
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
