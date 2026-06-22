<?php

namespace App\Models;

use App\Enums\Agama;
use App\Enums\JenisKelamin;
use App\Models\Concerns\HasEncryptedRouteKey;
use Database\Factories\SiswaFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Siswa extends Model
{
    /** @use HasFactory<SiswaFactory> */
    use HasEncryptedRouteKey, HasFactory;

    protected $table = 'siswa';

    protected $fillable = [
        'cabang_sekolah_id',
        'nis',
        'nisn',
        'nama',
        'jenis_kelamin',
        'tanggal_lahir',
        'tempat_lahir',
        'agama',
        'status_dalam_keluarga',
        'anak_ke',
        'alamat',
        'kecamatan',
        'kabupaten_kota',
        'nama_ayah',
        'nama_ibu',
        'alamat_orang_tua',
        'nomor_telepon_rumah',
        'pekerjaan_ayah',
        'pekerjaan_ibu',
        'nama_wali_siswa',
        'alamat_wali_siswa',
        'nomor_telepon_wali_siswa',
        'pekerjaan_wali_siswa',
        'aktif',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'jenis_kelamin' => JenisKelamin::class,
            'tanggal_lahir' => 'date',
            'agama' => Agama::class,
            'anak_ke' => 'integer',
            'aktif' => 'boolean',
        ];
    }

    /**
     * @return BelongsTo<CabangSekolah, $this>
     */
    public function cabangSekolah(): BelongsTo
    {
        return $this->belongsTo(CabangSekolah::class, 'cabang_sekolah_id');
    }

    /**
     * @return HasMany<RombelSiswa, $this>
     */
    public function rombelSiswa(): HasMany
    {
        return $this->hasMany(RombelSiswa::class, 'siswa_id');
    }

    /**
     * @return HasMany<NilaiUjian, $this>
     */
    public function nilaiUjian(): HasMany
    {
        return $this->hasMany(NilaiUjian::class, 'siswa_id');
    }

    /**
     * @return HasMany<AnggotaEkstrakurikuler, $this>
     */
    public function anggotaEkstrakurikuler(): HasMany
    {
        return $this->hasMany(AnggotaEkstrakurikuler::class, 'siswa_id');
    }
}
