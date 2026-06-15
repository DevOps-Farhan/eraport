@php
    $isReadonly = $readonly ?? false;
    $fieldPrefix = $prefix ?? 'form';
    $showPassword = $showPassword ?? ! $isReadonly;
@endphp

<div class="space-y-4">
    <div>
        <label class="mb-1.5 block text-sm font-medium text-slate-700" for="{{ $fieldPrefix }}-username">Username</label>
        <input
            id="{{ $fieldPrefix }}-username"
            name="username"
            type="text"
            @unless($isReadonly) value="{{ old('username') }}" @endunless
            @if($isReadonly) readonly @endif
            @unless($isReadonly) required @endunless
            autocomplete="off"
            class="block w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm {{ $isReadonly ? 'bg-slate-50 text-slate-700' : 'shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200' }}"
        >
    </div>

    <div>
        <label class="mb-1.5 block text-sm font-medium text-slate-700" for="{{ $fieldPrefix }}-nama">Nama Lengkap</label>
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
        <label class="mb-1.5 block text-sm font-medium text-slate-700" for="{{ $fieldPrefix }}-email">Email</label>
        <input
            id="{{ $fieldPrefix }}-email"
            name="email"
            type="email"
            @unless($isReadonly) value="{{ old('email') }}" @endunless
            @if($isReadonly) readonly @endif
            class="block w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm {{ $isReadonly ? 'bg-slate-50 text-slate-700' : 'shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200' }}"
        >
    </div>

    @if ($showPassword)
        <div>
            <label class="mb-1.5 block text-sm font-medium text-slate-700" for="{{ $fieldPrefix }}-password">
                Password
                @if (($passwordOptional ?? false) && ! $isReadonly)
                    <span class="font-normal text-slate-400">(kosongkan jika tidak diubah)</span>
                @endif
            </label>
            <input
                id="{{ $fieldPrefix }}-password"
                name="password"
                type="password"
                @unless($isReadonly) @if(!($passwordOptional ?? false)) required @endif @endunless
                @if($isReadonly) readonly @endif
                autocomplete="new-password"
                class="block w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm {{ $isReadonly ? 'bg-slate-50 text-slate-700' : 'shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200' }}"
            >
        </div>
    @endif

    <div>
        <label class="mb-1.5 block text-sm font-medium text-slate-700" for="{{ $fieldPrefix }}-cabang_sekolah_id">Cabang Sekolah</label>
        @if ($isReadonly)
            <input
                id="{{ $fieldPrefix }}-cabang_sekolah_nama"
                type="text"
                readonly
                class="block w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-2.5 text-sm text-slate-700"
            >
        @else
            <select
                id="{{ $fieldPrefix }}-cabang_sekolah_id"
                name="cabang_sekolah_id"
                required
                class="block w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200"
            >
                <option value="">Pilih cabang sekolah</option>
                @foreach ($cabangSekolah as $cabang)
                    <option value="{{ $cabang->id }}" @selected(old('cabang_sekolah_id') == $cabang->id)>
                        {{ $cabang->nama }} ({{ $cabang->kode }})
                    </option>
                @endforeach
            </select>
        @endif
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
            <label for="{{ $fieldPrefix }}-aktif" class="text-sm text-slate-700">Pengguna aktif</label>
        </div>
    @else
        <div>
            <label class="mb-1.5 block text-sm font-medium text-slate-700" for="{{ $fieldPrefix }}-peran">Peran</label>
            <input
                id="{{ $fieldPrefix }}-peran"
                type="text"
                readonly
                class="block w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-2.5 text-sm text-slate-700"
            >
        </div>
        <div>
            <label class="mb-1.5 block text-sm font-medium text-slate-700" for="{{ $fieldPrefix }}-aktif">Status</label>
            <input
                id="{{ $fieldPrefix }}-aktif"
                type="text"
                readonly
                class="block w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-2.5 text-sm text-slate-700"
            >
        </div>
    @endunless
</div>
