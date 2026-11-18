<?php
use App\Core\View;
?>
<div class="admin-card">
    <div class="admin-card__header-split">
        <h2 class="admin-card__title">Artículos del Blog (3 borradores iniciales listos para revisar)</h2>
        <a href="<?= View::url('admin/blog/crear') ?>" class="btn btn--primary btn--sm">+ Nuevo Artículo</a>
    </div>

    <div class="admin-table-wrap mt-md">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Título</th>
                    <th>Categoría</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach (($posts ?? []) as $p): ?>
                    <tr>
                        <td><strong><?= View::e($p['titulo']) ?></strong></td>
                        <td><?= View::e($p['categoria'] ?? '') ?></td>
                        <td>
                            <?php if (!empty($p['publicado'])): ?>
                                <span class="status-pill status-pill--ganado">Publicado</span>
                            <?php else: ?>
                                <span class="status-pill status-pill--nuevo">Borrador (Revisar)</span>
                            <?php endif; ?>
                        </td>
                        <td class="admin-actions-cell">
                            <a href="<?= View::url('admin/blog/' . $p['id'] . '/editar') ?>" class="btn btn--primary btn--sm">Editar / Publicar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

