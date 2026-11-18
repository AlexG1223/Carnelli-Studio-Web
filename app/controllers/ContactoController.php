<?php
/**
 * ContactoController: Formulario inteligente por pasos (Wizard de 3 pasos),
 * anti-spam (honeypot + tiempo mínimo + rate limit por IP), guardado en leads.json y envío de correos.
 */

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Validator;
use App\Core\RateLimiter;
use App\Core\Session;
use App\Core\Mailer;
use App\Core\View;
use App\Models\Lead;
use App\Models\Servicio;
use App\Models\Plan;

class ContactoController extends Controller
{
    public function index(): void
    {
        $preServicio = trim((string)($_GET['servicio'] ?? ''));
        $prePlan     = trim((string)($_GET['plan'] ?? ''));

        $this->seo
            ->setTitle('Pedí tu Presupuesto o Agendá una Videollamada | Contacto')
            ->setDescription('Contactá a Carnelli Studio en menos de 1 minuto. Contanos qué necesita tu pyme y te armamos un presupuesto a medida en menos de 24 horas hábiles.')
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
            'whatsappMessage'    => 'Hola Alex, estoy en la página de contacto y prefiero escribirte directo por WhatsApp.',
        ]);
    }

    public function submit(): void
    {
        $this->requireCsrf();

        // 1. Honeypot anti-spam
        $honeypot = trim((string)($_POST['website_url'] ?? ''));
        if ($honeypot !== '') {
            // Simular éxito silencioso frente a bots
            $this->redirect('gracias');
        }

        // 2. Tiempo mínimo de envío (2 segundos para humanos reales)
        $startedAt = (int)($_POST['form_started_at'] ?? 0);
        if ($startedAt > 0 && (time() - $startedAt) < 2) {
            Session::flash('error', 'Por favor completá el formulario con calma antes de enviarlo.');
            $this->redirect('contacto');
        }

        // 3. Rate Limit por IP (máximo 6 envíos cada 10 minutos)
        if (RateLimiter::tooManyAttempts('contact_form', 6, 600)) {
            Session::flash('error', 'Recibimos varias consultas seguidas desde tu conexión. También podés escribirnos directo por WhatsApp.');
            $this->redirect('contacto');
        }

        $nombre    = Validator::cleanString($_POST['nombre'] ?? '', 120);
        $negocio   = Validator::cleanString($_POST['negocio'] ?? '', 150);
        $rubro     = Validator::cleanString($_POST['rubro'] ?? '', 120);
        $situacion = Validator::cleanString($_POST['situacion_actual'] ?? '', 150);
        $whatsapp  = Validator::cleanString($_POST['whatsapp'] ?? '', 50);
        $email     = Validator::cleanString($_POST['email'] ?? '', 150);
        $plan      = Validator::cleanString($_POST['plan_interes'] ?? '', 80);
        $mensaje   = Validator::cleanText($_POST['mensaje'] ?? '', 2500);
        $pref      = Validator::cleanString($_POST['preferencia_contacto'] ?? 'whatsapp', 40);
        $origen    = Validator::cleanString($_POST['pagina_origen'] ?? '/contacto', 200);
        $utmSource = Validator::cleanString($_POST['utm_source'] ?? '', 100);
        $utmMedium = Validator::cleanString($_POST['utm_medium'] ?? '', 100);
        $utmCamp   = Validator::cleanString($_POST['utm_campaign'] ?? '', 100);

        $rawServices = $_POST['servicios_interes'] ?? [];
        if (!is_array($rawServices)) {
            $rawServices = [$rawServices];
        }
        $serviciosInteres = array_values(array_filter(array_map(
            fn($s) => Validator::cleanString((string)$s, 120),
            $rawServices
        )));
        if (empty($serviciosInteres)) {
            $serviciosInteres = ['Asesoramiento general'];
        }

        if ($nombre === '' || !Validator::isPhone($whatsapp)) {
            Session::flash('error', 'Por favor ingresá tu nombre y un número de WhatsApp o teléfono válido.');
            $this->redirect('contacto');
        }

        if ($email !== '' && !Validator::isEmail($email)) {
            Session::flash('error', 'El formato del correo electrónico no es válido.');
            $this->redirect('contacto');
        }

        RateLimiter::hit('contact_form', 600);

        $lead = Lead::create([
            'nombre'               => $nombre,
            'negocio'              => $negocio !== '' ? $negocio : 'No especificado',
            'rubro'                => $rubro,
            'situacion_actual'     => $situacion,
            'email'                => $email,
            'whatsapp'             => $whatsapp,
            'servicios_interes'    => $serviciosInteres,
            'plan_interes'         => $plan,
            'mensaje'              => $mensaje,
            'preferencia_contacto' => in_array($pref, ['whatsapp', 'llamada', 'videollamada', 'email'], true) ? $pref : 'whatsapp',
            'pagina_origen'        => $origen,
            'utm_source'           => $utmSource,
            'utm_medium'           => $utmMedium,
            'utm_campaign'         => $utmCamp,
            'ip_hash'              => RateLimiter::ipHash(),
            'estado'               => 'nuevo',
            'notas_admin'          => '',
        ]);

        // Guardar nombre en sesión para personalizar /gracias
        Session::set('last_lead_name', $nombre);

        // Enviar correo de aviso a Alex
        $adminEmail = (string)($this->settings['email_contacto'] ?? 'carnellistudio2026@gmail.com');
        $adminHtml  = View::render('emails/lead-admin', ['lead' => $lead], null);
        Mailer::send(
            $adminEmail,
            'Alex Carnelli',
            'Nuevo presupuesto web: ' . $nombre . ' (' . ($lead['negocio']) . ')',
            $adminHtml,
            $email !== '' ? $email : null,
            $nombre
        );

        // Enviar correo de confirmación automática al cliente si dejó su email
        if ($email !== '') {
            $clientHtml = View::render('emails/lead-cliente', [
                'lead'     => $lead,
                'settings' => $this->settings
            ], null);
            Mailer::send(
                $email,
                $nombre,
                'Recibimos tu consulta en Carnelli Studio',
                $clientHtml
            );
        }

        $this->redirect('gracias');
    }
}

