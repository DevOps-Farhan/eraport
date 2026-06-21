<?php

namespace App\Http\Requests\AdminCabang;

use App\Enums\FaseKelas;
use App\Enums\PeranPengguna;
use App\Enums\TingkatKelas;
use App\Services\KonteksAkademik;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreKelasRequest extends FormRequest
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
            'jurusan_id' => [
                'required',
                'integer',
                Rule::exists('jurusan', 'id')->where('cabang_sekolah_id', $cabangId),
            ],
            'tingkat' => ['required', Rule::enum(TingkatKelas::class)],
            'fase' => ['required', Rule::enum(FaseKelas::class)],
            'nama' => [
                'required',
                'string',
                'max:50',
                Rule::unique('kelas', 'nama')->where('tahun_ajaran_id', $tahunAjaranId),
            ],
            'wali_kelas_id' => [
                'nullable',
                'integer',
                Rule::exists('pengguna', 'id')
                    ->where('cabang_sekolah_id', $cabangId)
                    ->where('peran', PeranPengguna::Guru->value),
            ],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'jurusan_id.required' => 'Jurusan wajib dipilih.',
            'tingkat.required' => 'Tingkat wajib dipilih.',
            'fase.required' => 'Fase wajib dipilih.',
            'nama.required' => 'Nama kelas wajib diisi.',
            'nama.unique' => 'Nama kelas sudah digunakan pada tahun ajaran ini.',
            'tahun_ajaran_id.required' => 'Tidak ada tahun ajaran aktif. Aktifkan tahun ajaran terlebih dahulu.',
        ];
    }
}
