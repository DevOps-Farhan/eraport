<?php

namespace App\Models;

use App\Enums\JenisSemester;
use App\Enums\StatusSemester;
use App\Enums\StatusTahunAjaran;
use Database\Factories\TahunAjaranFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class TahunAjaran extends Model
{
    /** @use HasFactory<TahunAjaranFactory> */
    use HasFactory;

    protected $table = 'tahun_ajaran';

    protected $fillable = [
        'cabang_sekolah_id',
        'nama',
        'tanggal_mulai',
        'tanggal_selesai',
        'status',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => StatusTahunAjaran::class,
            'tanggal_mulai' => 'date',
            'tanggal_selesai' => 'date',
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
     * @return HasMany<Semester, $this>
     */
    public function semester(): HasMany
    {
        return $this->hasMany(Semester::class, 'tahun_ajaran_id');
    }

    /**
     * @return HasMany<LogAkademik, $this>
     */
    public function logAkademik(): HasMany
    {
        return $this->hasMany(LogAkademik::class, 'tahun_ajaran_id');
    }

    /**
     * @return HasOne<Semester, $this>
     */
    public function semesterBerjalan(): HasOne
    {
        return $this->hasOne(Semester::class, 'tahun_ajaran_id')
            ->where('status', StatusSemester::Berjalan);
    }

    public function isDraft(): bool
    {
        return $this->status === StatusTahunAjaran::Draft;
    }

    public function isAktif(): bool
    {
        return $this->status === StatusTahunAjaran::Aktif;
    }

    public function isDitutup(): bool
    {
        return $this->status === StatusTahunAjaran::Ditutup;
    }

    public function semesterGanjil(): ?Semester
    {
        return $this->semester->firstWhere('jenis', JenisSemester::Ganjil);
    }

    public function semesterGenap(): ?Semester
    {
        return $this->semester->firstWhere('jenis', JenisSemester::Genap);
    }
}
