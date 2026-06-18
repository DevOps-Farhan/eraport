<?php

namespace App\Models;

use App\Models\Concerns\HasEncryptedRouteKey;
use Database\Factories\CabangSekolahFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CabangSekolah extends Model
{
    /** @use HasFactory<CabangSekolahFactory> */
    use HasEncryptedRouteKey, HasFactory;

    protected $table = 'cabang_sekolah';

    protected $fillable = [
        'kode',
        'nama',
        'kepala_sekolah',
        'alamat',
        'telepon',
        'aktif',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'aktif' => 'boolean',
        ];
    }

    /**
     * @return HasMany<User, $this>
     */
    public function pengguna(): HasMany
    {
        return $this->hasMany(User::class, 'cabang_sekolah_id');
    }

    /**
     * @return HasMany<TahunAjaran, $this>
     */
    public function tahunAjaran(): HasMany
    {
        return $this->hasMany(TahunAjaran::class, 'cabang_sekolah_id');
    }

    /**
     * @return HasMany<Jurusan, $this>
     */
    public function jurusan(): HasMany
    {
        return $this->hasMany(Jurusan::class, 'cabang_sekolah_id');
    }

    /**
     * @return HasMany<MataPelajaran, $this>
     */
    public function mataPelajaran(): HasMany
    {
        return $this->hasMany(MataPelajaran::class, 'cabang_sekolah_id');
    }

    /**
     * @return HasMany<Siswa, $this>
     */
    public function siswa(): HasMany
    {
        return $this->hasMany(Siswa::class, 'cabang_sekolah_id');
    }

    /**
     * @return HasMany<Ekstrakurikuler, $this>
     */
    public function ekstrakurikuler(): HasMany
    {
        return $this->hasMany(Ekstrakurikuler::class, 'cabang_sekolah_id');
    }
}
