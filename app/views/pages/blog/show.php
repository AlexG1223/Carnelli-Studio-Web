<?php
/**
 * Vista: Artículo individual del Blog (/blog/{slug}) con bloque Answer-First para GEO y datos de autor.
 */

use App\Core\View;
?>
<?= View::partial('breadcrumbs', ['seo' => $seo]) ?>

<article class="section">
    <div class="container container--narrow">
        <header class="post-header">
            <span class="chip chip--layer1"><?= View::e($post['categoria'] ?? 'Guía Pyme') ?></span>
            <h1 class="page-hero__title mt-sm"><?= View::e($post['titulo']) ?></h1>
            <div class="post-meta">
                <span>Por <a href="<?= View::url('quien-soy') ?>"><strong><?= View::e($post['autor'] ?? 'Alex Carnelli') ?></strong></a></span>
                <span>·</span>
                <time datetime="<?= date('Y-m-d', strtotime($post['fecha_publicacion'] ?? 'now')) ?>">
                    Actualizado el <?= date('d/m/Y', strtotime($post['updated_at'] ?? ($post['fecha_publicacion'] ?? 'now'))) ?>
                </time>
                <span>·</span>
                <span><?= (int)($post['tiempo_lectura'] ?? 4) ?> min de lectura</span>
            </div>
        </header>

        <?php if (!empty($post['respuesta_directa'])): ?>
            <div class="answer-first-box mt-md">
                <span class="answer-first-box__label">En resumen</span>
                <p><?= View::e($post['respuesta_directa']) ?></p>
            </div>
        <?php endif; ?>

        <div class="rich-text mt-lg">
            <?= $post['contenido'] ?>
        </div>

        <footer class="post-author-box mt-lg">
            <div>
                <strong>Escrito por Alex Carnelli</strong>
                <p>Desarrollador full-stack y fundador de Carnelli Studio. Ayudo a pymes uruguayas a ordenar sus redes, vender con tiendas online propias y automatizar procesos con IA.</p>
                <a href="<?= View::url('contacto') ?>" class="link-arrow">Pedir presupuesto o asesoramiento →</a>
            </div>
        </footer>
    </div>
</article>

