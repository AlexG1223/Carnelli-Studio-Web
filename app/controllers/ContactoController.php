<?php
/**
 * ContactoController: Construye el mensaje personalizado de la consulta y redirige directo a WhatsApp de Alex
 * sin guardar datos en base de datos ni archivos de leads.
 */

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Validator;
use App\Core\View;
use App\Models\Servicio;
use App\Models\Plan;

class ContactoController extends Controller
{
    public function index(): void
    {
        $preServicio = trim((string)($_GET['servicio'] ?? ''));
        $prePlan     = trim((string)($_GET['plan'] ?? ''));

        $this->seo
            ->setTitle('Pedí tu Presupuesto por WhatsApp o Agendá una Videollamada | Contacto')
            ->setDescription('Contactá a Carnelli Studio en menos de 1 minuto. Armá tu consulta personalizada y enviala directo al WhatsApp de Alex Carnelli.')
            ->setCanonical('contacto')
            ->setBreadcrumbs([
                ['label' => 'Contacto', 'url' => 'contacto']
            ]);

        $this->render('pages/contacto', [
            'servicios'          => Servicio::active(),
            'planes'             => Plan::active(),
            'preselectedService' => $preServicio,
            'preselectedPlan'    => $prePlan,
            'hideFinalCta'       => true,
            'whatsappMessage'    => 'Hola Alex, estoy en la página de contacto y quiero hacerte una consulta para mi pyme.',
        ]);
    }

    /**
     * Fallback por si el navegador envía el formulario vía POST sin JS:
     * construye el mensaje personalizado y redirige directo al WhatsApp oficial (+59892473724).
     */
    public function submit(): void
    {
        $nombre    = Validator::cleanString($_POST['nombre'] ?? '', 120);
        $negocio   = Validator::cleanString($_POST['negocio'] ?? '', 150);
        $rubro     = Validator::cleanString($_POST['rubro'] ?? '', 120);
        $situacion = Validator::cleanString($_POST['situacion_actual'] ?? '', 150);
        $plan      = Validator::cleanString($_POST['plan_interes'] ?? '', 80);
        $mensaje   = Validator::cleanText($_POST['mensaje'] ?? '', 1500);

        $rawServices = $_POST['servicios_interes'] ?? [];
        if (!is_array($rawServices)) {
            $rawServices = [$rawServices];
        }
        $serviciosInteres = array_values(array_filter(array_map(
            fn($s) => Validator::cleanString((string)$s, 120),
            $rawServices
        )));

        $lines = ["¡Hola Alex! Te escribo desde la web de Carnelli Studio:"];
        if ($nombre !== '') {
            $lines[] = "• *Mi nombre:* {$nombre}";
        }
        if ($negocio !== '') {
            $lines[] = "• *Negocio:* {$negocio}" . ($rubro !== '' ? " ({$rubro})" : '');
        }
        if ($plan !== '') {
            $lines[] = "• *Plan de interés:* {$plan}";
        }
        if (!empty($serviciosInteres)) {
            $lines[] = "• *Qué necesito:* " . implode(', ', $serviciosInteres);
        }
        if ($situacion !== '') {
            $lines[] = "• *Situación actual:* {$situacion}";
        }
        if ($mensaje !== '') {
            $lines[] = "• *Detalle:* {$mensaje}";
        }

        $waText = implode("\n", $lines);
        $waUrl  = View::whatsappUrl($waText);

        header('Location: ' . $waUrl, true, 302);
        exit;
    }
}
