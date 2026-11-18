<?php
/**
 * Modelo Lead: Gestión CRM de consultas recibidas (filtros, estadísticas para Chart.js, estados).
 */

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

class Lead extends Model
{
    protected static string $collection = 'leads';

    public static function newestFirst(?string $estado = null, ?string $servicio = null): array
    {
        $items = static::all();
        if ($estado !== null && $estado !== '') {
            $items = array_filter($items, fn($l) => ($l['estado'] ?? 'nuevo') === $estado);
        }
        if ($servicio !== null && $servicio !== '') {
            $items = array_filter($items, function ($l) use ($servicio) {
                $servs = $l['servicios_interes'] ?? [];
                return in_array($servicio, $servs, true);
            });
        }
        $items = array_values($items);
        usort($items, fn($a, $b) => ((int)($b['id'] ?? 0)) <=> ((int)($a['id'] ?? 0)));
        return $items;
    }

    public static function stats(): array
    {
        $all = static::all();
        $byStatus = [
            'nuevo'               => 0,
            'contactado'          => 0,
            'presupuesto_enviado' => 0,
            'ganado'              => 0,
            'perdido'             => 0,
        ];
        $byService = [];
        $byMonth   = [];
        $bySource  = [];

        // Inicializar últimos 6 meses
        for ($i = 5; $i >= 0; $i--) {
            $ym = date('Y-m', strtotime("-{$i} months"));
            $byMonth[$ym] = 0;
        }

        foreach ($all as $lead) {
            $st = $lead['estado'] ?? 'nuevo';
            $byStatus[$st] = ($byStatus[$st] ?? 0) + 1;

            $ym = substr((string)($lead['created_at'] ?? date('Y-m')), 0, 7);
            if (isset($byMonth[$ym])) {
                $byMonth[$ym]++;
            }

            foreach (($lead['servicios_interes'] ?? []) as $srv) {
                $byService[$srv] = ($byService[$srv] ?? 0) + 1;
            }

            $src = ($lead['utm_source'] ?? '') !== '' ? $lead['utm_source'] : 'Directo / Orgánico';
            $bySource[$src] = ($bySource[$src] ?? 0) + 1;
        }

        arsort($byService);
        arsort($bySource);

        return [
            'total'      => count($all),
            'by_status'  => $byStatus,
            'by_service' => $byService,
            'by_month'   => $byMonth,
            'by_source'  => $bySource,
        ];
    }
}

