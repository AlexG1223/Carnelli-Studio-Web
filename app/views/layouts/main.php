<?php
/**
 * Layout público principal de Carnelli Studio.
 * Optimizado para Core Web Vitals (LCP < 2.0s, CLS < 0.05), fuentes WOFF2 self-hosted con preload,
 * View Transitions API y datos estructurados JSON-LD.
 */

use App\Core\View;
use App\Core\Session;

$ga4Id     = trim((string)($settings['ga4_id'] ?? ''));
$pixelId   = trim((string)($settings['meta_pixel_id'] ?? ''));
$gscMeta   = trim((string)($settings['search_console_meta'] ?? ''));
$flashErr  = Session::flash('error');
$flashOk   = Session::flash('success');
?>
<!DOCTYPE html>
<html lang="es-UY">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#0D3B8C">
    <meta name="view-transition" content="same-origin">

    <?= $seo->renderMetaTags() ?>

    <?php if ($gscMeta !== '' && !str_contains($gscMeta, '[COMPLETAR')): ?>
        <meta name="google-site-verification" content="<?= View::e($gscMeta) ?>">
    <?php endif; ?>

    <!-- Favicons & Manifest -->
    <link rel="icon" type="image/svg+xml" href="<?= View::asset('assets/brand/carnelli-favicon.svg', false) ?>">
    <link rel="icon" type="image/png" sizes="32x32" href="<?= View::asset('assets/brand/favicon-32x32.png', false) ?>">
    <link rel="icon" type="image/png" sizes="16x16" href="<?= View::asset('assets/brand/favicon-16x16.png', false) ?>">
    <link rel="apple-touch-icon" sizes="180x180" href="<?= View::asset('assets/brand/apple-touch-icon.png', false) ?>">
    <link rel="manifest" href="<?= View::url('manifest.webmanifest') ?>">

    <!-- Preload de fuentes críticas WOFF2 self-hosted (Quicksand Bold y Open Sans Regular/SemiBold) -->
    <link rel="preload" href="<?= View::asset('assets/fonts/Quicksand-Bold.woff2', false) ?>" as="font" type="font/woff2" crossorigin>
    <link rel="preload" href="<?= View::asset('assets/fonts/OpenSans-Regular.woff2', false) ?>" as="font" type="font/woff2" crossorigin>
    <link rel="preload" href="<?= View::asset('assets/fonts/OpenSans-SemiBold.woff2', false) ?>" as="font" type="font/woff2" crossorigin>

    <!-- Hojas de estilo -->
    <link rel="stylesheet" href="<?= View::asset('assets/css/base.css') ?>">
    <link rel="stylesheet" href="<?= View::asset('assets/css/components.css') ?>">
    <link rel="stylesheet" href="<?= View::asset('assets/css/pages.css') ?>">

    <!-- Datos estructurados JSON-LD (SEO & GEO) -->
    <?= View::partial('schema-jsonld', ['seo' => $seo, 'settings' => $settings]) ?>

    <?php if ($ga4Id !== '' && !str_contains($ga4Id, '[COMPLETAR')): ?>
        <script async src="https://www.googletagmanager.com/gtag/js?id=<?= View::e($ga4Id) ?>"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());
            gtag('config', '<?= View::e($ga4Id) ?>');
        </script>
    <?php endif; ?>

    <?php if ($pixelId !== '' && !str_contains($pixelId, '[COMPLETAR')): ?>
        <script>
            !function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?
            n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;
            n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;
            t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,
            document,'script','https://connect.facebook.net/en_US/fbevents.js');
            fbq('init', '<?= View::e($pixelId) ?>');
            fbq('track', 'PageView');
        </script>
    <?php endif; ?>
</head>
<body>
    <?= View::partial('header', [
        'currentPath'     => $currentPath ?? '/',
        'navServiciosC1'  => $navServiciosC1 ?? [],
        'navServiciosC2'  => $navServiciosC2 ?? [],
        'settings'        => $settings,
        'whatsappMessage' => $whatsappMessage ?? null,
    ]) ?>

    <?php if ($flashErr || $flashOk): ?>
        <div class="container flash-container" role="alert">
            <?php if ($flashErr): ?>
                <div class="alert alert--error"><?= View::e($flashErr) ?></div>
            <?php endif; ?>
            <?php if ($flashOk): ?>
                <div class="alert alert--success"><?= View::e($flashOk) ?></div>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <main id="main-content" class="site-main">
        <?= $content ?>
    </main>

    <?php if (empty($hideFinalCta)): ?>
        <?= View::partial('cta-final', [
            'settings'           => $settings,
            'currentPath'        => $currentPath ?? '/',
            'preselectedService' => $preselectedService ?? '',
            'whatsappMessage'    => $whatsappMessage ?? null,
        ]) ?>
    <?php endif; ?>

    <?= View::partial('footer', [
        'settings'       => $settings,
        'navServiciosC1' => $navServiciosC1 ?? [],
        'navServiciosC2' => $navServiciosC2 ?? [],
    ]) ?>

    <?= View::partial('whatsapp-flotante', [
        'settings'           => $settings,
        'whatsappMessage'    => $whatsappMessage ?? null,
        'preselectedService' => $preselectedService ?? '',
    ]) ?>

    <script type="module" src="<?= View::asset('assets/js/main.js') ?>"></script>
</body>
</html>

