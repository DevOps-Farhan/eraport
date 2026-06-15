<?php

namespace App\Models;

use App\Enums\PeranPengguna;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['username', 'nama', 'email', 'password', 'peran', 'cabang_sekolah_id', 'aktif'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $table = 'pengguna';

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'peran' => PeranPengguna::class,
            'aktif' => 'boolean',
        ];
    }

    /**
     * @return BelongsTo<CabangSekolah, $this>
     */
    public function cabangSekolah(): BelongsTo
    {
        return $this->belongsTo(CabangSekolah::class, 'cabang_sekolah_id');
    }

    public function isAdminPusat(): bool
    {
        return $this->peran === PeranPengguna::AdminPusat;
    }

    public function dashboardRouteName(): string
    {
        return match ($this->peran) {
            PeranPengguna::AdminPusat => 'admin-pusat.dashboard',
            PeranPengguna::AdminCabang => 'admin-cabang.dashboard',
            PeranPengguna::Guru => 'guru.dashboard',
            PeranPengguna::PembinaEkstrakurikuler => 'pembina.dashboard',
        };
    }

    public function layoutName(): string
    {
        return $this->isAdminPusat()
            ? 'layouts.admin-pusat'
            : 'layouts.app';
    }
}
