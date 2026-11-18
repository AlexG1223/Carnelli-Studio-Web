<?php
/**
 * PlanesController: Presentación de los 3 niveles (Arranque, Crecimiento y Transformación).
 */

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Plan;
use App\Models\Faq;

class PlanesController extends Controller
{
    public function index(): void
    {
        $this->seo
            ->setTitle('Planes para Pymes: Arranque, Crecimiento y Transformación')
            ->setDescription('Conocé los 3 planes de Carnelli Studio para pymes en Uruguay: desde presencia en redes y Google Maps hasta tiendas online, sistemas a medida e IA.')
            ->setCanonical('planes')
            ->setBreadcrumbs([
                ['label' => 'Planes', 'url' => 'planes']
            ]);

        $this->render('pages/planes', [
            'planes'          => Plan::active(),
            'faqs'            => Faq::forHome(5),
            'whatsappMessage' => 'Hola Alex, estuve viendo los planes (Arranque, Crecimiento y Transformación) y quiero pedir un presupuesto a medida.',
        ]);
    }
}

