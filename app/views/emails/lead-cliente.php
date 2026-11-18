<?php
/**
 * Plantilla de correo de confirmación automática al cliente.
 * Cálido, en primera persona de Alex Carnelli y con próximos pasos claros.
 * @var array $lead
 * @var array $settings
 */

use App\Core\View;

$waUrl = View::whatsappUrl('Hola Alex, recién mandé la consulta desde la web por ' . ($lead['negocio'] ?? 'mi negocio') . '.');
?>
<!DOCTYPE html>
<html lang="es-UY">
<head>
    <meta charset="UTF-8">
</head>
<body style="margin:0;padding:24px;background-color:#FAFAFA;font-family:'Open Sans',Arial,sans-serif;color:#1A1A1A;">
    <div style="max-width:600px;margin:0 auto;background:#FFFFFF;border-radius:16px;overflow:hidden;border:1px solid #E2E8F0;">
        <div style="background:linear-gradient(135deg,#0D3B8C 0%,#1E6FE0 55%,#3B82F6 100%);padding:28px 32px;color:#FFFFFF;">
            <h1 style="margin:0;font-size:22px;font-weight:700;">¡Hola <?= View::e($lead['nombre'] ?? '') ?>! Recibí tu consulta</h1>
            <p style="margin:8px 0 0;font-size:15px;opacity:0.92;">Carnelli Studio · «Diseñamos el crecimiento digital de tu pyme»</p>
        </div>
        <div style="padding:28px 32px;line-height:1.6;font-size:15px;">
            <p>Gracias por escribirme. Ya tengo registrados los datos de <strong><?= View::e($lead['negocio'] ?? 'tu negocio') ?></strong> y lo que te interesa trabajar (<em><?= View::e(implode(', ', $lead['servicios_interes'] ?? [])) ?></em>).</p>

            <div style="background:#F0F6FF;border-left:4px solid #1E6FE0;padding:16px 20px;border-radius:8px;margin:20px 0;">
                <p style="margin:0;font-weight:700;color:#0D3B8C;">¿Cuáles son los próximos pasos?</p>
                <ol style="margin:10px 0 0;padding-left:20px;">
                    <li>Reviso tu caso y te escribo personalmente en <strong>menos de 24 horas hábiles</strong> por <strong><?= View::e($lead['preferencia_contacto'] ?? 'WhatsApp') ?></strong>.</li>
                    <li>Coordinamos una llamada o videollamada corta sin costo para entender bien qué necesita tu pyme.</li>
                    <li>Te armo un presupuesto claro y a medida.</li>
                </ol>
            </div>

            <p>Si querés adelantarme algún detalle o preferís que conversemos ya mismo, podés escribirme directo a mi WhatsApp tocando el botón de abajo:</p>

            <p style="margin:24px 0;">
                <a href="<?= View::e($waUrl) ?>" style="display:inline-block;background:#1E6FE0;color:#FFFFFF;text-decoration:none;font-weight:700;padding:13px 24px;border-radius:10px;">
                    Hablar con Alex por WhatsApp (<?= View::e($settings['whatsapp_visible'] ?? '092 473 724') ?>)
                </a>
            </p>

            <p style="margin-top:28px;color:#475569;">
                Un saludo grande,<br>
                <strong>Alex Carnelli</strong><br>
                Fundador · Carnelli Studio (Uruguay)
            </p>
        </div>
    </div>
</body>
</html>

