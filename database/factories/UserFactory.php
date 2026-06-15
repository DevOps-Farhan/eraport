<?php

namespace Database\Factories;

use App\Enums\PeranPengguna;
use App\Models\CabangSekolah;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    protected $model = User::class;

    protected static ?string $password;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'username' => fake()->unique()->userName(),
            'nama' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'password' => static::$password ??= Hash::make('password'),
            'peran' => PeranPengguna::Guru,
            'cabang_sekolah_id' => CabangSekolah::factory(),
            'aktif' => true,
            'remember_token' => Str::random(10),
        ];
    }

    public function adminPusat(): static
    {
        return $this->state(fn (array $attributes) => [
            'peran' => PeranPengguna::AdminPusat,
            'cabang_sekolah_id' => null,
        ]);
    }

    public function adminCabang(): static
    {
        return $this->state(fn (array $attributes) => [
            'peran' => PeranPengguna::AdminCabang,
        ]);
    }

    public function guru(): static
    {
        return $this->state(fn (array $attributes) => [
            'peran' => PeranPengguna::Guru,
        ]);
    }

    public function pembinaEkstrakurikuler(): static
    {
        return $this->state(fn (array $attributes) => [
            'peran' => PeranPengguna::PembinaEkstrakurikuler,
        ]);
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'aktif' => false,
        ]);
    }
}
