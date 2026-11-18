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
        <div class="projects-grid" id="portfolio-grid">
            <?php if (empty($proyectos)): ?>
                <div class="empty-state">
                    <h3>Todavía no hay proyectos publicados</h3>
                    <p>Consultanos directamente para conocer más casos o pedir un presupuesto para tu negocio.</p>
                </div>
            <?php else: ?>
                <?php foreach ($proyectos as $proyecto): ?>
                    <?= View::partial('card-proyecto', ['proyecto' => $proyecto]) ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</section>

