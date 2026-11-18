<?php
/**
 * Vista: Quién soy (/quien-soy) — En primera persona (Alex Carnelli), cálido, directo y con E-E-A-T fuerte.
 */

use App\Core\View;
use App\Core\Icon;

$fotoFundador = trim((string)($settings['foto_fundador'] ?? ''));
?>
<?= View::partial('breadcrumbs', ['seo' => $seo]) ?>

<section class="page-hero">
    <div class="container about-hero-grid">
        <div class="about-hero__copy">
            <span class="eyebrow">QUIÉN ESTÁ DETRÁS DE CARNELLI STUDIO</span>
            <h1 class="page-hero__title">Hola, soy Alex Carnelli</h1>
            <p class="page-hero__subtitle">
                Soy desarrollador full-stack (PHP, JavaScript y MySQL) y fundador de <strong>Carnelli Studio</strong>. Hoy llevo adelante la agencia de forma personal, trabajando codo a codo con dueños de pymes de todo Uruguay.
            </p>
            <div class="about-hero__badges">
                <span class="chip chip--layer1">Desarrollador Full-Stack</span>
                <span class="chip chip--layer2">Sistemas a Medida & IA</span>
                <span class="chip chip--layer1">Estrategia Digital para Pymes</span>
            </div>
        </div>

        <div class="about-hero__photo-wrap">
            <?php if ($fotoFundador !== '' && !str_contains($fotoFundador, '[COMPLETAR')): ?>
                <img src="<?= View::asset($fotoFundador) ?>" alt="Alex Carnelli — Fundador de Carnelli Studio" class="about-hero__photo" width="420" height="480">
            <?php else: ?>
                <div class="founder-card-placeholder">
                    <img src="<?= View::asset('assets/brand/carnelli-isotipo-color-transparente.svg') ?>" alt="Carnelli Studio" width="96" height="96">
                    <strong>Alex Carnelli</strong>
                    <span>Fundador & Desarrollador Full-Stack</span>
                    <small>Ciudad de la Costa (Canelones) · Uruguay</small>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<section class="section">
    <div class="container container--narrow">
        <div class="rich-text about-story">
            <h2>Por qué creé Carnelli Studio</h2>
            <p>
                Trabajando con comercios y pymes uruguayas me encontré siempre con el mismo problema: por un lado, muchas agencias de marketing hacen publicaciones lindas para Instagram, pero cuando el negocio necesita una tienda online seria, conectar una pasarela de pago o armar un sistema para no perder pedidos, no tienen equipo técnico propio.
            </p>
            <p>
                Por otro lado, muchos programadores hablan en un lenguaje lleno de siglas difíciles y entregan sistemas que no están pensados para vender ni para el ritmo real de un comercio.
            </p>
            <p>
                <strong>Carnelli Studio nació para unir esas dos capas:</strong> que tengas en un solo lugar la presencia digital que atrae clientes (redes, anuncios, Google Maps, SEO y posicionamiento en IA) y el desarrollo técnico a medida (webs rápidas, tiendas online y sistemas con inteligencia artificial) que ordena tu negocio por dentro.
            </p>

            <h2>Qué significa trabajar directo conmigo</h2>
            <ul>
                <li><strong>Cero teléfono descompuesto:</strong> Cuando me contás cómo funciona tu negocio en la primera videollamada, estás hablando con la misma persona que va a diseñar, programar y configurar tu proyecto.</li>
                <li><strong>Soluciones reales antes que herramientas de moda:</strong> Primero entiendo dónde se te va el tiempo o dónde se frenan tus ventas, y recién ahí elijo la tecnología más simple y efectiva para resolverlo.</li>
                <li><strong>Acompañamiento a largo plazo:</strong> No entrego una web y desaparezco. Me encargo del mantenimiento para que vos te dediques a hacer crecer tu pyme con tranquilidad.</li>
            </ul>
        </div>

        <div class="about-cta-card">
            <h3>¿Conversamos sobre tu negocio?</h3>
            <p>Escribime por WhatsApp o pedí tu presupuesto y coordinamos una videollamada sin costo (o nos vemos en Ciudad de la Costa).</p>
            <div class="about-cta-card__btns">
                <a href="<?= View::url('contacto') ?>" class="btn btn--primary">Pedí tu presupuesto</a>
                <a href="<?= View::e(View::whatsappUrl($whatsappMessage ?? '')) ?>" target="_blank" rel="noopener" class="btn btn--outline" data-track="whatsapp">
                    <?= Icon::get('whatsapp', 18) ?> Escribirme por WhatsApp
                </a>
            </div>
        </div>
    </div>
</section>

