<?php

namespace App\Models;

use Database\Factories\NilaiUjianFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NilaiUjian extends Model
{
    /** @use HasFactory<NilaiUjianFactory> */
    use HasFactory;

    protected $table = 'nilai_ujian';

    protected $fillable = [
        'penugasan_mengajar_id',
        'siswa_id',
        'semester_id',
        'nilai',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'nilai' => 'decimal:2',
        ];
    }

    /**
     * @return BelongsTo<PenugasanMengajar, $this>
     */
    public function penugasanMengajar(): BelongsTo
    {
        return $this->belongsTo(PenugasanMengajar::class, 'penugasan_mengajar_id');
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
