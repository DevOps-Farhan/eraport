<?php

namespace App\Http\Requests\Guru;

use App\Http\Requests\Concerns\RulesProfilSiswa;
use App\Models\Siswa;
use Illuminate\Foundation\Http\FormRequest;

class UpdateSiswaWaliKelasRequest extends FormRequest
{
    use RulesProfilSiswa;

    public function authorize(): bool
    {
        return $this->user()?->isGuru() ?? false;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        /** @var Siswa $siswa */
        $siswa = $this->route('siswa');

        return $this->rulesProfilSiswa($this->user()->cabang_sekolah_id, $siswa->id);
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
