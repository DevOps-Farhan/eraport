<?php

namespace App\Http\Requests\Guru;

use App\Models\TujuanPembelajaran;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTujuanPembelajaranRequest extends FormRequest
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
        /** @var TujuanPembelajaran $tujuanPembelajaran */
        $tujuanPembelajaran = $this->route('tujuan_pembelajaran');

        return [
            'kode' => [
                'required',
                'string',
                'max:20',
                Rule::unique('tujuan_pembelajaran', 'kode')
                    ->where('penugasan_mengajar_id', $tujuanPembelajaran->penugasan_mengajar_id)
                    ->ignore($tujuanPembelajaran->id),
            ],
            'deskripsi_kompetensi' => ['required', 'string', 'max:2000'],
            'urutan' => ['required', 'integer', 'min:1'],
        ];
    }
}
