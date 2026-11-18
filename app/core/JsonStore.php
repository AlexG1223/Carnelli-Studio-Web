<?php
/**
 * JsonStore: Almacenamiento flat-file en archivos JSON con bloqueo atómico (flock).
 * Permite operar toda la web y el panel /admin sin base de datos MySQL.
 */

declare(strict_types=1);

namespace App\Core;

class JsonStore
{
    private static array $memoryCache = [];

    /**
     * Lee una colección JSON desde app/data/{collection}.json
     */
    public static function read(string $collection): array
    {
        if (isset(self::$memoryCache[$collection])) {
            return self::$memoryCache[$collection];
        }

        $file = self::getFilePath($collection);
        if (!is_file($file)) {
            return [];
        }

        $fp = fopen($file, 'rb');
        if ($fp === false) {
            return [];
        }

        flock($fp, LOCK_SH);
        $contents = stream_get_contents($fp);
        flock($fp, LOCK_UN);
        fclose($fp);

        if (!$contents) {
            return [];
        }

        $decoded = json_decode($contents, true);
        if (!is_array($decoded)) {
            return [];
        }

        self::$memoryCache[$collection] = $decoded;
        return $decoded;
    }

    /**
     * Escribe de forma atómica una colección JSON en app/data/{collection}.json
     */
    public static function write(string $collection, array $data): bool
    {
        $file = self::getFilePath($collection);
        $dir  = dirname($file);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        if ($json === false) {
            return false;
        }

        $tmpFile = $file . '.' . uniqid('tmp_', true);
        if (file_put_contents($tmpFile, $json, LOCK_EX) === false) {
            return false;
        }

        if (!rename($tmpFile, $file)) {
            @unlink($tmpFile);
            return false;
        }

        self::$memoryCache[$collection] = $data;

        // Invalidar caché HTML pública automáticamente al guardar datos
        Cache::clear();

        return true;
    }

    /**
     * Calcula el próximo ID numérico incremental de una colección
     */
    public static function nextId(array $items): int
    {
        $max = 0;
        foreach ($items as $item) {
            $id = (int)($item['id'] ?? 0);
            if ($id > $max) {
                $max = $id;
            }
        }
        return $max + 1;
    }

    private static function getFilePath(string $collection): string
    {
        $safeName = preg_replace('/[^a-zA-Z0-9_-]/', '', $collection);
        return DATA_PATH . '/' . $safeName . '.json';
    }
}

