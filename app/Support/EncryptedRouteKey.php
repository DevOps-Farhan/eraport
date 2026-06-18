<?php

namespace App\Support;

class EncryptedRouteKey
{
    private static function key(): string
    {
        return substr(hash('sha256', config('app.key'), true), 0, 32);
    }

    private static function iv(): string
    {
        return substr(hash('sha256', 'eraport-route-iv', true), 0, 16);
    }

    public static function encode(int|string $id): string
    {
        $encrypted = openssl_encrypt(
            (string) $id,
            'AES-256-CBC',
            self::key(),
            OPENSSL_RAW_DATA,
            self::iv(),
        );

        if ($encrypted === false) {
            throw new \RuntimeException('Gagal mengenkripsi route key.');
        }

        return rtrim(strtr(base64_encode($encrypted), '+/=', '-_.'), '.');
    }

    public static function decode(string $value): ?int
    {
        $encrypted = base64_decode(strtr($value, '-_.', '+/='), true);

        if ($encrypted === false) {
            return null;
        }

        $decrypted = openssl_decrypt(
            $encrypted,
            'AES-256-CBC',
            self::key(),
            OPENSSL_RAW_DATA,
            self::iv(),
        );

        if ($decrypted === false || ! ctype_digit($decrypted)) {
            return null;
        }

        return (int) $decrypted;
    }
}
