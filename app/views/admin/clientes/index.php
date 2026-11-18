<?php
use App\Core\View;
?>
<div class="admin-card">
    <div class="admin-card__header-split">
        <h2 class="admin-card__title">Clientes</h2>
        <a href="<?= View::url('admin/clientes/crear') ?>" class="btn btn--primary btn--sm">+ Nuevo Cliente</a>
    </div>
    <div class="admin-table-wrap mt-md">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Orden</th>
                    <th>Cliente</th>
                    <th>Rubro</th>
                    <th>Visible</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach (($clientes ?? []) as $c): ?>
                    <tr>
                        <td><?= (int)($c['orden'] ?? 1) ?></td>
                        <td><strong><?= View::e($c['nombre']) ?></strong></td>
                        <td><?= View::e($c['rubro']) ?></td>
                        <td><?= !empty($c['visible']) ? '✅ Sí' : 'Oculto' ?></td>
                        <td><a href="<?= View::url('admin/clientes/' . $c['id'] . '/editar') ?>" class="btn btn--primary btn--sm">Editar</a></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

