@php
    $isReadonly = $readonly ?? false;
    $fieldPrefix = $prefix ?? 'form';
@endphp

<div class="space-y-4">
    <div>
        <label class="mb-1.5 block text-sm font-medium text-slate-700" for="{{ $fieldPrefix }}-nama">Nama Tahun Ajaran</label>
        <input
            id="{{ $fieldPrefix }}-nama"
            name="nama"
            type="text"
            placeholder="2025/2026"
            @unless($isReadonly) value="{{ old('nama') }}" @endunless
            @if($isReadonly) readonly @endif
            @unless($isReadonly) required @endunless
            class="block w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm {{ $isReadonly ? 'bg-slate-50 text-slate-700' : 'shadow-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200' }}"
        >
        @unless($isReadonly)
            <p class="mt-1 text-xs text-slate-400">Contoh: 2025/2026</p>
        @endunless
    </div>

    <div>
        <label class="mb-1.5 block text-sm font-medium text-slate-700" for="{{ $fieldPrefix }}-tanggal_mulai">Tanggal Mulai</label>
        <input
            id="{{ $fieldPrefix }}-tanggal_mulai"
            name="tanggal_mulai"
            type="date"
            @unless($isReadonly) value="{{ old('tanggal_mulai') }}" @endunless
            @if($isReadonly) readonly @endif
            @unless($isReadonly) required @endunless
            class="block w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm {{ $isReadonly ? 'bg-slate-50 text-slate-700' : 'shadow-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200' }}"
        >
    </div>

    @if ($isReadonly)
        <div>
            <label class="mb-1.5 block text-sm font-medium text-slate-700" for="{{ $fieldPrefix }}-status">Status</label>
            <input
                id="{{ $fieldPrefix }}-status"
                type="text"
                readonly
                class="block w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-2.5 text-sm text-slate-700"
            >
        </div>
        <div>
            <label class="mb-1.5 block text-sm font-medium text-slate-700" for="{{ $fieldPrefix }}-tanggal_selesai">Tanggal Selesai</label>
            <input
                id="{{ $fieldPrefix }}-tanggal_selesai"
                type="text"
                readonly
                class="block w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-2.5 text-sm text-slate-700"
            >
        </div>
    @endif
</div>
