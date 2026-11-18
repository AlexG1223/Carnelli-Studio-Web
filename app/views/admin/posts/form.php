<?php
use App\Core\View;
use App\Core\Csrf;

$isEdit = !empty($post);
$action = $isEdit ? View::url('admin/blog/' . $post['id'] . '/actualizar') : View::url('admin/blog/guardar');
?>
<div class="admin-card">
    <form action="<?= $action ?>" method="POST" enctype="multipart/form-data">
        <?= Csrf::field() ?>
        <div class="form-group">
            <label class="form-label">Título del artículo (H1) *</label>
            <input type="text" name="titulo" class="form-input" value="<?= View::e($post['titulo'] ?? '') ?>" required>
        </div>

        <div class="form-row-2">
            <div class="form-group">
                <label class="form-label">Categoría</label>
                <input type="text" name="categoria" class="form-input" value="<?= View::e($post['categoria'] ?? 'Guías Pyme') ?>">
            </div>
            <div class="form-group">
                <label class="form-label">Tiempo de lectura (minutos)</label>
                <input type="number" name="tiempo_lectura" class="form-input" value="<?= (int)($post['tiempo_lectura'] ?? 4) ?>">
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Bloque Answer-First (40-60 palabras al inicio para GEO / IA) *</label>
            <textarea name="respuesta_directa" rows="3" class="form-textarea" required><?= View::e($post['respuesta_directa'] ?? '') ?></textarea>
        </div>

        <div class="form-group">
            <label class="form-label">Extracto corto *</label>
            <textarea name="extracto" rows="2" class="form-textarea" required><?= View::e($post['extracto'] ?? '') ?></textarea>
        </div>

        <div class="form-group">
            <label class="form-label">Contenido HTML del artículo *</label>
            <textarea name="contenido" rows="8" class="form-textarea" required><?= View::e($post['contenido'] ?? '') ?></textarea>
        </div>

        <div class="form-row-2">
            <div class="form-group">
                <label class="form-label">Meta Title SEO</label>
                <input type="text" name="meta_title" class="form-input" value="<?= View::e($post['meta_title'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label class="form-label">Meta Description SEO</label>
                <input type="text" name="meta_description" class="form-input" value="<?= View::e($post['meta_description'] ?? '') ?>">
            </div>
        </div>

        <div class="form-group admin-switches">
            <label class="admin-check-item">
                <input type="checkbox" name="publicado" value="1" <?= !empty($post['publicado']) ? 'checked' : '' ?>>
                <span><strong>Publicar artículo en el blog e incluir en sitemap.xml</strong></span>
            </label>
        </div>

        <div class="admin-actions-row mt-md">
            <button type="submit" class="btn btn--primary">Guardar Artículo</button>
            <a href="<?= View::url('admin/blog') ?>" class="btn btn--outline">Cancelar</a>
        </div>
    </form>
</div>

