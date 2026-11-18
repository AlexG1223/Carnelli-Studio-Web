<?php
/**
 * Modelo Setting: Configuración editable desde el panel de administración.
 */

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;
use App\Core\JsonStore;

class Setting extends Model
{
    protected static string $collection = 'settings';

    public static function allKeyValue(): array
    {
        $items = static::all();
        $map   = [];
        foreach ($items as $item) {
            if (isset($item['clave'])) {
                $map[$item['clave']] = $item['valor'] ?? '';
            }
        }
        return $map;
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        $map = self::allKeyValue();
        return ($map[$key] ?? '') !== '' ? $map[$key] : $default;
    }

    public static function saveKeyValues(array $pairs): bool
    {
        $items = static::all();
        $indexed = [];
        foreach ($items as $idx => $item) {
            $indexed[$item['clave']] = $idx;
        }

        foreach ($pairs as $key => $val) {
            if (isset($indexed[$key])) {
                $items[$indexed[$key]]['valor'] = (string)$val;
            } else {
                $items[] = [
                    'id'    => JsonStore::nextId($items),
                    'clave' => $key,
                    'valor' => (string)$val,
                    'grupo' => 'general'
                ];
            }
        }
        return JsonStore::write(static::$collection, $items);
    }
}

