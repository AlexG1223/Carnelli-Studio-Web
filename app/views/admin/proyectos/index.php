<?php
/**
 * Vista: Listado de Proyectos en Admin (/admin/proyectos) y Formulario de Creación/Edición.
 */

use App\Core\View;
use App\Core\Csrf;
?>
<div class="admin-card">
    <div class="admin-card__header-split">
        <h2 class="admin-card__title">Casos de estudio cargados</h2>
        <a href="<?= View::url('admin/proyectos/crear') ?>" class="btn btn--primary btn--sm">+ Nuevo Proyecto</a>
    </div>

    <div class="admin-table-wrap mt-md">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Orden</th>
                    <th>Cliente</th>
                    <th>Rubro</th>
                    <th>Servicios vinculados</th>
                    <th>Destacado</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach (($proyectos ?? []) as $p): ?>
                    <?php $hasCompletar = str_contains(($p['titulo'] ?? '') . ($p['resumen_corto'] ?? ''), '[COMPLETAR'); ?>
                    <tr>
                        <td><?= (int)($p['orden'] ?? 1) ?></td>
                        <td>
                            <strong><?= View::e($p['cliente_nombre']) ?></strong>
                            <?php if ($hasCompletar): ?>
                                <span class="badge-pending">[COMPLETAR]</span>
                            <?php endif; ?>
                        </td>
                        <td><?= View::e($p['rubro']) ?></td>
                        <td>
                            <?php foreach (($p['servicios'] ?? []) as $s): ?>
                                <span class="chip chip--layer1"><?= View::e($s['nombre']) ?></span>
                            <?php endforeach; ?>
                        </td>
                        <td><?= !empty($p['destacado']) ? '⭐ Sí' : 'No' ?></td>
                        <td>
                            <?php if (!empty($p['publicado'])): ?>
                                <span class="status-pill status-pill--ganado">Publicado</span>
                            <?php else: ?>
                                <span class="status-pill status-pill--nuevo">Borrador</span>
                            <?php endif; ?>
                        </td>
                        <td class="admin-actions-cell">
                            <a href="<?= View::url('admin/proyectos/' . $p['id'] . '/editar') ?>" class="btn btn--primary btn--sm">Editar</a>
                            <?php if (!empty($p['publicado'])): ?>
                                <a href="<?= View::url('proyectos/' . $p['slug']) ?>" target="_blank" class="btn btn--outline btn--sm">↗ Ver</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

