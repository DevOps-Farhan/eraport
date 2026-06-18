<?php

namespace App\Models;

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
}
