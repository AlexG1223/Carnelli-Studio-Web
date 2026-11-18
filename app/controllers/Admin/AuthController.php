<?php
/**
 * AuthController: Login seguro para el administrador con password_verify,
 * regeneración de sesión y bloqueo temporal tras intentos fallidos.
 */

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Session;
use App\Core\RateLimiter;
use App\Models\Usuario;

class AuthController extends Controller
{
    public function loginForm(): void
    {
        if (Session::get('admin_logged_in')) {
            $this->redirect('admin');
        }
        $this->seo->setTitle('Acceso Administrador | Carnelli Studio')->setNoIndex(true);
        $this->render('admin/login', [], null);
    }

    public function login(): void
    {
        $this->requireCsrf();

        if (RateLimiter::tooManyAttempts('admin_login', 5, 600)) {
            Session::flash('error', 'Demasiados intentos de inicio de sesión. Esperá 10 minutos antes de volver a intentar.');
            $this->redirect('admin/login');
        }

        $email    = trim((string)($_POST['email'] ?? ''));
        $password = (string)($_POST['password'] ?? '');

        $user = Usuario::findByEmail($email);

        if (!$user || !password_verify($password, (string)($user['password_hash'] ?? ''))) {
            RateLimiter::hit('admin_login', 600);
            Session::flash('error', 'Credenciales incorrectas.');
            $this->redirect('admin/login');
        }

        RateLimiter::clear('admin_login');
        Session::regenerate();
        Session::set('admin_logged_in', true);
        Session::set('admin_user_id', (int)$user['id']);
        Session::set('admin_user_email', $user['email']);

        Usuario::update((int)$user['id'], [
            'intentos_fallidos' => 0,
            'ultimo_acceso'     => date('Y-m-d H:i:s'),
        ]);

        $this->redirect('admin');
    }

    public function logout(): void
    {
        Session::destroy();
        $this->redirect('admin/login');
    }
}

