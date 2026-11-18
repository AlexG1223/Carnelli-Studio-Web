<?php
/**
 * Plantilla de correo de aviso para Alex Carnelli al entrar un nuevo lead.
 * Incluye botón directo para responderle al cliente por WhatsApp en un clic.
 * @var array $lead
 */

use App\Core\View;

$waDigits = preg_replace('/[^0-9]/', '', (string)($lead['whatsapp'] ?? ''));
if (str_starts_with($waDigits, '09')) {
    $waDigits = '598' . substr($waDigits, 1);
}
$waReplyMsg = 'Hola ' . ($lead['nombre'] ?? '') . ', soy Alex de Carnelli Studio. Recibí tu consulta sobre ' . implode(', ', $lead['servicios_interes'] ?? []) . ' para ' . ($lead['negocio'] ?? 'tu negocio') . '. ¿Cómo estás?';
$waReplyUrl = 'https://wa.me/' . $waDigits . '?text=' . rawurlencode($waReplyMsg);
$adminLeadUrl = View::url('admin/leads/' . ($lead['id'] ?? ''));
?>
<!DOCTYPE html>
<html lang="es-UY">
<head>
    <meta charset="UTF-8">
</head>
<body style="margin:0;padding:24px;background-color:#FAFAFA;font-family:'Open Sans',Arial,sans-serif;color:#1A1A1A;">
    <div style="max-width:600px;margin:0 auto;background:#FFFFFF;border-radius:16px;overflow:hidden;border:1px solid #E2E8F0;">
        <div style="background:linear-gradient(135deg,#0D3B8C 0%,#1E6FE0 55%,#3B82F6 100%);padding:24px 32px;color:#FFFFFF;">
            <p style="margin:0 0 6px;font-size:12px;letter-spacing:1.5px;text-transform:uppercase;opacity:0.9;">NUEVO LEAD EN CARNELLI STUDIO</p>
            <h1 style="margin:0;font-size:22px;font-weight:700;"><?= View::e($lead['nombre'] ?? '') ?> · <?= View::e($lead['negocio'] ?? '') ?></h1>
        </div>
        <div style="padding:28px 32px;">
            <table style="width:100%;border-collapse:collapse;font-size:15px;">
                <tr>
                    <td style="padding:8px 0;color:#64748B;width:160px;"><strong>Nombre:</strong></td>
                    <td style="padding:8px 0;"><?= View::e($lead['nombre'] ?? '') ?></td>
                </tr>
                <tr>
                    <td style="padding:8px 0;color:#64748B;"><strong>Negocio / Rubro:</strong></td>
                    <td style="padding:8px 0;"><?= View::e($lead['negocio'] ?? '') ?> <?= !empty($lead['rubro']) ? '(' . View::e($lead['rubro']) . ')' : '' ?></td>
                </tr>
                <tr>
                    <td style="padding:8px 0;color:#64748B;"><strong>Situación actual:</strong></td>
                    <td style="padding:8px 0;"><?= View::e($lead['situacion_actual'] ?: 'No especificada') ?></td>
                </tr>
                <tr>
                    <td style="padding:8px 0;color:#64748B;"><strong>WhatsApp:</strong></td>
                    <td style="padding:8px 0;"><strong><?= View::e($lead['whatsapp'] ?? '') ?></strong></td>
                </tr>
                <tr>
                    <td style="padding:8px 0;color:#64748B;"><strong>Email:</strong></td>
                    <td style="padding:8px 0;"><?= View::e($lead['email'] ?: 'No indicado') ?></td>
                </tr>
                <tr>
                    <td style="padding:8px 0;color:#64748B;"><strong>Prefiere contacto:</strong></td>
                    <td style="padding:8px 0;text-transform:capitalize;"><?= View::e($lead['preferencia_contacto'] ?? 'whatsapp') ?></td>
                </tr>
                <tr>
                    <td style="padding:8px 0;color:#64748B;"><strong>Servicios de interés:</strong></td>
                    <td style="padding:8px 0;color:#0D3B8C;font-weight:600;"><?= View::e(implode(', ', $lead['servicios_interes'] ?? [])) ?></td>
                </tr>
                <?php if (!empty($lead['plan_interes'])): ?>
                <tr>
                    <td style="padding:8px 0;color:#64748B;"><strong>Plan seleccionado:</strong></td>
                    <td style="padding:8px 0;"><?= View::e($lead['plan_interes']) ?></td>
                </tr>
                <?php endif; ?>
                <tr>
                    <td style="padding:8px 0;color:#64748B;"><strong>Mensaje:</strong></td>
                    <td style="padding:8px 0;"><?= nl2br(View::e($lead['mensaje'] ?: 'Sin mensaje adicional')) ?></td>
                </tr>
                <tr>
                    <td style="padding:8px 0;color:#64748B;"><strong>Origen / UTM:</strong></td>
                    <td style="padding:8px 0;font-size:13px;color:#64748B;">
                        Página: <?= View::e($lead['pagina_origen'] ?? '/') ?>
                        <?php if (!empty($lead['utm_source'])): ?>
                            | UTM: <?= View::e($lead['utm_source']) ?> / <?= View::e($lead['utm_medium']) ?> / <?= View::e($lead['utm_campaign']) ?>
                        <?php endif; ?>
                    </td>
                </tr>
            </table>

            <div style="margin-top:28px;display:flex;gap:12px;">
                <a href="<?= View::e($waReplyUrl) ?>" style="display:inline-block;background:#16A34A;color:#FFFFFF;text-decoration:none;font-weight:700;padding:12px 22px;border-radius:10px;margin-right:10px;">
                    💬 Responder por WhatsApp
                </a>
                <a href="<?= View::e($adminLeadUrl) ?>" style="display:inline-block;background:#1E6FE0;color:#FFFFFF;text-decoration:none;font-weight:700;padding:12px 22px;border-radius:10px;">
                    Ver en el Panel Admin
                </a>
            </div>
        </div>
    </div>
</body>
</html>

