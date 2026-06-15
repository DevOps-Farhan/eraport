<?php

namespace App\Http\Requests\AdminPusat;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePenggunaRequest extends FormRequest
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
        $penggunaId = $this->route('pengguna')?->id ?? $this->route('pengguna');

        return [
            'username' => ['required', 'string', 'max:50', Rule::unique('pengguna', 'username')->ignore($penggunaId)],
            'nama' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255', Rule::unique('pengguna', 'email')->ignore($penggunaId)],
            'password' => ['nullable', 'string', 'min:8'],
            'cabang_sekolah_id' => ['required', 'exists:cabang_sekolah,id'],
            'aktif' => ['sometimes', 'boolean'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'username.required' => 'Username wajib diisi.',
            'username.unique' => 'Username sudah digunakan.',
            'nama.required' => 'Nama wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah digunakan.',
            'password.min' => 'Password minimal 8 karakter.',
            'cabang_sekolah_id.required' => 'Cabang sekolah wajib dipilih.',
            'cabang_sekolah_id.exists' => 'Cabang sekolah tidak valid.',
        ];
    }
}
