<?php
/**
 * Vista: Listado CRM de Leads (/admin/leads) con filtros por estado/servicio y exportación CSV.
 */

use App\Core\View;
?>
<div class="admin-card">
    <div class="admin-card__header-split">
        <form action="<?= View::url('admin/leads') ?>" method="GET" class="admin-filters-bar">
            <select name="estado" class="form-select">
                <option value="">Todos los estados</option>
                <?php foreach (['nuevo' => 'Nuevo', 'contactado' => 'Contactado', 'presupuesto_enviado' => 'Presupuesto enviado', 'ganado' => 'Ganado', 'perdido' => 'Perdido'] as $val => $lbl): ?>
                    <option value="<?= $val ?>" <?= ($filtroEstado ?? '') === $val ? 'selected' : '' ?>><?= $lbl ?></option>
                <?php endforeach; ?>
            </select>

            <select name="servicio" class="form-select">
                <option value="">Todos los servicios</option>
                <?php foreach (($servicios ?? []) as $s): ?>
                    <option value="<?= View::e($s['nombre']) ?>" <?= ($filtroServicio ?? '') === $s['nombre'] ? 'selected' : '' ?>>
                        <?= View::e($s['nombre']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <button type="submit" class="btn btn--primary btn--sm">Filtrar</button>
            <a href="<?= View::url('admin/leads') ?>" class="btn btn--outline btn--sm">Limpiar</a>
        </form>

        <a href="<?= View::url('admin/leads/exportar-csv') ?>" class="btn btn--outline btn--sm">⬇️ Exportar a CSV</a>
    </div>

    <?php if (empty($leads)): ?>
        <p class="text-muted mt-md">No hay consultas que coincidan con el filtro seleccionado.</p>
    <?php else: ?>
        <div class="admin-table-wrap mt-md">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Fecha</th>
                        <th>Cliente</th>
                        <th>Negocio / Rubro</th>
                        <th>WhatsApp</th>
                        <th>Servicios / Plan</th>
                        <th>Origen / UTM</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($leads as $l): ?>
                        <?php
                        $waDigits = preg_replace('/[^0-9]/', '', (string)($l['whatsapp'] ?? ''));
                        if (str_starts_with($waDigits, '09')) {
                            $waDigits = '598' . substr($waDigits, 1);
                        }
                        $waDirect = 'https://wa.me/' . $waDigits . '?text=' . rawurlencode('Hola ' . $l['nombre'] . ', soy Alex de Carnelli Studio. Recibí tu consulta por ' . $l['negocio'] . '.');
                        ?>
                        <tr>
                            <td>#<?= (int)$l['id'] ?></td>
                            <td><?= View::e(substr((string)($l['created_at'] ?? ''), 0, 16)) ?></td>
                            <td><strong><?= View::e($l['nombre']) ?></strong></td>
                            <td><?= View::e($l['negocio']) ?> <?= !empty($l['rubro']) ? '<small>(' . View::e($l['rubro']) . ')</small>' : '' ?></td>
                            <td>
                                <a href="<?= View::e($waDirect) ?>" target="_blank" class="link-wa">💬 <?= View::e($l['whatsapp']) ?></a>
                            </td>
                            <td>
                                <?= View::e(implode(', ', $l['servicios_interes'] ?? [])) ?>
                                <?php if (!empty($l['plan_interes'])): ?>
                                    <br><small><strong><?= View::e($l['plan_interes']) ?></strong></small>
                                <?php endif; ?>
                            </td>
                            <td>
                                <small><?= View::e($l['pagina_origen'] ?? '/') ?></small>
                                <?php if (!empty($l['utm_source'])): ?>
                                    <br><span class="chip chip--layer1"><?= View::e($l['utm_source']) ?></span>
                                <?php endif; ?>
                            </td>
                            <td><span class="status-pill status-pill--<?= View::e($l['estado'] ?? 'nuevo') ?>"><?= View::e($l['estado'] ?? 'nuevo') ?></span></td>
                            <td>
                                <a href="<?= View::url('admin/leads/' . $l['id']) ?>" class="btn btn--primary btn--sm">Ver / Gestionar</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

