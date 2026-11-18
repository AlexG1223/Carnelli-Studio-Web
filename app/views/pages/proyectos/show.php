<?php
/**
 * Vista: Caso de Estudio individual (/proyectos/{slug}) con ficha técnica,
 * narrativa Desafío / Solución / Resultado, galería con Lightbox accesible y navegación entre proyectos.
 */

use App\Core\View;
use App\Core\Icon;

$galeria = $proyecto['galeria'] ?? [];
$metricas = $proyecto['metricas'] ?? [];
?>
<?= View::partial('breadcrumbs', ['seo' => $seo]) ?>

<article class="case-study">
    <header class="case-hero">
        <div class="container case-hero__grid">
            <div class="case-hero__info">
                <span class="eyebrow">CASO DE ESTUDIO · <?= View::e(mb_strtoupper($proyecto['rubro'] ?? 'PYME URUGUAY')) ?></span>
                <h1 class="case-hero__title"><?= View::e($proyecto['titulo']) ?></h1>
                <p class="case-hero__lead"><?= View::e($proyecto['resumen_corto']) ?></p>

                <dl class="case-specs">
                    <div class="case-specs__item">
                        <dt>Cliente</dt>
                        <dd><?= View::e($proyecto['cliente_nombre']) ?></dd>
                    </div>
                    <div class="case-specs__item">
                        <dt>Rubro</dt>
                        <dd><?= View::e($proyecto['rubro']) ?></dd>
                    </div>
                    <div class="case-specs__item">
                        <dt>Año</dt>
                        <dd><?= View::e($proyecto['anio'] ?? '2026') ?></dd>
                    </div>
                    <div class="case-specs__item case-specs__item--full">
                        <dt>Servicios implementados</dt>
                        <dd class="case-specs__chips">
                            <?php foreach (($proyecto['servicios'] ?? []) as $s): ?>
                                <a href="<?= View::url('servicios/' . $s['slug']) ?>" class="chip <?= ((int)($s['capa'] ?? 1) === 2) ? 'chip--layer2' : 'chip--layer1' ?>">
                                    <?= View::e($s['nombre']) ?>
                                </a>
                            <?php endforeach; ?>
                        </dd>
                    </div>
                </dl>

                <div class="case-hero__actions">
                    <a href="<?= View::url('contacto?servicio=' . urlencode($preselectedService ?? '')) ?>" class="btn btn--primary">
                        <span>Quiero algo así para mi negocio</span>
                        <?= Icon::get('arrow-right', 16) ?>
                    </a>
                    <?php if (!empty($proyecto['url_en_vivo'])): ?>
                        <a href="<?= View::e($proyecto['url_en_vivo']) ?>" target="_blank" rel="noopener" class="btn btn--outline">
                            <span>Visitar sitio en vivo</span>
                            <?= Icon::get('arrow-up-right', 16) ?>
                        </a>
                    <?php endif; ?>
                </div>
            </div>

            <div class="case-hero__mockup">
                <figure class="device-frame">
                    <img
                        src="<?= View::asset($proyecto['imagen_portada']) ?>"
                        alt="<?= View::e($proyecto['imagen_portada_alt'] ?: $proyecto['titulo']) ?>"
                        width="800"
                        height="533"
                        fetchpriority="high"
                    >
                </figure>
            </div>
        </div>
    </header>

    <!-- Narrativa Desafío -> Solución -> Resultado -->
    <section class="section">
        <div class="container">
            <div class="case-narrative-grid">
                <div class="narrative-card reveal-on-scroll">
                    <span class="narrative-card__step">01 · EL DESAFÍO</span>
                    <h2>Qué necesitaba resolver el negocio</h2>
                    <p><?= nl2br(View::e($proyecto['desafio'])) ?></p>
                </div>

                <div class="narrative-card narrative-card--highlight reveal-on-scroll">
                    <span class="narrative-card__step">02 · LA SOLUCIÓN IMPLEMENTADA</span>
                    <h2>Cómo lo diseñamos y desarrollamos</h2>
                    <p><?= nl2br(View::e($proyecto['solucion'])) ?></p>
                </div>

                <div class="narrative-card reveal-on-scroll">
                    <span class="narrative-card__step">03 · EL IMPACTO Y RESULTADO</span>
                    <h2>Qué cambió en la operatoria diaria</h2>
                    <p><?= nl2br(View::e($proyecto['resultado'])) ?></p>
                </div>
            </div>

            <!-- Métricas reales (solo se renderiza si se cargaron métricas en el admin) -->
            <?php if (!empty($metricas)): ?>
            <div class="case-metrics">
                <?php foreach ($metricas as $m): ?>
                    <div class="metric-box">
                        <strong class="metric-box__val"><?= View::e($m['valor'] ?? '') ?></strong>
                        <span class="metric-box__label"><?= View::e($m['etiqueta'] ?? '') ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Testimonio vinculado al proyecto (solo si existe y está publicado) -->
    <?php if (!empty($testimonio)): ?>
    <section class="section">
        <div class="container container--narrow">
            <blockquote class="testimonial-card testimonial-card--large">
                <p class="testimonial-card__text">«<?= View::e($testimonio['texto']) ?>»</p>
                <footer class="testimonial-card__author">
                    <strong><?= View::e($testimonio['nombre']) ?></strong>
                    <span><?= View::e($testimonio['cargo_negocio'] ?? '') ?></span>
                </footer>
            </blockquote>
        </div>
    </section>
    <?php endif; ?>

    <!-- Navegación proyecto anterior / siguiente -->
    <nav class="case-pagination" aria-label="Navegación entre proyectos">
        <div class="container case-pagination__grid">
            <?php if (!empty($prevProyecto)): ?>
                <a href="<?= View::url('proyectos/' . $prevProyecto['slug']) ?>" class="case-pagination__link">
                    <span class="case-pagination__dir">← Proyecto anterior</span>
                    <strong><?= View::e($prevProyecto['cliente_nombre']) ?></strong>
                </a>
            <?php endif; ?>
            <?php if (!empty($nextProyecto)): ?>
                <a href="<?= View::url('proyectos/' . $nextProyecto['slug']) ?>" class="case-pagination__link case-pagination__link--next">
                    <span class="case-pagination__dir">Siguiente proyecto →</span>
                    <strong><?= View::e($nextProyecto['cliente_nombre']) ?></strong>
                </a>
            <?php endif; ?>
        </div>
    </nav>
</article>

