<?php
use App\Core\View;
use App\Core\Csrf;
?>
<div class="admin-card">
    <div class="admin-card__header-split">
        <h2 class="admin-card__title">Preguntas Frecuentes</h2>
        <a href="<?= View::url('admin/faqs/crear') ?>" class="btn btn--primary btn--sm">+ Nueva Pregunta</a>
    </div>
    <div class="admin-table-wrap mt-md">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Orden</th>
                    <th>Pregunta</th>
                    <th>En Inicio</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach (($faqs ?? []) as $f): ?>
                    <tr>
                        <td><?= (int)($f['orden'] ?? 1) ?></td>
                        <td><strong><?= View::e($f['pregunta']) ?></strong></td>
                        <td><?= !empty($f['mostrar_en_home']) ? 'Sí' : 'No' ?></td>
                        <td class="admin-actions-cell">
                            <a href="<?= View::url('admin/faqs/' . $f['id'] . '/editar') ?>" class="btn btn--primary btn--sm">Editar</a>
                            <form action="<?= View::url('admin/faqs/' . $f['id'] . '/eliminar') ?>" method="POST" style="display:inline;" onsubmit="return confirm('¿Eliminar FAQ?');">
                                <?= Csrf::field() ?>
                                <button type="submit" class="btn btn--danger btn--sm">×</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

