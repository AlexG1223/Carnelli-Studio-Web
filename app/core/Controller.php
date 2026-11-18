<?php
/**
 * Clase base Controller para controladores públicos y de administración.
 */

declare(strict_types=1);

namespace App\Core;

use App\Models\Setting;
use App\Models\Servicio;

abstract class Controller
{
    protected Seo $seo;
    protected array $settings;

    public function __construct()
    {
        $this->settings = Setting::allKeyValue();
        $this->seo      = new Seo();
    }

    protected function render(string $view, array $data = [], ?string $layout = 'main'): void
    {
        $sharedData = [
            'seo'            => $this->seo,
            'settings'       => $this->settings,
            'navServiciosC1' => Servicio::byLayer(1),
            'navServiciosC2' => Servicio::byLayer(2),
            'currentPath'    => Router::currentPath(),
        ];

        echo View::render($view, array_merge($sharedData, $data), $layout);
    }

    protected function json(array $payload, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=UTF-8');
        echo json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        exit;
    }

    protected function redirect(string $path, int $code = 302): void
    {
        $target = str_starts_with($path, 'http') ? $path : View::url($path);
        header('Location: ' . $target, true, $code);
        exit;
    }

    protected function requireCsrf(): void
    {
        $token = $_POST['_csrf'] ?? ($_SERVER['HTTP_X_CSRF_TOKEN'] ?? null);
        if (!Csrf::verify(is_string($token) ? $token : null)) {
            if ($this->isAjax()) {
                $this->json(['ok' => false, 'error' => 'La sesión expiró. Recargá la página e intentá de nuevo.'], 419);
            }
            Session::flash('error', 'La sesión expiró. Por favor intentá nuevamente.');
            $this->redirect($_SERVER['HTTP_REFERER'] ?? '/');
        }
    }

    protected function requireAdmin(): void
    {
        Session::start();
        if (!Session::get('admin_logged_in')) {
            $this->redirect('admin/login');
        }
    }

    protected function isAjax(): bool
    {
        return strtolower($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '') === 'xmlhttprequest'
            || str_contains(strtolower($_SERVER['HTTP_ACCEPT'] ?? ''), 'application/json');
    }
}

