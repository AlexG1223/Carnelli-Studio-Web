<?php
/**
 * Admin\TestimoniosController: CRUD de testimonios reales (vacío inicialmente).
 */

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Session;
use App\Core\Validator;
use App\Models\Testimonio;
use App\Models\Proyecto;

class TestimoniosController extends Controller
{
    public function index(): void
    {
        $this->requireAdmin();
        $this->render('admin/testimonios/index', [
            'adminTitle'    => 'Testimonios de Clientes',
            'adminSubtitle' => 'Sin datos inventados: cargá acá los testimonios reales a medida que los recibas',
            'testimonios'   => Testimonio::all(),
        ], 'admin');
    }

    public function create(): void
    {
        $this->requireAdmin();
        $this->render('admin/testimonios/form', [
            'adminTitle' => 'Agregar Testimonio Real',
            'testimonio' => null,
            'proyectos'  => Proyecto::all(),
        ], 'admin');
    }

    public function store(): void
    {
        $this->requireAdmin();
        $this->requireCsrf();
        Testimonio::create($this->extractData());
        Session::flash('success', 'Testimonio agregado.');
        $this->redirect('admin/testimonios');
    }

    public function edit(string $id): void
    {
        $this->requireAdmin();
        $t = Testimonio::find((int)$id);
        if (!$t) {
            $this->redirect('admin/testimonios');
        }
        $this->render('admin/testimonios/form', [
            'adminTitle' => 'Editar Testimonio',
            'testimonio' => $t,
            'proyectos'  => Proyecto::all(),
        ], 'admin');
    }

    public function update(string $id): void
    {
        $this->requireAdmin();
        $this->requireCsrf();
        Testimonio::update((int)$id, $this->extractData());
        Session::flash('success', 'Testimonio actualizado.');
        $this->redirect('admin/testimonios');
    }

    public function delete(string $id): void
    {
        $this->requireAdmin();
        $this->requireCsrf();
        Testimonio::delete((int)$id);
        Session::flash('success', 'Testimonio eliminado.');
        $this->redirect('admin/testimonios');
    }

    private function extractData(): array
    {
        $projId = (int)($_POST['proyecto_id'] ?? 0);
        return [
            'nombre'        => Validator::cleanString($_POST['nombre'] ?? '', 120),
            'cargo_negocio' => Validator::cleanString($_POST['cargo_negocio'] ?? '', 140),
            'texto'         => Validator::cleanText($_POST['texto'] ?? '', 1200),
            'proyecto_id'   => $projId > 0 ? $projId : null,
            'orden'         => (int)($_POST['orden'] ?? 1),
            'publicado'     => !empty($_POST['publicado']) ? 1 : 0,
        ];
    }
}

