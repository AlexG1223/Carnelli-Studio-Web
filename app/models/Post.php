<?php
/**
 * Modelo Post: Artículos del blog orientados a pymes uruguayas (motor SEO y GEO).
 */

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

class Post extends Model
{
    protected static string $collection = 'posts';

    public static function published(): array
    {
        $items = static::where(fn($p) => !empty($p['publicado']));
        usort($items, fn($a, $b) => strcmp($b['fecha_publicacion'] ?? '', $a['fecha_publicacion'] ?? ''));
        return $items;
    }

    public static function byCategory(string $category): array
    {
        $items = static::where(
            fn($p) => !empty($p['publicado']) && mb_strtolower($p['categoria'] ?? '') === mb_strtolower($category)
        );
        usort($items, fn($a, $b) => strcmp($b['fecha_publicacion'] ?? '', $a['fecha_publicacion'] ?? ''));
        return $items;
    }

    public static function categories(): array
    {
        $cats = [];
        foreach (self::published() as $p) {
            if (!empty($p['categoria'])) {
                $cats[$p['categoria']] = ($cats[$p['categoria']] ?? 0) + 1;
            }
        }
        return $cats;
    }
}

