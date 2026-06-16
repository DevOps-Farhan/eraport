<a
    href="{{ route($route) }}"
    @class([
        'block rounded-lg px-3 py-2 text-sm transition',
        'bg-sky-50 font-medium text-sky-700' => request()->routeIs($route),
        'text-slate-600 hover:bg-slate-50 hover:text-slate-900' => ! request()->routeIs($route),
    ])
>
    {{ $label }}
</a>
