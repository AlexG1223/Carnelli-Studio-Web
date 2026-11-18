<?php
/**
 * Vista: Hub de Servicios (/servicios) con buscador y desglose completo de Capa 1 y Capa 2.
 */

use App\Core\View;
use App\Core\Icon;
?>
<?= View::partial('breadcrumbs', ['seo' => $seo]) ?>

<section class="page-hero">
    <div class="container">
        <span class="eyebrow">MODELO EN DOS CAPAS</span>
        <h1 class="page-hero__title">Servicios de transformación digital para pymes en Uruguay</h1>
        <p class="page-hero__subtitle">
            Combinamos <strong>marketing y presencia digital</strong> para atraer consultas hoy con <strong>sitios web, tiendas online, sistemas a medida e IA</strong> para que tu negocio crezca ordenado.
        </p>

        <form action="<?= View::url('servicios') ?>" method="GET" class="service-search" role="search">
            <input
                type="search"
                name="q"
                value="<?= View::e($searchQuery ?? '') ?>"
                placeholder="Buscar servicio (ej: tienda online, redes, pedidos, Google Maps, IA)..."
                class="form-input service-search__input"
                aria-label="Buscar servicio"
            >
            <button type="submit" class="btn btn--primary">Buscar</button>
            <?php if (!empty($searchQuery)): ?>
                <a href="<?= View::url('servicios') ?>" class="btn btn--outline">Limpiar</a>
            <?php endif; ?>
        </form>
    </div>
</section>

<!-- CAPA 1 -->
<section class="section" aria-labelledby="capa1-title">
    <div class="container">
        <div class="layer-banner">
            <span class="chip chip--layer1">CAPA 1 · LA PUERTA DE ENTRADA</span>
            <h2 id="capa1-title" class="section-title">Marketing y presencia digital</h2>
            <p class="section-subtitle">
                Para pymes que necesitan ordenar su imagen, publicar contenido profesional y captar clientes activos en Google, Instagram y su zona local.
            </p>
        </div>

        <div class="services-cards-grid">
            <?php foreach (($serviciosC1 ?? []) as $srv): ?>
                <article class="service-card reveal-on-scroll">
                    <div class="service-card__icon"><?= Icon::get($srv['icono_svg'] ?? 'sparkles', 24) ?></div>
                    <h3 class="service-card__title">
                        <a href="<?= View::url('servicios/' . $srv['slug']) ?>"><?= View::e($srv['nombre']) ?></a>
                    </h3>
                    <p class="service-card__desc"><?= View::e($srv['resumen']) ?></p>
                    <div class="service-card__actions">
                        <a href="<?= View::url('servicios/' . $srv['slug']) ?>" class="link-arrow">Ver qué incluye →</a>
                        <a href="<?= View::url('contacto?servicio=' . urlencode($srv['nombre'])) ?>" class="service-card__quote">Pedir presupuesto</a>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- CAPA 2 -->
<section class="section section--alt" aria-labelledby="capa2-title">
    <div class="container">
        <div class="layer-banner">
            <span class="chip chip--layer2">CAPA 2 · NUESTRO DIFERENCIAL</span>
            <h2 id="capa2-title" class="section-title">Sistemas, sitios web, automatización e IA</h2>
            <p class="section-subtitle">
                Desarrollo propio y a medida para vender online con pasarela de pago, controlar pedidos de punta a punta y automatizar tareas repetitivas.
            </p>
        </div>

        <div class="services-cards-grid">
            <?php foreach (($serviciosC2 ?? []) as $srv): ?>
                <article class="service-card service-card--layer2 reveal-on-scroll">
                    <div class="service-card__icon"><?= Icon::get($srv['icono_svg'] ?? 'cpu', 24) ?></div>
                    <h3 class="service-card__title">
                        <a href="<?= View::url('servicios/' . $srv['slug']) ?>"><?= View::e($srv['nombre']) ?></a>
                    </h3>
                    <p class="service-card__desc"><?= View::e($srv['resumen']) ?></p>
                    <div class="service-card__actions">
                        <a href="<?= View::url('servicios/' . $srv['slug']) ?>" class="link-arrow">Ver qué incluye →</a>
                        <a href="<?= View::url('contacto?servicio=' . urlencode($srv['nombre'])) ?>" class="service-card__quote">Pedir presupuesto</a>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>

