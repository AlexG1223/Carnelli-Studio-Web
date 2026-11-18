<?php
/**
 * Admin\ProyectosController: CRUD completo de Casos de Estudio con subida de imágenes WebP y galería.
 */

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Session;
use App\Core\Validator;
use App\Core\ImageUploader;
use App\Models\Proyecto;
use App\Models\Servicio;
use App\Models\Cliente;

class ProyectosController extends Controller
{
    public function index(): void
    {
        $this->requireAdmin();
        $proyectos = Proyecto::hydrateServices(Proyecto::all());
        usort($proyectos, fn($a, $b) => ((int)($a['orden'] ?? 99)) <=> ((int)($b['orden'] ?? 99)));

        $this->render('admin/proyectos/index', [
            'adminTitle'    => 'Proyectos (Portfolio)',
            'adminSubtitle' => 'Administrá los casos de estudio, subí capturas reales y completá FisioLink',
            'proyectos'     => $proyectos,
        ], 'admin');
    }

    public function create(): void
    {
        $this->requireAdmin();
        $this->render('admin/proyectos/form', [
            'adminTitle' => 'Nuevo Proyecto / Caso de Estudio',
            'proyecto'   => null,
            'servicios'  => Servicio::all(),
            'clientes'   => Cliente::all(),
        ], 'admin');
    }

    public function store(): void
    {
        $this->requireAdmin();
        $this->requireCsrf();

        $data = $this->extractProjectData();
        Proyecto::create($data);

        Session::flash('success', 'Proyecto creado correctamente.');
        $this->redirect('admin/proyectos');
    }

    public function edit(string $id): void
    {
        $this->requireAdmin();
        $proyecto = Proyecto::find((int)$id);
        if (!$proyecto) {
            Session::flash('error', 'Proyecto no encontrado.');
            $this->redirect('admin/proyectos');
        }

        $this->render('admin/proyectos/form', [
            'adminTitle' => 'Editar Proyecto: ' . $proyecto['cliente_nombre'],
            'proyecto'   => $proyecto,
            'servicios'  => Servicio::all(),
            'clientes'   => Cliente::all(),
        ], 'admin');
    }

    public function update(string $id): void
    {
        $this->requireAdmin();
        $this->requireCsrf();

        $existing = Proyecto::find((int)$id);
        if (!$existing) {
            $this->redirect('admin/proyectos');
        }

        $data = $this->extractProjectData($existing);
        Proyecto::update((int)$id, $data);

        Session::flash('success', 'Proyecto actualizado y caché regenerada.');
        $this->redirect('admin/proyectos');
    }

    public function delete(string $id): void
    {
        $this->requireAdmin();
        $this->requireCsrf();
        Proyecto::delete((int)$id);
        Session::flash('success', 'Proyecto eliminado.');
        $this->redirect('admin/proyectos');
    }

    private function extractProjectData(?array $existing = null): array
    {
        $clienteNombre = Validator::cleanString($_POST['cliente_nombre'] ?? '', 120);
        $titulo        = Validator::cleanString($_POST['titulo'] ?? '', 180);
        $slugInput     = Validator::cleanSlug($_POST['slug'] ?? '');
        $slug          = $slugInput !== '' ? $slugInput : Validator::cleanSlug($clienteNombre ?: $titulo);

        $portadaPath = $existing['imagen_portada'] ?? 'assets/img/placeholders/impresos-carnelli-cover.webp';
        if (!empty($_FILES['imagen_portada_file']['name'])) {
            $uploaded = ImageUploader::upload($_FILES['imagen_portada_file'], 'proyectos', $slug);
            if ($uploaded) {
                $portadaPath = $uploaded;
            }
        }

        $galeria = $existing['galeria'] ?? [];
        if (!empty($_FILES['galeria_files']['name'][0])) {
            $count = count($_FILES['galeria_files']['name']);
            for ($i = 0; $i < $count; $i++) {
                $singleFile = [
                    'name'     => $_FILES['galeria_files']['name'][$i],
                    'type'     => $_FILES['galeria_files']['type'][$i],
                    'tmp_name' => $_FILES['galeria_files']['tmp_name'][$i],
                    'error'    => $_FILES['galeria_files']['error'][$i],
                    'size'     => $_FILES['galeria_files']['size'][$i],
                ];
                $upGal = ImageUploader::upload($singleFile, 'proyectos', $slug . '-gal');
                if ($upGal) {
                    $galeria[] = [
                        'ruta'  => $upGal,
                        'alt'   => Validator::cleanString($_POST['galeria_alt_nuevo'] ?? ('Vista de ' . $clienteNombre), 160),
                        'tipo'  => Validator::cleanString($_POST['galeria_tipo_nuevo'] ?? 'desktop', 20),
                        'orden' => count($galeria) + 1,
                    ];
                }
            }
        }

        $servIds = array_map('intval', $_POST['servicios_ids'] ?? []);

        return [
            'slug'               => $slug,
            'titulo'             => $titulo,
            'cliente_nombre'     => $clienteNombre,
            'cliente_id'         => (int)($_POST['cliente_id'] ?? ($existing['cliente_id'] ?? 1)),
            'rubro'              => Validator::cleanString($_POST['rubro'] ?? '', 120),
            'anio'               => (int)($_POST['anio'] ?? date('Y')),
            'servicios_ids'      => array_values($servIds),
            'resumen_corto'      => Validator::cleanText($_POST['resumen_corto'] ?? '', 400),
            'desafio'            => Validator::cleanText($_POST['desafio'] ?? '', 3000),
            'solucion'           => Validator::cleanText($_POST['solucion'] ?? '', 3000),
            'resultado'          => Validator::cleanText($_POST['resultado'] ?? '', 3000),
            'metricas'           => $existing['metricas'] ?? [],
            'url_en_vivo'        => Validator::cleanString($_POST['url_en_vivo'] ?? '', 255),
            'imagen_portada'     => $portadaPath,
            'imagen_portada_alt' => Validator::cleanString($_POST['imagen_portada_alt'] ?? ('Mockup de ' . $clienteNombre), 180),
            'galeria'            => $galeria,
            'destacado'          => !empty($_POST['destacado']) ? 1 : 0,
            'orden'              => (int)($_POST['orden'] ?? 1),
            'publicado'          => !empty($_POST['publicado']) ? 1 : 0,
            'meta_title'         => Validator::cleanString($_POST['meta_title'] ?? '', 160),
            'meta_description'   => Validator::cleanString($_POST['meta_description'] ?? '', 250),
        ];
    }
}

