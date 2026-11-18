<?php
use App\Core\View;
use App\Core\Csrf;

$isEdit = !empty($testimonio);
$action = $isEdit ? View::url('admin/testimonios/' . $testimonio['id'] . '/actualizar') : View::url('admin/testimonios/guardar');
?>
<div class="admin-card">
    <form action="<?= $action ?>" method="POST">
        <?= Csrf::field() ?>
        <div class="form-row-2">
            <div class="form-group">
                <label class="form-label">Nombre del cliente *</label>
                <input type="text" name="nombre" class="form-input" value="<?= View::e($testimonio['nombre'] ?? '') ?>" required>
            </div>
            <div class="form-group">
                <label class="form-label">Cargo / Empresa *</label>
                <input type="text" name="cargo_negocio" class="form-input" value="<?= View::e($testimonio['cargo_negocio'] ?? '') ?>" required>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Testimonio *</label>
            <textarea name="texto" rows="4" class="form-textarea" required><?= View::e($testimonio['texto'] ?? '') ?></textarea>
        </div>

        <div class="form-row-2">
            <div class="form-group">
                <label class="form-label">Vincular a un proyecto (opcional)</label>
                <select name="proyecto_id" class="form-select">
                    <option value="0">Ninguno en particular</option>
                    <?php foreach (($proyectos ?? []) as $p): ?>
                        <option value="<?= (int)$p['id'] ?>" <?= ((int)($testimonio['proyecto_id'] ?? 0) === (int)$p['id']) ? 'selected' : '' ?>>
                            <?= View::e($p['cliente_nombre']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group admin-switches">
                <label class="admin-check-item">
                    <input type="checkbox" name="publicado" value="1" <?= (!isset($testimonio['publicado']) || !empty($testimonio['publicado'])) ? 'checked' : '' ?>>
                    <span><strong>Publicado en la web</strong></span>
                </label>
            </div>
        </div>

        <div class="admin-actions-row mt-md">
            <button type="submit" class="btn btn--primary">Guardar Testimonio</button>
            <a href="<?= View::url('admin/testimonios') ?>" class="btn btn--outline">Cancelar</a>
        </div>
    </form>
</div>

