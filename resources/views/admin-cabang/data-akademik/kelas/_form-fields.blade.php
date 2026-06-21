@php
    use App\Enums\FaseKelas;
    use App\Enums\TingkatKelas;
    $readonly = $readonly ?? false;
    $jurusanList = $jurusanList ?? collect();
    $guruList = $guruList ?? collect();
@endphp

<div class="space-y-4">
    <div>
        <label for="{{ $prefix }}-jurusan_id" class="mb-1 block text-sm font-medium text-slate-700">Jurusan</label>
        @if($readonly)
            <input type="text" id="{{ $prefix }}-jurusan_nama" readonly class="w-full rounded-xl border border-slate-300 bg-slate-50 px-3 py-2 text-sm">
        @else
            <select id="{{ $prefix }}-jurusan_id" name="jurusan_id" required class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm">
                <option value="">Pilih jurusan</option>
                @foreach ($jurusanList as $jurusan)
                    <option value="{{ $jurusan->id }}">{{ $jurusan->kode }} — {{ $jurusan->nama }}</option>
                @endforeach
            </select>
        @endif
    </div>
    <div>
        <label for="{{ $prefix }}-tingkat" class="mb-1 block text-sm font-medium text-slate-700">Tingkat</label>
        @if($readonly)
            <input type="text" id="{{ $prefix }}-tingkat" readonly class="w-full rounded-xl border border-slate-300 bg-slate-50 px-3 py-2 text-sm">
        @else
            <select id="{{ $prefix }}-tingkat" name="tingkat" required class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm">
                @foreach (TingkatKelas::cases() as $tingkat)
                    <option value="{{ $tingkat->value }}">{{ $tingkat->label() }}</option>
                @endforeach
            </select>
        @endif
    </div>
    <div>
        <label for="{{ $prefix }}-fase" class="mb-1 block text-sm font-medium text-slate-700">Fase</label>
        @if($readonly)
            <input type="text" id="{{ $prefix }}-fase" readonly class="w-full rounded-xl border border-slate-300 bg-slate-50 px-3 py-2 text-sm">
        @else
            <select id="{{ $prefix }}-fase" name="fase" required class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm">
                @foreach (FaseKelas::cases() as $fase)
                    <option value="{{ $fase->value }}">{{ $fase->label() }}</option>
                @endforeach
            </select>
        @endif
    </div>
    <div>
        <label for="{{ $prefix }}-nama" class="mb-1 block text-sm font-medium text-slate-700">Nama Kelas</label>
        <input type="text" id="{{ $prefix }}-nama" name="nama" @unless($readonly) required @endunless @if($readonly) readonly @endif placeholder="Contoh: X RPL 1" class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm @if($readonly) bg-slate-50 @endif">
    </div>
    <div>
        <label for="{{ $prefix }}-wali_kelas_id" class="mb-1 block text-sm font-medium text-slate-700">Wali Kelas</label>
        @if($readonly)
            <input type="text" id="{{ $prefix }}-wali_kelas_nama" readonly class="w-full rounded-xl border border-slate-300 bg-slate-50 px-3 py-2 text-sm">
        @else
            <select id="{{ $prefix }}-wali_kelas_id" name="wali_kelas_id" class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm">
                <option value="">— Tidak ada —</option>
                @foreach ($guruList as $guru)
                    <option value="{{ $guru->id }}">{{ $guru->nama }}</option>
                @endforeach
            </select>
        @endif
    </div>
</div>
