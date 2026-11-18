<?php
/**
 * Admin\FaqsController: CRUD de preguntas frecuentes generales y por servicio.
 */

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Session;
use App\Core\Validator;
use App\Models\Faq;
use App\Models\Servicio;

class FaqsController extends Controller
{
    public function index(): void
    {
        $this->requireAdmin();
        $this->render('admin/faqs/index', [
            'adminTitle'    => 'Preguntas Frecuentes (FAQs)',
            'adminSubtitle' => 'Se usan en Inicio, en /preguntas-frecuentes, en páginas de servicio y en el Schema JSON-LD FAQPage',
            'faqs'          => Faq::all(),
            'servicios'     => Servicio::all(),
        ], 'admin');
    }

    public function create(): void
    {
        $this->requireAdmin();
        $this->render('admin/faqs/form', [
            'adminTitle' => 'Nueva Pregunta Frecuente',
            'faq'        => null,
            'servicios'  => Servicio::all(),
        ], 'admin');
    }

    public function store(): void
    {
        $this->requireAdmin();
        $this->requireCsrf();
        Faq::create($this->extractData());
        Session::flash('success', 'Pregunta frecuente creada.');
        $this->redirect('admin/faqs');
    }

    public function edit(string $id): void
    {
        $this->requireAdmin();
        $faq = Faq::find((int)$id);
        if (!$faq) {
            $this->redirect('admin/faqs');
        }
        $this->render('admin/faqs/form', [
            'adminTitle' => 'Editar Pregunta Frecuente',
            'faq'        => $faq,
            'servicios'  => Servicio::all(),
        ], 'admin');
    }

    public function update(string $id): void
    {
        $this->requireAdmin();
        $this->requireCsrf();
        Faq::update((int)$id, $this->extractData());
        Session::flash('success', 'Pregunta frecuente actualizada.');
        $this->redirect('admin/faqs');
    }

    public function delete(string $id): void
    {
        $this->requireAdmin();
        $this->requireCsrf();
        Faq::delete((int)$id);
        Session::flash('success', 'Pregunta frecuente eliminada.');
        $this->redirect('admin/faqs');
    }

    private function extractData(): array
    {
        $srvId = (int)($_POST['servicio_id'] ?? 0);
        return [
            'pregunta'        => Validator::cleanString($_POST['pregunta'] ?? '', 250),
            'respuesta'       => Validator::cleanText($_POST['respuesta'] ?? '', 2000),
            'categoria'       => Validator::cleanString($_POST['categoria'] ?? 'general', 60),
            'servicio_id'     => $srvId > 0 ? $srvId : null,
            'mostrar_en_home' => !empty($_POST['mostrar_en_home']) ? 1 : 0,
            'orden'           => (int)($_POST['orden'] ?? 1),
            'activo'          => !empty($_POST['activo']) ? 1 : 0,
        ];
    }
}

