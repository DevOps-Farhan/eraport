<?php

namespace App\Http\Requests\AdminCabang;

use App\Enums\PeranPengguna;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePenugasanMengajarRequest extends FormRequest
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
        $tahunAjaranId = $this->input('tahun_ajaran_id');

        return [
            'tahun_ajaran_id' => ['required', 'integer', 'exists:tahun_ajaran,id'],
            'guru_id' => [
                'required',
                'integer',
                Rule::exists('pengguna', 'id')
                    ->where('cabang_sekolah_id', $cabangId)
                    ->where('peran', PeranPengguna::Guru->value),
            ],
            'mata_pelajaran_id' => [
                'required',
                'integer',
                Rule::exists('mata_pelajaran', 'id')->where('cabang_sekolah_id', $cabangId),
            ],
            'kelas_id' => [
                'required',
                'integer',
                Rule::exists('kelas', 'id')->where('tahun_ajaran_id', $tahunAjaranId),
            ],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'guru_id.required' => 'Guru wajib dipilih.',
            'mata_pelajaran_id.required' => 'Mata pelajaran wajib dipilih.',
            'kelas_id.required' => 'Kelas wajib dipilih.',
        ];
    }
}
