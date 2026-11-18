<?php
/**
 * View: Motor de plantillas nativo PHP con helpers de escape seguro, URLs y assets.
 */

declare(strict_types=1);

namespace App\Core;

class View
{
    /**
     * Escapa texto para salida segura en HTML (previene XSS).
     */
    public static function e(mixed $value): string
    {
        return htmlspecialchars((string)($value ?? ''), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }

    /**
     * Genera una URL absoluta hacia una ruta del sitio.
     */
    public static function url(string $path = ''): string
    {
        $clean = ltrim($path, '/');
        return $clean === '' ? BASE_URL . '/' : BASE_URL . '/' . $clean;
    }

    /**
     * Genera una URL hacia un recurso estático en public_html con cache-busting opcional.
     */
    public static function asset(string $path, bool $versioned = true): string
    {
        $clean = ltrim($path, '/');
        $url   = BASE_URL . '/' . $clean;
        if ($versioned && !str_contains($clean, '?')) {
            $url .= '?v=' . ASSETS_VERSION;
        }
        return $url;
    }

    /**
     * Devuelve la URL canónica actual sin query strings de tracking.
     */
    public static function currentUrl(): string
    {
        $uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
        if (BASE_PATH !== '' && str_starts_with($uri, BASE_PATH)) {
            $uri = substr($uri, strlen(BASE_PATH)) ?: '/';
        }
        return self::url($uri === '/' ? '' : $uri);
    }

    /**
     * Renderiza una vista dentro de un layout.
     */
    public static function render(string $view, array $data = [], ?string $layout = 'main'): string
    {
        $viewFile = APP_PATH . '/views/' . trim($view, '/') . '.php';
        if (!is_file($viewFile)) {
            throw new \RuntimeException("Vista no encontrada: {$view}");
        }

        extract($data, EXTR_SKIP);

        ob_start();
        require $viewFile;
        $content = (string)ob_get_clean();

        if ($layout === null) {
            return $content;
        }

        $layoutFile = APP_PATH . '/views/layouts/' . $layout . '.php';
        if (!is_file($layoutFile)) {
            return $content;
        }

        ob_start();
        require $layoutFile;
        return (string)ob_get_clean();
    }

    /**
     * Renderiza un partial (fragmento reutilizable).
     */
    public static function partial(string $partial, array $data = []): string
    {
        $file = APP_PATH . '/views/partials/' . trim($partial, '/') . '.php';
        if (!is_file($file)) {
            return '';
        }
        extract($data, EXTR_SKIP);
        ob_start();
        require $file;
        return (string)ob_get_clean();
    }

    /**
     * Genera el enlace directo a WhatsApp con mensaje personalizado.
     */
    public static function whatsappUrl(string $customMessage = '', ?string $phone = null): string
    {
        $number = $phone ?: (\App\Models\Setting::get('whatsapp_intl', '59892473724'));
        $number = preg_replace('/[^0-9]/', '', (string)$number) ?: '59892473724';
        if ($customMessage === '') {
            $customMessage = (string)\App\Models\Setting::get(
                'whatsapp_mensaje_default',
                'Hola Alex, entré a la web de Carnelli Studio y quiero hacerte una consulta para mi negocio.'
            );
        }
        return 'https://wa.me/' . $number . '?text=' . rawurlencode($customMessage);
    }
}

