<?php

namespace App\Http\Requests\Guru;

use Illuminate\Foundation\Http\FormRequest;

class StoreNilaiTpRequest extends FormRequest
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
            'nilai' => ['required', 'array'],
            'nilai.*' => ['nullable', 'numeric', 'min:0', 'max:100'],
        ];
    }
}
