<?php
/**
 * Admin\PlanesController: Edición de los 3 niveles (Arranque, Crecimiento y Transformación).
 */

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Session;
use App\Core\Validator;
use App\Models\Plan;

class PlanesController extends Controller
{
    public function index(): void
    {
        $this->requireAdmin();
        $this->render('admin/planes/index', [
            'adminTitle'    => 'Planes de Trabajo',
            'adminSubtitle' => 'Editá qué incluye cada nivel (Arranque, Crecimiento y Transformación)',
            'planes'        => Plan::all(),
        ], 'admin');
    }

    public function edit(string $id): void
    {
        $this->requireAdmin();
        $plan = Plan::find((int)$id);
        if (!$plan) {
            $this->redirect('admin/planes');
        }
        $this->render('admin/planes/form', [
            'adminTitle' => 'Editar Plan ' . $plan['nombre'],
            'plan'       => $plan,
        ], 'admin');
    }

    public function update(string $id): void
    {
        $this->requireAdmin();
        $this->requireCsrf();

        $items = array_values(array_filter(array_map('trim', explode("\n", (string)($_POST['items_txt'] ?? '')))));

        Plan::update((int)$id, [
            'nombre'          => Validator::cleanString($_POST['nombre'] ?? '', 80),
            'subtitulo'       => Validator::cleanString($_POST['subtitulo'] ?? '', 180),
            'ideal_para'      => Validator::cleanText($_POST['ideal_para'] ?? '', 400),
            'capas_incluidas' => Validator::cleanString($_POST['capas_incluidas'] ?? '', 140),
            'items_incluidos' => $items,
            'texto_cta'       => Validator::cleanString($_POST['texto_cta'] ?? 'Pedí tu presupuesto', 60),
            'destacado'       => !empty($_POST['destacado']) ? 1 : 0,
            'orden'           => (int)($_POST['orden'] ?? 1),
            'activo'          => !empty($_POST['activo']) ? 1 : 0,
        ]);

        Session::flash('success', 'Plan actualizado correctamente.');
        $this->redirect('admin/planes');
    }
}

