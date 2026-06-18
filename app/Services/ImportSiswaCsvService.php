<?php

namespace App\Services;

use App\Enums\Agama;
use App\Enums\JenisKelamin;
use App\Models\Siswa;
use App\Support\SiswaCsv;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use SplFileObject;

class ImportSiswaCsvService
{
    private const MAKS_BARIS = 1000;

    public function kontenTemplate(): string
    {
        $kolom = SiswaCsv::kolom();
        $baris = [];

        $baris[] = $this->barisKeCsv($kolom);

        foreach (SiswaCsv::barisContoh() as $contoh) {
            $baris[] = $this->barisKeCsv(array_map(fn (string $k) => $contoh[$k] ?? '', $kolom));
        }

        return "\xEF\xBB\xBF".implode("\n", $baris);
    }

    /**
     * @return array{berhasil: int, kesalahan: list<string>}
     */
    public function impor(string $path, int $cabangSekolahId): array
    {
        $file = new SplFileObject($path);
        $file->setFlags(SplFileObject::READ_CSV | SplFileObject::SKIP_EMPTY | SplFileObject::DROP_NEW_LINE);

        $semuaBaris = [];
        foreach ($file as $baris) {
            if ($baris === false || $baris === [null] || $this->barisKosong($baris)) {
                continue;
            }
            $semuaBaris[] = $baris;
        }

        if ($semuaBaris === []) {
            throw ValidationException::withMessages([
                'file' => 'File CSV kosong atau tidak valid.',
            ]);
        }

        $header = $this->normalisasiHeader(array_shift($semuaBaris));
        $indeksKolom = $this->petakanHeader($header);
        $kesalahan = [];
        $barisData = [];
        $nisDalamFile = [];
        $nomorBaris = 1;

        foreach ($semuaBaris as $baris) {
            $nomorBaris++;

            if (count($barisData) >= self::MAKS_BARIS) {
                $kesalahan[] = 'Maksimal '.self::MAKS_BARIS.' baris data per impor.';

                break;
            }

            $data = $this->barisKeAsosiatif($baris, $indeksKolom);
            $data = $this->normalisasiNilai($data);
            $data = $this->normalisasiSebelumValidasi($data);

            if ($this->barisContoh($data)) {
                continue;
            }

            $validator = Validator::make($data, $this->aturanBaris($cabangSekolahId));

            if ($validator->fails()) {
                foreach ($validator->errors()->all() as $pesan) {
                    $kesalahan[] = "Baris {$nomorBaris}: {$pesan}";
                }

                continue;
            }

            if (isset($nisDalamFile[$data['nis']])) {
                $kesalahan[] = "Baris {$nomorBaris}: NIS {$data['nis']} duplikat dalam file (baris {$nisDalamFile[$data['nis']]}).";

                continue;
            }

            $nisDalamFile[$data['nis']] = $nomorBaris;
            $barisData[] = ['nomor' => $nomorBaris, 'data' => $data];
        }

        if ($barisData === [] && $kesalahan === []) {
            throw ValidationException::withMessages([
                'file' => 'Tidak ada data siswa untuk diimpor.',
            ]);
        }

        if ($kesalahan !== []) {
            return ['berhasil' => 0, 'kesalahan' => $kesalahan];
        }

        $berhasil = DB::transaction(function () use ($barisData, $cabangSekolahId) {
            $jumlah = 0;

            foreach ($barisData as $item) {
                Siswa::query()->create([
                    'cabang_sekolah_id' => $cabangSekolahId,
                    ...$this->atributDariBaris($item['data']),
                ]);
                $jumlah++;
            }

            return $jumlah;
        });

        return ['berhasil' => $berhasil, 'kesalahan' => []];
    }

    /**
     * @param  list<string|null>  $header
     * @return list<string>
     */
    private function normalisasiHeader(array $header): array
    {
        return array_map(function (?string $kolom) {
            $kolom = ltrim((string) $kolom, "\xEF\xBB\xBF");
            $kolom = strtolower(trim($kolom));

            return str_replace(' ', '_', $kolom);
        }, $header);
    }

    /**
     * @param  list<string>  $header
     * @return array<string, int>
     */
    private function petakanHeader(array $header): array
    {
        $kolomValid = array_flip(SiswaCsv::kolom());
        $indeks = [];

        foreach ($header as $posisi => $nama) {
            if ($nama === '') {
                continue;
            }

            if (! isset($kolomValid[$nama])) {
                throw ValidationException::withMessages([
                    'file' => "Kolom tidak dikenali: {$nama}. Unduh template CSV terbaru.",
                ]);
            }

            $indeks[$nama] = $posisi;
        }

        foreach (SiswaCsv::kolomWajib() as $wajib) {
            if (! array_key_exists($wajib, $indeks)) {
                throw ValidationException::withMessages([
                    'file' => "Kolom wajib tidak ditemukan: {$wajib}. Unduh template CSV terbaru.",
                ]);
            }
        }

        return $indeks;
    }

    /**
     * @param  list<string|null>  $baris
     * @param  array<string, int>  $indeksKolom
     * @return array<string, string>
     */
    private function barisKeAsosiatif(array $baris, array $indeksKolom): array
    {
        $data = [];
        foreach (SiswaCsv::kolom() as $kolom) {
            $data[$kolom] = isset($indeksKolom[$kolom]) ? (string) ($baris[$indeksKolom[$kolom]] ?? '') : '';
        }

        return $data;
    }

    /**
     * @param  array<string, string>  $data
     * @return array<string, string|null>
     */
    private function normalisasiNilai(array $data): array
    {
        $hasil = [];
        foreach ($data as $kunci => $nilai) {
            $nilai = trim($nilai);
            $hasil[$kunci] = $nilai === '' ? null : $nilai;
        }

        return $hasil;
    }

    /**
     * @param  array<string, string|null>  $data
     */
    private function barisContoh(array $data): bool
    {
        return $data['nis'] === '2026001' && $data['nisn'] === '9988776655' && $data['nama'] === 'Budi Santoso';
    }

    /**
     * @param  list<string|null>  $baris
     */
    private function barisKosong(array $baris): bool
    {
        foreach ($baris as $nilai) {
            if (trim((string) $nilai) !== '') {
                return false;
            }
        }

        return true;
    }

    /**
     * @param  array<string, string|null>  $data
     * @return array<string, string|null>
     */
    private function normalisasiSebelumValidasi(array $data): array
    {
        if ($data['jenis_kelamin'] !== null) {
            $data['jenis_kelamin'] = strtoupper($data['jenis_kelamin']);
        }

        if ($data['agama'] !== null) {
            $agama = $this->parseAgama($data['agama']);
            $data['agama'] = $agama?->value ?? $data['agama'];
        }

        return $data;
    }

    /**
     * @return array<string, mixed>
     */
    private function aturanBaris(int $cabangSekolahId): array
    {
        return [
            'nis' => [
                'required',
                'string',
                'max:30',
                Rule::unique('siswa', 'nis')->where('cabang_sekolah_id', $cabangSekolahId),
            ],
            'nisn' => ['required', 'string', 'max:30'],
            'nama' => ['required', 'string', 'max:150'],
            'jenis_kelamin' => ['nullable', Rule::in(['L', 'P', 'l', 'p'])],
            'tanggal_lahir' => ['nullable', 'date'],
            'tempat_lahir' => ['nullable', 'string', 'max:100'],
            'agama' => ['nullable', Rule::enum(Agama::class)],
            'status_dalam_keluarga' => ['nullable', 'string', 'max:50'],
            'anak_ke' => ['nullable', 'integer', 'min:1', 'max:20'],
            'alamat' => ['nullable', 'string', 'max:500'],
            'kecamatan' => ['nullable', 'string', 'max:100'],
            'kabupaten_kota' => ['nullable', 'string', 'max:100'],
            'nama_ayah' => ['nullable', 'string', 'max:150'],
            'nama_ibu' => ['nullable', 'string', 'max:150'],
            'alamat_orang_tua' => ['nullable', 'string', 'max:500'],
            'nomor_telepon_rumah' => ['nullable', 'string', 'max:30'],
            'pekerjaan_ayah' => ['nullable', 'string', 'max:100'],
            'pekerjaan_ibu' => ['nullable', 'string', 'max:100'],
            'nama_wali_siswa' => ['nullable', 'string', 'max:150'],
            'alamat_wali_siswa' => ['nullable', 'string', 'max:500'],
            'nomor_telepon_wali_siswa' => ['nullable', 'string', 'max:30'],
            'pekerjaan_wali_siswa' => ['nullable', 'string', 'max:100'],
            'aktif' => ['nullable', 'in:0,1,ya,tidak,yes,no,true,false'],
        ];
    }

    /**
     * @param  array<string, string|null>  $data
     * @return array<string, mixed>
     */
    private function atributDariBaris(array $data): array
    {
        return [
            'nis' => $data['nis'],
            'nisn' => $data['nisn'],
            'nama' => $data['nama'],
            'jenis_kelamin' => $this->parseJenisKelamin($data['jenis_kelamin']),
            'tanggal_lahir' => $data['tanggal_lahir'],
            'tempat_lahir' => $data['tempat_lahir'],
            'agama' => $this->parseAgama($data['agama']),
            'status_dalam_keluarga' => $data['status_dalam_keluarga'],
            'anak_ke' => $data['anak_ke'] !== null ? (int) $data['anak_ke'] : null,
            'alamat' => $data['alamat'],
            'kecamatan' => $data['kecamatan'],
            'kabupaten_kota' => $data['kabupaten_kota'],
            'nama_ayah' => $data['nama_ayah'],
            'nama_ibu' => $data['nama_ibu'],
            'alamat_orang_tua' => $data['alamat_orang_tua'],
            'nomor_telepon_rumah' => $data['nomor_telepon_rumah'],
            'pekerjaan_ayah' => $data['pekerjaan_ayah'],
            'pekerjaan_ibu' => $data['pekerjaan_ibu'],
            'nama_wali_siswa' => $data['nama_wali_siswa'],
            'alamat_wali_siswa' => $data['alamat_wali_siswa'],
            'nomor_telepon_wali_siswa' => $data['nomor_telepon_wali_siswa'],
            'pekerjaan_wali_siswa' => $data['pekerjaan_wali_siswa'],
            'aktif' => $this->parseAktif($data['aktif']),
        ];
    }

    private function parseJenisKelamin(?string $nilai): ?JenisKelamin
    {
        if ($nilai === null) {
            return null;
        }

        return match (strtoupper($nilai)) {
            'L' => JenisKelamin::LakiLaki,
            'P' => JenisKelamin::Perempuan,
            default => null,
        };
    }

    private function parseAgama(?string $nilai): ?Agama
    {
        if ($nilai === null) {
            return null;
        }

        $normalized = strtolower($nilai);

        foreach (Agama::cases() as $agama) {
            if ($normalized === $agama->value || $normalized === strtolower($agama->label())) {
                return $agama;
            }
        }

        return null;
    }

    private function parseAktif(?string $nilai): bool
    {
        if ($nilai === null) {
            return true;
        }

        return in_array(strtolower($nilai), ['1', 'ya', 'yes', 'true'], true);
    }

    /**
     * @param  list<string>  $nilai
     */
    private function barisKeCsv(array $nilai): string
    {
        $handle = fopen('php://temp', 'r+');
        fputcsv($handle, $nilai);
        rewind($handle);
        $csv = stream_get_contents($handle) ?: '';
        fclose($handle);

        return rtrim($csv, "\n");
    }
}
