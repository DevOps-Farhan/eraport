<?php

namespace App\Http\Requests\Guru;

use Illuminate\Foundation\Http\FormRequest;

class StoreAbsensiCatatanRequest extends FormRequest
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
        return [
            'absensi' => ['required', 'array'],
            'absensi.*.sakit' => ['required', 'integer', 'min:0'],
            'absensi.*.izin' => ['required', 'integer', 'min:0'],
            'absensi.*.alpha' => ['required', 'integer', 'min:0'],
            'catatan' => ['required', 'array'],
            'catatan.*' => ['nullable', 'string', 'max:2000'],
        ];
    }
}
