<?php
/**
 * Vista: Página individual de Servicio (/servicios/{slug}).
 * Estructura fija optimizada para SEO y GEO:
 * H1 con keyword + Respuesta directa Answer-First (40-60 palabras) -> Beneficios -> Qué incluye -> Proceso -> Proyectos relacionados -> FAQs -> CTA preseleccionado.
 */

use App\Core\View;
use App\Core\Icon;

$capa = (int)($servicio['capa'] ?? 1);
?>
<?= View::partial('breadcrumbs', ['seo' => $seo]) ?>

<article class="service-detail">
    <header class="page-hero page-hero--service">
        <div class="container">
            <span class="chip <?= $capa === 2 ? 'chip--layer2' : 'chip--layer1' ?>">
                <?= $capa === 2 ? 'Capa 2 · Sistemas, Webs, Automatización e IA' : 'Capa 1 · Marketing y Presencia Digital' ?>
            </span>
            <h1 class="page-hero__title"><?= View::e($servicio['nombre']) ?></h1>
            <p class="page-hero__subtitle"><?= View::e($servicio['subtitulo_corto'] ?? $servicio['resumen']) ?></p>

            <!-- Bloque Answer-First (40-60 palabras citables para GEO: ChatGPT, Gemini, Perplexity) -->
            <div class="answer-first-box" role="region" aria-label="Resumen directo del servicio">
                <span class="answer-first-box__label">¿Qué es y para quién es?</span>
                <p><?= View::e($servicio['respuesta_directa']) ?></p>
            </div>

            <div class="page-hero__actions">
                <a href="<?= View::url('contacto?servicio=' . urlencode($servicio['nombre'])) ?>" class="btn btn--primary btn--lg">
                    <span>Pedí tu presupuesto para <?= View::e($servicio['nombre']) ?></span>
                    <?= Icon::get('arrow-right', 18) ?>
                </a>
                <a href="<?= View::e(View::whatsappUrl($whatsappMessage ?? '')) ?>" target="_blank" rel="noopener" class="btn btn--secondary btn--lg" data-track="whatsapp">
                    <?= Icon::get('whatsapp', 20) ?>
                    <span>Consultar por WhatsApp</span>
                </a>
            </div>
        </div>
    </header>

    <section class="section">
        <div class="container service-detail__grid">
            <div class="service-detail__main">
                <h2>Por qué trabajar este servicio en tu pyme</h2>
                <div class="rich-text">
                    <?= $servicio['contenido'] ?>
                </div>

                <h2 class="mt-lg">Beneficios concretos para tu negocio</h2>
                <ul class="check-grid">
                    <?php foreach (($servicio['beneficios'] ?? []) as $ben): ?>
                        <li class="check-grid__item">
                            <span class="check-grid__icon"><?= Icon::get('check', 18) ?></span>
                            <span><?= View::e($ben) ?></span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <aside class="service-detail__sidebar">
                <div class="includes-box">
                    <h3>¿Qué incluye este servicio?</h3>
                    <ul class="includes-list">
                        <?php foreach (($servicio['que_incluye'] ?? []) as $inc): ?>
                            <li>
                                <?= Icon::get('check', 16) ?>
                                <span><?= View::e($inc) ?></span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                    <div class="includes-box__cta">
                        <p>Armamos el presupuesto a medida según el tamaño y etapa de tu negocio.</p>
                        <a href="<?= View::url('contacto?servicio=' . urlencode($servicio['nombre'])) ?>" class="btn btn--primary btn--full">
                            Pedir presupuesto a medida
                        </a>
                    </div>
                </div>
            </aside>
        </div>
    </section>

    <!-- Proceso del servicio -->
    <?php if (!empty($servicio['proceso'])): ?>
    <section class="section section--alt">
        <div class="container">
            <div class="section-header">
                <span class="eyebrow">PASO A PASO</span>
                <h2 class="section-title">Cómo implementamos <?= View::e($servicio['nombre']) ?></h2>
            </div>
            <ol class="timeline-steps">
                <?php foreach ($servicio['proceso'] as $idx => $paso): ?>
                    <li class="timeline-step">
                        <span class="timeline-step__num">0<?= $idx + 1 ?></span>
                        <div class="timeline-step__body">
                            <p><?= View::e($paso) ?></p>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ol>
        </div>
    </section>
    <?php endif; ?>

    <!-- Proyectos relacionados automáticamente -->
    <?php if (!empty($proyectosRelacionados)): ?>
    <section class="section">
        <div class="container">
            <div class="section-header section-header--split">
                <div>
                    <span class="eyebrow">CASOS DE ESTUDIO VINCULADOS</span>
                    <h2 class="section-title">Proyectos reales donde aplicamos este servicio</h2>
                </div>
                <a href="<?= View::url('proyectos') ?>" class="btn btn--outline">Ver portfolio completo →</a>
            </div>
            <div class="projects-grid">
                <?php foreach ($proyectosRelacionados as $p): ?>
                    <?= View::partial('card-proyecto', ['proyecto' => $p]) ?>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- FAQs del servicio -->
    <?php if (!empty($faqsServicio)): ?>
    <section class="section section--alt">
        <div class="container container--narrow">
            <div class="section-header">
                <span class="eyebrow">PREGUNTAS FRECUENTES</span>
                <h2 class="section-title">Dudas comunes sobre <?= View::e($servicio['nombre']) ?></h2>
            </div>
            <div class="faq-accordion">
                <?php foreach ($faqsServicio as $i => $f): ?>
                    <details class="faq-item" <?= $i === 0 ? 'open' : '' ?>>
                        <summary class="faq-item__question">
                            <span><?= View::e($f['pregunta']) ?></span>
                            <span class="faq-item__icon" aria-hidden="true">+</span>
                        </summary>
                        <div class="faq-item__answer">
                            <p><?= View::e($f['respuesta']) ?></p>
                        </div>
                    </details>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- Otros servicios de la misma capa (enlazado interno SEO) -->
    <?php if (!empty($otrosServicios)): ?>
    <section class="section">
        <div class="container">
            <h2 class="section-title">Servicios complementarios</h2>
            <div class="services-cards-grid mt-md">
                <?php foreach ($otrosServicios as $os): ?>
                    <article class="service-card">
                        <h3 class="service-card__title">
                            <a href="<?= View::url('servicios/' . $os['slug']) ?>"><?= View::e($os['nombre']) ?></a>
                        </h3>
                        <p class="service-card__desc"><?= View::e($os['resumen']) ?></p>
                        <a href="<?= View::url('servicios/' . $os['slug']) ?>" class="link-arrow">Conocer servicio →</a>
                    </article>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>
</article>

