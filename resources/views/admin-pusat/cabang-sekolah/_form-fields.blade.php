@php
    $isReadonly = $readonly ?? false;
    $fieldPrefix = $prefix ?? 'form';
@endphp

<div class="space-y-4">
    <div>
        <label class="mb-1.5 block text-sm font-medium text-slate-700" for="{{ $fieldPrefix }}-kode">Kode</label>
        <input
            id="{{ $fieldPrefix }}-kode"
            name="kode"
            type="text"
            @unless($isReadonly) value="{{ old('kode') }}" @endunless
            @if($isReadonly) readonly @endif
            @unless($isReadonly) required @endunless
            class="block w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm {{ $isReadonly ? 'bg-slate-50 text-slate-700' : 'shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200' }}"
        >
    </div>

    <div>
        <label class="mb-1.5 block text-sm font-medium text-slate-700" for="{{ $fieldPrefix }}-nama">Nama Cabang</label>
        <input
            id="{{ $fieldPrefix }}-nama"
            name="nama"
            type="text"
            @unless($isReadonly) value="{{ old('nama') }}" @endunless
            @if($isReadonly) readonly @endif
            @unless($isReadonly) required @endunless
            class="block w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm {{ $isReadonly ? 'bg-slate-50 text-slate-700' : 'shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200' }}"
        >
    </div>

    <div>
        <label class="mb-1.5 block text-sm font-medium text-slate-700" for="{{ $fieldPrefix }}-kepala_sekolah">Kepala Sekolah</label>
        <input
            id="{{ $fieldPrefix }}-kepala_sekolah"
            name="kepala_sekolah"
            type="text"
            @unless($isReadonly) value="{{ old('kepala_sekolah') }}" @endunless
            @if($isReadonly) readonly @endif
            @unless($isReadonly) required @endunless
            class="block w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm {{ $isReadonly ? 'bg-slate-50 text-slate-700' : 'shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200' }}"
        >
    </div>

    <div>
        <label class="mb-1.5 block text-sm font-medium text-slate-700" for="{{ $fieldPrefix }}-alamat">Alamat</label>
        <textarea
            id="{{ $fieldPrefix }}-alamat"
            name="alamat"
            rows="3"
            @if($isReadonly) readonly @endif
            class="block w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm {{ $isReadonly ? 'bg-slate-50 text-slate-700' : 'shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200' }}"
        >@unless($isReadonly){{ old('alamat') }}@endunless</textarea>
    </div>

    <div>
        <label class="mb-1.5 block text-sm font-medium text-slate-700" for="{{ $fieldPrefix }}-telepon">Telepon</label>
        <input
            id="{{ $fieldPrefix }}-telepon"
            name="telepon"
            type="text"
            @unless($isReadonly) value="{{ old('telepon') }}" @endunless
            @if($isReadonly) readonly @endif
            class="block w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm {{ $isReadonly ? 'bg-slate-50 text-slate-700' : 'shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200' }}"
        >
    </div>

    @unless($isReadonly)
        <div class="flex items-center gap-3">
            <input
                id="{{ $fieldPrefix }}-aktif"
                name="aktif"
                type="checkbox"
                value="1"
                @checked(old('aktif', true))
                class="h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500"
            >
            <label for="{{ $fieldPrefix }}-aktif" class="text-sm text-slate-700">Cabang aktif</label>
        </div>
    @else
        <div>
            <label class="mb-1.5 block text-sm font-medium text-slate-700" for="{{ $fieldPrefix }}-aktif">Status</label>
            <input
                id="{{ $fieldPrefix }}-aktif"
                type="text"
                readonly
                class="block w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-2.5 text-sm text-slate-700"
            >
        </div>
        <div>
            <label class="mb-1.5 block text-sm font-medium text-slate-700" for="{{ $fieldPrefix }}-pengguna_count">Jumlah Pengguna</label>
            <input
                id="{{ $fieldPrefix }}-pengguna_count"
                type="text"
                readonly
                class="block w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-2.5 text-sm text-slate-700"
            >
        </div>
    @endunless
</div>
