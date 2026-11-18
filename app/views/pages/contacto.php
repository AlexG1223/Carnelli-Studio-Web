<?php
/**
 * Vista: Contacto (/contacto) — Asistente en 3 pasos que construye un mensaje personalizado
 * y lo envía directamente al WhatsApp de Alex Carnelli (+59892473724) sin guardar en base de datos.
 */

use App\Core\View;
use App\Core\Csrf;
use App\Core\Icon;

$preServ   = $preselectedService ?? '';
$prePlan   = $preselectedPlan ?? '';
$agendaUrl = trim((string)($settings['agenda_url'] ?? ''));
$waNumber  = preg_replace('/[^0-9]/', '', (string)($settings['whatsapp_intl'] ?? '59892473724')) ?: '59892473724';
?>
<?= View::partial('breadcrumbs', ['seo' => $seo]) ?>

<section class="section contact-page">
    <div class="container contact-page__grid">
        <div class="contact-page__info">
            <span class="eyebrow">CONTACTO DIRECTO POR WHATSAPP</span>
            <h1 class="contact-page__title">Contanos qué necesita tu pyme</h1>
            <p class="contact-page__lead">
                Elegí qué necesitás en 3 pasos rápidos para armar tu mensaje personalizado y enviármelo directo a mi WhatsApp. <strong><?= View::e($settings['tiempo_respuesta'] ?? 'Te respondo en menos de 24 horas hábiles') ?></strong>.
            </p>

            <div class="contact-direct-cards">
                <a href="<?= View::e(View::whatsappUrl($whatsappMessage ?? '')) ?>" target="_blank" rel="noopener" class="contact-channel-card" data-track="whatsapp">
                    <span class="contact-channel-card__icon"><?= Icon::get('whatsapp', 24) ?></span>
                    <div>
                        <strong>WhatsApp directo</strong>
                        <span><?= View::e($settings['whatsapp_visible'] ?? '092 473 724') ?> · Escribime sin llenar nada</span>
                    </div>
                </a>

                <a href="mailto:<?= View::e($settings['email_contacto'] ?? 'carnellistudio2026@gmail.com') ?>" class="contact-channel-card" data-track="email">
                    <span class="contact-channel-card__icon"><?= Icon::get('mail', 24) ?></span>
                    <div>
                        <strong>Correo electrónico</strong>
                        <span><?= View::e($settings['email_contacto'] ?? 'carnellistudio2026@gmail.com') ?></span>
                    </div>
                </a>

                <?php if ($agendaUrl !== '' && !str_contains($agendaUrl, '[COMPLETAR')): ?>
                    <a href="<?= View::e($agendaUrl) ?>" target="_blank" rel="noopener" class="contact-channel-card" data-track="schedule">
                        <span class="contact-channel-card__icon"><?= Icon::get('calendar', 24) ?></span>
                        <div>
                            <strong>Agendar videollamada</strong>
                            <span>Elegí día y horario directamente en mi agenda</span>
                        </div>
                    </a>
                <?php endif; ?>
            </div>

            <div class="contact-location-box">
                <h3>📍 Cobertura de atención</h3>
                <p><strong>Presencial:</strong> <?= View::e($settings['ubicacion_presencial'] ?? 'Ciudad de la Costa (Canelones)') ?>.</p>
                <p><strong>Remoto:</strong> <?= View::e($settings['ubicacion_remota'] ?? 'Todo Uruguay por videollamada') ?> (reunión inicial, presupuesto y seguimiento).</p>
            </div>
        </div>

        <div class="contact-page__wizard-wrap">
            <div class="wizard-card">
                <div class="wizard-progress" aria-label="Progreso para armar tu mensaje">
                    <div class="wizard-progress__steps">
                        <span class="wizard-step-indicator is-active" data-step-ind="1">1. Qué necesitás</span>
                        <span class="wizard-step-indicator" data-step-ind="2">2. Tu negocio</span>
                        <span class="wizard-step-indicator" data-step-ind="3">3. Enviar a WhatsApp</span>
                    </div>
                    <div class="wizard-progress__bar">
                        <div class="wizard-progress__fill" id="wizard-progress-fill" style="width: 33.33%;"></div>
                    </div>
                </div>

                <form action="<?= View::url('contacto/enviar') ?>" method="POST" id="contact-wizard-form" data-wa-form="wizard" data-wa-phone="<?= View::e($waNumber) ?>" novalidate>
                    <?= Csrf::field() ?>
                    <input type="hidden" name="plan_interes" value="<?= View::e($prePlan) ?>">

                    <!-- PASO 1: ¿Qué necesitás? -->
                    <fieldset class="wizard-step is-active" data-step="1">
                        <legend class="wizard-step__title">Paso 1: ¿Qué necesita hoy tu pyme?</legend>
                        <p class="wizard-step__desc">Podés marcar uno o varios servicios para incluirlos en el mensaje.</p>

                        <?php if ($prePlan !== ''): ?>
                            <div class="preselected-banner">
                                Plan seleccionado: <strong><?= View::e($prePlan) ?></strong>
                            </div>
                        <?php endif; ?>

                        <div class="selectable-chips">
                            <?php foreach (($servicios ?? []) as $s): ?>
                                <?php $checked = ($preServ !== '' && mb_strtolower($preServ) === mb_strtolower($s['nombre'])); ?>
                                <label class="chip-checkbox">
                                    <input type="checkbox" name="servicios_interes[]" value="<?= View::e($s['nombre']) ?>" <?= $checked ? 'checked' : '' ?>>
                                    <span class="chip-checkbox__box">
                                        <small class="chip-checkbox__layer">Capa <?= (int)$s['capa'] ?></small>
                                        <strong><?= View::e($s['nombre']) ?></strong>
                                    </span>
                                </label>
                            <?php endforeach; ?>
                            <label class="chip-checkbox">
                                <input type="checkbox" name="servicios_interes[]" value="Quiero asesoramiento para elegir" <?= $preServ === '' ? 'checked' : '' ?>>
                                <span class="chip-checkbox__box">
                                    <small class="chip-checkbox__layer">Ayuda</small>
                                    <strong>No estoy seguro, quiero asesoramiento</strong>
                                </span>
                            </label>
                        </div>

                        <div class="wizard-nav">
                            <span></span>
                            <button type="button" class="btn btn--primary" data-wizard-next="2">
                                <span>Siguiente: Tu negocio</span>
                                <?= Icon::get('arrow-right', 16) ?>
                            </button>
                        </div>
                    </fieldset>

                    <!-- PASO 2: Tu negocio -->
                    <fieldset class="wizard-step" data-step="2">
                        <legend class="wizard-step__title">Paso 2: Contanos sobre tu negocio</legend>
                        <p class="wizard-step__desc">Así ya sé de qué rubro sos cuando me llegue tu WhatsApp.</p>

                        <div class="form-group">
                            <label for="wiz_negocio" class="form-label">Nombre de tu negocio o emprendimiento *</label>
                            <input type="text" id="wiz_negocio" name="negocio" class="form-input" placeholder="Ej: Carnelli Studio..." required>
                        </div>

                        <div class="form-group">
                            <label for="wiz_rubro" class="form-label">¿A qué rubro se dedican?</label>
                            <input type="text" id="wiz_rubro" name="rubro" class="form-input" placeholder="Ej: Comercio, gastronomía, servicios, industria...">
                        </div>

                        <div class="form-group">
                            <label for="wiz_situacion" class="form-label">¿Cómo están hoy en lo digital?</label>
                            <select id="wiz_situacion" name="situacion_actual" class="form-select">
                                <option value="Tenemos redes activas pero queremos dar el salto (web, tienda o sistema)">Tenemos redes activas pero queremos dar el salto (web, tienda o sistema)</option>
                                <option value="Ya tenemos web o tienda online pero queremos mejorarla">Ya tenemos web o tienda online pero queremos mejorarla</option>
                                <option value="Estamos arrancando o queremos ordenar todo desde cero">Estamos arrancando o queremos ordenar todo desde cero</option>
                                <option value="Necesitamos ordenar pedidos, procesos internos o IA">Necesitamos ordenar pedidos, procesos internos o IA</option>
                            </select>
                        </div>

                        <div class="wizard-nav">
                            <button type="button" class="btn btn--outline" data-wizard-prev="1">← Volver</button>
                            <button type="button" class="btn btn--primary" data-wizard-next="3">
                                <span>Último paso: Enviar por WhatsApp</span>
                                <?= Icon::get('arrow-right', 16) ?>
                            </button>
                        </div>
                    </fieldset>

                    <!-- PASO 3: Tu nombre y envío directo a WhatsApp -->
                    <fieldset class="wizard-step" data-step="3">
                        <legend class="wizard-step__title">Paso 3: Revisá y enviá tu consulta</legend>
                        <p class="wizard-step__desc">Al tocar el botón se abrirá tu WhatsApp con el mensaje armado listo para enviarme.</p>

                        <div class="form-group">
                            <label for="wiz_nombre" class="form-label">Tu nombre *</label>
                            <input type="text" id="wiz_nombre" name="nombre" class="form-input" placeholder="Ej: Martín Rodríguez" required>
                        </div>

                        <div class="form-group">
                            <label for="wiz_mensaje" class="form-label">¿Querés sumar algún detalle más al mensaje? (opcional)</label>
                            <textarea id="wiz_mensaje" name="mensaje" rows="3" class="form-textarea" placeholder="Contame qué te gustaría lograr o cualquier duda puntual..."></textarea>
                        </div>

                        <div class="wizard-nav">
                            <button type="button" class="btn btn--outline" data-wizard-prev="2">← Volver</button>
                            <button type="submit" class="btn btn--primary btn--lg">
                                <?= Icon::get('whatsapp', 20) ?>
                                <span>Enviar mensaje a mi WhatsApp</span>
                            </button>
                        </div>
                    </fieldset>
                </form>
            </div>
        </div>
    </div>
</section>
