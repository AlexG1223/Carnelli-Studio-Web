<?php
/**
 * Vista: Planes (/planes) — Tres niveles: Arranque, Crecimiento y Transformación (sin precios fijos publicados).
 */

use App\Core\View;
use App\Core\Icon;
?>
<?= View::partial('breadcrumbs', ['seo' => $seo]) ?>

<section class="page-hero">
    <div class="container">
        <span class="eyebrow">PROPUESTAS A MEDIDA</span>
        <h1 class="page-hero__title">Tres niveles pensados para la etapa de tu pyme</h1>
        <p class="page-hero__subtitle">
            Cada negocio es distinto: no te atamos a paquetes cerrados. Elegí el nivel que mejor represente lo que buscás hoy y armamos tu presupuesto a medida tras una llamada inicial sin costo.
        </p>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="plans-grid">
            <?php foreach (($planes ?? []) as $plan): ?>
                <?php $isFeatured = !empty($plan['destacado']); ?>
                <article class="plan-card <?= $isFeatured ? 'plan-card--featured' : '' ?> reveal-on-scroll">
                    <?php if ($isFeatured): ?>
                        <div class="plan-card__ribbon">MÁS ELEGIDO POR PYMES</div>
                    <?php endif; ?>
                    <div class="plan-card__header">
                        <span class="plan-card__layers"><?= View::e($plan['capas_incluidas']) ?></span>
                        <h2 class="plan-card__name">Plan <?= View::e($plan['nombre']) ?></h2>
                        <p class="plan-card__subtitle"><?= View::e($plan['subtitulo']) ?></p>
                    </div>

                    <p class="plan-card__ideal"><?= View::e($plan['ideal_para']) ?></p>

                    <div class="plan-card__body">
                        <span class="plan-card__includes-label">¿Qué incluye como base?</span>
                        <ul class="plan-card__list">
                            <?php foreach (($plan['items_incluidos'] ?? []) as $item): ?>
                                <li>
                                    <?= Icon::get('check', 18) ?>
                                    <span><?= View::e($item) ?></span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>

                    <div class="plan-card__footer">
                        <span class="plan-card__price-note">Presupuesto 100% adaptado a tu alcance</span>
                        <a
                            href="<?= View::url('contacto?plan=' . urlencode('Plan ' . $plan['nombre'])) ?>"
                            class="btn <?= $isFeatured ? 'btn--primary' : 'btn--outline' ?> btn--full btn--lg"
                        >
                            <span><?= View::e($plan['texto_cta'] ?? 'Pedí tu presupuesto') ?></span>
                            <?= Icon::get('arrow-right', 16) ?>
                        </a>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>

