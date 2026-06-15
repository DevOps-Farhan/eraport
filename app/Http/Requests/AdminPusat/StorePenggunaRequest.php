<?php

namespace App\Http\Requests\AdminPusat;

use Illuminate\Foundation\Http\FormRequest;

class StorePenggunaRequest extends FormRequest
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
        return [
            'username' => ['required', 'string', 'max:50', 'unique:pengguna,username'],
            'nama' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255', 'unique:pengguna,email'],
            'password' => ['required', 'string', 'min:8'],
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
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'cabang_sekolah_id.required' => 'Cabang sekolah wajib dipilih.',
            'cabang_sekolah_id.exists' => 'Cabang sekolah tidak valid.',
        ];
    }
}
