<?php

namespace App\Http\Requests\AdminCabang;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePenggunaCabangRequest extends FormRequest
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
        $penggunaId = $this->route('pengguna')?->id;

        return [
            'username' => ['required', 'string', 'max:50', Rule::unique('pengguna', 'username')->ignore($penggunaId)],
            'nama' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255', Rule::unique('pengguna', 'email')->ignore($penggunaId)],
            'password' => ['nullable', 'string', 'min:8'],
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
        ];
    }
}
