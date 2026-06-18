<?php

namespace App\Models;

use App\Models\Concerns\HasEncryptedRouteKey;
use Database\Factories\EkstrakurikulerFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ekstrakurikuler extends Model
{
    /** @use HasFactory<EkstrakurikulerFactory> */
    use HasEncryptedRouteKey, HasFactory;

    protected $table = 'ekstrakurikuler';

    protected $fillable = [
        'cabang_sekolah_id',
        'kode',
        'nama',
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
     * @return BelongsTo<CabangSekolah, $this>
     */
    public function cabangSekolah(): BelongsTo
    {
        return $this->belongsTo(CabangSekolah::class, 'cabang_sekolah_id');
    }

    /**
     * @return HasMany<PenugasanPembina, $this>
     */
    public function penugasanPembina(): HasMany
    {
        return $this->hasMany(PenugasanPembina::class, 'ekstrakurikuler_id');
    }
}
