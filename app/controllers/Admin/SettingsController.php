<?php
/**
 * Admin\SettingsController: Configuración de contacto, redes ([COMPLETAR]), hero, foto de Alex,
 * link de agenda, analítica y cambio de contraseña del administrador.
 */

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Session;
use App\Core\Validator;
use App\Core\ImageUploader;
use App\Models\Setting;
use App\Models\Usuario;

class SettingsController extends Controller
{
    public function index(): void
    {
        $this->requireAdmin();
        $this->render('admin/ajustes', [
            'adminTitle'    => 'Ajustes de la Marca y Campos [COMPLETAR]',
            'adminSubtitle' => 'Completá desde acá Facebook, LinkedIn, link de agenda, dominio, foto y analítica',
            'settings'      => Setting::allKeyValue(),
        ], 'admin');
    }

    public function save(): void
    {
        $this->requireAdmin();
        $this->requireCsrf();

        $allowedKeys = [
            'tagline',
            'hero_subtitulo',
            'ubicacion_presencial',
            'ubicacion_remota',
            'whatsapp_visible',
            'whatsapp_intl',
            'whatsapp_mensaje_default',
            'email_contacto',
            'instagram_url',
            'instagram_handle',
            'facebook_url',
            'linkedin_url',
            'agenda_url',
            'dominio',
            'tiempo_respuesta',
            'horarios_atencion',
            'ga4_id',
            'meta_pixel_id',
            'search_console_meta',
            'meta_description_default',
        ];

        $toSave = [];
        foreach ($allowedKeys as $key) {
            if (isset($_POST[$key])) {
                $toSave[$key] = Validator::cleanString((string)$_POST[$key], 400);
            }
        }

        if (!empty($_FILES['foto_fundador_file']['name'])) {
            $up = ImageUploader::upload($_FILES['foto_fundador_file'], 'clientes', 'alex-carnelli');
            if ($up) {
                $toSave['foto_fundador'] = $up;
            }
        }

        Setting::saveKeyValues($toSave);
        Session::flash('success', 'Ajustes guardados e invalidación de caché realizada.');
        $this->redirect('admin/ajustes');
    }

    public function updatePassword(): void
    {
        $this->requireAdmin();
        $this->requireCsrf();

        $current = (string)($_POST['current_password'] ?? '');
        $newPass = (string)($_POST['new_password'] ?? '');

        $userId = (int)Session::get('admin_user_id', 1);
        $user   = Usuario::find($userId);

        if (!$user || !password_verify($current, (string)$user['password_hash'])) {
            Session::flash('error', 'La contraseña actual no es correcta.');
            $this->redirect('admin/ajustes');
        }

        if (strlen($newPass) < 8) {
            Session::flash('error', 'La nueva contraseña debe tener al menos 8 caracteres.');
            $this->redirect('admin/ajustes');
        }

        Usuario::update($userId, [
            'password_hash' => password_hash($newPass, PASSWORD_DEFAULT),
        ]);

        Session::flash('success', 'Contraseña de administrador actualizada con éxito.');
        $this->redirect('admin/ajustes');
    }
}

