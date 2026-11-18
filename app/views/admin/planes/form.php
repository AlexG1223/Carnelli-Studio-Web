<?php
use App\Core\View;
use App\Core\Csrf;
?>
<div class="admin-card">
    <form action="<?= View::url('admin/planes/' . $plan['id'] . '/actualizar') ?>" method="POST">
        <?= Csrf::field() ?>
        <div class="form-row-2">
            <div class="form-group">
                <label class="form-label">Nombre del Plan *</label>
                <input type="text" name="nombre" class="form-input" value="<?= View::e($plan['nombre']) ?>" required>
            </div>
            <div class="form-group">
                <label class="form-label">Capas incluidas</label>
                <input type="text" name="capas_incluidas" class="form-input" value="<?= View::e($plan['capas_incluidas']) ?>">
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Subtítulo</label>
            <input type="text" name="subtitulo" class="form-input" value="<?= View::e($plan['subtitulo']) ?>">
        </div>

        <div class="form-group">
            <label class="form-label">¿Para quién es ideal?</label>
            <textarea name="ideal_para" rows="2" class="form-textarea"><?= View::e($plan['ideal_para']) ?></textarea>
        </div>

        <div class="form-group">
            <label class="form-label">Qué incluye (un ítem por línea)</label>
            <textarea name="items_txt" rows="6" class="form-textarea"><?= View::e(implode("\n", $plan['items_incluidos'] ?? [])) ?></textarea>
        </div>

        <div class="form-row-2">
            <div class="form-group">
                <label class="form-label">Texto del botón CTA</label>
                <input type="text" name="texto_cta" class="form-input" value="<?= View::e($plan['texto_cta'] ?? 'Pedí tu presupuesto') ?>">
            </div>
            <div class="form-group admin-switches">
                <label class="admin-check-item">
                    <input type="checkbox" name="destacado" value="1" <?= !empty($plan['destacado']) ? 'checked' : '' ?>>
                    <span><strong>Plan Destacado</strong></span>
                </label>
                <label class="admin-check-item">
                    <input type="checkbox" name="activo" value="1" <?= !empty($plan['activo']) ? 'checked' : '' ?>>
                    <span><strong>Activo</strong></span>
                </label>
            </div>
        </div>

        <input type="hidden" name="orden" value="<?= (int)($plan['orden'] ?? 1) ?>">

        <div class="admin-actions-row mt-md">
            <button type="submit" class="btn btn--primary">Guardar Plan</button>
            <a href="<?= View::url('admin/planes') ?>" class="btn btn--outline">Cancelar</a>
        </div>
    </form>
</div>

