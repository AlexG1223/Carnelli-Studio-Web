<?php
/**
 * Admin\PostsController: CRUD de artículos del blog (incluyendo los 3 borradores iniciales).
 */

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Session;
use App\Core\Validator;
use App\Core\ImageUploader;
use App\Models\Post;

class PostsController extends Controller
{
    public function index(): void
    {
        $this->requireAdmin();
        $this->render('admin/posts/index', [
            'adminTitle'    => 'Blog (SEO y GEO)',
            'adminSubtitle' => 'Revisá y publicá los 3 borradores iniciales o creá nuevos artículos para pymes uruguayas',
            'posts'         => Post::all(),
        ], 'admin');
    }

    public function create(): void
    {
        $this->requireAdmin();
        $this->render('admin/posts/form', [
            'adminTitle' => 'Nuevo Artículo del Blog',
            'post'       => null,
        ], 'admin');
    }

    public function store(): void
    {
        $this->requireAdmin();
        $this->requireCsrf();
        Post::create($this->extractData());
        Session::flash('success', 'Artículo guardado.');
        $this->redirect('admin/blog');
    }

    public function edit(string $id): void
    {
        $this->requireAdmin();
        $post = Post::find((int)$id);
        if (!$post) {
            $this->redirect('admin/blog');
        }
        $this->render('admin/posts/form', [
            'adminTitle' => 'Editar Artículo',
            'post'       => $post,
        ], 'admin');
    }

    public function update(string $id): void
    {
        $this->requireAdmin();
        $this->requireCsrf();
        $existing = Post::find((int)$id);
        if ($existing) {
            Post::update((int)$id, $this->extractData($existing));
            Session::flash('success', 'Artículo actualizado.');
        }
        $this->redirect('admin/blog');
    }

    public function delete(string $id): void
    {
        $this->requireAdmin();
        $this->requireCsrf();
        Post::delete((int)$id);
        Session::flash('success', 'Artículo eliminado.');
        $this->redirect('admin/blog');
    }

    private function extractData(?array $existing = null): array
    {
        $titulo = Validator::cleanString($_POST['titulo'] ?? '', 180);
        $slug   = Validator::cleanSlug($_POST['slug'] ?? '') ?: Validator::cleanSlug($titulo);

        $portada = $existing['portada'] ?? 'assets/img/placeholders/impresos-carnelli-cover.webp';
        if (!empty($_FILES['portada_file']['name'])) {
            $up = ImageUploader::upload($_FILES['portada_file'], 'blog', $slug);
            if ($up) {
                $portada = $up;
            }
        }

        return [
            'slug'              => $slug,
            'titulo'            => $titulo,
            'respuesta_directa' => Validator::cleanText($_POST['respuesta_directa'] ?? '', 600),
            'extracto'          => Validator::cleanText($_POST['extracto'] ?? '', 400),
            'contenido'         => trim((string)($_POST['contenido'] ?? '')),
            'portada'           => $portada,
            'portada_alt'       => Validator::cleanString($_POST['portada_alt'] ?? $titulo, 160),
            'categoria'         => Validator::cleanString($_POST['categoria'] ?? 'Guías Pyme', 80),
            'autor'             => Validator::cleanString($_POST['autor'] ?? 'Alex Carnelli', 80),
            'tiempo_lectura'    => (int)($_POST['tiempo_lectura'] ?? 4),
            'fecha_publicacion' => $existing['fecha_publicacion'] ?? date('Y-m-d H:i:s'),
            'publicado'         => !empty($_POST['publicado']) ? 1 : 0,
            'meta_title'        => Validator::cleanString($_POST['meta_title'] ?? '', 160),
            'meta_description'  => Validator::cleanString($_POST['meta_description'] ?? '', 250),
        ];
    }
}

