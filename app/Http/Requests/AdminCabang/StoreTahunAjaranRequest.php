<?php

namespace App\Http\Requests\AdminCabang;

use Illuminate\Foundation\Http\FormRequest;

class StoreTahunAjaranRequest extends FormRequest
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
        return [
            'nama' => ['required', 'string', 'max:20'],
            'tanggal_mulai' => ['required', 'date'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'nama.required' => 'Nama tahun ajaran wajib diisi.',
            'tanggal_mulai.required' => 'Tanggal mulai wajib diisi.',
        ];
    }
}
