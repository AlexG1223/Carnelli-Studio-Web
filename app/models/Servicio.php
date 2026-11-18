<?php
/**
 * Modelo Servicio: Servicios de Capa 1 (Marketing) y Capa 2 (Sistemas e IA).
 */

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

class Servicio extends Model
{
    protected static string $collection = 'servicios';

    public static function active(): array
    {
        $items = static::where(fn($s) => !empty($s['activo']));
        usort($items, fn($a, $b) => ((int)($a['orden'] ?? 99)) <=> ((int)($b['orden'] ?? 99)));
        return $items;
    }

    public static function byLayer(int $layer): array
    {
        $items = static::where(fn($s) => !empty($s['activo']) && (int)($s['capa'] ?? 1) === $layer);
        usort($items, fn($a, $b) => ((int)($a['orden'] ?? 99)) <=> ((int)($b['orden'] ?? 99)));
        return $items;
    }

    public static function byIds(array $ids): array
    {
        $idMap = array_map('intval', $ids);
        return static::where(fn($s) => in_array((int)($s['id'] ?? 0), $idMap, true));
    }
}

