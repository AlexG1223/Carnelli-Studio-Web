<?php
/**
 * Vista: Página de confirmación post-envío (/gracias).
 * Sirve para medir conversiones, permite escribir ya por WhatsApp, agendar videollamada y ver 2 casos destacados.
 */

use App\Core\View;
use App\Core\Session;
use App\Core\Icon;

$leadName  = Session::get('last_lead_name', '');
$agendaUrl = trim((string)($settings['agenda_url'] ?? ''));
?>
<section class="section thankyou-section" data-conversion="lead_submitted">
    <div class="container container--narrow">
        <div class="thankyou-card">
            <div class="thankyou-card__badge"><?= Icon::get('check', 28) ?></div>
            <h1 class="thankyou-card__title">
                ¡Gracias<?= $leadName ? ', ' . View::e($leadName) : '' ?>! Recibí tu consulta
            </h1>
            <p class="thankyou-card__lead">
                Ya tengo los datos de tu negocio. Voy a analizar tu caso personalmente y te voy a responder en <strong>menos de 24 horas hábiles</strong>.
            </p>

            <div class="thankyou-card__actions">
                <a href="<?= View::e(View::whatsappUrl($whatsappMessage ?? '')) ?>" target="_blank" rel="noopener" class="btn btn--primary btn--lg" data-track="whatsapp">
                    <?= Icon::get('whatsapp', 20) ?>
                    <span>¿Querés hablar ya? Escribime por WhatsApp</span>
                </a>

                <?php if ($agendaUrl !== '' && !str_contains($agendaUrl, '[COMPLETAR')): ?>
                    <a href="<?= View::e($agendaUrl) ?>" target="_blank" rel="noopener" class="btn btn--outline btn--lg" data-track="schedule">
                        <?= Icon::get('calendar', 18) ?>
                        <span>Agendar videollamada directamente</span>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<?php if (!empty($proyectosDestacados)): ?>
<section class="section section--alt">
    <div class="container">
        <div class="section-header">
            <span class="eyebrow">MIENTRAS TANTO</span>
            <h2 class="section-title">Mirá cómo ayudamos a otras pymes uruguayas</h2>
        </div>
        <div class="projects-grid">
            <?php foreach ($proyectosDestacados as $p): ?>
                <?= View::partial('card-proyecto', ['proyecto' => $p]) ?>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

