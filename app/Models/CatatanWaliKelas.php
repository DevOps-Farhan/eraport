<?php

namespace App\Models;

use Database\Factories\CatatanWaliKelasFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CatatanWaliKelas extends Model
{
    /** @use HasFactory<CatatanWaliKelasFactory> */
    use HasFactory;

    protected $table = 'catatan_wali_kelas';

    protected $fillable = [
        'siswa_id',
        'semester_id',
        'catatan',
    ];

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
