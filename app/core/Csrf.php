<?php
/**
 * Protección contra ataques Cross-Site Request Forgery (CSRF).
 */

declare(strict_types=1);

namespace App\Core;

class Csrf
{
    public static function token(): string
    {
        Session::start();
        $token = Session::get('_csrf_token');
        if (!$token || !is_string($token)) {
            $token = bin2hex(random_bytes(32));
            Session::set('_csrf_token', $token);
        }
        return $token;
    }

    public static function field(): string
    {
        $token = htmlspecialchars(self::token(), ENT_QUOTES, 'UTF-8');
        return '<input type="hidden" name="_csrf" value="' . $token . '">';
    }

    public static function verify(?string $submittedToken): bool
    {
        Session::start();
        $sessionToken = Session::get('_csrf_token');
        if (!$sessionToken || !$submittedToken) {
            return false;
        }
        return hash_equals((string)$sessionToken, (string)$submittedToken);
    }
}

