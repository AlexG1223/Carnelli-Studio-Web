<?php
/**
 * Vista: Listado del Blog (/blog) y vista de artículo (/blog/{slug}).
 */

use App\Core\View;
?>
<?= View::partial('breadcrumbs', ['seo' => $seo]) ?>

<section class="page-hero">
    <div class="container">
        <span class="eyebrow">RECURSOS PARA PYMES URUGUAYAS</span>
        <h1 class="page-hero__title">Blog de transformación digital, webs e IA</h1>
        <p class="page-hero__subtitle">
            Artículos prácticos en criollo sobre ventas online con Mercado Pago, posicionamiento en Google e IA, redes sociales y sistemas a medida.
        </p>
    </div>
</section>

<section class="section">
    <div class="container">
        <?php if (!empty($categorias)): ?>
            <div class="portfolio-filters">
                <a href="<?= View::url('blog') ?>" class="filter-pill <?= empty($categoriaActual) ? 'is-active' : '' ?>">Todos</a>
                <?php foreach ($categorias as $catName => $count): ?>
                    <a href="<?= View::url('blog?categoria=' . urlencode($catName)) ?>" class="filter-pill <?= ($categoriaActual ?? '') === $catName ? 'is-active' : '' ?>">
                        <?= View::e($catName) ?> (<?= (int)$count ?>)
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php if (empty($posts)): ?>
            <div class="empty-state">
                <h2>Próximamente nuevos artículos</h2>
                <p>Estamos preparando guías prácticas pensadas para dueños de pymes en Uruguay. Mientras tanto, podés explorar nuestros casos de estudio o servicios.</p>
                <div class="mt-md">
                    <a href="<?= View::url('proyectos') ?>" class="btn btn--primary">Ver casos de estudio</a>
                    <a href="<?= View::url('servicios') ?>" class="btn btn--outline">Ver servicios</a>
                </div>
            </div>
        <?php else: ?>
            <div class="projects-grid">
                <?php foreach ($posts as $post): ?>
                    <article class="project-card">
                        <a href="<?= View::url('blog/' . $post['slug']) ?>" class="project-card__media">
                            <img src="<?= View::asset($post['portada'] ?: 'assets/brand/og-default.png') ?>" alt="<?= View::e($post['portada_alt'] ?: $post['titulo']) ?>" width="600" height="400" loading="lazy" class="project-card__img">
                        </a>
                        <div class="project-card__body">
                            <div class="project-card__meta">
                                <span class="chip chip--layer1"><?= View::e($post['categoria'] ?? 'Guía') ?></span>
                                <span>· <?= (int)($post['tiempo_lectura'] ?? 4) ?> min de lectura</span>
                            </div>
                            <h2 class="project-card__title">
                                <a href="<?= View::url('blog/' . $post['slug']) ?>"><?= View::e($post['titulo']) ?></a>
                            </h2>
                            <p class="project-card__excerpt"><?= View::e($post['extracto']) ?></p>
                            <a href="<?= View::url('blog/' . $post['slug']) ?>" class="link-arrow">Leer artículo completo →</a>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

