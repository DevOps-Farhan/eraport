<?php

namespace Tests\Unit;

use App\Models\PenugasanMengajar;
use App\Support\EncryptedRouteKey;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EncryptedRouteKeyTest extends TestCase
{
    use RefreshDatabase;

    public function test_encode_decode_id(): void
    {
        $encoded = EncryptedRouteKey::encode(42);

        $this->assertNotSame('42', $encoded);
        $this->assertSame(42, EncryptedRouteKey::decode($encoded));
    }

    public function test_model_route_key_tidak_menampilkan_id_asli(): void
    {
        $penugasan = PenugasanMengajar::factory()->create();

        $this->assertNotSame((string) $penugasan->id, $penugasan->getRouteKey());
    }

    public function test_model_binding_menggunakan_route_key_terenkripsi(): void
    {
        $penugasan = PenugasanMengajar::factory()->create();

        $resolved = (new PenugasanMengajar)->resolveRouteBinding($penugasan->getRouteKey());

        $this->assertNotNull($resolved);
        $this->assertTrue($penugasan->is($resolved));
    }
}
