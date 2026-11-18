<?php
/**
 * Front Controller principal de Carnelli Studio (ubicado dentro de public_html).
 * Soporta tanto la estructura estándar de Hostinger (app/ fuera de public_html)
 * como el modo alternativo (app/ dentro de public_html protegida con .htaccess).
 */

declare(strict_types=1);

$parentApp = dirname(__DIR__) . '/app';
$insideApp = __DIR__ . '/app';

define('APP_PATH', is_dir($parentApp) ? $parentApp : $insideApp);
define('PUBLIC_PATH', __DIR__);

require_once APP_PATH . '/config/config.php';
require_once APP_PATH . '/core/App.php';

\App\Core\App::run();

