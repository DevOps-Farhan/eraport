<?php

namespace App\Models;

use App\Enums\FaseKelas;
use App\Enums\TingkatKelas;
use App\Models\Concerns\HasEncryptedRouteKey;
use Database\Factories\KelasFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kelas extends Model
{
    /** @use HasFactory<KelasFactory> */
    use HasEncryptedRouteKey, HasFactory;

    protected $table = 'kelas';

    protected $fillable = [
        'tahun_ajaran_id',
        'jurusan_id',
        'tingkat',
        'fase',
        'nama',
        'wali_kelas_id',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'tingkat' => TingkatKelas::class,
            'fase' => FaseKelas::class,
        ];
    }

    /**
     * @return BelongsTo<TahunAjaran, $this>
     */
    public function tahunAjaran(): BelongsTo
    {
        return $this->belongsTo(TahunAjaran::class, 'tahun_ajaran_id');
    }

    /**
     * @return BelongsTo<Jurusan, $this>
     */
    public function jurusan(): BelongsTo
    {
        return $this->belongsTo(Jurusan::class, 'jurusan_id');
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function waliKelas(): BelongsTo
    {
        return $this->belongsTo(User::class, 'wali_kelas_id');
    }

    /**
     * @return HasMany<RombelSiswa, $this>
     */
    public function rombelSiswa(): HasMany
    {
        return $this->hasMany(RombelSiswa::class, 'kelas_id');
    }

    /**
     * @return HasMany<PenugasanMengajar, $this>
     */
    public function penugasanMengajar(): HasMany
    {
        return $this->hasMany(PenugasanMengajar::class, 'kelas_id');
    }
}
