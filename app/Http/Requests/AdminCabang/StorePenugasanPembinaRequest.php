<?php

namespace App\Http\Requests\AdminCabang;

use App\Enums\PeranPengguna;
use App\Services\KonteksAkademik;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePenugasanPembinaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdminCabang() ?? false;
    }

    protected function prepareForValidation(): void
    {
        $cabangId = $this->user()?->cabang_sekolah_id;

        if (! $cabangId) {
            return;
        }

        $tahunAjaran = app(KonteksAkademik::class)->tahunAjaranAktif($cabangId);

        if ($tahunAjaran) {
            $this->merge(['tahun_ajaran_id' => $tahunAjaran->id]);
        }
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $cabangId = $this->user()->cabang_sekolah_id;
        $tahunAjaranId = $this->input('tahun_ajaran_id');

        return [
            'tahun_ajaran_id' => ['required', 'integer', 'exists:tahun_ajaran,id'],
            'ekstrakurikuler_id' => [
                'required',
                'integer',
                Rule::exists('ekstrakurikuler', 'id')->where('cabang_sekolah_id', $cabangId),
            ],
            'pembina_id' => [
                'required',
                'integer',
                Rule::exists('pengguna', 'id')
                    ->where('cabang_sekolah_id', $cabangId)
                    ->where('peran', PeranPengguna::PembinaEkstrakurikuler->value),
            ],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'ekstrakurikuler_id.required' => 'Ekstrakurikuler wajib dipilih.',
            'pembina_id.required' => 'Pembina wajib dipilih.',
            'tahun_ajaran_id.required' => 'Tidak ada tahun ajaran aktif.',
        ];
    }
}
