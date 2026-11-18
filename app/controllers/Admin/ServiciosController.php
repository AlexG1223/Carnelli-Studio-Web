<?php
/**
 * Admin\ServiciosController: CRUD de los servicios de Capa 1 y Capa 2 con campos SEO y Answer-First (GEO).
 */

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Session;
use App\Core\Validator;
use App\Models\Servicio;

class ServiciosController extends Controller
{
    public function index(): void
    {
        $this->requireAdmin();
        $servicios = Servicio::all();
        usort($servicios, fn($a, $b) => ((int)($a['orden'] ?? 99)) <=> ((int)($b['orden'] ?? 99)));

        $this->render('admin/servicios/index', [
            'adminTitle'    => 'Servicios (Capa 1 y Capa 2)',
            'adminSubtitle' => 'Editá textos, bloques Answer-First (GEO) y metadatos SEO de cada servicio',
            'servicios'     => $servicios,
        ], 'admin');
    }

    public function create(): void
    {
        $this->requireAdmin();
        $this->render('admin/servicios/form', [
            'adminTitle' => 'Nuevo Servicio',
            'servicio'   => null,
        ], 'admin');
    }

    public function store(): void
    {
        $this->requireAdmin();
        $this->requireCsrf();
        Servicio::create($this->extractData());
        Session::flash('success', 'Servicio creado.');
        $this->redirect('admin/servicios');
    }

    public function edit(string $id): void
    {
        $this->requireAdmin();
        $servicio = Servicio::find((int)$id);
        if (!$servicio) {
            $this->redirect('admin/servicios');
        }
        $this->render('admin/servicios/form', [
            'adminTitle' => 'Editar Servicio: ' . $servicio['nombre'],
            'servicio'   => $servicio,
        ], 'admin');
    }

    public function update(string $id): void
    {
        $this->requireAdmin();
        $this->requireCsrf();
        $existing = Servicio::find((int)$id);
        if ($existing) {
            Servicio::update((int)$id, $this->extractData($existing));
            Session::flash('success', 'Servicio actualizado.');
        }
        $this->redirect('admin/servicios');
    }

    public function delete(string $id): void
    {
        $this->requireAdmin();
        $this->requireCsrf();
        Servicio::delete((int)$id);
        Session::flash('success', 'Servicio eliminado.');
        $this->redirect('admin/servicios');
    }

    private function extractData(?array $existing = null): array
    {
        $nombre = Validator::cleanString($_POST['nombre'] ?? '', 120);
        $slug   = Validator::cleanSlug($_POST['slug'] ?? '') ?: Validator::cleanSlug($nombre);

        $beneficios = array_values(array_filter(array_map('trim', explode("\n", (string)($_POST['beneficios_txt'] ?? '')))));
        $incluye    = array_values(array_filter(array_map('trim', explode("\n", (string)($_POST['que_incluye_txt'] ?? '')))));
        $proceso    = array_values(array_filter(array_map('trim', explode("\n", (string)($_POST['proceso_txt'] ?? '')))));

        return [
            'slug'              => $slug,
            'nombre'            => $nombre,
            'capa'              => (int)($_POST['capa'] ?? 1),
            'subtitulo_corto'   => Validator::cleanString($_POST['subtitulo_corto'] ?? '', 250),
            'respuesta_directa' => Validator::cleanText($_POST['respuesta_directa'] ?? '', 600),
            'resumen'           => Validator::cleanText($_POST['resumen'] ?? '', 350),
            'contenido'         => trim((string)($_POST['contenido'] ?? '')),
            'icono_svg'         => Validator::cleanString($_POST['icono_svg'] ?? ($existing['icono_svg'] ?? 'sparkles'), 40),
            'beneficios'        => $beneficios,
            'que_incluye'       => $incluye,
            'proceso'           => $proceso,
            'orden'             => (int)($_POST['orden'] ?? 1),
            'activo'            => !empty($_POST['activo']) ? 1 : 0,
            'meta_title'        => Validator::cleanString($_POST['meta_title'] ?? '', 160),
            'meta_description'  => Validator::cleanString($_POST['meta_description'] ?? '', 250),
        ];
    }
}

