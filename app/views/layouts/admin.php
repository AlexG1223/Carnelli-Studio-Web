<?php
/**
 * Layout del Panel de Administración (/admin) de Carnelli Studio.
 * Estricto noindex, diseño limpio coherente con la marca y navegación lateral.
 */

use App\Core\View;
use App\Core\Session;
use App\Models\Lead;

$cp = $currentPath ?? '/admin';
$flashErr = Session::flash('error');
$flashOk  = Session::flash('success');
$newLeadsCount = count(Lead::newestFirst('nuevo'));
?>
<!DOCTYPE html>
<html lang="es-UY">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title><?= View::e($adminTitle ?? 'Panel de Administración') ?> · Carnelli Studio</title>
    <link rel="icon" type="image/svg+xml" href="<?= View::asset('assets/brand/carnelli-favicon.svg', false) ?>">
    <link rel="stylesheet" href="<?= View::asset('assets/css/base.css') ?>">
    <link rel="stylesheet" href="<?= View::asset('assets/css/components.css') ?>">
    <link rel="stylesheet" href="<?= View::asset('assets/css/admin.css') ?>">
    <?php if (!empty($useChartJs)): ?>
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <?php endif; ?>
</head>
<body class="admin-body">
    <div class="admin-shell">
        <aside class="admin-sidebar">
            <div class="admin-sidebar__brand">
                <a href="<?= View::url('admin') ?>">
                    <img src="<?= View::asset('assets/brand/carnelli-imagotipo-blanco-transparente.svg') ?>" alt="Carnelli Studio Admin" width="185" height="48">
                </a>
                <span class="admin-badge">Panel Admin</span>
            </div>

            <nav class="admin-nav" aria-label="Menú de administración">
                <a href="<?= View::url('admin') ?>" class="admin-nav__link <?= $cp === '/admin' ? 'is-active' : '' ?>">
                    <span>📊 Dashboard</span>
                </a>
                <a href="<?= View::url('admin/leads') ?>" class="admin-nav__link <?= str_starts_with($cp, '/admin/leads') ? 'is-active' : '' ?>">
                    <span>📬 Leads (CRM)</span>
                    <?php if ($newLeadsCount > 0): ?>
                        <span class="admin-nav__counter"><?= $newLeadsCount ?></span>
                    <?php endif; ?>
                </a>
                <a href="<?= View::url('admin/proyectos') ?>" class="admin-nav__link <?= str_starts_with($cp, '/admin/proyectos') ? 'is-active' : '' ?>">
                    <span>💼 Proyectos</span>
                </a>
                <a href="<?= View::url('admin/servicios') ?>" class="admin-nav__link <?= str_starts_with($cp, '/admin/servicios') ? 'is-active' : '' ?>">
                    <span>⚡ Servicios (2 Capas)</span>
                </a>
                <a href="<?= View::url('admin/planes') ?>" class="admin-nav__link <?= str_starts_with($cp, '/admin/planes') ? 'is-active' : '' ?>">
                    <span>📦 Planes</span>
                </a>
                <a href="<?= View::url('admin/blog') ?>" class="admin-nav__link <?= str_starts_with($cp, '/admin/blog') ? 'is-active' : '' ?>">
                    <span>✍️ Blog (SEO/GEO)</span>
                </a>
                <a href="<?= View::url('admin/faqs') ?>" class="admin-nav__link <?= str_starts_with($cp, '/admin/faqs') ? 'is-active' : '' ?>">
                    <span>❓ Preguntas (FAQs)</span>
                </a>
                <a href="<?= View::url('admin/testimonios') ?>" class="admin-nav__link <?= str_starts_with($cp, '/admin/testimonios') ? 'is-active' : '' ?>">
                    <span>💬 Testimonios</span>
                </a>
                <a href="<?= View::url('admin/clientes') ?>" class="admin-nav__link <?= str_starts_with($cp, '/admin/clientes') ? 'is-active' : '' ?>">
                    <span>🤝 Clientes</span>
                </a>
                <a href="<?= View::url('admin/ajustes') ?>" class="admin-nav__link <?= str_starts_with($cp, '/admin/ajustes') ? 'is-active' : '' ?>">
                    <span>⚙️ Ajustes y [COMPLETAR]</span>
                </a>
            </nav>

            <div class="admin-sidebar__footer">
                <a href="<?= View::url('') ?>" target="_blank" class="admin-sidebar__ext">↗ Ver sitio público</a>
                <a href="<?= View::url('admin/logout') ?>" class="admin-sidebar__logout">Cerrar sesión</a>
            </div>
        </aside>

        <div class="admin-main">
            <header class="admin-topbar">
                <div>
                    <h1 class="admin-topbar__title"><?= View::e($adminTitle ?? 'Dashboard') ?></h1>
                    <?php if (!empty($adminSubtitle)): ?>
                        <p class="admin-topbar__sub"><?= View::e($adminSubtitle) ?></p>
                    <?php endif; ?>
                </div>
                <div class="admin-topbar__right">
                    <span class="admin-user-pill">👤 Alex Carnelli</span>
                </div>
            </header>

            <div class="admin-content">
                <?php if ($flashErr): ?>
                    <div class="alert alert--error"><?= View::e($flashErr) ?></div>
                <?php endif; ?>
                <?php if ($flashOk): ?>
                    <div class="alert alert--success"><?= View::e($flashOk) ?></div>
                <?php endif; ?>

                <?= $content ?>
            </div>
        </div>
    </div>
    <script src="<?= View::asset('assets/js/admin.js') ?>"></script>
</body>
</html>

