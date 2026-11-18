<?php
/**
 * ServiciosController: Hub de servicios (las dos capas) y páginas individuales de cada servicio
 * optimizadas para SEO y GEO (estructura Answer-First + JSON-LD Service + FAQPage + proyectos vinculados).
 */

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\View;
use App\Models\Servicio;
use App\Models\Proyecto;
use App\Models\Faq;

class ServiciosController extends Controller
{
    public function index(): void
    {
        $query = trim((string)($_GET['q'] ?? ''));

        $this->seo
            ->setTitle('Servicios de Marketing Digital, Sitios Web, Sistemas e IA en Uruguay')
            ->setDescription('Conocé nuestro modelo en dos capas para pymes uruguayas: Capa 1 (Redes, Ads, SEO/GEO, Google Maps) y Capa 2 (Webs, Tiendas Online, Sistemas a medida e IA).')
            ->setCanonical('servicios')
            ->setBreadcrumbs([
                ['label' => 'Servicios', 'url' => 'servicios']
            ]);

        $c1 = Servicio::byLayer(1);
        $c2 = Servicio::byLayer(2);

        if ($query !== '') {
            $filterFn = fn($s) => mb_stripos($s['nombre'] . ' ' . $s['resumen'] . ' ' . $s['respuesta_directa'], $query) !== false;
            $c1 = array_values(array_filter($c1, $filterFn));
            $c2 = array_values(array_filter($c2, $filterFn));
        }

        $this->render('pages/servicios/index', [
            'serviciosC1'     => $c1,
            'serviciosC2'     => $c2,
            'searchQuery'     => $query,
            'whatsappMessage' => 'Hola Alex, estoy viendo los servicios de Carnelli Studio y quiero consultar cuál se adapta mejor a mi negocio.',
        ]);
    }

    public function show(string $slug): void
    {
        $servicio = Servicio::findBySlug($slug);
        if (!$servicio || empty($servicio['activo'])) {
            http_response_code(404);
            (new PaginasController())->notFound();
            return;
        }

        $proyectosRelacionados = Proyecto::byServiceId((int)$servicio['id']);
        $faqsServicio = Faq::forService((int)$servicio['id']);
        if (empty($faqsServicio)) {
            $faqsServicio = array_slice(Faq::forHome(4), 0, 3);
        }

        $this->seo
            ->setTitle($servicio['meta_title'] ?: $servicio['nombre'], false)
            ->setDescription($servicio['meta_description'] ?: $servicio['resumen'])
            ->setCanonical('servicios/' . $servicio['slug'])
            ->setBreadcrumbs([
                ['label' => 'Servicios', 'url' => 'servicios'],
                ['label' => $servicio['nombre'], 'url' => 'servicios/' . $servicio['slug']]
            ]);

        // Schema JSON-LD Service con provider y areaServed
        $this->seo->addSchema([
            '@type'       => 'Service',
            '@id'         => View::url('servicios/' . $servicio['slug']) . '#service',
            'name'        => $servicio['nombre'],
            'description' => $servicio['respuesta_directa'] ?: $servicio['resumen'],
            'url'         => View::url('servicios/' . $servicio['slug']),
            'serviceType' => ((int)$servicio['capa'] === 2) ? 'Desarrollo Web, Sistemas e IA' : 'Marketing Digital',
            'provider'    => [
                '@id' => rtrim(BASE_URL, '/') . '/#organization'
            ],
            'areaServed'  => [
                ['@type' => 'City', 'name' => 'Ciudad de la Costa'],
                ['@type' => 'Country', 'name' => 'Uruguay']
            ]
        ]);

        if (!empty($faqsServicio)) {
            $faqEntities = [];
            foreach ($faqsServicio as $f) {
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

        $otrosServicios = array_filter(
            Servicio::byLayer((int)$servicio['capa']),
            fn($s) => (int)$s['id'] !== (int)$servicio['id']
        );

        $this->render('pages/servicios/show', [
            'servicio'              => $servicio,
            'proyectosRelacionados' => $proyectosRelacionados,
            'faqsServicio'          => $faqsServicio,
            'otrosServicios'        => array_slice(array_values($otrosServicios), 0, 3),
            'preselectedService'    => $servicio['nombre'],
            'whatsappMessage'       => 'Hola Alex, vi el servicio de ' . $servicio['nombre'] . ' en la web y quiero consultar para mi pyme.',
        ]);
    }
}

