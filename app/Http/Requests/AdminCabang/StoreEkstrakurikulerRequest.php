<?php

namespace App\Http\Requests\AdminCabang;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreEkstrakurikulerRequest extends FormRequest
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
                'required',
                'string',
                'max:20',
                Rule::unique('ekstrakurikuler', 'kode')->where('cabang_sekolah_id', $cabangId),
            ],
            'nama' => ['required', 'string', 'max:100'],
            'aktif' => ['sometimes', 'boolean'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'kode.required' => 'Kode ekstrakurikuler wajib diisi.',
            'kode.unique' => 'Kode ekstrakurikuler sudah digunakan di cabang ini.',
            'nama.required' => 'Nama ekstrakurikuler wajib diisi.',
        ];
    }
}
