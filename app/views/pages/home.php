<?php
/**
 * Vista: Inicio (/) con las 10 secciones clave, diseño asimétrico y foco en conversión y claridad.
 */

use App\Core\View;
use App\Core\Icon;
?>
<!-- 1. HERO CON IMPACTO Y VISUAL INTERACTIVO DE MARCA -->
<section class="hero" aria-labelledby="hero-title">
    <div class="hero__bg-mesh" aria-hidden="true"></div>
    <div class="container hero__grid">
        <div class="hero__content">
            <div class="hero__pill">
                <span class="hero__pill-dot"></span>
                <span>Agencia de transformación digital para pymes uruguayas</span>
            </div>

            <h1 id="hero-title" class="hero__title">
                <?= View::e($settings['tagline'] ?? 'Diseñamos el crecimiento digital de tu pyme') ?>
            </h1>

            <p class="hero__subtitle">
                <?= View::e($settings['hero_subtitulo'] ?? 'Ordenamos tus redes, creamos tu sitio web o tienda online y desarrollamos sistemas a medida con inteligencia artificial que te ahorran horas de trabajo todas las semanas.') ?>
            </p>

            <div class="hero__actions">
                <a href="<?= View::url('contacto') ?>" class="btn btn--primary btn--lg btn--magnetic">
                    <span>Pedí tu presupuesto</span>
                    <?= Icon::get('arrow-right', 18) ?>
                </a>
                <a href="<?= View::e(View::whatsappUrl($whatsappMessage ?? '')) ?>" target="_blank" rel="noopener"
                    class="btn btn--secondary btn--lg" data-track="whatsapp">
                    <?= Icon::get('whatsapp', 20) ?>
                    <span>Hablemos por WhatsApp</span>
                </a>
            </div>

            <ul class="hero__trust-list">
                <li><?= Icon::get('check', 16) ?> <span>Trato directo con quien diseña y programa</span></li>
                <li><?= Icon::get('check', 16) ?> <span>Código propio y a medida (sin plantillas lentas)</span></li>
                <li><?= Icon::get('check', 16) ?> <span>Presencial en Ciudad de la Costa · Remoto en todo Uruguay</span>
                </li>
            </ul>
        </div>

        <div class="hero__visual" aria-hidden="true">
            <div class="hero-showcase">
                <div class="hero-showcase__card hero-showcase__card--layer1">
                    <span class="hero-showcase__badge">Capa 1 · Puerta de entrada</span>
                    <strong>Marketing y Presencia Digital</strong>
                    <p>Redes sociales, Reels, Meta/Google Ads, SEO/GEO y Google Maps.</p>
                </div>

                <div class="hero-showcase__emblem">
                    <img src="<?= View::asset('assets/brand/carnelli-isotipo-color-transparente.svg') ?>" alt=""
                        width="132" height="132" class="hero-showcase__isotipo">
                </div>

                <div class="hero-showcase__card hero-showcase__card--layer2">
                    <span class="hero-showcase__badge hero-showcase__badge--accent">Capa 2 · Nuestro diferencial</span>
                    <strong>Sistemas, Webs, Tiendas e IA</strong>
                    <p>Tiendas con Mercado Pago, sistemas de pedidos a medida y automatización con IA.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 2. FRANJA DE CLIENTES (MARQUEE INFINITO PAUSABLE) -->
<section class="clients-strip" aria-label="Empresas que confían en Carnelli Studio">
    <div class="container clients-strip__inner">
        <span class="clients-strip__label">Pymes uruguayas que ya digitalizamos:</span>
        <div class="marquee" tabindex="0">
            <div class="marquee__track">
                <?php for ($loop = 0; $loop < 2; $loop++): ?>
                    <?php foreach (($clientes ?? []) as $cl): ?>
                        <?php if ($cl['slug'] === 'fisiolink')
                            continue; ?>
                        <a href="<?= View::url('proyectos/' . $cl['slug']) ?>" class="marquee__item">
                            <span class="marquee__dot"></span>
                            <strong><?= View::e($cl['nombre']) ?></strong>
                            <small><?= View::e($cl['rubro']) ?></small>
                        </a>
                    <?php endforeach; ?>
                <?php endfor; ?>
            </div>
        </div>
    </div>
</section>

<!-- 3. PROBLEMA -> SOLUCIÓN -->
<section class="section problem-solution" aria-labelledby="ps-heading">
    <div class="container">
        <div class="section-header">
            <span class="eyebrow">REALIDAD PYME EN URUGUAY</span>
            <h2 id="ps-heading" class="section-title">¿Te pasa esto en el día a día de tu negocio?</h2>
            <p class="section-subtitle">
                No vendemos humo ni paquetes enlatados: resolvemos los tres cuellos de botella más comunes de los dueños
                de pymes.
            </p>
        </div>

        <div class="ps-grid">
            <article class="ps-card reveal-on-scroll">
                <div class="ps-card__problem">
                    <span class="ps-card__tag ps-card__tag--bad">El problema hoy</span>
                    <h3>«No me da el día para pensar qué subir a las redes y responder todo»</h3>
                    <p>Entre atender el local, proveedores y entregas, el Instagram queda abandonado o se publica a las
                        apuradas sin una estrategia clara.</p>
                </div>
                <div class="ps-card__divider" aria-hidden="true">↓</div>
                <div class="ps-card__solution">
                    <span class="ps-card__tag ps-card__tag--good">Cómo lo resolvemos</span>
                    <h4>Ordenamos tus redes y tus campañas mes a mes</h4>
                    <p>Planificamos el calendario, editamos tus Reels y piezas gráficas con identidad profesional y
                        activamos anuncios para que entren consultas calificadas.</p>
                </div>
            </article>

            <article class="ps-card reveal-on-scroll">
                <div class="ps-card__problem">
                    <span class="ps-card__tag ps-card__tag--bad">El problema hoy</span>
                    <h3>«Vendemos solo cuando estamos conectados respondiendo precios por WhatsApp»</h3>
                    <p>Si el cliente no ve el catálogo claro ni puede pagar en el momento con tarjeta, se enfría la
                        venta o le compra a otro negocio que sí aparece en Google.</p>
                </div>
                <div class="ps-card__divider" aria-hidden="true">↓</div>
                <div class="ps-card__solution">
                    <span class="ps-card__tag ps-card__tag--good">Cómo lo resolvemos</span>
                    <h4>Creamos tu web o tienda online y te posicionamos</h4>
                    <p>Desarrollamos tu sitio o tienda online integrada con Mercado Pago, optimizada para celulares,
                        Google Maps y buscadores con IA.</p>
                </div>
            </article>

            <article class="ps-card reveal-on-scroll">
                <div class="ps-card__problem">
                    <span class="ps-card__tag ps-card__tag--bad">El problema hoy</span>
                    <h3>«Anotamos pedidos en cuadernos, grupos de WhatsApp y planillas sueltas»</h3>
                    <p>Cuando entran más pedidos, empieza el caos: nadie sabe en qué etapa está cada trabajo, se pierden
                        datos y todo depende de tu memoria.</p>
                </div>
                <div class="ps-card__divider" aria-hidden="true">↓</div>
                <div class="ps-card__solution">
                    <span class="ps-card__tag ps-card__tag--good">Cómo lo resolvemos</span>
                    <h4>Sistemas a medida y automatización con IA</h4>
                    <p>Programamos el sistema exacto que tu negocio necesita (ej. seguimiento de pedidos de punta a
                        punta) y automatizamos tareas repetitivas con IA.</p>
                </div>
            </article>
        </div>
    </div>
</section>

<!-- 4. LAS DOS CAPAS (INTERACTIVO EN BLOQUE AZUL PROFUNDO) -->
<section class="section section--dark layers-section" aria-labelledby="layers-heading">
    <div class="container">
        <div class="section-header section-header--light">
            <span class="eyebrow eyebrow--light">NUESTRO MODELO DE TRABAJO</span>
            <h2 id="layers-heading" class="section-title">Dos capas que se conectan para hacer crecer tu pyme</h2>
            <p class="section-subtitle">
                Podés empezar por ordenar tu marketing y redes (Capa 1), por crear tu web o sistema (Capa 2), o combinar
                ambas en una estrategia completa.
            </p>
        </div>

        <div class="layers-switcher" role="tablist" aria-label="Explorar las dos capas de servicios">
            <button type="button" class="layers-switcher__btn is-active" data-layer-tab="all" role="tab"
                aria-selected="true">
                Ver las 2 Capas conectadas
            </button>
            <button type="button" class="layers-switcher__btn" data-layer-tab="1" role="tab" aria-selected="false">
                Capa 1 · Marketing y Presencia
            </button>
            <button type="button" class="layers-switcher__btn" data-layer-tab="2" role="tab" aria-selected="false">
                Capa 2 · Sistemas, Webs e IA
            </button>
        </div>

        <div class="layers-grid" id="layers-container">
            <!-- Columna Capa 1 -->
            <div class="layer-box" data-layer-panel="1">
                <div class="layer-box__header">
                    <span class="layer-box__pill">CAPA 1 · LA PUERTA DE ENTRADA</span>
                    <h3>Marketing y presencia digital</h3>
                    <p>Para que te encuentren en tu zona, confíen en tu marca al ver tus redes y te escriban todos los
                        días.</p>
                </div>
                <div class="layer-box__items">
                    <?php foreach (($serviciosC1 ?? []) as $srv): ?>
                        <a href="<?= View::url('servicios/' . $srv['slug']) ?>" class="layer-service-item">
                            <span
                                class="layer-service-item__icon"><?= Icon::get($srv['icono_svg'] ?? 'sparkles', 20) ?></span>
                            <span class="layer-service-item__text">
                                <strong><?= View::e($srv['nombre']) ?></strong>
                                <small><?= View::e($srv['resumen']) ?></small>
                            </span>
                            <span class="layer-service-item__arrow"><?= Icon::get('arrow-right', 16) ?></span>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Columna Capa 2 -->
            <div class="layer-box layer-box--featured" data-layer-panel="2">
                <div class="layer-box__header">
                    <span class="layer-box__pill layer-box__pill--accent">CAPA 2 · EL DIFERENCIAL</span>
                    <h3>Sistemas, sitios web, automatización e IA</h3>
                    <p>Para vender online las 24 hs, gestionar pedidos de punta a punta y ahorrar horas de trabajo
                        operativo.</p>
                </div>
                <div class="layer-box__items">
                    <?php foreach (($serviciosC2 ?? []) as $srv): ?>
                        <a href="<?= View::url('servicios/' . $srv['slug']) ?>" class="layer-service-item">
                            <span class="layer-service-item__icon"><?= Icon::get($srv['icono_svg'] ?? 'cpu', 20) ?></span>
                            <span class="layer-service-item__text">
                                <strong><?= View::e($srv['nombre']) ?></strong>
                                <small><?= View::e($srv['resumen']) ?></small>
                            </span>
                            <span class="layer-service-item__arrow"><?= Icon::get('arrow-right', 16) ?></span>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <div class="layers-section__footer">
            <a href="<?= View::url('planes') ?>" class="btn btn--white">
                <span>Ver planes Arranque, Crecimiento y Transformación</span>
                <?= Icon::get('arrow-right', 18) ?>
            </a>
        </div>
    </div>
</section>

<!-- 5. PROYECTOS DESTACADOS -->
<section class="section" aria-labelledby="projects-heading">
    <div class="container">
        <div class="section-header section-header--split">
            <div>
                <span class="eyebrow">PORTFOLIO REAL</span>
                <h2 id="projects-heading" class="section-title">Proyectos realizados para pymes uruguayas</h2>
                <p class="section-subtitle">
                    Conocé el desafío, la solución técnica y el resultado de negocios que confiaron en Carnelli Studio.
                </p>
            </div>
            <a href="<?= View::url('proyectos') ?>" class="btn btn--outline">
                <span>Ver todos los proyectos</span>
                <?= Icon::get('arrow-right', 16) ?>
            </a>
        </div>

        <div class="projects-grid">
            <?php foreach (($proyectosDestacados ?? []) as $proyecto): ?>
                <?= View::partial('card-proyecto', ['proyecto' => $proyecto]) ?>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- 6. CÓMO TRABAJAMOS (4 PASOS CLAROS) -->
<section class="section section--alt" aria-labelledby="process-heading">
    <div class="container">
        <div class="section-header">
            <span class="eyebrow">PROCESO TRANSPARENTE</span>
            <h2 id="process-heading" class="section-title">Cómo trabajamos en 4 pasos</h2>
            <p class="section-subtitle">
                Sin letra chica ni presupuestos cerrados que te obliguen a pagar por cosas que no necesitás.
            </p>
        </div>

        <ol class="timeline-steps">
            <li class="timeline-step reveal-on-scroll">
                <span class="timeline-step__num">01</span>
                <div class="timeline-step__body">
                    <h3>Llamada o videollamada inicial</h3>
                    <p>Nos reunimos sin costo (por videollamada en todo Uruguay o presencial en Ciudad de la Costa) para
                        escuchar qué hace tu negocio y qué necesita resolver hoy.</p>
                </div>
            </li>
            <li class="timeline-step reveal-on-scroll">
                <span class="timeline-step__num">02</span>
                <div class="timeline-step__body">
                    <h3>Presupuesto a medida</h3>
                    <p>Te presento una propuesta clara y adaptada puntualmente a tus necesidades reales, ya sea empezar
                        por redes o desarrollar un sistema completo.</p>
                </div>
            </li>
            <li class="timeline-step reveal-on-scroll">
                <span class="timeline-step__num">03</span>
                <div class="timeline-step__body">
                    <h3>Ejecución flexible</h3>
                    <p>Avanzamos mostrándote el progreso real. Si sobre la marcha las necesidades de tu negocio cambian,
                        el presupuesto y el alcance se ajustan con total transparencia.</p>
                </div>
            </li>
            <li class="timeline-step reveal-on-scroll">
                <span class="timeline-step__num">04</span>
                <div class="timeline-step__body">
                    <h3>Entrega y mantenimiento posterior</h3>
                    <p>Lanzamos el proyecto, te capacitamos y quedamos a cargo del mantenimiento de tus sistemas y webs
                        para que todo siga funcionando siempre al 100%.</p>
                </div>
            </li>
        </ol>
    </div>
</section>

<!-- 7. POR QUÉ CARNELLI STUDIO (DIFERENCIALES + ALEX CARNELLI) -->
<section class="section" aria-labelledby="why-heading">
    <div class="container why-grid">
        <div class="why-grid__intro">
            <span class="eyebrow">POR QUÉ ELEGIRNOS</span>
            <h2 id="why-heading" class="section-title">Hablás directo con quien hace el trabajo</h2>
            <p class="why-grid__lead">
                Soy <strong>Alex Carnelli</strong>, desarrollador full-stack (PHP, JavaScript, MySQL). Creé Carnelli
                Studio porque vi cómo muchas pymes uruguayas quedaban a mitad de camino entre agencias que solo suben
                posteos y programadores que no entienden la parte comercial.
            </p>
            <p>
                Acá unimos los dos mundos: atraemos clientes con marketing bien hecho y construimos la tecnología para
                que tu negocio venda y se ordene de verdad.
            </p>
            <div class="why-grid__cta">
                <a href="<?= View::url('quien-soy') ?>" class="btn btn--outline">Conocé mi forma de trabajar →</a>
            </div>
        </div>

        <div class="why-grid__cards">
            <div class="feature-card reveal-on-scroll">
                <div class="feature-card__icon"><?= Icon::get('sparkles', 22) ?></div>
                <h3>Trato directo, sin intermediarios</h3>
                <p>No pasás por un vendedor que promete algo y después desaparece. Hablás siempre conmigo desde la
                    primera llamada hasta el mantenimiento.</p>
            </div>
            <div class="feature-card reveal-on-scroll">
                <div class="feature-card__icon"><?= Icon::get('cpu', 22) ?></div>
                <h3>Desarrollo propio (no plantillas lentas)</h3>
                <p>Programo webs, tiendas online y sistemas a medida pensados para cargar rápido en el celular y
                    adaptarse al flujo exacto de tu pyme.</p>
            </div>
            <div class="feature-card reveal-on-scroll">
                <div class="feature-card__icon"><?= Icon::get('zap', 22) ?></div>
                <h3>Automatización e IA aterrizada</h3>
                <p>Aplicamos inteligencia artificial y automatizaciones donde realmente te ahorran tiempo: pedidos,
                    consultas repetitivas y orden interno.</p>
            </div>
            <div class="feature-card reveal-on-scroll">
                <div class="feature-card__icon"><?= Icon::get('map-pin', 22) ?></div>
                <h3>En Ciudad de la Costa y en todo Uruguay</h3>
                <p>Atención presencial en Ciudad de la Costa (Canelones) y seguimiento ágil por videollamada para pymes
                    de Montevideo y todo el interior.</p>
            </div>
        </div>
    </div>
</section>

<!-- 8. TESTIMONIOS (SE MUESTRA SOLO SI HAY ALGUNO PUBLICADO EN app/data/testimonios.json) -->
<?php if (!empty($testimonios)): ?>
    <section class="section section--alt" aria-labelledby="testimonials-heading">
        <div class="container">
            <div class="section-header">
                <span class="eyebrow">LO QUE DICEN NUESTROS CLIENTES</span>
                <h2 id="testimonials-heading" class="section-title">Experiencias reales trabajando juntos</h2>
            </div>
            <div class="testimonials-grid">
                <?php foreach ($testimonios as $t): ?>
                    <blockquote class="testimonial-card">
                        <p class="testimonial-card__text">«<?= View::e($t['texto']) ?>»</p>
                        <footer class="testimonial-card__author">
                            <strong><?= View::e($t['nombre']) ?></strong>
                            <span><?= View::e($t['cargo_negocio'] ?? '') ?></span>
                        </footer>
                    </blockquote>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
<?php endif; ?>

<!-- 9. PREGUNTAS FRECUENTES (CON SCHEMA FAQPage) -->
<section class="section" aria-labelledby="faq-home-heading">
    <div class="container container--narrow">
        <div class="section-header">
            <span class="eyebrow">DUDAS FRECUENTES</span>
            <h2 id="faq-home-heading" class="section-title">Respuestas claras antes de empezar</h2>
        </div>

        <div class="faq-accordion">
            <?php foreach (($faqs ?? []) as $idx => $f): ?>
                <details class="faq-item" <?= $idx === 0 ? 'open' : '' ?>>
                    <summary class="faq-item__question">
                        <span><?= View::e($f['pregunta']) ?></span>
                        <span class="faq-item__icon" aria-hidden="true">+</span>
                    </summary>
                    <div class="faq-item__answer">
                        <p><?= View::e($f['respuesta']) ?></p>
                    </div>
                </details>
            <?php endforeach; ?>
        </div>

        <div class="section-footer-center">
            <a href="<?= View::url('preguntas-frecuentes') ?>" class="link-arrow">Ver todas las preguntas frecuentes
                →</a>
        </div>
    </div>
</section>