<?php
/**
 * HomeController: Página de Inicio con las 10 secciones clave, SEO y FAQs JSON-LD.
 */

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Servicio;
use App\Models\Proyecto;
use App\Models\Plan;
use App\Models\Cliente;
use App\Models\Testimonio;
use App\Models\Faq;

class HomeController extends Controller
{
    public function index(): void
    {
        $tagline = $this->settings['tagline'] ?? 'Diseñamos el crecimiento digital de tu pyme';
        $this->seo
            ->setTitle('Carnelli Studio — ' . $tagline . ' | Agencia Digital en Uruguay', false)
            ->setDescription($this->settings['meta_description_default'] ?? 'Agencia de transformación digital para pymes uruguayas. Gestión de redes, diseño web, tiendas online con Mercado Pago, sistemas a medida e IA.')
            ->setCanonical('');

        $faqs = Faq::forHome(6);

        if (!empty($faqs)) {
            $faqEntities = [];
            foreach ($faqs as $f) {
                $faqEntities[] = [
                    '@type' => 'Question',
                    'name'  => $f['pregunta'],
                    'acceptedAnswer' => [
                        '@type' => 'Answer',
                        'text'  => $f['respuesta']
                    ]
                ];
            }
            $this->seo->addSchema([
                '@type'      => 'FAQPage',
                'mainEntity' => $faqEntities
            ]);
        }

        $this->render('pages/home', [
            'serviciosC1'         => Servicio::byLayer(1),
            'serviciosC2'         => Servicio::byLayer(2),
            'proyectosDestacados' => Proyecto::featured(4),
            'planes'              => Plan::active(),
            'clientes'            => Cliente::visible(),
            'testimonios'         => Testimonio::published(),
            'faqs'                => $faqs,
            'whatsappMessage'     => 'Hola Alex, entré a la web de Carnelli Studio y quiero consultar sobre el crecimiento digital de mi pyme.',
        ]);
    }
}

