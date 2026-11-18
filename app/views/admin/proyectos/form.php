<?php
/**
 * Vista: Formulario de creación/edición de Proyecto (/admin/proyectos/crear y /editar)
 * con conversión automática a WebP, galería y contador de caracteres SEO.
 */

use App\Core\View;
use App\Core\Csrf;

$isEdit = !empty($proyecto);
$action = $isEdit
    ? View::url('admin/proyectos/' . $proyecto['id'] . '/actualizar')
    : View::url('admin/proyectos/guardar');
$selectedServs = array_map('intval', $proyecto['servicios_ids'] ?? []);
?>
<div class="admin-card">
    <form action="<?= $action ?>" method="POST" enctype="multipart/form-data">
        <?= Csrf::field() ?>

        <div class="form-row-2">
            <div class="form-group">
                <label class="form-label">Nombre del Cliente *</label>
                <input type="text" name="cliente_nombre" class="form-input" value="<?= View::e($proyecto['cliente_nombre'] ?? '') ?>" required>
            </div>
            <div class="form-group">
                <label class="form-label">Slug (URL limpia en /proyectos/{slug})</label>
                <input type="text" name="slug" class="form-input" value="<?= View::e($proyecto['slug'] ?? '') ?>" placeholder="Se genera solo si lo dejás vacío">
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Título del Caso de Estudio (H1) *</label>
            <input type="text" name="titulo" class="form-input" value="<?= View::e($proyecto['titulo'] ?? '') ?>" required>
        </div>

        <div class="form-row-2">
            <div class="form-group">
                <label class="form-label">Rubro *</label>
                <input type="text" name="rubro" class="form-input" value="<?= View::e($proyecto['rubro'] ?? '') ?>" required>
            </div>
            <div class="form-group">
                <label class="form-label">Año / URL en vivo (opcional)</label>
                <div class="form-row-2">
                    <input type="number" name="anio" class="form-input" value="<?= (int)($proyecto['anio'] ?? date('Y')) ?>">
                    <input type="url" name="url_en_vivo" class="form-input" value="<?= View::e($proyecto['url_en_vivo'] ?? '') ?>" placeholder="https://...">
                </div>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Servicios aplicados en este proyecto (para filtros y enlazado automático)</label>
            <div class="admin-checkbox-grid">
                <?php foreach (($servicios ?? []) as $s): ?>
                    <label class="admin-check-item">
                        <input type="checkbox" name="servicios_ids[]" value="<?= (int)$s['id'] ?>" <?= in_array((int)$s['id'], $selectedServs, true) ? 'checked' : '' ?>>
                        <span>[Capa <?= (int)$s['capa'] ?>] <?= View::e($s['nombre']) ?></span>
                    </label>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Resumen corto (para las tarjetas del portfolio) *</label>
            <textarea name="resumen_corto" rows="2" class="form-textarea" required><?= View::e($proyecto['resumen_corto'] ?? '') ?></textarea>
        </div>

        <div class="form-group">
            <label class="form-label">01. El Desafío *</label>
            <textarea name="desafio" rows="4" class="form-textarea" required><?= View::e($proyecto['desafio'] ?? '') ?></textarea>
        </div>

        <div class="form-group">
            <label class="form-label">02. La Solución implementada *</label>
            <textarea name="solucion" rows="4" class="form-textarea" required><?= View::e($proyecto['solucion'] ?? '') ?></textarea>
        </div>

        <div class="form-group">
            <label class="form-label">03. El Resultado *</label>
            <textarea name="resultado" rows="3" class="form-textarea" required><?= View::e($proyecto['resultado'] ?? '') ?></textarea>
        </div>

        <div class="form-row-2">
            <div class="form-group">
                <label class="form-label">Subir nueva imagen de portada (se convierte automáticamente a WebP)</label>
                <input type="file" name="imagen_portada_file" accept="image/*" class="form-input">
                <?php if (!empty($proyecto['imagen_portada'])): ?>
                    <small class="text-muted">Actual: <code><?= View::e($proyecto['imagen_portada']) ?></code></small>
                <?php endif; ?>
            </div>
            <div class="form-group">
                <label class="form-label">Texto alternativo (ALT obligatorio para accesibilidad y SEO) *</label>
                <input type="text" name="imagen_portada_alt" class="form-input" value="<?= View::e($proyecto['imagen_portada_alt'] ?? '') ?>" required>
            </div>
        </div>

        <div class="form-row-2">
            <div class="form-group">
                <label class="form-label">Agregar imágenes a la galería del caso (múltiple)</label>
                <input type="file" name="galeria_files[]" accept="image/*" multiple class="form-input">
            </div>
            <div class="form-group">
                <label class="form-label">Tipo y ALT para las nuevas imágenes de galería</label>
                <div class="form-row-2">
                    <select name="galeria_tipo_nuevo" class="form-select">
                        <option value="desktop">Desktop</option>
                        <option value="mobile">Mobile</option>
                    </select>
                    <input type="text" name="galeria_alt_nuevo" class="form-input" placeholder="Descripción ALT de las capturas">
                </div>
            </div>
        </div>

        <hr class="admin-hr">
        <h3>Configuración SEO y Publicación</h3>

        <div class="form-row-2">
            <div class="form-group">
                <label class="form-label">Meta Title SEO (<span data-char-count="meta_title">0</span>/65)</label>
                <input type="text" name="meta_title" class="form-input" data-count-target="meta_title" value="<?= View::e($proyecto['meta_title'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label class="form-label">Meta Description SEO (<span data-char-count="meta_desc">0</span>/160)</label>
                <input type="text" name="meta_description" class="form-input" data-count-target="meta_desc" value="<?= View::e($proyecto['meta_description'] ?? '') ?>">
            </div>
        </div>

        <div class="form-row-2">
            <div class="form-group">
                <label class="form-label">Orden de aparición</label>
                <input type="number" name="orden" class="form-input" value="<?= (int)($proyecto['orden'] ?? 1) ?>">
            </div>
            <div class="form-group admin-switches">
                <label class="admin-check-item">
                    <input type="checkbox" name="destacado" value="1" <?= !empty($proyecto['destacado']) ? 'checked' : '' ?>>
                    <span><strong>Mostrar en Inicio (Destacado)</strong></span>
                </label>
                <label class="admin-check-item">
                    <input type="checkbox" name="publicado" value="1" <?= (!isset($proyecto['publicado']) || !empty($proyecto['publicado'])) ? 'checked' : '' ?>>
                    <span><strong>Publicado (Visible en la web)</strong></span>
                </label>
            </div>
        </div>

        <div class="admin-actions-row mt-md">
            <button type="submit" class="btn btn--primary">Guardar Proyecto</button>
            <a href="<?= View::url('admin/proyectos') ?>" class="btn btn--outline">Cancelar</a>
        </div>
    </form>
</div>

