@php
    use App\Enums\Agama;
    use App\Enums\JenisKelamin;
    $readonly = $readonly ?? false;
    $showAktif = $showAktif ?? true;
    $inputClass = 'w-full rounded-xl border border-slate-300 px-3 py-2 text-sm' . ($readonly ? ' bg-slate-50' : '');
@endphp

<div class="space-y-6">
    <fieldset class="space-y-4">
        <legend class="text-sm font-semibold text-slate-800">Identitas Siswa</legend>
        <div class="grid gap-4 sm:grid-cols-2">
            <div>
                <label for="{{ $prefix }}-nis" class="mb-1 block text-sm font-medium text-slate-700">NIS <span class="text-red-500">*</span></label>
                <input type="text" id="{{ $prefix }}-nis" name="nis" @unless($readonly) required @endunless @if($readonly) readonly @endif class="{{ $inputClass }}">
            </div>
            <div>
                <label for="{{ $prefix }}-nisn" class="mb-1 block text-sm font-medium text-slate-700">NISN <span class="text-red-500">*</span></label>
                <input type="text" id="{{ $prefix }}-nisn" name="nisn" @unless($readonly) required @endunless @if($readonly) readonly @endif class="{{ $inputClass }}">
            </div>
            <div class="sm:col-span-2">
                <label for="{{ $prefix }}-nama" class="mb-1 block text-sm font-medium text-slate-700">Nama Lengkap <span class="text-red-500">*</span></label>
                <input type="text" id="{{ $prefix }}-nama" name="nama" @unless($readonly) required @endunless @if($readonly) readonly @endif class="{{ $inputClass }}">
            </div>
            <div>
                <label for="{{ $prefix }}-jenis_kelamin" class="mb-1 block text-sm font-medium text-slate-700">Jenis Kelamin</label>
                @if($readonly)
                    <input type="text" id="{{ $prefix }}-jenis_kelamin" readonly class="{{ $inputClass }}">
                @else
                    <select id="{{ $prefix }}-jenis_kelamin" name="jenis_kelamin" class="{{ $inputClass }}">
                        <option value="">— Pilih —</option>
                        @foreach (JenisKelamin::cases() as $jk)
                            <option value="{{ $jk->value }}">{{ $jk->label() }}</option>
                        @endforeach
                    </select>
                @endif
            </div>
            <div>
                <label for="{{ $prefix }}-tanggal_lahir" class="mb-1 block text-sm font-medium text-slate-700">Tanggal Lahir</label>
                <input type="date" id="{{ $prefix }}-tanggal_lahir" name="tanggal_lahir" @if($readonly) readonly @endif class="{{ $inputClass }}">
            </div>
            <div>
                <label for="{{ $prefix }}-tempat_lahir" class="mb-1 block text-sm font-medium text-slate-700">Tempat Lahir</label>
                <input type="text" id="{{ $prefix }}-tempat_lahir" name="tempat_lahir" @if($readonly) readonly @endif class="{{ $inputClass }}">
            </div>
            <div>
                <label for="{{ $prefix }}-agama" class="mb-1 block text-sm font-medium text-slate-700">Agama</label>
                @if($readonly)
                    <input type="text" id="{{ $prefix }}-agama" readonly class="{{ $inputClass }}">
                @else
                    <select id="{{ $prefix }}-agama" name="agama" class="{{ $inputClass }}">
                        <option value="">— Pilih —</option>
                        @foreach (Agama::cases() as $agama)
                            <option value="{{ $agama->value }}">{{ $agama->label() }}</option>
                        @endforeach
                    </select>
                @endif
            </div>
            <div>
                <label for="{{ $prefix }}-status_dalam_keluarga" class="mb-1 block text-sm font-medium text-slate-700">Status dalam Keluarga</label>
                <input type="text" id="{{ $prefix }}-status_dalam_keluarga" name="status_dalam_keluarga" @if($readonly) readonly @endif class="{{ $inputClass }}" placeholder="Mis. Kandung, Tiri">
            </div>
            <div>
                <label for="{{ $prefix }}-anak_ke" class="mb-1 block text-sm font-medium text-slate-700">Anak Ke</label>
                <input type="number" id="{{ $prefix }}-anak_ke" name="anak_ke" min="1" max="20" @if($readonly) readonly @endif class="{{ $inputClass }}">
            </div>
        </div>
    </fieldset>

    <fieldset class="space-y-4">
        <legend class="text-sm font-semibold text-slate-800">Alamat Siswa</legend>
        <div class="grid gap-4 sm:grid-cols-2">
            <div class="sm:col-span-2">
                <label for="{{ $prefix }}-alamat" class="mb-1 block text-sm font-medium text-slate-700">Alamat</label>
                <textarea id="{{ $prefix }}-alamat" name="alamat" rows="2" @if($readonly) readonly @endif class="{{ $inputClass }}"></textarea>
            </div>
            <div>
                <label for="{{ $prefix }}-kecamatan" class="mb-1 block text-sm font-medium text-slate-700">Kecamatan</label>
                <input type="text" id="{{ $prefix }}-kecamatan" name="kecamatan" @if($readonly) readonly @endif class="{{ $inputClass }}">
            </div>
            <div>
                <label for="{{ $prefix }}-kabupaten_kota" class="mb-1 block text-sm font-medium text-slate-700">Kabupaten/Kota</label>
                <input type="text" id="{{ $prefix }}-kabupaten_kota" name="kabupaten_kota" @if($readonly) readonly @endif class="{{ $inputClass }}">
            </div>
        </div>
    </fieldset>

    <fieldset class="space-y-4">
        <legend class="text-sm font-semibold text-slate-800">Data Orang Tua</legend>
        <div class="grid gap-4 sm:grid-cols-2">
            <div>
                <label for="{{ $prefix }}-nama_ayah" class="mb-1 block text-sm font-medium text-slate-700">Nama Ayah</label>
                <input type="text" id="{{ $prefix }}-nama_ayah" name="nama_ayah" @if($readonly) readonly @endif class="{{ $inputClass }}">
            </div>
            <div>
                <label for="{{ $prefix }}-pekerjaan_ayah" class="mb-1 block text-sm font-medium text-slate-700">Pekerjaan Ayah</label>
                <input type="text" id="{{ $prefix }}-pekerjaan_ayah" name="pekerjaan_ayah" @if($readonly) readonly @endif class="{{ $inputClass }}">
            </div>
            <div>
                <label for="{{ $prefix }}-nama_ibu" class="mb-1 block text-sm font-medium text-slate-700">Nama Ibu</label>
                <input type="text" id="{{ $prefix }}-nama_ibu" name="nama_ibu" @if($readonly) readonly @endif class="{{ $inputClass }}">
            </div>
            <div>
                <label for="{{ $prefix }}-pekerjaan_ibu" class="mb-1 block text-sm font-medium text-slate-700">Pekerjaan Ibu</label>
                <input type="text" id="{{ $prefix }}-pekerjaan_ibu" name="pekerjaan_ibu" @if($readonly) readonly @endif class="{{ $inputClass }}">
            </div>
            <div class="sm:col-span-2">
                <label for="{{ $prefix }}-alamat_orang_tua" class="mb-1 block text-sm font-medium text-slate-700">Alamat Orang Tua</label>
                <textarea id="{{ $prefix }}-alamat_orang_tua" name="alamat_orang_tua" rows="2" @if($readonly) readonly @endif class="{{ $inputClass }}"></textarea>
            </div>
            <div>
                <label for="{{ $prefix }}-nomor_telepon_rumah" class="mb-1 block text-sm font-medium text-slate-700">Nomor Telepon Rumah</label>
                <input type="text" id="{{ $prefix }}-nomor_telepon_rumah" name="nomor_telepon_rumah" @if($readonly) readonly @endif class="{{ $inputClass }}">
            </div>
        </div>
    </fieldset>

    <fieldset class="space-y-4">
        <legend class="text-sm font-semibold text-slate-800">Data Wali Siswa</legend>
        <div class="grid gap-4 sm:grid-cols-2">
            <div class="sm:col-span-2">
                <label for="{{ $prefix }}-nama_wali_siswa" class="mb-1 block text-sm font-medium text-slate-700">Nama Wali Siswa</label>
                <input type="text" id="{{ $prefix }}-nama_wali_siswa" name="nama_wali_siswa" @if($readonly) readonly @endif class="{{ $inputClass }}">
            </div>
            <div class="sm:col-span-2">
                <label for="{{ $prefix }}-alamat_wali_siswa" class="mb-1 block text-sm font-medium text-slate-700">Alamat Wali Siswa</label>
                <textarea id="{{ $prefix }}-alamat_wali_siswa" name="alamat_wali_siswa" rows="2" @if($readonly) readonly @endif class="{{ $inputClass }}"></textarea>
            </div>
            <div>
                <label for="{{ $prefix }}-nomor_telepon_wali_siswa" class="mb-1 block text-sm font-medium text-slate-700">Nomor Telepon Wali Siswa</label>
                <input type="text" id="{{ $prefix }}-nomor_telepon_wali_siswa" name="nomor_telepon_wali_siswa" @if($readonly) readonly @endif class="{{ $inputClass }}">
            </div>
            <div>
                <label for="{{ $prefix }}-pekerjaan_wali_siswa" class="mb-1 block text-sm font-medium text-slate-700">Pekerjaan Wali Siswa</label>
                <input type="text" id="{{ $prefix }}-pekerjaan_wali_siswa" name="pekerjaan_wali_siswa" @if($readonly) readonly @endif class="{{ $inputClass }}">
            </div>
        </div>
    </fieldset>

    @if($readonly)
        <fieldset class="space-y-4">
            <legend class="text-sm font-semibold text-slate-800">Status Akademik</legend>
            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label for="{{ $prefix }}-aktif" class="mb-1 block text-sm font-medium text-slate-700">Status</label>
                    <input type="text" id="{{ $prefix }}-aktif" readonly class="{{ $inputClass }}">
                </div>
                <div>
                    <label for="{{ $prefix }}-kelas_saat_ini" class="mb-1 block text-sm font-medium text-slate-700">Kelas (TA Aktif)</label>
                    <input type="text" id="{{ $prefix }}-kelas_saat_ini" readonly class="{{ $inputClass }}">
                </div>
            </div>
        </fieldset>
    @elseif($showAktif)
        <div class="flex items-center gap-2">
            <input type="hidden" name="aktif" value="0">
            <input type="checkbox" id="{{ $prefix }}-aktif" name="aktif" value="1" checked class="rounded border-slate-300 text-emerald-600">
            <label for="{{ $prefix }}-aktif" class="text-sm text-slate-700">Aktif</label>
        </div>
    @endif
</div>
