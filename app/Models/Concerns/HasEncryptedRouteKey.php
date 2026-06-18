<?php

namespace App\Models\Concerns;

use App\Support\EncryptedRouteKey;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin Model
 */
trait HasEncryptedRouteKey
{
    public function initializeHasEncryptedRouteKey(): void
    {
        $this->append('route_key');
    }

    public function getRouteKey(): string
    {
        return EncryptedRouteKey::encode($this->getKey());
    }

    public function getRouteKeyAttribute(): string
    {
        return EncryptedRouteKey::encode($this->getKey());
    }

    /**
     * @return ?static
     */
    public function resolveRouteBinding($value, $field = null)
    {
        $id = EncryptedRouteKey::decode($value);

        if ($id === null) {
            return null;
        }

        return $this->where($field ?? $this->getRouteKeyName(), $id)->first();
    }
}
