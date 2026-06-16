<?php

namespace App\Models;

use App\Enums\AksiLogAkademik;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LogAkademik extends Model
{
    public $timestamps = false;

    protected $table = 'log_akademik';

    protected $fillable = [
        'cabang_sekolah_id',
        'tahun_ajaran_id',
        'pengguna_id',
        'aksi',
        'keterangan',
        'created_at',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'aksi' => AksiLogAkademik::class,
            'created_at' => 'datetime',
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
     * @return BelongsTo<TahunAjaran, $this>
     */
    public function tahunAjaran(): BelongsTo
    {
        return $this->belongsTo(TahunAjaran::class, 'tahun_ajaran_id');
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function pengguna(): BelongsTo
    {
        return $this->belongsTo(User::class, 'pengguna_id');
    }
}
