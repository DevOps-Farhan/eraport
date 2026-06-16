<?php

namespace App\Http\Requests\AdminCabang;

use App\Enums\PeranPengguna;
use App\Enums\TingkatKelas;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateKelasRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdminCabang() ?? false;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $cabangId = $this->user()->cabang_sekolah_id;
        $kelas = $this->route('kelas');
        $tahunAjaranId = $kelas?->tahun_ajaran_id;

        return [
            'jurusan_id' => [
                'required',
                'integer',
                Rule::exists('jurusan', 'id')->where('cabang_sekolah_id', $cabangId),
            ],
            'tingkat' => ['required', Rule::enum(TingkatKelas::class)],
            'nama' => [
                'required',
                'string',
                'max:50',
                Rule::unique('kelas', 'nama')
                    ->where('tahun_ajaran_id', $tahunAjaranId)
                    ->ignore($kelas?->id),
            ],
            'wali_kelas_id' => [
                'nullable',
                'integer',
                Rule::exists('pengguna', 'id')
                    ->where('cabang_sekolah_id', $cabangId)
                    ->where('peran', PeranPengguna::Guru->value),
            ],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'jurusan_id.required' => 'Jurusan wajib dipilih.',
            'tingkat.required' => 'Tingkat wajib dipilih.',
            'nama.required' => 'Nama kelas wajib diisi.',
            'nama.unique' => 'Nama kelas sudah digunakan pada tahun ajaran ini.',
        ];
    }
}
