<?php
use App\Core\View;
?>
<div class="admin-card">
    <h2 class="admin-card__title">Planes publicados en /planes</h2>
    <div class="admin-table-wrap mt-md">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Nivel</th>
                    <th>Capas</th>
                    <th>Ítems incluidos</th>
                    <th>Destacado</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach (($planes ?? []) as $pl): ?>
                    <tr>
                        <td><strong>Plan <?= View::e($pl['nombre']) ?></strong></td>
                        <td><?= View::e($pl['capas_incluidas']) ?></td>
                        <td><?= count($pl['items_incluidos'] ?? []) ?> ítems</td>
                        <td><?= !empty($pl['destacado']) ? '⭐ Sí' : 'No' ?></td>
                        <td><a href="<?= View::url('admin/planes/' . $pl['id'] . '/editar') ?>" class="btn btn--primary btn--sm">Editar</a></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

