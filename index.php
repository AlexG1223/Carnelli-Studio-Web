<?php
/**
 * Front Controller principal de Carnelli Studio.
 * Optimizado para autodeploy de Git en Hostinger (dentro de public_html) y XAMPP local.
 */

declare(strict_types=1);

$insideApp = __DIR__ . '/app';
$parentApp = dirname(__DIR__) . '/app';

define('APP_PATH', is_dir($insideApp) ? $insideApp : $parentApp);
define('PUBLIC_PATH', __DIR__);

require_once APP_PATH . '/config/config.php';
require_once APP_PATH . '/core/App.php';

\App\Core\App::run();
