<?php
/**
 * Partial: Footer institucional con logotipo de marca, las dos capas, contacto y enlaces legales.
 */

use App\Core\View;
use App\Core\Icon;

$instaUrl = trim((string)($settings['instagram_url'] ?? ''));
$fbUrl    = trim((string)($settings['facebook_url'] ?? ''));
$liUrl    = trim((string)($settings['linkedin_url'] ?? ''));
?>
<footer class="site-footer">
    <div class="container">
        <div class="site-footer__grid">
            <div class="site-footer__brand-col">
                <a href="<?= View::url('') ?>" class="site-footer__logo-link" aria-label="Carnelli Studio">
                    <img
                        src="<?= View::asset('assets/brand/carnelli-imagotipo-blanco-transparente.svg') ?>"
                        alt="Carnelli Studio"
                        width="220"
                        height="56"
                        loading="lazy"
                    >
                </a>
                <p class="site-footer__tagline">
                    «<?= View::e($settings['tagline'] ?? 'Diseñamos el crecimiento digital de tu pyme') ?>»
                </p>
                <p class="site-footer__desc">
                    Agencia de transformación digital para pymes uruguayas. Atención presencial en <strong><?= View::e($settings['ubicacion_presencial'] ?? 'Ciudad de la Costa (Canelones)') ?></strong> y remota en <strong>todo Uruguay</strong> por videollamada.
                </p>
                <div class="site-footer__socials">
                    <?php if ($instaUrl !== '' && !str_contains($instaUrl, '[COMPLETAR')): ?>
                        <a href="<?= View::e($instaUrl) ?>" target="_blank" rel="noopener" class="social-pill" aria-label="Instagram de Carnelli Studio">
                            <span>Instagram</span>
                            <strong><?= View::e($settings['instagram_handle'] ?? '@carnelli.studio') ?></strong>
                        </a>
                    <?php endif; ?>
                    <?php if ($fbUrl !== '' && !str_contains($fbUrl, '[COMPLETAR')): ?>
                        <a href="<?= View::e($fbUrl) ?>" target="_blank" rel="noopener" class="social-pill" aria-label="Facebook de Carnelli Studio">
                            <span>Facebook</span>
                        </a>
                    <?php endif; ?>
                    <?php if ($liUrl !== '' && !str_contains($liUrl, '[COMPLETAR')): ?>
                        <a href="<?= View::e($liUrl) ?>" target="_blank" rel="noopener" class="social-pill" aria-label="LinkedIn de Carnelli Studio">
                            <span>LinkedIn</span>
                        </a>
                    <?php endif; ?>
                </div>
            </div>

            <div class="site-footer__col">
                <h3 class="site-footer__heading">Capa 1 · Marketing Digital</h3>
                <ul class="site-footer__list">
                    <?php foreach (($navServiciosC1 ?? []) as $srv): ?>
                        <li><a href="<?= View::url('servicios/' . $srv['slug']) ?>"><?= View::e($srv['nombre']) ?></a></li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <div class="site-footer__col">
                <h3 class="site-footer__heading">Capa 2 · Sistemas, Webs e IA</h3>
                <ul class="site-footer__list">
                    <?php foreach (($navServiciosC2 ?? []) as $srv): ?>
                        <li><a href="<?= View::url('servicios/' . $srv['slug']) ?>"><?= View::e($srv['nombre']) ?></a></li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <div class="site-footer__col">
                <h3 class="site-footer__heading">Navegación y Contacto</h3>
                <ul class="site-footer__list">
                    <li><a href="<?= View::url('proyectos') ?>">Proyectos realizados</a></li>
                    <li><a href="<?= View::url('planes') ?>">Planes (Arranque, Crecimiento, Transformación)</a></li>
                    <li><a href="<?= View::url('como-trabajamos') ?>">Cómo trabajamos</a></li>
                    <li><a href="<?= View::url('quien-soy') ?>">Quién soy (Alex Carnelli)</a></li>
                    <li><a href="<?= View::url('blog') ?>">Blog y recursos</a></li>
                    <li><a href="<?= View::url('preguntas-frecuentes') ?>">Preguntas frecuentes</a></li>
                    <li><a href="<?= View::url('contacto') ?>">Pedí tu presupuesto</a></li>
                </ul>

                <div class="site-footer__contact-box">
                    <a href="<?= View::e(View::whatsappUrl()) ?>" target="_blank" rel="noopener" class="site-footer__contact-item" data-track="whatsapp">
                        <?= Icon::get('whatsapp', 16) ?>
                        <span>WhatsApp: <?= View::e($settings['whatsapp_visible'] ?? '092 473 724') ?></span>
                    </a>
                    <a href="mailto:<?= View::e($settings['email_contacto'] ?? 'carnellistudio2026@gmail.com') ?>" class="site-footer__contact-item" data-track="email">
                        <?= Icon::get('mail', 16) ?>
                        <span><?= View::e($settings['email_contacto'] ?? 'carnellistudio2026@gmail.com') ?></span>
                    </a>
                </div>
            </div>
        </div>

        <div class="site-footer__bottom">
            <p>© <?= date('Y') ?> Carnelli Studio · Desarrollado a medida por Alex Carnelli en Uruguay.</p>
            <div class="site-footer__legal">
                <a href="<?= View::url('politica-de-privacidad') ?>">Política de Privacidad (Ley 18.331)</a>
                <a href="<?= View::url('llms.txt') ?>">llms.txt (IA)</a>
                <a href="<?= View::url('sitemap.xml') ?>">Mapa del sitio</a>
            </div>
        </div>
    </div>
</footer>

