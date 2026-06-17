<?php

namespace App\Models;

use Database\Factories\TujuanPembelajaranFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TujuanPembelajaran extends Model
{
    /** @use HasFactory<TujuanPembelajaranFactory> */
    use HasFactory;

    protected $table = 'tujuan_pembelajaran';

    protected $fillable = [
        'penugasan_mengajar_id',
        'kode',
        'deskripsi_kompetensi',
        'urutan',
    ];

    /**
     * @return BelongsTo<PenugasanMengajar, $this>
     */
    public function penugasanMengajar(): BelongsTo
    {
        return $this->belongsTo(PenugasanMengajar::class, 'penugasan_mengajar_id');
    }

    /**
     * @return HasMany<NilaiTujuanPembelajaran, $this>
     */
    public function nilaiTujuanPembelajaran(): HasMany
    {
        return $this->hasMany(NilaiTujuanPembelajaran::class, 'tujuan_pembelajaran_id');
    }
}
