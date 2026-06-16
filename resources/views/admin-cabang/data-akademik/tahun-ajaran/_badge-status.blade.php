@php
    use App\Enums\StatusTahunAjaran;

    $classes = match ($status) {
        StatusTahunAjaran::Draft => 'bg-slate-100 text-slate-600',
        StatusTahunAjaran::Aktif => 'bg-emerald-100 text-emerald-700',
        StatusTahunAjaran::Ditutup => 'bg-amber-100 text-amber-700',
    };
@endphp

<span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-medium {{ $classes }}">
    {{ $status->label() }}
</span>
