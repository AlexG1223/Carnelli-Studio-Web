<?php
/**
 * Modelo Plan: Los 3 niveles (Arranque, Crecimiento y Transformación).
 */

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

class Plan extends Model
{
    protected static string $collection = 'planes';

    public static function active(): array
    {
        $items = static::where(fn($p) => !empty($p['activo']));
        usort($items, fn($a, $b) => ((int)($a['orden'] ?? 99)) <=> ((int)($b['orden'] ?? 99)));
        return $items;
    }
}

