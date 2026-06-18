<?php

namespace App\Http\Requests\AdminCabang;

use App\Http\Requests\Concerns\RulesProfilSiswa;
use Illuminate\Foundation\Http\FormRequest;

class UpdateSiswaRequest extends FormRequest
{
    use RulesProfilSiswa;

    public function authorize(): bool
    {
        return $this->user()?->isAdminCabang() ?? false;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return $this->rulesProfilSiswa(
            $this->user()->cabang_sekolah_id,
            $this->route('siswa')?->id,
            includeAktif: true,
        );
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'nis.required' => 'NIS wajib diisi.',
            'nis.unique' => 'NIS sudah terdaftar di cabang ini.',
            'nisn.required' => 'NISN wajib diisi.',
            'nama.required' => 'Nama siswa wajib diisi.',
        ];
    }
}
