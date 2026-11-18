<?php
/**
 * Partial: Header principal con navegación por capas, menú desplegable accesible y CTA fijo.
 */

use App\Core\View;
use App\Core\Icon;

$cp = $currentPath ?? '/';
?>
<a href="#main-content" class="skip-link">Saltar al contenido principal</a>

<header class="site-header" id="site-header">
    <div class="container site-header__inner">
        <a href="<?= View::url('') ?>" class="site-header__brand" aria-label="Carnelli Studio — Ir al inicio">
            <img
                src="<?= View::asset('assets/brand/carnelli-imagotipo-color-transparente.svg') ?>"
                alt="Carnelli Studio"
                width="210"
                height="54"
                class="site-header__logo"
                fetchpriority="high"
            >
        </a>

        <nav class="site-nav" id="site-nav" aria-label="Navegación principal">
            <ul class="site-nav__list">
                <li class="site-nav__item site-nav__item--dropdown">
                    <a href="<?= View::url('servicios') ?>" class="site-nav__link <?= str_starts_with($cp, '/servicios') ? 'is-active' : '' ?>" aria-haspopup="true">
                        Servicios
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><polyline points="6 9 12 15 18 9"/></svg>
                    </a>
                    <div class="mega-menu" role="region" aria-label="Submenú de servicios">
                        <div class="mega-menu__grid">
                            <div class="mega-menu__col">
                                <span class="mega-menu__eyebrow">Capa 1 · Puerta de entrada</span>
                                <p class="mega-menu__title">Marketing y Presencia Digital</p>
                                <ul class="mega-menu__links">
                                    <?php foreach (($navServiciosC1 ?? []) as $srv): ?>
                                        <li>
                                            <a href="<?= View::url('servicios/' . $srv['slug']) ?>">
                                                <?= Icon::get($srv['icono_svg'] ?? 'sparkles', 16) ?>
                                                <span><?= View::e($srv['nombre']) ?></span>
                                            </a>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                            <div class="mega-menu__col mega-menu__col--highlight">
                                <span class="mega-menu__eyebrow">Capa 2 · Nuestro diferencial</span>
                                <p class="mega-menu__title">Sistemas, Webs, Automatización e IA</p>
                                <ul class="mega-menu__links">
                                    <?php foreach (($navServiciosC2 ?? []) as $srv): ?>
                                        <li>
                                            <a href="<?= View::url('servicios/' . $srv['slug']) ?>">
                                                <?= Icon::get($srv['icono_svg'] ?? 'cpu', 16) ?>
                                                <span><?= View::e($srv['nombre']) ?></span>
                                            </a>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </div>
                        <div class="mega-menu__footer">
                            <span>¿No sabés por qué capa empezar? Te asesoramos sin costo.</span>
                            <a href="<?= View::url('servicios') ?>" class="link-arrow">Ver todos los servicios →</a>
                        </div>
                    </div>
                </li>
                <li class="site-nav__item">
                    <a href="<?= View::url('proyectos') ?>" class="site-nav__link <?= str_starts_with($cp, '/proyectos') ? 'is-active' : '' ?>">Proyectos</a>
                </li>
                <li class="site-nav__item">
                    <a href="<?= View::url('planes') ?>" class="site-nav__link <?= $cp === '/planes' ? 'is-active' : '' ?>">Planes</a>
                </li>
                <li class="site-nav__item">
                    <a href="<?= View::url('como-trabajamos') ?>" class="site-nav__link <?= $cp === '/como-trabajamos' ? 'is-active' : '' ?>">Cómo trabajamos</a>
                </li>
                <li class="site-nav__item">
                    <a href="<?= View::url('quien-soy') ?>" class="site-nav__link <?= $cp === '/quien-soy' ? 'is-active' : '' ?>">Quién soy</a>
                </li>
                <li class="site-nav__item">
                    <a href="<?= View::url('preguntas-frecuentes') ?>" class="site-nav__link <?= $cp === '/preguntas-frecuentes' ? 'is-active' : '' ?>">FAQ</a>
                </li>
            </ul>

            <div class="site-nav__mobile-cta">
                <a href="<?= View::url('contacto') ?>" class="btn btn--primary btn--full">Pedí tu presupuesto</a>
                <a href="<?= View::e(View::whatsappUrl($whatsappMessage ?? '')) ?>" target="_blank" rel="noopener" class="btn btn--outline btn--full" data-track="whatsapp">
                    <?= Icon::get('whatsapp', 18) ?> Hablemos por WhatsApp
                </a>
            </div>
        </nav>

        <div class="site-header__actions">
            <a href="<?= View::url('contacto') ?>" class="btn btn--primary btn--magnetic site-header__cta">
                <span>Pedí tu presupuesto</span>
                <?= Icon::get('arrow-right', 16) ?>
            </a>
            <button type="button" class="nav-toggle" id="nav-toggle" aria-expanded="false" aria-controls="site-nav" aria-label="Abrir menú de navegación">
                <span class="nav-toggle__bar"></span>
                <span class="nav-toggle__bar"></span>
                <span class="nav-toggle__bar"></span>
            </button>
        </div>
    </div>
</header>

