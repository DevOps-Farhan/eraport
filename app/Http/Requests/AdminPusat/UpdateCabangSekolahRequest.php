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
            'npsn' => ['required', 'numeric', 'digits_between:1,8'],
            'nss' => ['required', 'numeric'],
            'desa_kelurahan' => ['required', 'string', 'max:255'],
            'kecamatan' => ['required', 'string', 'max:255'],
            'kabupaten_kota' => ['required', 'string', 'max:255'],
            'provinsi' => ['required', 'string', 'max:255'],
            'website' => ['nullable', 'url', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
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
