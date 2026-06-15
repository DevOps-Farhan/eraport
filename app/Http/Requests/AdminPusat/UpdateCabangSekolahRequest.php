<?php

namespace App\Http\Requests\AdminPusat;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCabangSekolahRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdminPusat() ?? false;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $cabangId = $this->route('cabang_sekolah');

        return [
            'kode' => ['required', 'string', 'max:20', Rule::unique('cabang_sekolah', 'kode')->ignore($cabangId)],
            'nama' => ['required', 'string', 'max:255'],
            'kepala_sekolah' => ['required', 'string', 'max:255'],
            'alamat' => ['nullable', 'string'],
            'telepon' => ['nullable', 'string', 'max:20'],
            'aktif' => ['sometimes', 'boolean'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'kode.required' => 'Kode cabang wajib diisi.',
            'kode.unique' => 'Kode cabang sudah digunakan.',
            'nama.required' => 'Nama cabang wajib diisi.',
            'kepala_sekolah.required' => 'Nama kepala sekolah wajib diisi.',
        ];
    }
}
