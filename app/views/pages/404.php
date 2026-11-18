<?php
/**
 * Vista: Página 404 personalizada con buscador de servicios y accesos directos.
 */

use App\Core\View;
use App\Core\Icon;
?>
<section class="section">
    <div class="container container--narrow text-center">
        <span class="eyebrow">ERROR 404 · PÁGINA NO ENCONTRADA</span>
        <h1 class="page-hero__title">Parece que esta página se mudó o no existe</h1>
        <p class="page-hero__subtitle">
            Pero no te preocupes: podés buscar directamente el servicio que necesita tu pyme o volver al inicio.
        </p>

        <form action="<?= View::url('servicios') ?>" method="GET" class="service-search mt-md" role="search">
            <input type="search" name="q" placeholder="Buscar servicio (ej: tienda online, redes, sistema de pedidos)..." class="form-input service-search__input" required>
            <button type="submit" class="btn btn--primary">Buscar servicio</button>
        </form>

        <div class="hero__actions mt-lg" style="justify-content: center;">
            <a href="<?= View::url('') ?>" class="btn btn--primary">Ir a la página de inicio</a>
            <a href="<?= View::url('proyectos') ?>" class="btn btn--outline">Ver proyectos realizados</a>
            <a href="<?= View::url('contacto') ?>" class="btn btn--outline">Pedir presupuesto</a>
        </div>
    </div>
</section>

