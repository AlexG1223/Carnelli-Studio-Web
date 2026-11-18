<?php
/**
 * Vista: Dashboard Administrativo (/admin) con KPIs, recordatorio de campos [COMPLETAR],
 * gráficas Chart.js (Leads por mes y por servicio) y últimos contactos.
 */

use App\Core\View;

$byMonth   = $leadStats['by_month'] ?? [];
$byService = $leadStats['by_service'] ?? [];
$byStatus  = $leadStats['by_status'] ?? [];
?>
<!-- Alerta recordatorio de los [COMPLETAR] pendientes según el prompt -->
<div class="admin-pending-banner">
    <div>
        <strong>📌 Marcadores <code>[COMPLETAR]</code> listos para cuando tengas los datos:</strong>
        <ul class="admin-pending-list">
            <li><?php if (empty($settings['facebook_url'])): ?><span class="badge-pending">[COMPLETAR]</span> Facebook y LinkedIn (ocultos automáticamente en la web hasta que los cargues)<?php else: ?>✅ Facebook configurado<?php endif; ?></li>
            <li><?php if (empty($settings['agenda_url'])): ?><span class="badge-pending">[COMPLETAR]</span> Link para agendar videollamada (Google Calendar / Calendly)<?php else: ?>✅ Link de agenda configurado<?php endif; ?></li>
            <li><?php if (empty($settings['foto_fundador'])): ?><span class="badge-pending">[COMPLETAR]</span> Foto de Alex Carnelli para <code>/quien-soy</code><?php else: ?>✅ Foto de fundador cargada<?php endif; ?></li>
            <li><span class="badge-pending">[COMPLETAR]</span> Caso de estudio <strong>FisioLink</strong> (creado en borrador en <a href="<?= View::url('admin/proyectos') ?>">Proyectos</a>) y métricas/testimonios reales.</li>
        </ul>
    </div>
    <a href="<?= View::url('admin/ajustes') ?>" class="btn btn--primary btn--sm">Completar en Ajustes →</a>
</div>

<!-- KPIs -->
<div class="admin-kpis">
    <div class="admin-kpi-card">
        <span class="admin-kpi-card__label">Leads Nuevos</span>
        <strong class="admin-kpi-card__val"><?= (int)($byStatus['nuevo'] ?? 0) ?></strong>
        <a href="<?= View::url('admin/leads?estado=nuevo') ?>">Ver nuevos →</a>
    </div>
    <div class="admin-kpi-card">
        <span class="admin-kpi-card__label">Total Consultas (CRM)</span>
        <strong class="admin-kpi-card__val"><?= (int)($leadStats['total'] ?? 0) ?></strong>
        <a href="<?= View::url('admin/leads') ?>">Ir al CRM →</a>
    </div>
    <div class="admin-kpi-card">
        <span class="admin-kpi-card__label">Proyectos Publicados</span>
        <strong class="admin-kpi-card__val"><?= (int)$proyectosPub ?> / <?= (int)$totalProyectos ?></strong>
        <a href="<?= View::url('admin/proyectos') ?>">Gestionar portfolio →</a>
    </div>
    <div class="admin-kpi-card">
        <span class="admin-kpi-card__label">Servicios Activos</span>
        <strong class="admin-kpi-card__val"><?= (int)$totalServicios ?></strong>
        <a href="<?= View::url('admin/servicios') ?>">Editar las 2 capas →</a>
    </div>
</div>

<!-- Gráficas Chart.js -->
<div class="admin-charts-grid">
    <div class="admin-card">
        <h2 class="admin-card__title">Consultas recibidas por mes</h2>
        <canvas id="chartLeadsMonth" height="210"></canvas>
    </div>
    <div class="admin-card">
        <h2 class="admin-card__title">Consultas por servicio de interés</h2>
        <canvas id="chartLeadsService" height="210"></canvas>
    </div>
</div>

<!-- Últimos mensajes -->
<div class="admin-card mt-md">
    <div class="admin-card__header-split">
        <h2 class="admin-card__title">Últimas consultas recibidas</h2>
        <a href="<?= View::url('admin/leads') ?>" class="btn btn--outline btn--sm">Ver todos los leads</a>
    </div>

    <?php if (empty($ultimosLeads)): ?>
        <p class="text-muted">Aún no se recibieron consultas desde los formularios públicos. Podés hacer una prueba desde <a href="<?= View::url('contacto') ?>" target="_blank">/contacto</a>.</p>
    <?php else: ?>
        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Nombre</th>
                        <th>Negocio</th>
                        <th>WhatsApp</th>
                        <th>Servicios</th>
                        <th>Estado</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($ultimosLeads as $l): ?>
                        <tr>
                            <td><?= View::e(substr((string)($l['created_at'] ?? ''), 0, 16)) ?></td>
                            <td><strong><?= View::e($l['nombre']) ?></strong></td>
                            <td><?= View::e($l['negocio']) ?></td>
                            <td><?= View::e($l['whatsapp']) ?></td>
                            <td><?= View::e(implode(', ', $l['servicios_interes'] ?? [])) ?></td>
                            <td><span class="status-pill status-pill--<?= View::e($l['estado'] ?? 'nuevo') ?>"><?= View::e($l['estado'] ?? 'nuevo') ?></span></td>
                            <td><a href="<?= View::url('admin/leads/' . $l['id']) ?>" class="btn btn--outline btn--sm">Abrir</a></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    if (typeof Chart === 'undefined') return;
    const monthLabels = <?= json_encode(array_keys($byMonth)) ?>;
    const monthData   = <?= json_encode(array_values($byMonth)) ?>;
    new Chart(document.getElementById('chartLeadsMonth'), {
        type: 'bar',
        data: {
            labels: monthLabels,
            datasets: [{
                label: 'Leads por mes',
                data: monthData,
                backgroundColor: '#1E6FE0',
                borderRadius: 6
            }]
        },
        options: { responsive: true, plugins: { legend: { display: false } } }
    });

    const srvLabels = <?= json_encode(array_keys($byService) ?: ['Sin datos aún']) ?>;
    const srvData   = <?= json_encode(array_values($byService) ?: [0]) ?>;
    new Chart(document.getElementById('chartLeadsService'), {
        type: 'doughnut',
        data: {
            labels: srvLabels,
            datasets: [{
                data: srvData,
                backgroundColor: ['#0D3B8C', '#1E6FE0', '#3B82F6', '#60A5FA', '#93C5FD']
            }]
        },
        options: { responsive: true }
    });
});
</script>

