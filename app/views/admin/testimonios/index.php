<?php
use App\Core\View;
use App\Core\Csrf;
?>
<div class="admin-card">
    <div class="admin-card__header-split">
        <h2 class="admin-card__title">Testimonios de Clientes</h2>
        <a href="<?= View::url('admin/testimonios/crear') ?>" class="btn btn--primary btn--sm">+ Agregar Testimonio Real</a>
    </div>
    <?php if (empty($testimonios)): ?>
        <p class="text-muted mt-md">No hay testimonios cargados todavía (la sección de testimonios se mantiene oculta en la web hasta que publiques uno real).</p>
    <?php else: ?>
        <div class="admin-table-wrap mt-md">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Cargo / Negocio</th>
                        <th>Publicado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($testimonios as $t): ?>
                        <tr>
                            <td><strong><?= View::e($t['nombre']) ?></strong></td>
                            <td><?= View::e($t['cargo_negocio'] ?? '') ?></td>
                            <td><?= !empty($t['publicado']) ? '✅ Sí' : 'Oculto' ?></td>
                            <td class="admin-actions-cell">
                                <a href="<?= View::url('admin/testimonios/' . $t['id'] . '/editar') ?>" class="btn btn--primary btn--sm">Editar</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

