<?php
/**
 * Vista: Portfolio filtrable de Proyectos (/proyectos) con filtro dinámico JS + fallback SEO con ?servicio=slug.
 */

use App\Core\View;
use App\Core\Icon;
?>
<?= View::partial('breadcrumbs', ['seo' => $seo]) ?>

<section class="page-hero">
    <div class="container">
        <span class="eyebrow">PORTFOLIO Y CASOS DE ESTUDIO</span>
        <h1 class="page-hero__title">Proyectos reales para pymes uruguayas</h1>
        <p class="page-hero__subtitle">
            Desde landing pages y tiendas online con pasarela de pago hasta sistemas internos de gestión de pedidos de punta a punta y manejo de redes sociales.
        </p>
    </div>
</section>

<section class="section">
    <div class="container">
        <!-- Filtros animados por servicio (accesibles con teclado y fallback URL para SEO) -->
        <div class="portfolio-filters" role="region" aria-label="Filtrar proyectos por servicio">
            <a
                href="<?= View::url('proyectos') ?>"
                class="filter-pill <?= empty($filtroActual) ? 'is-active' : '' ?>"
                data-filter-slug="todos"
            >
                Todos los proyectos
            </a>
            <?php foreach (($serviciosFiltro ?? []) as $srv): ?>
                <a
                    href="<?= View::url('proyectos?servicio=' . urlencode($srv['slug'])) ?>"
                    class="filter-pill <?= ($filtroActual ?? '') === $srv['slug'] ? 'is-active' : '' ?>"
                    data-filter-slug="<?= View::e($srv['slug']) ?>"
                >
                    <?= View::e($srv['nombre']) ?>
                </a>
            <?php endforeach; ?>
        </div>

        <div class="projects-grid" id="portfolio-grid" aria-live="polite">
            <?php if (empty($proyectos)): ?>
                <div class="empty-state">
                    <h3>Todavía no cargamos casos públicos con ese filtro puntual</h3>
                    <p>Podés ver todos nuestros casos de estudio o consultarnos directamente por tu proyecto.</p>
                    <a href="<?= View::url('proyectos') ?>" class="btn btn--outline">Ver todos los proyectos</a>
                </div>
            <?php else: ?>
                <?php foreach ($proyectos as $proyecto): ?>
                    <?= View::partial('card-proyecto', ['proyecto' => $proyecto]) ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</section>

