<?php
/**
 * Cache: Sistema de caché HTML en archivos para páginas públicas.
 * Se invalida automáticamente cuando JsonStore escribe cualquier cambio desde el Admin.
 */

declare(strict_types=1);

namespace App\Core;

class Cache
{
    private static int $ttl = 3600; // 1 hora

    public static function get(string $uri): ?string
    {
        if (APP_ENV === 'development') {
            return null;
        }
        $file = self::getFilePath($uri);
        if (!is_file($file)) {
            return null;
        }
        if ((time() - filemtime($file)) > self::$ttl) {
            @unlink($file);
            return null;
        }
        $content = @file_get_contents($file);
        return $content !== false ? $content : null;
    }

    public static function put(string $uri, string $html): void
    {
        if (APP_ENV === 'development') {
            return;
        }
        $file = self::getFilePath($uri);
        $dir  = dirname($file);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        @file_put_contents($file, $html, LOCK_EX);
    }

    public static function clear(): void
    {
        $dir = STORAGE_PATH . '/cache';
        if (!is_dir($dir)) {
            return;
        }
        foreach (glob($dir . '/*.html') ?: [] as $file) {
            @unlink($file);
        }
    }

    private static function getFilePath(string $uri): string
    {
        return STORAGE_PATH . '/cache/' . md5($uri) . '.html';
    }
}

