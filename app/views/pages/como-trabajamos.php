<?php
/**
 * Vista: Cómo trabajamos (/como-trabajamos) — Explicación detallada de los 4 pasos y modalidad en todo Uruguay.
 */

use App\Core\View;
use App\Core\Icon;
?>
<?= View::partial('breadcrumbs', ['seo' => $seo]) ?>

<section class="page-hero">
    <div class="container">
        <span class="eyebrow">METODOLOGÍA SIMPLE Y CERCANA</span>
        <h1 class="page-hero__title">Cómo trabajamos con tu pyme de principio a fin</h1>
        <p class="page-hero__subtitle">
            Sabemos que no tenés tiempo para reuniones eternas ni tecnicismos complicados. Nuestro proceso en 4 pasos está pensado para ser ágil, claro y previsible.
        </p>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="process-detailed-grid">
            <article class="process-box reveal-on-scroll">
                <div class="process-box__num">Paso 01</div>
                <h2>Llamada o videollamada inicial</h2>
                <p>Coordinamos una charla de 20 a 30 minutos sin costo (por videollamada para todo Uruguay o presencial en Ciudad de la Costa, Canelones). Me contás a qué se dedica tu negocio, cómo entran hoy las ventas o los pedidos y qué te gustaría destrabar.</p>
                <ul class="check-list-sm">
                    <li><?= Icon::get('check', 16) ?> Sin compromiso ni presión comercial.</li>
                    <li><?= Icon::get('check', 16) ?> Hablás directo conmigo (Alex Carnelli).</li>
                </ul>
            </article>

            <article class="process-box reveal-on-scroll">
                <div class="process-box__num">Paso 02</div>
                <h2>Presupuesto a medida según tus necesidades</h2>
                <p>Con lo conversado, preparo una propuesta detallada donde queda claro qué vamos a hacer, en qué plazos y con qué inversión. No te cobramos paquetes estándar llenos de cosas que tu pyme no necesita.</p>
                <ul class="check-list-sm">
                    <li><?= Icon::get('check', 16) ?> Alcance claro y explicado en español simple.</li>
                    <li><?= Icon::get('check', 16) ?> Podés arrancar por una etapa e ir sumando capas.</li>
                </ul>
            </article>

            <article class="process-box reveal-on-scroll">
                <div class="process-box__num">Paso 03</div>
                <h2>Ejecución con alcance adaptable</h2>
                <p>Nos ponemos a trabajar mostrándote avances concretos. En una pyme las prioridades a veces cambian sobre la marcha: si durante el desarrollo surge una necesidad nueva o querés ajustar el rumbo, adaptamos el trabajo y el presupuesto con total transparencia.</p>
                <ul class="check-list-sm">
                    <li><?= Icon::get('check', 16) ?> Comunicación fluida por WhatsApp y videollamadas cortas.</li>
                    <li><?= Icon::get('check', 16) ?> Revisiones sobre avances reales funcionando.</li>
                </ul>
            </article>

            <article class="process-box reveal-on-scroll">
                <div class="process-box__num">Paso 04</div>
                <h2>Entrega y mantenimiento posterior</h2>
                <p>Publicamos tu sitio web, tienda online o sistema a medida, te explicamos paso a paso cómo usarlo y no desaparecemos: ofrecemos mantenimiento técnico posterior para que todo siga seguro, rápido y actualizado.</p>
                <ul class="check-list-sm">
                    <li><?= Icon::get('check', 16) ?> Capacitación 1 a 1 para vos o tu equipo.</li>
                    <li><?= Icon::get('check', 16) ?> Soporte directo cada vez que necesites actualizar o mejorar algo.</li>
                </ul>
            </article>
        </div>
    </div>
</section>

