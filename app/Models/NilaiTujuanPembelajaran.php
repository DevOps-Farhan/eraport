<?php

namespace App\Models;

use App\Enums\PredikatCapaian;
use Database\Factories\NilaiTujuanPembelajaranFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NilaiTujuanPembelajaran extends Model
{
    /** @use HasFactory<NilaiTujuanPembelajaranFactory> */
    use HasFactory;

    protected $table = 'nilai_tujuan_pembelajaran';

    protected $fillable = [
        'tujuan_pembelajaran_id',
        'siswa_id',
        'semester_id',
        'nilai',
        'predikat',
        'deskripsi_pencapaian',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'nilai' => 'decimal:2',
            'predikat' => PredikatCapaian::class,
        ];
    }

    /**
     * @return BelongsTo<TujuanPembelajaran, $this>
     */
    public function tujuanPembelajaran(): BelongsTo
    {
        return $this->belongsTo(TujuanPembelajaran::class, 'tujuan_pembelajaran_id');
    }

    /**
     * @return BelongsTo<Siswa, $this>
     */
    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }

    /**
     * @return BelongsTo<Semester, $this>
     */
    public function semester(): BelongsTo
    {
        return $this->belongsTo(Semester::class, 'semester_id');
    }
}
