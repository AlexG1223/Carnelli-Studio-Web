<?php
/**
 * Modelo Proyecto: Casos de estudio del portfolio, filtrado por servicio y navegación anterior/siguiente.
 */

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

class Proyecto extends Model
{
    protected static string $collection = 'proyectos';

    public static function published(): array
    {
        $items = static::where(fn($p) => !empty($p['publicado']));
        usort($items, fn($a, $b) => ((int)($a['orden'] ?? 99)) <=> ((int)($b['orden'] ?? 99)));
        return self::hydrateServices($items);
    }

    public static function featured(int $limit = 4): array
    {
        $items = static::where(fn($p) => !empty($p['publicado']) && !empty($p['destacado']));
        usort($items, fn($a, $b) => ((int)($a['orden'] ?? 99)) <=> ((int)($b['orden'] ?? 99)));
        return array_slice(self::hydrateServices($items), 0, $limit);
    }

    public static function byServiceSlug(string $serviceSlug): array
    {
        $service = Servicio::findBySlug($serviceSlug);
        if (!$service) {
            return [];
        }
        return self::byServiceId((int)$service['id']);
    }

    public static function byServiceId(int $serviceId): array
    {
        $items = static::where(function ($p) use ($serviceId) {
            if (empty($p['publicado'])) {
                return false;
            }
            $ids = array_map('intval', $p['servicios_ids'] ?? []);
            return in_array($serviceId, $ids, true);
        });
        usort($items, fn($a, $b) => ((int)($a['orden'] ?? 99)) <=> ((int)($b['orden'] ?? 99)));
        return self::hydrateServices($items);
    }

    public static function findPublishedBySlug(string $slug): ?array
    {
        $item = static::findBySlug($slug);
        if (!$item || empty($item['publicado'])) {
            return null;
        }
        $hydrated = self::hydrateServices([$item]);
        return $hydrated[0] ?? null;
    }

    public static function adjacent(int $currentId): array
    {
        $published = self::published();
        $count = count($published);
        if ($count <= 1) {
            return ['prev' => null, 'next' => null];
        }

        $currentIndex = 0;
        foreach ($published as $idx => $p) {
            if ((int)$p['id'] === $currentId) {
                $currentIndex = $idx;
                break;
            }
        }

        $prevIndex = ($currentIndex - 1 + $count) % $count;
        $nextIndex = ($currentIndex + 1) % $count;

        return [
            'prev' => $published[$prevIndex] ?? null,
            'next' => $published[$nextIndex] ?? null,
        ];
    }

    public static function hydrateServices(array $projects): array
    {
        $allServices = Servicio::all();
        $serviceMap  = [];
        foreach ($allServices as $s) {
            $serviceMap[(int)$s['id']] = $s;
        }

        foreach ($projects as &$proj) {
            $sIds = array_map('intval', $proj['servicios_ids'] ?? []);
            $proj['servicios'] = [];
            foreach ($sIds as $sid) {
                if (isset($serviceMap[$sid])) {
                    $proj['servicios'][] = [
                        'id'     => $serviceMap[$sid]['id'],
                        'nombre' => $serviceMap[$sid]['nombre'],
                        'slug'   => $serviceMap[$sid]['slug'],
                        'capa'   => $serviceMap[$sid]['capa'],
                    ];
                }
            }
        }
        return $projects;
    }
}

