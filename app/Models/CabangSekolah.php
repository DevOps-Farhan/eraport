<?php

namespace App\Models;

use Database\Factories\CabangSekolahFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CabangSekolah extends Model
{
    /** @use HasFactory<CabangSekolahFactory> */
    use HasFactory;

    protected $table = 'cabang_sekolah';

    protected $fillable = [
        'kode',
        'nama',
        'alamat',
        'telepon',
        'aktif',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'aktif' => 'boolean',
        ];
    }

    /**
     * @return HasMany<User, $this>
     */
    public function pengguna(): HasMany
    {
        return $this->hasMany(User::class, 'cabang_sekolah_id');
    }
}
