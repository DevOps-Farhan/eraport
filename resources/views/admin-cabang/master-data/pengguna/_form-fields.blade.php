@php
    $isReadonly = $readonly ?? false;
    $fieldPrefix = $prefix ?? 'form';
    $showPassword = $showPassword ?? ! $isReadonly;
@endphp

<div class="space-y-4">
    <div>
        <label class="mb-1.5 block text-sm font-medium text-slate-700" for="{{ $fieldPrefix }}-username">Username</label>
        <input id="{{ $fieldPrefix }}-username" name="username" type="text" @unless($isReadonly) required @endunless @if($isReadonly) readonly @endif autocomplete="off" class="block w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm @if($isReadonly) bg-slate-50 @endif">
    </div>
    <div>
        <label class="mb-1.5 block text-sm font-medium text-slate-700" for="{{ $fieldPrefix }}-nama">Nama Lengkap</label>
        <input id="{{ $fieldPrefix }}-nama" name="nama" type="text" @unless($isReadonly) required @endunless @if($isReadonly) readonly @endif class="block w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm @if($isReadonly) bg-slate-50 @endif">
    </div>
    <div>
        <label class="mb-1.5 block text-sm font-medium text-slate-700" for="{{ $fieldPrefix }}-email">Email</label>
        <input id="{{ $fieldPrefix }}-email" name="email" type="email" @if($isReadonly) readonly @endif class="block w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm @if($isReadonly) bg-slate-50 @endif">
    </div>
    @if ($showPassword)
        <div>
            <label class="mb-1.5 block text-sm font-medium text-slate-700" for="{{ $fieldPrefix }}-password">
                Password
                @if (($passwordOptional ?? false) && ! $isReadonly)
                    <span class="font-normal text-slate-400">(kosongkan jika tidak diubah)</span>
                @endif
            </label>
            <input id="{{ $fieldPrefix }}-password" name="password" type="password" @unless($isReadonly) @if(!($passwordOptional ?? false)) required @endif @endunless @if($isReadonly) readonly @endif autocomplete="new-password" class="block w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm @if($isReadonly) bg-slate-50 @endif">
        </div>
    @endif
    @unless($isReadonly)
        <div class="flex items-center gap-3">
            <input type="hidden" name="aktif" value="0">
            <input id="{{ $fieldPrefix }}-aktif" name="aktif" type="checkbox" value="1" checked class="h-4 w-4 rounded border-slate-300 text-emerald-600">
            <label for="{{ $fieldPrefix }}-aktif" class="text-sm text-slate-700">Pengguna aktif</label>
        </div>
    @else
        <div>
            <label class="mb-1.5 block text-sm font-medium text-slate-700" for="{{ $fieldPrefix }}-peran">Peran</label>
            <input id="{{ $fieldPrefix }}-peran" type="text" readonly class="block w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-2.5 text-sm">
        </div>
        <div>
            <label class="mb-1.5 block text-sm font-medium text-slate-700" for="{{ $fieldPrefix }}-aktif">Status</label>
            <input id="{{ $fieldPrefix }}-aktif" type="text" readonly class="block w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-2.5 text-sm">
        </div>
    @endunless
</div>
