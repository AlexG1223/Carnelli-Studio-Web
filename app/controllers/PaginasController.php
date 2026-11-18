<?php
/**
 * PaginasController: /como-trabajamos, /quien-soy, /preguntas-frecuentes, /gracias, /politica-de-privacidad y 404.
 */

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Faq;
use App\Models\Proyecto;
use App\Models\Servicio;

class PaginasController extends Controller
{
    public function comoTrabajamos(): void
    {
        $this->seo
            ->setTitle('Cómo Trabajamos: Proceso en 4 Pasos para Pymes')
            ->setDescription('Conocé cómo trabajamos en Carnelli Studio: llamada inicial sin costo, presupuesto a medida, ejecución flexible y entrega con mantenimiento continuo.')
            ->setCanonical('como-trabajamos')
            ->setBreadcrumbs([
                ['label' => 'Cómo trabajamos', 'url' => 'como-trabajamos']
            ]);

        $this->render('pages/como-trabajamos', [
            'faqs'            => Faq::forHome(4),
            'whatsappMessage' => 'Hola Alex, vi cómo trabajan en Carnelli Studio y me gustaría coordinar una llamada inicial.',
        ]);
    }

    public function quienSoy(): void
    {
        $this->seo
            ->setTitle('Quién Soy — Alex Carnelli | Fundador y Desarrollador Full-Stack')
            ->setDescription('Soy Alex Carnelli, desarrollador full-stack (PHP, JavaScript, MySQL) y fundador de Carnelli Studio. Ayudo a pymes uruguayas a crecer con marketing, webs y sistemas a medida.')
            ->setCanonical('quien-soy')
            ->setBreadcrumbs([
                ['label' => 'Quién soy', 'url' => 'quien-soy']
            ]);

        $this->render('pages/quien-soy', [
            'proyectosDestacados' => Proyecto::featured(3),
            'whatsappMessage'     => 'Hola Alex, leí la sección "Quién soy" en la web y quiero conversar sobre mi negocio.',
        ]);
    }

    public function preguntasFrecuentes(): void
    {
        $faqs = Faq::active();

        $this->seo
            ->setTitle('Preguntas Frecuentes sobre Diseño Web, Redes, Sistemas e IA en Uruguay')
            ->setDescription('Respuestas claras a las dudas más comunes de dueños de pymes en Uruguay sobre sitios web, tiendas online con Mercado Pago, sistemas a medida y modalidad de trabajo.')
            ->setCanonical('preguntas-frecuentes')
            ->setBreadcrumbs([
                ['label' => 'Preguntas frecuentes', 'url' => 'preguntas-frecuentes']
            ]);

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

        $this->render('pages/preguntas-frecuentes', [
            'faqs'            => $faqs,
            'whatsappMessage' => 'Hola Alex, estuve leyendo las preguntas frecuentes y me quedó una consulta.',
        ]);
    }

    public function gracias(): void
    {
        $this->seo
            ->setTitle('¡Gracias por tu consulta! | Carnelli Studio')
            ->setDescription('Recibimos tu solicitud de presupuesto en Carnelli Studio.')
            ->setCanonical('gracias')
            ->setNoIndex(true);

        $this->render('pages/gracias', [
            'proyectosDestacados' => Proyecto::featured(2),
            'hideFinalCta'        => true,
            'whatsappMessage'     => 'Hola Alex, recién envié el formulario de presupuesto en la web y te escribo por acá para agilizar.',
        ]);
    }

    public function privacidad(): void
    {
        $this->seo
            ->setTitle('Política de Privacidad y Protección de Datos (Ley 18.331 Uruguay)')
            ->setDescription('Política de privacidad y tratamiento de datos personales de Carnelli Studio conforme a la Ley N° 18.331 de la República Oriental del Uruguay.')
            ->setCanonical('politica-de-privacidad')
            ->setBreadcrumbs([
                ['label' => 'Política de Privacidad', 'url' => 'politica-de-privacidad']
            ]);

        $this->render('pages/privacidad', [
            'hideFinalCta' => true,
        ]);
    }

    public function notFound(): void
    {
        $this->seo
            ->setTitle('Página no encontrada (404) | Carnelli Studio')
            ->setDescription('La página que buscás no existe o cambió de dirección.')
            ->setNoIndex(true);

        $this->render('pages/404', [
            'servicios' => Servicio::active(),
        ]);
    }
}

