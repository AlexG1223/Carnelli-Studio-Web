<?php
/**
 * Router: Enrutador limpio con soporte para parámetros dinámicos ({slug}, {id}) y redirecciones 301.
 */

declare(strict_types=1);

namespace App\Core;

class Router
{
    private array $routes = [
        'GET'  => [],
        'POST' => [],
    ];

    private static string $resolvedPath = '/';

    public function get(string $pattern, array $handler): void
    {
        $this->routes['GET'][$pattern] = $handler;
    }

    public function post(string $pattern, array $handler): void
    {
        $this->routes['POST'][$pattern] = $handler;
    }

    public static function currentPath(): string
    {
        return self::$resolvedPath;
    }

    public function dispatch(string $method, string $requestUri): void
    {
        $path = parse_url($requestUri, PHP_URL_PATH) ?: '/';
        if (BASE_PATH !== '' && str_starts_with($path, BASE_PATH)) {
            $path = substr($path, strlen(BASE_PATH)) ?: '/';
        }
        if (str_starts_with($path, '/public_html')) {
            $path = substr($path, strlen('/public_html')) ?: '/';
        }
        $path = '/' . trim($path, '/');
        self::$resolvedPath = $path;

        // Comprobar mapa de redirecciones 301
        $redirectsFile = APP_PATH . '/config/redirects.php';
        if (is_file($redirectsFile)) {
            $redirects = require $redirectsFile;
            if (isset($redirects[$path])) {
                header('Location: ' . View::url($redirects[$path]), true, 301);
                exit;
            }
        }

        $method = strtoupper($method);
        if ($method === 'HEAD') {
            $method = 'GET';
        }

        // Servir desde caché HTML en páginas públicas GET (sin sesión flash ni admin)
        $isCacheable = $method === 'GET'
            && !str_starts_with($path, '/admin')
            && !str_starts_with($path, '/api/')
            && empty($_GET);

        if ($isCacheable) {
            $cached = Cache::get($path);
            if ($cached !== null) {
                echo $cached;
                return;
            }
        }

        $routesForMethod = $this->routes[$method] ?? [];
        foreach ($routesForMethod as $pattern => $handler) {
            $regex = preg_replace('#\{([a-zA-Z0-9_]+)\}#', '(?P<$1>[^/]+)', $pattern);
            $regex = '#^' . $regex . '$#';

            if (preg_match($regex, $path, $matches)) {
                $params = [];
                foreach ($matches as $key => $val) {
                    if (is_string($key)) {
                        $params[$key] = $val;
                    }
                }

                [$controllerClass, $action] = $handler;
                $controller = new $controllerClass();

                if ($isCacheable && !in_array($path, ['/sitemap.xml', '/robots.txt', '/llms.txt', '/gracias'], true)) {
                    ob_start();
                    call_user_func_array([$controller, $action], $params);
                    $output = (string)ob_get_clean();
                    if (http_response_code() === 200) {
                        Cache::put($path, $output);
                    }
                    echo $output;
                    return;
                }

                call_user_func_array([$controller, $action], $params);
                return;
            }
        }

        // Si ninguna ruta coincide, mostrar 404 personalizada
        http_response_code(404);
        $errorController = new \App\Controllers\PaginasController();
        $errorController->notFound();
    }
}

