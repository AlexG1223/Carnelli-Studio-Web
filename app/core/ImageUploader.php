<?php
/**
 * ImageUploader: Validación de MIME real, conversión a WebP con GD y generación de tamaños responsivos.
 */

declare(strict_types=1);

namespace App\Core;

class ImageUploader
{
    private static array $allowedMimes = [
        'image/jpeg',
        'image/png',
        'image/webp',
        'image/gif',
        'image/svg+xml'
    ];

    private static int $maxSizeBytes = 8 * 1024 * 1024; // 8 MB

    /**
     * Procesa un archivo subido ($_FILES['campo']), lo convierte a WebP y devuelve la ruta relativa en public_html.
     */
    public static function upload(array $file, string $folder = 'proyectos', string $prefix = 'img'): ?string
    {
        if (($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
            return null;
        }

        if (($file['size'] ?? 0) > self::$maxSizeBytes || ($file['size'] ?? 0) <= 0) {
            return null;
        }

        $tmpName = $file['tmp_name'] ?? '';
        if (!is_uploaded_file($tmpName)) {
            return null;
        }

        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mime  = $finfo->file($tmpName);
        if (!in_array($mime, self::$allowedMimes, true)) {
            return null;
        }

        $targetDir = PUBLIC_PATH . '/uploads/' . trim($folder, '/');
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0755, true);
        }

        $safePrefix = Validator::cleanSlug($prefix) ?: 'imagen';
        $baseName   = $safePrefix . '-' . substr(bin2hex(random_bytes(4)), 0, 8);

        if ($mime === 'image/svg+xml') {
            $svgContent = file_get_contents($tmpName);
            if ($svgContent === false || stripos($svgContent, '<script') !== false) {
                return null;
            }
            $fileName = $baseName . '.svg';
            file_put_contents($targetDir . '/' . $fileName, $svgContent);
            return 'uploads/' . trim($folder, '/') . '/' . $fileName;
        }

        $srcImg = match ($mime) {
            'image/jpeg' => @imagecreatefromjpeg($tmpName),
            'image/png'  => @imagecreatefrompng($tmpName),
            'image/webp' => @imagecreatefromwebp($tmpName),
            'image/gif'  => @imagecreatefromgif($tmpName),
            default      => false,
        };

        if (!$srcImg) {
            return null;
        }

        $origW = imagesx($srcImg);
        $origH = imagesy($srcImg);

        // Guardar tamaño principal (máximo 1600px de ancho)
        $mainFile = $baseName . '.webp';
        self::saveResizedWebp($srcImg, $origW, $origH, min($origW, 1600), $targetDir . '/' . $mainFile);

        // Generar variantes responsivas si la imagen original es más grande
        foreach ([400, 800, 1200] as $w) {
            if ($origW > $w) {
                self::saveResizedWebp($srcImg, $origW, $origH, $w, $targetDir . '/' . $baseName . '-' . $w . 'w.webp');
            }
        }

        imagedestroy($srcImg);

        return 'uploads/' . trim($folder, '/') . '/' . $mainFile;
    }

    private static function saveResizedWebp(\GdImage $src, int $origW, int $origH, int $targetW, string $destPath): void
    {
        $targetH = (int)round($origH * ($targetW / $origW));
        $dst = imagecreatetruecolor($targetW, $targetH);
        imagealphablending($dst, false);
        imagesavealpha($dst, true);
        $transparent = imagecolorallocatealpha($dst, 0, 0, 0, 127);
        imagefill($dst, 0, 0, $transparent);
        imagecopyresampled($dst, $src, 0, 0, 0, 0, $targetW, $targetH, $origW, $origH);
        imagewebp($dst, $destPath, 86);
        imagedestroy($dst);
    }
}

