<?php
/**
 * Configuración global y carga de variables de entorno (.env).
 */

declare(strict_types=1);

if (!defined('APP_PATH')) {
    define('APP_PATH', dirname(__DIR__));
}

if (!defined('PUBLIC_PATH')) {
    define('PUBLIC_PATH', dirname(APP_PATH) . '/public_html');
}

// Cargador liviano de archivo .env
$envFile = APP_PATH . '/.env';
if (is_file($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $line = trim($line);
        if ($line === '' || str_starts_with($line, '#') || !str_contains($line, '=')) {
            continue;
        }
        [$key, $val] = explode('=', $line, 2);
        $key = trim($key);
        $val = trim($val, " \t\n\r\0\x0B\"'");
        $_ENV[$key] = $val;
        putenv("$key=$val");
    }
}

function env(string $key, mixed $default = null): mixed
{
    $val = $_ENV[$key] ?? getenv($key);
    if ($val === false || $val === null) {
        return $default;
    }
    $lower = strtolower((string)$val);
    if ($lower === 'true') return true;
    if ($lower === 'false') return false;
    return $val;
}

date_default_timezone_set((string)env('APP_TIMEZONE', 'America/Montevideo'));

$isDebug = (bool)env('APP_DEBUG', false);
if ($isDebug) {
    ini_set('display_errors', '1');
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', '0');
    error_reporting(E_ALL);
    ini_set('log_errors', '1');
    ini_set('error_log', APP_PATH . '/storage/logs/php-errors.log');
}

// Detectar URL base automáticamente para funcionar tanto en XAMPP (subcarpeta) como en Hostinger (raíz)
$scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host   = $_SERVER['HTTP_HOST'] ?? 'localhost';
$scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? ''));
$scriptDir = preg_replace('#/public_html$#', '', rtrim($scriptDir, '/'));
$detectedBaseUrl = $scheme . '://' . $host . ($scriptDir !== '' && $scriptDir !== '/' ? $scriptDir : '');

define('BASE_URL', rtrim((string)env('APP_URL', $detectedBaseUrl), '/'));
define('BASE_PATH', $scriptDir !== '/' ? $scriptDir : '');
define('ASSETS_VERSION', '2.0.1');
define('DATA_PATH', APP_PATH . '/data');
define('STORAGE_PATH', APP_PATH . '/storage');

