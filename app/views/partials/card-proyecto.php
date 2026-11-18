<?php
/**
 * Partial: Tarjeta de proyecto reutilizable para Home, Portfolio y Servicios.
 * @var array $proyecto
 */

use App\Core\View;
use App\Core\Icon;

$slug    = $proyecto['slug'] ?? '';
$titulo  = $proyecto['titulo'] ?? '';
$cliente = $proyecto['cliente_nombre'] ?? $titulo;
$rubro   = $proyecto['rubro'] ?? '';
$resumen = $proyecto['resumen_corto'] ?? '';
$portada = $proyecto['imagen_portada'] ?? 'assets/img/placeholders/impresos-carnelli-cover.webp';
$alt     = $proyecto['imagen_portada_alt'] ?? ('Caso de estudio ' . $cliente);
$servs   = $proyecto['servicios'] ?? [];
?>
<article class="project-card reveal-on-scroll" data-services="<?= View::e(implode(',', array_column($servs, 'slug'))) ?>">
    <a href="<?= View::url('proyectos/' . $slug) ?>" class="project-card__media" aria-label="Ver caso de estudio de <?= View::e($cliente) ?>">
        <img
            src="<?= View::asset($portada) ?>"
            alt="<?= View::e($alt) ?>"
            width="600"
            height="400"
            loading="lazy"
            class="project-card__img"
        >
        <span class="project-card__overlay">
            <span class="project-card__view-btn">
                Ver caso de estudio <?= Icon::get('arrow-up-right', 18) ?>
            </span>
        </span>
    </a>
    <div class="project-card__body">
        <div class="project-card__meta">
            <span class="project-card__client"><?= View::e($cliente) ?></span>
            <?php if ($rubro !== ''): ?>
                <span class="project-card__dot">·</span>
                <span class="project-card__industry"><?= View::e($rubro) ?></span>
            <?php endif; ?>
        </div>
        <h3 class="project-card__title">
            <a href="<?= View::url('proyectos/' . $slug) ?>"><?= View::e($titulo) ?></a>
        </h3>
        <p class="project-card__excerpt"><?= View::e($resumen) ?></p>
        <?php if (!empty($servs)): ?>
            <div class="project-card__chips" aria-label="Servicios aplicados">
                <?php foreach ($servs as $s): ?>
                    <span class="chip <?= ((int)($s['capa'] ?? 1) === 2) ? 'chip--layer2' : 'chip--layer1' ?>">
                        <?= View::e($s['nombre']) ?>
                    </span>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</article>

