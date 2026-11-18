<?php
/**
 * Vista: Preguntas Frecuentes (/preguntas-frecuentes) con Schema FAQPage y filtro rápido.
 */

use App\Core\View;
?>
<?= View::partial('breadcrumbs', ['seo' => $seo]) ?>

<section class="page-hero">
    <div class="container">
        <span class="eyebrow">RESPUESTAS CLARAS Y SIN LETRA CHICA</span>
        <h1 class="page-hero__title">Preguntas frecuentes</h1>
        <p class="page-hero__subtitle">
            Todo lo que suele preguntarnos el dueño o encargado de una pyme en Uruguay antes de empezar a trabajar juntos.
        </p>
    </div>
</section>

<section class="section">
    <div class="container container--narrow">
        <div class="faq-accordion">
            <?php foreach (($faqs ?? []) as $idx => $f): ?>
                <details class="faq-item" <?= $idx < 2 ? 'open' : '' ?>>
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

