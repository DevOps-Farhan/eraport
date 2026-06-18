<?php

namespace App\Models;

use Database\Factories\PencapaianTpFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PencapaianTp extends Model
{
    /** @use HasFactory<PencapaianTpFactory> */
    use HasFactory;

    protected $table = 'pencapaian_tp';

    protected $fillable = [
        'penugasan_mengajar_id',
        'siswa_id',
        'semester_id',
        'pencapaian',
    ];

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
