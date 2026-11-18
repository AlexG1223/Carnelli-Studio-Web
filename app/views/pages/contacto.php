<?php
/**
 * Vista: Contacto (/contacto) — Formulario inteligente por pasos (Wizard de 3 pasos),
 * barra de progreso, guardado automático en sessionStorage, fallback sin JS, WhatsApp directo y link de agenda.
 */

use App\Core\View;
use App\Core\Csrf;
use App\Core\Icon;

$preServ = $preselectedService ?? '';
$prePlan = $preselectedPlan ?? '';
$agendaUrl = trim((string) ($settings['agenda_url'] ?? ''));
?>
<?= View::partial('breadcrumbs', ['seo' => $seo]) ?>

<section class="section contact-page">
    <div class="container contact-page__grid">
        <div class="contact-page__info">
            <span class="eyebrow">CONTACTO ÁGIL · EN MENOS DE 1 MINUTO</span>
            <h1 class="contact-page__title">Contanos qué necesita tu pyme</h1>
            <p class="contact-page__lead">
                Completá los 3 pasos rápidos o escribinos directo por WhatsApp.
                <strong><?= View::e($settings['tiempo_respuesta'] ?? 'Te respondo en menos de 24 horas hábiles') ?></strong>.
            </p>

            <div class="contact-direct-cards">
                <a href="<?= View::e(View::whatsappUrl($whatsappMessage ?? '')) ?>" target="_blank" rel="noopener"
                    class="contact-channel-card" data-track="whatsapp">
                    <span class="contact-channel-card__icon"><?= Icon::get('whatsapp', 24) ?></span>
                    <div>
                        <strong>WhatsApp directo</strong>
                        <span><?= View::e($settings['whatsapp_visible'] ?? '092 473 724') ?> · Respuesta rápida</span>
                    </div>
                </a>

                <a href="mailto:<?= View::e($settings['email_contacto'] ?? 'carnellistudio2026@gmail.com') ?>"
                    class="contact-channel-card" data-track="email">
                    <span class="contact-channel-card__icon"><?= Icon::get('mail', 24) ?></span>
                    <div>
                        <strong>Correo electrónico</strong>
                        <span><?= View::e($settings['email_contacto'] ?? 'carnellistudio2026@gmail.com') ?></span>
                    </div>
                </a>

                <?php if ($agendaUrl !== '' && !str_contains($agendaUrl, '[COMPLETAR')): ?>
                    <a href="<?= View::e($agendaUrl) ?>" target="_blank" rel="noopener" class="contact-channel-card"
                        data-track="schedule">
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
                <p><strong>Presencial:</strong>
                    <?= View::e($settings['ubicacion_presencial'] ?? 'Ciudad de la Costa (Canelones)') ?>.</p>
                <p><strong>Remoto:</strong>
                    <?= View::e($settings['ubicacion_remota'] ?? 'Todo Uruguay por videollamada') ?> (reunión inicial,
                    presupuesto y seguimiento).</p>
            </div>
        </div>

        <div class="contact-page__wizard-wrap">
            <div class="wizard-card">
                <!-- Barra de progreso del Wizard -->
                <div class="wizard-progress" aria-label="Progreso del formulario">
                    <div class="wizard-progress__steps">
                        <span class="wizard-step-indicator is-active" data-step-ind="1">1. Qué necesitás</span>
                        <span class="wizard-step-indicator" data-step-ind="2">2. Tu negocio</span>
                        <span class="wizard-step-indicator" data-step-ind="3">3. Tus datos</span>
                    </div>
                    <div class="wizard-progress__bar">
                        <div class="wizard-progress__fill" id="wizard-progress-fill" style="width: 33.33%;"></div>
                    </div>
                </div>

                <form action="<?= View::url('contacto/enviar') ?>" method="POST" id="contact-wizard-form"
                    data-form="lead" novalidate>
                    <?= Csrf::field() ?>
                    <input type="hidden" name="form_started_at" value="<?= time() ?>">
                    <input type="hidden" name="pagina_origen" value="/contacto">
                    <input type="hidden" name="plan_interes" value="<?= View::e($prePlan) ?>">
                    <input type="hidden" name="utm_source" value="">
                    <input type="hidden" name="utm_medium" value="">
                    <input type="hidden" name="utm_campaign" value="">

                    <!-- Honeypot oculto -->
                    <div class="hp-field" aria-hidden="true">
                        <label for="wiz_website">Sitio web alternativo</label>
                        <input type="text" id="wiz_website" name="website_url" tabindex="-1" autocomplete="off">
                    </div>

                    <!-- PASO 1: ¿Qué necesitás? -->
                    <fieldset class="wizard-step is-active" data-step="1">
                        <legend class="wizard-step__title">Paso 1: ¿Qué necesita hoy tu pyme?</legend>
                        <p class="wizard-step__desc">Podés elegir uno o varios servicios (si no estás seguro, marcá
                            asesoramiento).</p>

                        <?php if ($prePlan !== ''): ?>
                            <div class="preselected-banner">
                                Plan seleccionado: <strong><?= View::e($prePlan) ?></strong>
                            </div>
                        <?php endif; ?>

                        <div class="selectable-chips">
                            <?php foreach (($servicios ?? []) as $s): ?>
                                <?php
                                $checked = ($preServ !== '' && mb_strtolower($preServ) === mb_strtolower($s['nombre']));
                                ?>
                                <label class="chip-checkbox">
                                    <input type="checkbox" name="servicios_interes[]" value="<?= View::e($s['nombre']) ?>"
                                        <?= $checked ? 'checked' : '' ?>>
                                    <span class="chip-checkbox__box">
                                        <small class="chip-checkbox__layer">Capa <?= (int) $s['capa'] ?></small>
                                        <strong><?= View::e($s['nombre']) ?></strong>
                                    </span>
                                </label>
                            <?php endforeach; ?>
                            <label class="chip-checkbox">
                                <input type="checkbox" name="servicios_interes[]"
                                    value="Quiero asesoramiento para elegir" <?= $preServ === '' ? 'checked' : '' ?>>
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
                        <p class="wizard-step__desc">Con esto preparo ideas concretas antes de nuestra primera charla.
                        </p>

                        <div class="form-group">
                            <label for="wiz_negocio" class="form-label">Nombre de tu negocio o emprendimiento *</label>
                            <input type="text" id="wiz_negocio" name="negocio" class="form-input"
                                placeholder="Ej: Carnelli Studio..." required>
                        </div>

                        <div class="form-group">
                            <label for="wiz_rubro" class="form-label">¿A qué rubro se dedican?</label>
                            <input type="text" id="wiz_rubro" name="rubro" class="form-input"
                                placeholder="Ej: Comercio, gastronomía, servicios profesionales, industria...">
                        </div>

                        <div class="form-group">
                            <label for="wiz_situacion" class="form-label">¿Cómo están hoy en lo digital?</label>
                            <select id="wiz_situacion" name="situacion_actual" class="form-select">
                                <option value="Tenemos redes pero queremos vender más o sistematizar">Tenemos redes
                                    activas pero queremos dar el salto (web, tienda o sistema)</option>
                                <option value="Tenemos web o tienda pero no da resultados">Ya tenemos web o tienda
                                    online pero queremos mejorarla</option>
                                <option value="Recién empezamos / queremos ordenar todo desde cero">Estamos arrancando o
                                    queremos ordenar todo desde cero</option>
                                <option value="Buscamos automatizar procesos o un sistema a medida">Necesitamos ordenar
                                    pedidos, procesos internos o IA</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="wiz_mensaje" class="form-label">¿Algún detalle o idea puntual que quieras
                                contarme? (opcional)</label>
                            <textarea id="wiz_mensaje" name="mensaje" rows="3" class="form-textarea"
                                placeholder="Contame qué te gustaría lograr o qué problema querés sacarte de encima..."></textarea>
                        </div>

                        <div class="wizard-nav">
                            <button type="button" class="btn btn--outline" data-wizard-prev="1">← Volver</button>
                            <button type="button" class="btn btn--primary" data-wizard-next="3">
                                <span>Último paso: Tus datos</span>
                                <?= Icon::get('arrow-right', 16) ?>
                            </button>
                        </div>
                    </fieldset>

                    <!-- PASO 3: Tus datos -->
                    <fieldset class="wizard-step" data-step="3">
                        <legend class="wizard-step__title">Paso 3: ¿A dónde te respondo?</legend>
                        <p class="wizard-step__desc">Te contacto personalmente en menos de 24 horas hábiles.</p>

                        <div class="form-group">
                            <label for="wiz_nombre" class="form-label">Tu nombre y apellido *</label>
                            <input type="text" id="wiz_nombre" name="nombre" class="form-input"
                                placeholder="Ej: Laura Gómez" required>
                        </div>

                        <div class="form-row-2">
                            <div class="form-group">
                                <label for="wiz_whatsapp" class="form-label">Tu WhatsApp / Celular *</label>
                                <input type="tel" id="wiz_whatsapp" name="whatsapp" class="form-input"
                                    placeholder="Ej: 099 123 456" required>
                            </div>
                            <div class="form-group">
                                <label for="wiz_email" class="form-label">Tu Email</label>
                                <input type="email" id="wiz_email" name="email" class="form-input"
                                    placeholder="tu@correo.com">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">¿Cómo preferís que te contacte?</label>
                            <div class="radio-pills">
                                <label class="radio-pill">
                                    <input type="radio" name="preferencia_contacto" value="whatsapp" checked>
                                    <span>💬 Por WhatsApp</span>
                                </label>
                                <label class="radio-pill">
                                    <input type="radio" name="preferencia_contacto" value="videollamada">
                                    <span>💻 Videollamada</span>
                                </label>
                                <label class="radio-pill">
                                    <input type="radio" name="preferencia_contacto" value="llamada">
                                    <span>📞 Llamada telefónica</span>
                                </label>
                                <label class="radio-pill">
                                    <input type="radio" name="preferencia_contacto" value="email">
                                    <span>✉️ Por Email</span>
                                </label>
                            </div>
                        </div>

                        <p class="form-legal-note">
                            Tus datos están protegidos bajo la <a href="<?= View::url('politica-de-privacidad') ?>">Ley
                                N° 18.331</a> y solo los uso para responder a tu consulta.
                        </p>

                        <div class="wizard-nav">
                            <button type="button" class="btn btn--outline" data-wizard-prev="2">← Volver</button>
                            <button type="submit" class="btn btn--primary btn--lg">
                                <span>Enviar solicitud de presupuesto</span>
                                <?= Icon::get('arrow-right', 18) ?>
                            </button>
                        </div>
                    </fieldset>
                </form>
            </div>
        </div>
    </div>
</section>