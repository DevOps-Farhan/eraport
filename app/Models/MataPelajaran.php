<?php

namespace App\Models;

use App\Enums\KelompokMataPelajaran;
use App\Models\Concerns\HasEncryptedRouteKey;
use Database\Factories\MataPelajaranFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MataPelajaran extends Model
{
    /** @use HasFactory<MataPelajaranFactory> */
    use HasEncryptedRouteKey, HasFactory;

    protected $table = 'mata_pelajaran';

    protected $fillable = [
        'cabang_sekolah_id',
        'kode',
        'nama',
        'kelompok',
        'aktif',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'kelompok' => KelompokMataPelajaran::class,
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
     * @return HasMany<PenugasanMengajar, $this>
     */
    public function penugasanMengajar(): HasMany
    {
        return $this->hasMany(PenugasanMengajar::class, 'mata_pelajaran_id');
    }
}
