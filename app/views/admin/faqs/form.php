<?php
use App\Core\View;
use App\Core\Csrf;

$isEdit = !empty($faq);
$action = $isEdit ? View::url('admin/faqs/' . $faq['id'] . '/actualizar') : View::url('admin/faqs/guardar');
?>
<div class="admin-card">
    <form action="<?= $action ?>" method="POST">
        <?= Csrf::field() ?>
        <div class="form-group">
            <label class="form-label">Pregunta *</label>
            <input type="text" name="pregunta" class="form-input" value="<?= View::e($faq['pregunta'] ?? '') ?>" required>
        </div>

        <div class="form-group">
            <label class="form-label">Respuesta *</label>
            <textarea name="respuesta" rows="4" class="form-textarea" required><?= View::e($faq['respuesta'] ?? '') ?></textarea>
        </div>

        <div class="form-row-2">
            <div class="form-group">
                <label class="form-label">Servicio relacionado (opcional)</label>
                <select name="servicio_id" class="form-select">
                    <option value="0">General (Todos)</option>
                    <?php foreach (($servicios ?? []) as $s): ?>
                        <option value="<?= (int)$s['id'] ?>" <?= ((int)($faq['servicio_id'] ?? 0) === (int)$s['id']) ? 'selected' : '' ?>>
                            <?= View::e($s['nombre']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Orden</label>
                <input type="number" name="orden" class="form-input" value="<?= (int)($faq['orden'] ?? 1) ?>">
            </div>
        </div>

        <div class="form-group admin-switches">
            <label class="admin-check-item">
                <input type="checkbox" name="mostrar_en_home" value="1" <?= !empty($faq['mostrar_en_home']) ? 'checked' : '' ?>>
                <span>Mostrar en la página de Inicio</span>
            </label>
            <label class="admin-check-item">
                <input type="checkbox" name="activo" value="1" <?= (!isset($faq['activo']) || !empty($faq['activo'])) ? 'checked' : '' ?>>
                <span>Activa</span>
            </label>
        </div>

        <div class="admin-actions-row mt-md">
            <button type="submit" class="btn btn--primary">Guardar Pregunta</button>
            <a href="<?= View::url('admin/faqs') ?>" class="btn btn--outline">Cancelar</a>
        </div>
    </form>
</div>

