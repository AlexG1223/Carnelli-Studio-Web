<?php
/**
 * Modelo Cliente: Clientes para la franja de confianza y asociación con casos de estudio.
 */

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

class Cliente extends Model
{
    protected static string $collection = 'clientes';

    public static function visible(): array
    {
        $items = static::where(fn($c) => !empty($c['visible']));
        usort($items, fn($a, $b) => ((int)($a['orden'] ?? 99)) <=> ((int)($b['orden'] ?? 99)));
        return $items;
    }
}

