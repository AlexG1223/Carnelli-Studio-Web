<?php
/**
 * App: Bootstrap del framework MVC liviano y registro de autoloader PSR-4 propio.
 */

declare(strict_types=1);

namespace App\Core;

class App
{
    public static function run(): void
    {
        // Autoloader PSR-4 para el namespace App\
        spl_autoload_register(function (string $class): void {
            $prefix = 'App\\';
            if (!str_starts_with($class, $prefix)) {
                return;
            }
            $relative = substr($class, strlen($prefix));
            $parts    = explode('\\', $relative);
            $fileName = array_pop($parts) . '.php';
            $subPath  = strtolower(implode('/', $parts));
            if (isset($parts[0]) && strtolower($parts[0]) === 'controllers' && isset($parts[1]) && $parts[1] === 'Admin') {
                $subPath = 'controllers/Admin';
            }
            $file = APP_PATH . '/' . ($subPath ? $subPath . '/' : '') . $fileName;
            if (is_file($file)) {
                require_once $file;
            }
        });

        Session::start();

        // Headers de seguridad recomendados
        if (!headers_sent()) {
            header('X-Content-Type-Options: nosniff');
            header('Referrer-Policy: strict-origin-when-cross-origin');
            header('X-Frame-Options: SAMEORIGIN');
            header('Permissions-Policy: geolocation=(), microphone=(), camera=()');
        }

        $router = new Router();
        require APP_PATH . '/config/routes.php';

        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        $uri    = $_SERVER['REQUEST_URI'] ?? '/';

        $router->dispatch($method, $uri);
    }
}

