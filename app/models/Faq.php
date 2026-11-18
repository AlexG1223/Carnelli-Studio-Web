<?php
/**
 * Modelo Faq: Preguntas frecuentes generales y específicas por servicio.
 */

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

class Faq extends Model
{
    protected static string $collection = 'faqs';

    public static function active(): array
    {
        $items = static::where(fn($f) => !empty($f['activo']));
        usort($items, fn($a, $b) => ((int)($a['orden'] ?? 99)) <=> ((int)($b['orden'] ?? 99)));
        return $items;
    }

    public static function forHome(int $limit = 6): array
    {
        $items = static::where(fn($f) => !empty($f['activo']) && !empty($f['mostrar_en_home']));
        usort($items, fn($a, $b) => ((int)($a['orden'] ?? 99)) <=> ((int)($b['orden'] ?? 99)));
        return array_slice($items, 0, $limit);
    }

    public static function forService(int $serviceId): array
    {
        $items = static::where(fn($f) => !empty($f['activo']) && (int)($f['servicio_id'] ?? 0) === $serviceId);
        usort($items, fn($a, $b) => ((int)($a['orden'] ?? 99)) <=> ((int)($b['orden'] ?? 99)));
        return $items;
    }
}

