<?php
/**
 * Vista: Ajustes de la marca y marcadores [COMPLETAR] (/admin/ajustes).
 */

use App\Core\View;
use App\Core\Csrf;
?>
<div class="admin-grid-2">
    <div class="admin-card">
        <h2 class="admin-card__title">Datos de Contacto, Redes y Campos <code>[COMPLETAR]</code></h2>
        <form action="<?= View::url('admin/ajustes/guardar') ?>" method="POST" enctype="multipart/form-data">
            <?= Csrf::field() ?>

            <div class="form-group">
                <label class="form-label">Tagline oficial</label>
                <input type="text" name="tagline" class="form-input" value="<?= View::e($settings['tagline'] ?? '') ?>">
            </div>

            <div class="form-group">
                <label class="form-label">Subtítulo del Hero en Inicio</label>
                <textarea name="hero_subtitulo" rows="2" class="form-textarea"><?= View::e($settings['hero_subtitulo'] ?? '') ?></textarea>
            </div>

            <div class="form-row-2">
                <div class="form-group">
                    <label class="form-label">WhatsApp visible</label>
                    <input type="text" name="whatsapp_visible" class="form-input" value="<?= View::e($settings['whatsapp_visible'] ?? '092 473 724') ?>">
                </div>
                <div class="form-group">
                    <label class="form-label">WhatsApp formato internacional (sin +)</label>
                    <input type="text" name="whatsapp_intl" class="form-input" value="<?= View::e($settings['whatsapp_intl'] ?? '59892473724') ?>">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Mensaje prellenado de WhatsApp por defecto</label>
                <input type="text" name="whatsapp_mensaje_default" class="form-input" value="<?= View::e($settings['whatsapp_mensaje_default'] ?? '') ?>">
            </div>

            <div class="form-row-2">
                <div class="form-group">
                    <label class="form-label">Email de contacto y recepción de leads</label>
                    <input type="email" name="email_contacto" class="form-input" value="<?= View::e($settings['email_contacto'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label class="form-label">Dominio oficial <span class="badge-pending">[COMPLETAR]</span></label>
                    <input type="text" name="dominio" class="form-input" value="<?= View::e($settings['dominio'] ?? 'carnellistudio.com.uy') ?>">
                </div>
            </div>

            <div class="form-row-2">
                <div class="form-group">
                    <label class="form-label">Zona de atención presencial</label>
                    <input type="text" name="ubicacion_presencial" class="form-input" value="<?= View::e($settings['ubicacion_presencial'] ?? 'Ciudad de la Costa (Canelones)') ?>">
                </div>
                <div class="form-group">
                    <label class="form-label">Tiempo de respuesta prometido</label>
                    <input type="text" name="tiempo_respuesta" class="form-input" value="<?= View::e($settings['tiempo_respuesta'] ?? '') ?>">
                </div>
            </div>

            <hr class="admin-hr">
            <h3>Redes Sociales y Agenda (Si están vacías, se ocultan en la web y en el Schema)</h3>

            <div class="form-row-2">
                <div class="form-group">
                    <label class="form-label">Instagram URL</label>
                    <input type="url" name="instagram_url" class="form-input" value="<?= View::e($settings['instagram_url'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label class="form-label">Usuario de Instagram</label>
                    <input type="text" name="instagram_handle" class="form-input" value="<?= View::e($settings['instagram_handle'] ?? '@carnelli.studio') ?>">
                </div>
            </div>

            <div class="form-row-2">
                <div class="form-group">
                    <label class="form-label">Facebook URL <span class="badge-pending">[COMPLETAR]</span></label>
                    <input type="url" name="facebook_url" class="form-input" value="<?= View::e($settings['facebook_url'] ?? '') ?>" placeholder="Dejar vacío para ocultar">
                </div>
                <div class="form-group">
                    <label class="form-label">LinkedIn URL <span class="badge-pending">[COMPLETAR]</span></label>
                    <input type="url" name="linkedin_url" class="form-input" value="<?= View::e($settings['linkedin_url'] ?? '') ?>" placeholder="Dejar vacío para ocultar">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Link para agendar videollamada (Google Calendar / Calendly) <span class="badge-pending">[COMPLETAR]</span></label>
                <input type="url" name="agenda_url" class="form-input" value="<?= View::e($settings['agenda_url'] ?? '') ?>" placeholder="https://calendar.app.google/... o https://calendly.com/...">
            </div>

            <div class="form-group">
                <label class="form-label">Foto de Alex Carnelli para <code>/quien-soy</code> <span class="badge-pending">[COMPLETAR]</span></label>
                <input type="file" name="foto_fundador_file" accept="image/*" class="form-input">
                <?php if (!empty($settings['foto_fundador'])): ?>
                    <small class="text-muted">Actual: <code><?= View::e($settings['foto_fundador']) ?></code></small>
                <?php endif; ?>
            </div>

            <hr class="admin-hr">
            <h3>Analítica y Conversión (GA4, Meta Pixel, Search Console)</h3>

            <div class="form-row-2">
                <div class="form-group">
                    <label class="form-label">Google Analytics 4 ID (ej. G-XXXXXXXXXX)</label>
                    <input type="text" name="ga4_id" class="form-input" value="<?= View::e($settings['ga4_id'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label class="form-label">Meta Pixel ID</label>
                    <input type="text" name="meta_pixel_id" class="form-input" value="<?= View::e($settings['meta_pixel_id'] ?? '') ?>">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Código de verificación de Google Search Console</label>
                <input type="text" name="search_console_meta" class="form-input" value="<?= View::e($settings['search_console_meta'] ?? '') ?>">
            </div>

            <button type="submit" class="btn btn--primary mt-md">Guardar todos los ajustes</button>
        </form>
    </div>

    <div class="admin-card">
        <h2 class="admin-card__title">Seguridad · Cambiar contraseña del Admin</h2>
        <form action="<?= View::url('admin/ajustes/password') ?>" method="POST">
            <?= Csrf::field() ?>
            <div class="form-group">
                <label class="form-label">Contraseña actual *</label>
                <input type="password" name="current_password" class="form-input" required>
            </div>
            <div class="form-group">
                <label class="form-label">Nueva contraseña (mínimo 8 caracteres) *</label>
                <input type="password" name="new_password" class="form-input" minlength="8" required>
            </div>
            <button type="submit" class="btn btn--outline">Actualizar contraseña</button>
        </form>
    </div>
</div>

