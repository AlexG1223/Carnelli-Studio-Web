<?php
/**
 * ProyectosController: Portfolio filtrable (HTML + endpoint JSON sin recargar) y páginas de Caso de Estudio.
 */

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\View;
use App\Models\Proyecto;
use App\Models\Servicio;
use App\Models\Testimonio;

class ProyectosController extends Controller
{
    public function index(): void
    {
        $filtroSlug = trim((string)($_GET['servicio'] ?? ''));

        $this->seo
            ->setTitle('Proyectos y Casos de Estudio de Pymes en Uruguay')
            ->setDescription('Mirá los proyectos reales realizados por Carnelli Studio: tiendas online con pasarela de pago, landing pages, sistemas de gestión de pedidos y redes sociales.')
            ->setCanonical('proyectos')
            ->setBreadcrumbs([
                ['label' => 'Proyectos', 'url' => 'proyectos']
            ]);

        $proyectos = $filtroSlug !== ''
            ? Proyecto::byServiceSlug($filtroSlug)
            : Proyecto::published();

        $this->render('pages/proyectos/index', [
            'proyectos'       => $proyectos,
            'serviciosFiltro' => Servicio::active(),
            'filtroActual'    => $filtroSlug,
            'whatsappMessage' => 'Hola Alex, estuve viendo el portfolio de proyectos de Carnelli Studio y quiero consultar por un proyecto para mi negocio.',
        ]);
    }

    public function apiFilter(): void
    {
        $slug = trim((string)($_GET['servicio'] ?? ''));
        $proyectos = ($slug !== '' && $slug !== 'todos')
            ? Proyecto::byServiceSlug($slug)
            : Proyecto::published();

        $cardsHtml = '';
        foreach ($proyectos as $p) {
            $cardsHtml .= View::partial('card-proyecto', ['proyecto' => $p]);
        }

        $this->json([
            'ok'    => true,
            'count' => count($proyectos),
            'html'  => $cardsHtml,
        ]);
    }

    public function show(string $slug): void
    {
        $proyecto = Proyecto::findPublishedBySlug($slug);
        if (!$proyecto) {
            http_response_code(404);
            (new PaginasController())->notFound();
            return;
        }

        $adjacent   = Proyecto::adjacent((int)$proyecto['id']);
        $testimonio = Testimonio::forProject((int)$proyecto['id']);

        $this->seo
            ->setTitle($proyecto['meta_title'] ?: ($proyecto['cliente_nombre'] . ' — Caso de Estudio'), false)
            ->setDescription($proyecto['meta_description'] ?: $proyecto['resumen_corto'])
            ->setCanonical('proyectos/' . $proyecto['slug'])
            ->setOgImage($proyecto['imagen_portada'] ?: 'assets/brand/og-default.png')
            ->setBreadcrumbs([
                ['label' => 'Proyectos', 'url' => 'proyectos'],
                ['label' => $proyecto['cliente_nombre'], 'url' => 'proyectos/' . $proyecto['slug']]
            ]);

        // Schema JSON-LD CreativeWork para cada caso de estudio
        $this->seo->addSchema([
            '@type'       => 'CreativeWork',
            '@id'         => View::url('proyectos/' . $proyecto['slug']) . '#case-study',
            'name'        => $proyecto['titulo'],
            'headline'    => $proyecto['titulo'],
            'description' => $proyecto['resumen_corto'],
            'url'         => View::url('proyectos/' . $proyecto['slug']),
            'image'       => View::asset($proyecto['imagen_portada'], false),
            'dateCreated' => (string)($proyecto['anio'] ?? date('Y')),
            'creator'     => [
                '@id' => rtrim(BASE_URL, '/') . '/#organization'
            ],
            'about'       => $proyecto['rubro'] ?? 'Transformación Digital para Pymes'
        ]);

        $firstService = $proyecto['servicios'][0]['nombre'] ?? '';

        $this->render('pages/proyectos/show', [
            'proyecto'           => $proyecto,
            'prevProyecto'       => $adjacent['prev'],
            'nextProyecto'       => $adjacent['next'],
            'testimonio'         => $testimonio,
            'preselectedService' => $firstService,
            'whatsappMessage'    => 'Hola Alex, vi el caso de estudio de ' . $proyecto['cliente_nombre'] . ' y me gustaría hacer algo similar para mi negocio.',
        ]);
    }
}

