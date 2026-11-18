<?php
/**
 * Clase base Model para acceso estructurado a colecciones JSON.
 */

declare(strict_types=1);

namespace App\Core;

abstract class Model
{
    protected static string $collection = '';

    public static function all(): array
    {
        return JsonStore::read(static::$collection);
    }

    public static function find(int $id): ?array
    {
        foreach (static::all() as $item) {
            if ((int)($item['id'] ?? 0) === $id) {
                return $item;
            }
        }
        return null;
    }

    public static function findBySlug(string $slug): ?array
    {
        foreach (static::all() as $item) {
            if (($item['slug'] ?? '') === $slug) {
                return $item;
            }
        }
        return null;
    }

    public static function where(callable $predicate): array
    {
        return array_values(array_filter(static::all(), $predicate));
    }

    public static function create(array $attributes): array
    {
        $items = static::all();
        $attributes['id'] = JsonStore::nextId($items);
        $now = date('Y-m-d H:i:s');
        if (!isset($attributes['created_at'])) {
            $attributes['created_at'] = $now;
        }
        $attributes['updated_at'] = $now;
        $items[] = $attributes;
        JsonStore::write(static::$collection, $items);
        return $attributes;
    }

    public static function update(int $id, array $attributes): ?array
    {
        $items = static::all();
        $updatedItem = null;
        foreach ($items as $index => $item) {
            if ((int)($item['id'] ?? 0) === $id) {
                $attributes['id'] = $id;
                $attributes['created_at'] = $item['created_at'] ?? date('Y-m-d H:i:s');
                $attributes['updated_at'] = date('Y-m-d H:i:s');
                $items[$index] = array_merge($item, $attributes);
                $updatedItem = $items[$index];
                break;
            }
        }
        if ($updatedItem !== null) {
            JsonStore::write(static::$collection, $items);
        }
        return $updatedItem;
    }

    public static function delete(int $id): bool
    {
        $items = static::all();
        $filtered = array_values(array_filter($items, fn($item) => (int)($item['id'] ?? 0) !== $id));
        if (count($filtered) === count($items)) {
            return false;
        }
        return JsonStore::write(static::$collection, $filtered);
    }

    public static function saveAll(array $items): bool
    {
        return JsonStore::write(static::$collection, array_values($items));
    }
}

