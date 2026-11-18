<?php
/**
 * Admin\ClientesController: CRUD de clientes y logos para la franja de confianza.
 */

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Session;
use App\Core\Validator;
use App\Core\ImageUploader;
use App\Models\Cliente;

class ClientesController extends Controller
{
    public function index(): void
    {
        $this->requireAdmin();
        $this->render('admin/clientes/index', [
            'adminTitle'    => 'Clientes',
            'adminSubtitle' => 'Empresas y comercios que aparecen en la franja de clientes y casos de estudio',
            'clientes'      => Cliente::all(),
        ], 'admin');
    }

    public function create(): void
    {
        $this->requireAdmin();
        $this->render('admin/clientes/form', [
            'adminTitle' => 'Nuevo Cliente',
            'cliente'    => null,
        ], 'admin');
    }

    public function store(): void
    {
        $this->requireAdmin();
        $this->requireCsrf();
        Cliente::create($this->extractData());
        Session::flash('success', 'Cliente creado.');
        $this->redirect('admin/clientes');
    }

    public function edit(string $id): void
    {
        $this->requireAdmin();
        $cl = Cliente::find((int)$id);
        if (!$cl) {
            $this->redirect('admin/clientes');
        }
        $this->render('admin/clientes/form', [
            'adminTitle' => 'Editar Cliente',
            'cliente'    => $cl,
        ], 'admin');
    }

    public function update(string $id): void
    {
        $this->requireAdmin();
        $this->requireCsrf();
        $existing = Cliente::find((int)$id);
        if ($existing) {
            Cliente::update((int)$id, $this->extractData($existing));
            Session::flash('success', 'Cliente actualizado.');
        }
        $this->redirect('admin/clientes');
    }

    public function delete(string $id): void
    {
        $this->requireAdmin();
        $this->requireCsrf();
        Cliente::delete((int)$id);
        Session::flash('success', 'Cliente eliminado.');
        $this->redirect('admin/clientes');
    }

    private function extractData(?array $existing = null): array
    {
        $nombre = Validator::cleanString($_POST['nombre'] ?? '', 120);
        $slug   = Validator::cleanSlug($_POST['slug'] ?? '') ?: Validator::cleanSlug($nombre);

        $logo = $existing['logo'] ?? '';
        if (!empty($_FILES['logo_file']['name'])) {
            $up = ImageUploader::upload($_FILES['logo_file'], 'clientes', $slug);
            if ($up) {
                $logo = $up;
            }
        }

        return [
            'nombre'        => $nombre,
            'slug'          => $slug,
            'rubro'         => Validator::cleanString($_POST['rubro'] ?? '', 120),
            'logo'          => $logo,
            'web_url'       => Validator::cleanString($_POST['web_url'] ?? '', 250),
            'instagram_url' => Validator::cleanString($_POST['instagram_url'] ?? '', 250),
            'orden'         => (int)($_POST['orden'] ?? 1),
            'visible'       => !empty($_POST['visible']) ? 1 : 0,
        ];
    }
}

