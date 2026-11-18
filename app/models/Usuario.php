<?php
/**
 * Modelo Usuario: Autenticación segura del administrador único con bloqueo temporal.
 */

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

class Usuario extends Model
{
    protected static string $collection = 'usuarios';

    public static function findByEmail(string $email): ?array
    {
        $email = strtolower(trim($email));
        foreach (static::all() as $user) {
            if (strtolower($user['email'] ?? '') === $email) {
                return $user;
            }
        }
        return null;
    }
}

