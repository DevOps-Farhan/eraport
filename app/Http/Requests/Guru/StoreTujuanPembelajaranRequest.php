<?php

namespace App\Http\Requests\Guru;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTujuanPembelajaranRequest extends FormRequest
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
            'penugasan_mengajar_id' => [
                'required',
                'integer',
                Rule::exists('penugasan_mengajar', 'id')
                    ->where('guru_id', $this->user()->id),
            ],
            'kode' => [
                'required',
                'string',
                'max:20',
                Rule::unique('tujuan_pembelajaran', 'kode')
                    ->where('penugasan_mengajar_id', $this->integer('penugasan_mengajar_id')),
            ],
            'deskripsi_kompetensi' => ['required', 'string', 'max:2000'],
            'urutan' => ['required', 'integer', 'min:1'],
        ];
    }
}
