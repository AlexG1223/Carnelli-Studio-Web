<?php
/**
 * Configuración global y autodetección de entorno (Producción en Hostinger / Staging / Local XAMPP).
 */

declare(strict_types=1);

if (!defined('APP_PATH')) {
    define('APP_PATH', dirname(__DIR__));
}

if (!defined('PUBLIC_PATH')) {
    define('PUBLIC_PATH', dirname(APP_PATH));
}

// Cargador liviano de archivo .env (opcional; si no existe en autodeploy, usa autodetección inteligente)
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

$host      = $_SERVER['HTTP_HOST'] ?? 'localhost';
$isLocal   = in_array($host, ['localhost', '127.0.0.1'], true) || str_starts_with($host, 'localhost:');
$defaultEnv= $isLocal ? 'development' : 'production';
$appEnv    = (string)env('APP_ENV', $defaultEnv);
$isDebug   = (bool)env('APP_DEBUG', $isLocal);

if ($isDebug) {
    ini_set('display_errors', '1');
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', '0');
    error_reporting(E_ALL);
    ini_set('log_errors', '1');
    ini_set('error_log', APP_PATH . '/storage/logs/php-errors.log');
}

// Detectar esquema HTTPS detrás de proxy / Cloudflare / LiteSpeed en Hostinger
$isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
    || (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https')
    || (($_SERVER['SERVER_PORT'] ?? '') == 443);
$scheme = $isHttps ? 'https' : 'http';

$scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? ''));
$scriptDir = ($scriptDir === '/' || $scriptDir === '\\' || $scriptDir === '.') ? '' : rtrim($scriptDir, '/');
$detectedBaseUrl = $scheme . '://' . $host . $scriptDir;

// Si APP_URL en .env apunta a localhost pero estamos en dominio real de Hostinger, priorizar el dominio real
$configuredUrl = (string)env('APP_URL', '');
if ($configuredUrl === '' || (!$isLocal && str_contains($configuredUrl, 'localhost'))) {
    $configuredUrl = $detectedBaseUrl;
}

define('APP_ENV', $appEnv);
define('BASE_URL', rtrim($configuredUrl, '/'));
define('BASE_PATH', $scriptDir);
define('ASSETS_VERSION', '2.8.0');
define('DATA_PATH', APP_PATH . '/data');
define('STORAGE_PATH', APP_PATH . '/storage');
