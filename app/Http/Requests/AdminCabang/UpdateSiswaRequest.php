<?php

namespace App\Http\Requests\AdminCabang;

use App\Enums\JenisKelamin;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSiswaRequest extends FormRequest
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
        $siswaId = $this->route('siswa')?->id;

        return [
            'nis' => [
                'required',
                'string',
                'max:30',
                Rule::unique('siswa', 'nis')
                    ->where('cabang_sekolah_id', $cabangId)
                    ->ignore($siswaId),
            ],
            'nisn' => ['nullable', 'string', 'max:30'],
            'nama' => ['required', 'string', 'max:150'],
            'jenis_kelamin' => ['required', Rule::enum(JenisKelamin::class)],
            'tanggal_lahir' => ['nullable', 'date'],
            'aktif' => ['sometimes', 'boolean'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'nis.required' => 'NIS wajib diisi.',
            'nis.unique' => 'NIS sudah terdaftar di cabang ini.',
            'nama.required' => 'Nama siswa wajib diisi.',
            'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih.',
        ];
    }
}
