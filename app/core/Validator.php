<?php
/**
 * Validator: Sanitización y validación de datos de formularios.
 */

declare(strict_types=1);

namespace App\Core;

class Validator
{
    public static function cleanString(?string $value, int $maxLength = 500): string
    {
        $val = trim(strip_tags((string)$value));
        $val = preg_replace('/\s+/u', ' ', $val) ?? '';
        return mb_substr($val, 0, $maxLength, 'UTF-8');
    }

    public static function cleanText(?string $value, int $maxLength = 5000): string
    {
        $val = trim(strip_tags((string)$value));
        return mb_substr($val, 0, $maxLength, 'UTF-8');
    }

    public static function cleanSlug(?string $value): string
    {
        $val = mb_strtolower(trim((string)$value), 'UTF-8');
        $replacements = [
            'á' => 'a', 'é' => 'e', 'í' => 'i', 'ó' => 'o', 'ú' => 'u',
            'ñ' => 'n', 'ü' => 'u'
        ];
        $val = strtr($val, $replacements);
        $val = preg_replace('/[^a-z0-9-]+/', '-', $val) ?? '';
        return trim($val, '-');
    }

    public static function isEmail(string $email): bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    public static function isPhone(string $phone): bool
    {
        $digits = preg_replace('/[^0-9]/', '', $phone) ?? '';
        return strlen($digits) >= 7 && strlen($digits) <= 15;
    }
}

