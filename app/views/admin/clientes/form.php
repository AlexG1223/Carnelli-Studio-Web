<?php
use App\Core\View;
use App\Core\Csrf;

$isEdit = !empty($cliente);
$action = $isEdit ? View::url('admin/clientes/' . $cliente['id'] . '/actualizar') : View::url('admin/clientes/guardar');
?>
<div class="admin-card">
    <form action="<?= $action ?>" method="POST" enctype="multipart/form-data">
        <?= Csrf::field() ?>
        <div class="form-row-2">
            <div class="form-group">
                <label class="form-label">Nombre del Cliente *</label>
                <input type="text" name="nombre" class="form-input" value="<?= View::e($cliente['nombre'] ?? '') ?>" required>
            </div>
            <div class="form-group">
                <label class="form-label">Rubro *</label>
                <input type="text" name="rubro" class="form-input" value="<?= View::e($cliente['rubro'] ?? '') ?>" required>
            </div>
        </div>

        <div class="form-row-2">
            <div class="form-group">
                <label class="form-label">Sitio Web (opcional)</label>
                <input type="url" name="web_url" class="form-input" value="<?= View::e($cliente['web_url'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label class="form-label">Instagram (opcional)</label>
                <input type="url" name="instagram_url" class="form-input" value="<?= View::e($cliente['instagram_url'] ?? '') ?>">
            </div>
        </div>

        <div class="form-row-2">
            <div class="form-group">
                <label class="form-label">Subir Logo (opcional)</label>
                <input type="file" name="logo_file" accept="image/*" class="form-input">
            </div>
            <div class="form-group admin-switches">
                <label class="admin-check-item">
                    <input type="checkbox" name="visible" value="1" <?= (!isset($cliente['visible']) || !empty($cliente['visible'])) ? 'checked' : '' ?>>
                    <span><strong>Visible en la franja de clientes</strong></span>
                </label>
            </div>
        </div>

        <input type="hidden" name="orden" value="<?= (int)($cliente['orden'] ?? 1) ?>">

        <div class="admin-actions-row mt-md">
            <button type="submit" class="btn btn--primary">Guardar Cliente</button>
            <a href="<?= View::url('admin/clientes') ?>" class="btn btn--outline">Cancelar</a>
        </div>
    </form>
</div>

