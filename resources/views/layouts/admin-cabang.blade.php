<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Admin Cabang') — eRaport</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-100 font-sans text-slate-900 antialiased">
    <div class="flex min-h-screen">
        @include('partials.admin-cabang.sidebar')

        <div class="flex min-w-0 flex-1 flex-col lg:pl-64">
            <header class="sticky top-0 z-20 border-b border-slate-200 bg-white/95 px-4 py-4 backdrop-blur sm:px-6 lg:hidden">
                <p class="text-lg font-semibold text-slate-900">eRaport</p>
                <p class="text-sm text-slate-500">Admin Cabang</p>
                @if (auth()->user()->cabangSekolah)
                    <p class="mt-0.5 truncate text-xs text-emerald-600">{{ auth()->user()->cabangSekolah->nama }}</p>
                @endif
            </header>

            <main class="flex-1 p-4 sm:p-6 lg:p-8">
                @include('partials.flash')
                @yield('content')
            </main>
        </div>
    </div>

    @stack('modals')
    @stack('scripts')
</body>
</html>
