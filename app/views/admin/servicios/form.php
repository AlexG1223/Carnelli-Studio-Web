<?php
use App\Core\View;
use App\Core\Csrf;

$isEdit = !empty($servicio);
$action = $isEdit ? View::url('admin/servicios/' . $servicio['id'] . '/actualizar') : View::url('admin/servicios/guardar');
?>
<div class="admin-card">
    <form action="<?= $action ?>" method="POST">
        <?= Csrf::field() ?>
        <div class="form-row-2">
            <div class="form-group">
                <label class="form-label">Nombre del Servicio *</label>
                <input type="text" name="nombre" class="form-input" value="<?= View::e($servicio['nombre'] ?? '') ?>" required>
            </div>
            <div class="form-group">
                <label class="form-label">Capa *</label>
                <select name="capa" class="form-select">
                    <option value="1" <?= ((int)($servicio['capa'] ?? 1) === 1) ? 'selected' : '' ?>>Capa 1 · Marketing y presencia digital</option>
                    <option value="2" <?= ((int)($servicio['capa'] ?? 1) === 2) ? 'selected' : '' ?>>Capa 2 · Sistemas, Webs, Automatización e IA</option>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Subtítulo corto (debajo del H1)</label>
            <input type="text" name="subtitulo_corto" class="form-input" value="<?= View::e($servicio['subtitulo_corto'] ?? '') ?>">
        </div>

        <div class="form-group">
            <label class="form-label">Respuesta directa Answer-First (40-60 palabras citables por ChatGPT/Gemini) *</label>
            <textarea name="respuesta_directa" rows="3" class="form-textarea" required><?= View::e($servicio['respuesta_directa'] ?? '') ?></textarea>
        </div>

        <div class="form-group">
            <label class="form-label">Resumen para tarjetas *</label>
            <textarea name="resumen" rows="2" class="form-textarea" required><?= View::e($servicio['resumen'] ?? '') ?></textarea>
        </div>

        <div class="form-group">
            <label class="form-label">Contenido desarrollado (HTML permitido: &lt;p&gt;, &lt;strong&gt;, &lt;ul&gt;)</label>
            <textarea name="contenido" rows="5" class="form-textarea"><?= View::e($servicio['contenido'] ?? '') ?></textarea>
        </div>

        <div class="form-row-2">
            <div class="form-group">
                <label class="form-label">Beneficios (uno por línea)</label>
                <textarea name="beneficios_txt" rows="5" class="form-textarea"><?= View::e(implode("\n", $servicio['beneficios'] ?? [])) ?></textarea>
            </div>
            <div class="form-group">
                <label class="form-label">Qué incluye (uno por línea)</label>
                <textarea name="que_incluye_txt" rows="5" class="form-textarea"><?= View::e(implode("\n", $servicio['que_incluye'] ?? [])) ?></textarea>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Pasos del proceso (uno por línea)</label>
            <textarea name="proceso_txt" rows="4" class="form-textarea"><?= View::e(implode("\n", $servicio['proceso'] ?? [])) ?></textarea>
        </div>

        <div class="form-row-2">
            <div class="form-group">
                <label class="form-label">Meta Title SEO</label>
                <input type="text" name="meta_title" class="form-input" value="<?= View::e($servicio['meta_title'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label class="form-label">Meta Description SEO</label>
                <input type="text" name="meta_description" class="form-input" value="<?= View::e($servicio['meta_description'] ?? '') ?>">
            </div>
        </div>

        <div class="form-row-2">
            <div class="form-group">
                <label class="form-label">Orden</label>
                <input type="number" name="orden" class="form-input" value="<?= (int)($servicio['orden'] ?? 1) ?>">
            </div>
            <div class="form-group admin-switches">
                <label class="admin-check-item">
                    <input type="checkbox" name="activo" value="1" <?= (!isset($servicio['activo']) || !empty($servicio['activo'])) ? 'checked' : '' ?>>
                    <span><strong>Servicio Activo</strong></span>
                </label>
            </div>
        </div>

        <div class="admin-actions-row mt-md">
            <button type="submit" class="btn btn--primary">Guardar Servicio</button>
            <a href="<?= View::url('admin/servicios') ?>" class="btn btn--outline">Cancelar</a>
        </div>
    </form>
</div>

