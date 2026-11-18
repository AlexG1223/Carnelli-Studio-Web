<?php
/**
 * LeadsController: CRM liviano (filtros por estado/servicio, cambio de estado, notas, WhatsApp 1-clic y CSV).
 */

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Session;
use App\Core\Validator;
use App\Models\Lead;
use App\Models\Servicio;

class LeadsController extends Controller
{
    public function index(): void
    {
        $this->requireAdmin();

        $estado   = trim((string)($_GET['estado'] ?? ''));
        $servicio = trim((string)($_GET['servicio'] ?? ''));

        $this->render('admin/leads/index', [
            'adminTitle'     => 'Leads y Consultas (CRM)',
            'adminSubtitle'  => 'Gestioná el estado de cada presupuesto, agregá notas y respondé en 1 clic por WhatsApp',
            'leads'          => Lead::newestFirst($estado, $servicio),
            'filtroEstado'   => $estado,
            'filtroServicio' => $servicio,
            'servicios'      => Servicio::active(),
        ], 'admin');
    }

    public function show(string $id): void
    {
        $this->requireAdmin();
        $lead = Lead::find((int)$id);
        if (!$lead) {
            Session::flash('error', 'Consulta no encontrada.');
            $this->redirect('admin/leads');
        }

        $this->render('admin/leads/show', [
            'adminTitle'    => 'Detalle de Lead #' . $lead['id'],
            'adminSubtitle' => $lead['nombre'] . ' · ' . ($lead['negocio'] ?? ''),
            'lead'          => $lead,
        ], 'admin');
    }

    public function update(string $id): void
    {
        $this->requireAdmin();
        $this->requireCsrf();

        $estado = Validator::cleanString($_POST['estado'] ?? 'nuevo', 40);
        $notas  = Validator::cleanText($_POST['notas_admin'] ?? '', 3000);

        Lead::update((int)$id, [
            'estado'      => $estado,
            'notas_admin' => $notas,
        ]);

        Session::flash('success', 'Estado y notas del lead actualizados correctamente.');
        $this->redirect('admin/leads/' . (int)$id);
    }

    public function delete(string $id): void
    {
        $this->requireAdmin();
        $this->requireCsrf();
        Lead::delete((int)$id);
        Session::flash('success', 'Lead eliminado.');
        $this->redirect('admin/leads');
    }

    public function exportCsv(): void
    {
        $this->requireAdmin();
        $leads = Lead::newestFirst();

        header('Content-Type: text/csv; charset=UTF-8');
        header('Content-Disposition: attachment; filename="leads-carnelli-studio-' . date('Y-m-d') . '.csv"');

        $out = fopen('php://output', 'w');
        fprintf($out, chr(0xEF) . chr(0xBB) . chr(0xBF));
        fputcsv($out, ['ID', 'Fecha', 'Nombre', 'Negocio', 'Rubro', 'WhatsApp', 'Email', 'Servicios', 'Plan', 'Preferencia', 'Estado', 'Origen', 'UTM Source', 'Mensaje', 'Notas Admin']);

        foreach ($leads as $l) {
            fputcsv($out, [
                $l['id'] ?? '',
                $l['created_at'] ?? '',
                $l['nombre'] ?? '',
                $l['negocio'] ?? '',
                $l['rubro'] ?? '',
                $l['whatsapp'] ?? '',
                $l['email'] ?? '',
                implode(' | ', $l['servicios_interes'] ?? []),
                $l['plan_interes'] ?? '',
                $l['preferencia_contacto'] ?? '',
                $l['estado'] ?? 'nuevo',
                $l['pagina_origen'] ?? '',
                $l['utm_source'] ?? '',
                $l['mensaje'] ?? '',
                $l['notas_admin'] ?? '',
            ]);
        }
        fclose($out);
        exit;
    }
}

