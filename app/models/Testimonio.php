<?php
/**
 * Modelo Testimonio: Testimonios reales de clientes (vacío por defecto, nunca inventado).
 */

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

class Testimonio extends Model
{
    protected static string $collection = 'testimonios';

    public static function published(): array
    {
        $items = static::where(fn($t) => !empty($t['publicado']));
        usort($items, fn($a, $b) => ((int)($a['orden'] ?? 99)) <=> ((int)($b['orden'] ?? 99)));
        return $items;
    }

    public static function forProject(int $projectId): ?array
    {
        foreach (self::published() as $t) {
            if ((int)($t['proyecto_id'] ?? 0) === $projectId) {
                return $t;
            }
        }
        return null;
    }
}

