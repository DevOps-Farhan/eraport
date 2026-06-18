<?php

namespace App\Models;

use App\Models\Concerns\HasEncryptedRouteKey;
use Database\Factories\PenugasanPembinaFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PenugasanPembina extends Model
{
    /** @use HasFactory<PenugasanPembinaFactory> */
    use HasEncryptedRouteKey, HasFactory;

    protected $table = 'penugasan_pembina';

    protected $fillable = [
        'tahun_ajaran_id',
        'ekstrakurikuler_id',
        'pembina_id',
    ];

    /**
     * @return BelongsTo<TahunAjaran, $this>
     */
    public function tahunAjaran(): BelongsTo
    {
        return $this->belongsTo(TahunAjaran::class, 'tahun_ajaran_id');
    }

    /**
     * @return BelongsTo<Ekstrakurikuler, $this>
     */
    public function ekstrakurikuler(): BelongsTo
    {
        return $this->belongsTo(Ekstrakurikuler::class, 'ekstrakurikuler_id');
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function pembina(): BelongsTo
    {
        return $this->belongsTo(User::class, 'pembina_id');
    }
}
