<?php
use App\Core\View;
?>
<div class="admin-card">
    <div class="admin-card__header-split">
        <h2 class="admin-card__title">Servicios por Capa</h2>
        <a href="<?= View::url('admin/servicios/crear') ?>" class="btn btn--primary btn--sm">+ Nuevo Servicio</a>
    </div>
    <div class="admin-table-wrap mt-md">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Orden</th>
                    <th>Capa</th>
                    <th>Servicio</th>
                    <th>Slug</th>
                    <th>Activo</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach (($servicios ?? []) as $s): ?>
                    <tr>
                        <td><?= (int)($s['orden'] ?? 1) ?></td>
                        <td><span class="chip <?= ((int)$s['capa'] === 2) ? 'chip--layer2' : 'chip--layer1' ?>">Capa <?= (int)$s['capa'] ?></span></td>
                        <td><strong><?= View::e($s['nombre']) ?></strong></td>
                        <td><code>/servicios/<?= View::e($s['slug']) ?></code></td>
                        <td><?= !empty($s['activo']) ? '✅ Sí' : 'Oculto' ?></td>
                        <td class="admin-actions-cell">
                            <a href="<?= View::url('admin/servicios/' . $s['id'] . '/editar') ?>" class="btn btn--primary btn--sm">Editar</a>
                            <a href="<?= View::url('servicios/' . $s['slug']) ?>" target="_blank" class="btn btn--outline btn--sm">↗ Ver</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

