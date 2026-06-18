<?php

namespace App\Models;

use App\Models\Concerns\HasEncryptedRouteKey;
use Database\Factories\JurusanFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Jurusan extends Model
{
    /** @use HasFactory<JurusanFactory> */
    use HasEncryptedRouteKey, HasFactory;

    protected $table = 'jurusan';

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
     * @return HasMany<Kelas, $this>
     */
    public function kelas(): HasMany
    {
        return $this->hasMany(Kelas::class, 'jurusan_id');
    }
}
