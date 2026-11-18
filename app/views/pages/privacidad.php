<?php
/**
 * Vista: Política de Privacidad según Ley N° 18.331 de Protección de Datos Personales (Uruguay).
 */

use App\Core\View;
?>
<?= View::partial('breadcrumbs', ['seo' => $seo]) ?>

<section class="section">
    <div class="container container--narrow">
        <span class="eyebrow">INFORMACIÓN LEGAL · REPÚBLICA ORIENTAL DEL URUGUAY</span>
        <h1 class="page-hero__title">Política de Privacidad y Protección de Datos Personales</h1>

        <div class="rich-text mt-md">
            <p>En <strong>Carnelli Studio</strong> (responsable: <strong>Alex Carnelli</strong>), valoramos la confianza de nuestros clientes y visitantes, y garantizamos el estricto cumplimiento de la <strong>Ley N° 18.331 de Protección de Datos Personales y Acción de Habeas Data</strong> de la República Oriental del Uruguay y sus decretos reglamentarios.</p>

            <h2>1. Datos que recopilamos</h2>
            <p>Únicamente recolectamos los datos que vos nos proporcionás de forma voluntaria a través de nuestros formularios de contacto o canales directos (WhatsApp y correo electrónico): nombre, nombre de tu negocio o rubro, teléfono/WhatsApp, dirección de correo electrónico y detalles sobre el servicio que consultás.</p>

            <h2>2. Finalidad del tratamiento</h2>
            <p>Los datos recabados se utilizan exclusivamente para:</p>
            <ul>
                <li>Responder a tus consultas y elaborar presupuestos a medida para tu pyme.</li>
                <li>Coordinar llamadas o videollamadas de asesoramiento.</li>
                <li>Brindar soporte y seguimiento sobre los servicios contratados.</li>
            </ul>
            <p><strong>No vendemos, alquilamos ni cedemos tus datos personales a terceros bajo ninguna circunstancia.</strong></p>

            <h2>3. Ejercicio de tus derechos (Acceso, Rectificación, Actualización y Supresión)</h2>
            <p>Como titular de los datos personales, tenés derecho a solicitar en cualquier momento el acceso, rectificación, actualización o eliminación de tu información escribiendo a <a href="mailto:<?= View::e($settings['email_contacto'] ?? 'carnellistudio2026@gmail.com') ?>"><?= View::e($settings['email_contacto'] ?? 'carnellistudio2026@gmail.com') ?></a> o por WhatsApp al <?= View::e($settings['whatsapp_visible'] ?? '092 473 724') ?>.</p>

            <h2>4. Seguridad y Cookies</h2>
            <p>Aplicamos medidas técnicas de seguridad para proteger la información almacenada contra accesos no autorizados. Este sitio utiliza cookies técnicas de sesión y analítica anónima para medir el rendimiento de nuestras páginas y mejorar la experiencia del usuario.</p>
        </div>
    </div>
</section>

