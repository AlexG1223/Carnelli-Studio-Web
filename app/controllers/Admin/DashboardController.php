<?php
/**
 * DashboardController: Resumen ejecutivo de Leads, Proyectos, gráfica Chart.js y recordatorios [COMPLETAR].
 */

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Models\Lead;
use App\Models\Proyecto;
use App\Models\Servicio;
use App\Models\Post;

class DashboardController extends Controller
{
    public function index(): void
    {
        $this->requireAdmin();

        $leadStats = Lead::stats();
        $ultimosLeads = array_slice(Lead::newestFirst(), 0, 6);
        $proyectos = Proyecto::all();
        $publicados = count(array_filter($proyectos, fn($p) => !empty($p['publicado'])));

        $this->render('admin/dashboard', [
            'adminTitle'     => 'Dashboard General',
            'adminSubtitle'  => 'Resumen de consultas (CRM), proyectos y estado del sitio web',
            'useChartJs'     => true,
            'leadStats'      => $leadStats,
            'ultimosLeads'   => $ultimosLeads,
            'totalProyectos' => count($proyectos),
            'proyectosPub'   => $publicados,
            'totalServicios' => count(Servicio::active()),
            'totalPosts'     => count(Post::all()),
        ], 'admin');
    }
}

