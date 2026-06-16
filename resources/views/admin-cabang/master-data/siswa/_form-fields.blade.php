@php
    use App\Enums\JenisKelamin;
    $readonly = $readonly ?? false;
@endphp

<div class="space-y-4">
    <div>
        <label for="{{ $prefix }}-nis" class="mb-1 block text-sm font-medium text-slate-700">NIS</label>
        <input type="text" id="{{ $prefix }}-nis" name="nis" @unless($readonly) required @endunless @if($readonly) readonly @endif class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm @if($readonly) bg-slate-50 @endif">
    </div>
    <div>
        <label for="{{ $prefix }}-nisn" class="mb-1 block text-sm font-medium text-slate-700">NISN</label>
        <input type="text" id="{{ $prefix }}-nisn" name="nisn" @if($readonly) readonly @endif class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm @if($readonly) bg-slate-50 @endif">
    </div>
    <div>
        <label for="{{ $prefix }}-nama" class="mb-1 block text-sm font-medium text-slate-700">Nama Lengkap</label>
        <input type="text" id="{{ $prefix }}-nama" name="nama" @unless($readonly) required @endunless @if($readonly) readonly @endif class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm @if($readonly) bg-slate-50 @endif">
    </div>
    <div>
        <label for="{{ $prefix }}-jenis_kelamin" class="mb-1 block text-sm font-medium text-slate-700">Jenis Kelamin</label>
        @if($readonly)
            <input type="text" id="{{ $prefix }}-jenis_kelamin" readonly class="w-full rounded-xl border border-slate-300 bg-slate-50 px-3 py-2 text-sm">
        @else
            <select id="{{ $prefix }}-jenis_kelamin" name="jenis_kelamin" required class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm">
                @foreach (JenisKelamin::cases() as $jk)
                    <option value="{{ $jk->value }}">{{ $jk->label() }}</option>
                @endforeach
            </select>
        @endif
    </div>
    <div>
        <label for="{{ $prefix }}-tanggal_lahir" class="mb-1 block text-sm font-medium text-slate-700">Tanggal Lahir</label>
        <input type="date" id="{{ $prefix }}-tanggal_lahir" name="tanggal_lahir" @if($readonly) readonly @endif class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm @if($readonly) bg-slate-50 @endif">
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
        <div>
            <label for="{{ $prefix }}-kelas_saat_ini" class="mb-1 block text-sm font-medium text-slate-700">Kelas (TA Aktif)</label>
            <input type="text" id="{{ $prefix }}-kelas_saat_ini" readonly class="w-full rounded-xl border border-slate-300 bg-slate-50 px-3 py-2 text-sm">
        </div>
    @endunless
</div>
