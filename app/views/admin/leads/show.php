<?php
/**
 * Vista: Detalle y seguimiento CRM de un Lead (/admin/leads/{id}).
 */

use App\Core\View;
use App\Core\Csrf;

$waDigits = preg_replace('/[^0-9]/', '', (string)($lead['whatsapp'] ?? ''));
if (str_starts_with($waDigits, '09')) {
    $waDigits = '598' . substr($waDigits, 1);
}
$waMsg = 'Hola ' . ($lead['nombre'] ?? '') . ', soy Alex de Carnelli Studio. Recibí tu consulta sobre ' . implode(', ', $lead['servicios_interes'] ?? []) . ' para ' . ($lead['negocio'] ?? 'tu negocio') . '. ¿Cómo estás?';
$waUrl = 'https://wa.me/' . $waDigits . '?text=' . rawurlencode($waMsg);
?>
<div class="admin-grid-2">
    <div class="admin-card">
        <h2 class="admin-card__title">Datos de la consulta</h2>
        <dl class="admin-dl">
            <dt>Fecha de ingreso:</dt>
            <dd><?= View::e($lead['created_at'] ?? '') ?></dd>

            <dt>Nombre:</dt>
            <dd><strong><?= View::e($lead['nombre'] ?? '') ?></strong></dd>

            <dt>Negocio y rubro:</dt>
            <dd><?= View::e($lead['negocio'] ?? '') ?> <?= !empty($lead['rubro']) ? '(' . View::e($lead['rubro']) . ')' : '' ?></dd>

            <dt>Situación actual:</dt>
            <dd><?= View::e($lead['situacion_actual'] ?: 'No especificada') ?></dd>

            <dt>WhatsApp:</dt>
            <dd><strong><?= View::e($lead['whatsapp'] ?? '') ?></strong></dd>

            <dt>Email:</dt>
            <dd><?= View::e($lead['email'] ?: 'No proporcionado') ?></dd>

            <dt>Preferencia de contacto:</dt>
            <dd><?= View::e($lead['preferencia_contacto'] ?? 'whatsapp') ?></dd>

            <dt>Servicios de interés:</dt>
            <dd><?= View::e(implode(', ', $lead['servicios_interes'] ?? [])) ?></dd>

            <?php if (!empty($lead['plan_interes'])): ?>
                <dt>Plan consultado:</dt>
                <dd><?= View::e($lead['plan_interes']) ?></dd>
            <?php endif; ?>

            <dt>Mensaje del cliente:</dt>
            <dd><?= nl2br(View::e($lead['mensaje'] ?: 'Sin mensaje adicional')) ?></dd>

            <dt>Página de origen y UTMs:</dt>
            <dd>
                Página: <code><?= View::e($lead['pagina_origen'] ?? '/') ?></code><br>
                UTM Source: <code><?= View::e($lead['utm_source'] ?: '-') ?></code> |
                Medium: <code><?= View::e($lead['utm_medium'] ?: '-') ?></code> |
                Campaign: <code><?= View::e($lead['utm_campaign'] ?: '-') ?></code>
            </dd>
        </dl>

        <div class="admin-actions-row mt-md">
            <a href="<?= View::e($waUrl) ?>" target="_blank" class="btn btn--primary">💬 Abrir WhatsApp con mensaje listo</a>
            <?php if (!empty($lead['email'])): ?>
                <a href="mailto:<?= View::e($lead['email']) ?>?subject=<?= rawurlencode('Presupuesto Carnelli Studio — ' . ($lead['negocio'] ?? '')) ?>" class="btn btn--outline">✉️ Responder por Email</a>
            <?php endif; ?>
        </div>
    </div>

    <div class="admin-card">
        <h2 class="admin-card__title">Seguimiento CRM</h2>
        <form action="<?= View::url('admin/leads/' . $lead['id'] . '/actualizar') ?>" method="POST">
            <?= Csrf::field() ?>
            <div class="form-group">
                <label class="form-label">Estado del Lead</label>
                <select name="estado" class="form-select">
                    <?php foreach (['nuevo' => 'Nuevo', 'contactado' => 'Contactado', 'presupuesto_enviado' => 'Presupuesto enviado', 'ganado' => 'Ganado (Cliente)', 'perdido' => 'Perdido / Descartado'] as $val => $lbl): ?>
                        <option value="<?= $val ?>" <?= ($lead['estado'] ?? 'nuevo') === $val ? 'selected' : '' ?>><?= $lbl ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Notas internas (reunión, presupuesto, próximos pasos)</label>
                <textarea name="notas_admin" rows="6" class="form-textarea" placeholder="Anotá acá el resumen de la llamada o presupuesto enviado..."><?= View::e($lead['notas_admin'] ?? '') ?></textarea>
            </div>

            <button type="submit" class="btn btn--primary">Guardar seguimiento</button>
        </form>

        <hr class="admin-hr">

        <form action="<?= View::url('admin/leads/' . $lead['id'] . '/eliminar') ?>" method="POST" onsubmit="return confirm('¿Seguro que querés eliminar este lead?');">
            <?= Csrf::field() ?>
            <button type="submit" class="btn btn--danger btn--sm">Eliminar lead</button>
        </form>
    </div>
</div>

