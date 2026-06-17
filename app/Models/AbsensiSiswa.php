<?php

namespace App\Models;

use Database\Factories\AbsensiSiswaFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AbsensiSiswa extends Model
{
    /** @use HasFactory<AbsensiSiswaFactory> */
    use HasFactory;

    protected $table = 'absensi_siswa';

    protected $fillable = [
        'siswa_id',
        'semester_id',
        'sakit',
        'izin',
        'alpha',
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
