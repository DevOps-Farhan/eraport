<?php

namespace App\Http\Requests\Guru;

use App\Enums\JenisKelamin;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreSiswaWaliKelasRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isGuru() ?? false;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $cabangId = $this->user()->cabang_sekolah_id;

        return [
            'nis' => [
                'required',
                'string',
                'max:30',
                Rule::unique('siswa', 'nis')->where('cabang_sekolah_id', $cabangId),
            ],
            'nisn' => ['nullable', 'string', 'max:30'],
            'nama' => ['required', 'string', 'max:100'],
            'jenis_kelamin' => ['required', Rule::enum(JenisKelamin::class)],
            'tanggal_lahir' => ['nullable', 'date'],
        ];
    }
}
