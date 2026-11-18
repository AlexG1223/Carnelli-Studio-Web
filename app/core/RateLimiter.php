<?php
/**
 * RateLimiter: Control de frecuencia de envíos por IP hasheada usando archivos locales.
 */

declare(strict_types=1);

namespace App\Core;

class RateLimiter
{
    public static function ipHash(): string
    {
        $ip   = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
        $salt = (string)env('SECURITY_SALT', 'cs_salt_default');
        return hash('sha256', $ip . '|' . $salt);
    }

    /**
     * Comprueba si la clave supera el máximo de intentos en la ventana de segundos.
     */
    public static function tooManyAttempts(string $action, int $maxAttempts = 5, int $windowSeconds = 300): bool
    {
        $file = self::getFile($action);
        $now  = time();
        $hits = self::readHits($file, $now - $windowSeconds);
        return count($hits) >= $maxAttempts;
    }

    /**
     * Registra un intento para la acción actual.
     */
    public static function hit(string $action, int $windowSeconds = 300): void
    {
        $file = self::getFile($action);
        $now  = time();
        $hits = self::readHits($file, $now - $windowSeconds);
        $hits[] = $now;
        @file_put_contents($file, json_encode($hits), LOCK_EX);
    }

    public static function clear(string $action): void
    {
        $file = self::getFile($action);
        if (is_file($file)) {
            @unlink($file);
        }
    }

    private static function getFile(string $action): string
    {
        $dir = STORAGE_PATH . '/rate-limit';
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        $key = hash('sha256', $action . ':' . self::ipHash());
        return $dir . '/' . $key . '.json';
    }

    private static function readHits(string $file, int $cutoff): array
    {
        if (!is_file($file)) {
            return [];
        }
        $raw = @file_get_contents($file);
        $arr = $raw ? json_decode($raw, true) : [];
        if (!is_array($arr)) {
            return [];
        }
        return array_values(array_filter($arr, fn($ts) => is_int($ts) && $ts >= $cutoff));
    }
}

