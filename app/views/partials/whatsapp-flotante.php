<?php
/**
 * Partial: Botón flotante de WhatsApp con mensaje contextual según la página actual
 * y Barra CTA fija en la parte inferior para dispositivos móviles (< 2 clics para contactar).
 */

use App\Core\View;
use App\Core\Icon;

$waMsg = $whatsappMessage ?? ($settings['whatsapp_mensaje_default'] ?? 'Hola Alex, vi la web de Carnelli Studio y quiero hacerte una consulta.');
$waUrl = View::whatsappUrl($waMsg);
$ctaUrl = !empty($preselectedService)
    ? View::url('contacto?servicio=' . urlencode((string)$preselectedService))
    : View::url('contacto');
?>
<!-- Botón flotante de WhatsApp (Desktop & Tablet) -->
<a
    href="<?= View::e($waUrl) ?>"
    target="_blank"
    rel="noopener"
    class="wa-float"
    data-track="whatsapp"
    aria-label="Escribir por WhatsApp a Carnelli Studio"
>
    <span class="wa-float__pulse" aria-hidden="true"></span>
    <span class="wa-float__icon">
        <?= Icon::get('whatsapp', 26) ?>
    </span>
    <span class="wa-float__tooltip">Hablemos por WhatsApp</span>
</a>

<!-- Barra CTA Fija en Mobile -->
<div class="mobile-cta-bar" role="region" aria-label="Contacto rápido">
    <a href="<?= View::e($waUrl) ?>" target="_blank" rel="noopener" class="mobile-cta-bar__btn mobile-cta-bar__btn--wa" data-track="whatsapp">
        <?= Icon::get('whatsapp', 18) ?>
        <span>WhatsApp</span>
    </a>
    <a href="<?= View::e($ctaUrl) ?>" class="mobile-cta-bar__btn mobile-cta-bar__btn--primary">
        <span>Pedir presupuesto</span>
        <?= Icon::get('arrow-right', 16) ?>
    </a>
</div>

