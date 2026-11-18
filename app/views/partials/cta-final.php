<?php
/**
 * Partial: Bloque CTA Final grande con generador rápido de mensaje personalizado a WhatsApp.
 */

use App\Core\View;
use App\Core\Csrf;
use App\Core\Icon;

$preselected = $preselectedService ?? '';
$waMsg       = $whatsappMessage ?? ($settings['whatsapp_mensaje_default'] ?? 'Hola Alex, quiero pedir un presupuesto para mi pyme.');
$waNumber    = preg_replace('/[^0-9]/', '', (string)($settings['whatsapp_intl'] ?? '59892473724')) ?: '59892473724';
?>
<section class="cta-final" aria-labelledby="cta-final-heading">
    <div class="container">
        <div class="cta-final__box">
            <div class="cta-final__copy">
                <span class="eyebrow eyebrow--light">CONTACTO DIRECTO Y SIN VUELTAS</span>
                <h2 id="cta-final-heading" class="cta-final__title">
                    ¿Querés ordenar la presencia digital o los procesos de tu pyme?
                </h2>
                <p class="cta-final__text">
                    Contame en qué etapa está tu negocio hoy. Coordinamos una videollamada o reunión inicial sin costo, evaluamos qué necesitás y te armo un presupuesto a medida.
                </p>
                <ul class="cta-final__perks">
                    <li><?= Icon::get('check', 18) ?> <span>Trato directo conmigo (Alex Carnelli), sin intermediarios.</span></li>
                    <li><?= Icon::get('check', 18) ?> <span>Presupuesto adaptado a lo que tu pyme realmente necesita hoy.</span></li>
                    <li><?= Icon::get('check', 18) ?> <span><?= View::e($settings['tiempo_respuesta'] ?? 'Te respondo en menos de 24 horas hábiles') ?>.</span></li>
                </ul>
                <div class="cta-final__direct">
                    <a href="<?= View::e(View::whatsappUrl($waMsg)) ?>" target="_blank" rel="noopener" class="btn btn--white" data-track="whatsapp">
                        <?= Icon::get('whatsapp', 20) ?>
                        <span>Abrir chat directo (<?= View::e($settings['whatsapp_visible'] ?? '092 473 724') ?>)</span>
                    </a>
                </div>
            </div>

            <div class="cta-final__form-card">
                <h3 class="cta-final__form-title">Armá tu consulta para WhatsApp</h3>
                <p class="cta-final__form-sub">Completá estos datos y se abrirá tu WhatsApp con el mensaje armado listo para enviarme.</p>

                <form action="<?= View::url('contacto/enviar') ?>" method="POST" class="quick-form" data-wa-form="quick" data-wa-phone="<?= View::e($waNumber) ?>">
                    <?= Csrf::field() ?>

                    <div class="form-row-2">
                        <div class="form-group">
                            <label for="qf_nombre" class="form-label">Tu nombre *</label>
                            <input type="text" id="qf_nombre" name="nombre" class="form-input" placeholder="Ej: Martín Rodríguez" required>
                        </div>
                        <div class="form-group">
                            <label for="qf_negocio" class="form-label">Tu pyme / rubro *</label>
                            <input type="text" id="qf_negocio" name="negocio" class="form-input" placeholder="Ej: Ferretería Central" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="qf_servicio" class="form-label">¿Qué te interesa priorizar?</label>
                        <select id="qf_servicio" name="servicios_interes[]" class="form-select">
                            <option value="Quiero asesoramiento para elegir">Quiero asesoramiento para elegir</option>
                            <option value="Sitios web y Landing Pages" <?= $preselected === 'Sitios web y Landing Pages' ? 'selected' : '' ?>>Sitio Web o Landing Page</option>
                            <option value="Tiendas online (E-commerce)" <?= $preselected === 'Tiendas online (E-commerce)' ? 'selected' : '' ?>>Tienda Online con Mercado Pago</option>
                            <option value="Sistemas web a medida" <?= $preselected === 'Sistemas web a medida' ? 'selected' : '' ?>>Sistema web a medida (ej. pedidos)</option>
                            <option value="Organización, automatización e IA aplicada" <?= $preselected === 'Organización, automatización e IA aplicada' ? 'selected' : '' ?>>Automatización de procesos e IA</option>
                            <option value="Gestión de redes sociales" <?= $preselected === 'Gestión de redes sociales' ? 'selected' : '' ?>>Gestión de redes sociales y Reels</option>
                            <option value="Campañas de Meta Ads" <?= $preselected === 'Campañas de Meta Ads' ? 'selected' : '' ?>>Publicidad en Meta Ads / Google Ads</option>
                            <option value="SEO y GEO (Posicionamiento en Google e IA)" <?= $preselected === 'SEO y GEO (Posicionamiento en Google e IA)' ? 'selected' : '' ?>>SEO, GEO (IA) y Google Maps</option>
                            <option value="Clases personalizadas y consultoría" <?= $preselected === 'Clases personalizadas y consultoría' ? 'selected' : '' ?>>Clases personalizadas / Consultoría 1 a 1</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="qf_mensaje" class="form-label">Contanos brevemente qué buscás (opcional)</label>
                        <textarea id="qf_mensaje" name="mensaje" rows="2" class="form-textarea" placeholder="Ej: Queremos vender por web con Mercado Pago y ordenar los pedidos..."></textarea>
                    </div>

                    <button type="submit" class="btn btn--primary btn--full btn--lg">
                        <?= Icon::get('whatsapp', 20) ?>
                        <span>Enviar consulta a mi WhatsApp</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>
