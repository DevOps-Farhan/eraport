@php
    $readonly = $readonly ?? false;
@endphp

<div class="space-y-4">
    <div>
        <label for="{{ $prefix }}-kode" class="mb-1 block text-sm font-medium text-slate-700">Kode</label>
        <input
            type="text"
            id="{{ $prefix }}-kode"
            name="kode"
            @unless($readonly) required @endunless
            @if($readonly) readonly @endif
            class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm @if($readonly) bg-slate-50 @endif"
        >
    </div>
    <div>
        <label for="{{ $prefix }}-nama" class="mb-1 block text-sm font-medium text-slate-700">Nama Jurusan</label>
        <input
            type="text"
            id="{{ $prefix }}-nama"
            name="nama"
            @unless($readonly) required @endunless
            @if($readonly) readonly @endif
            class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm @if($readonly) bg-slate-50 @endif"
        >
    </div>
    @unless($readonly)
        <div class="flex items-center gap-2">
            <input type="hidden" name="aktif" value="0">
            <input type="checkbox" id="{{ $prefix }}-aktif" name="aktif" value="1" checked class="rounded border-slate-300 text-emerald-600">
            <label for="{{ $prefix }}-aktif" class="text-sm text-slate-700">Aktif</label>
        </div>
    @else
        <div>
            <label for="{{ $prefix }}-aktif" class="mb-1 block text-sm font-medium text-slate-700">Status</label>
            <input type="text" id="{{ $prefix }}-aktif" readonly class="w-full rounded-xl border border-slate-300 bg-slate-50 px-3 py-2 text-sm">
        </div>
    @endunless
</div>
