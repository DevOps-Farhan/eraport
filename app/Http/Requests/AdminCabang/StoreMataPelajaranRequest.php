<?php

namespace App\Http\Requests\AdminCabang;

use App\Enums\KelompokMataPelajaran;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreMataPelajaranRequest extends FormRequest
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

        return [
            'kode' => [
                'nullable',
                'string',
                'max:20',
                Rule::unique('mata_pelajaran', 'kode')->where('cabang_sekolah_id', $cabangId),
            ],
            'nama' => ['required', 'string', 'max:100'],
            'kelompok' => ['required', Rule::enum(KelompokMataPelajaran::class)],
            'aktif' => ['sometimes', 'boolean'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'kode.required' => 'Kode mata pelajaran wajib diisi.',
            'kode.unique' => 'Kode mata pelajaran sudah digunakan di cabang ini.',
            'nama.required' => 'Nama mata pelajaran wajib diisi.',
            'kelompok.required' => 'Kelompok mata pelajaran wajib dipilih.',
        ];
    }
}
