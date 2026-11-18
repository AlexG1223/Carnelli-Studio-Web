<?php
/**
 * BlogController: Listado de artículos y vista individual con Schema BlogPosting y bloque Answer-First.
 */

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\View;
use App\Models\Post;
use App\Models\Servicio;

class BlogController extends Controller
{
    public function index(): void
    {
        $cat = trim((string)($_GET['categoria'] ?? ''));

        $this->seo
            ->setTitle('Blog de Transformación Digital, Webs e IA para Pymes en Uruguay')
            ->setDescription('Guías prácticas y recursos sobre tiendas online con Mercado Pago, SEO local, Google Maps, sistemas a medida e inteligencia artificial para pymes uruguayas.')
            ->setCanonical('blog')
            ->setBreadcrumbs([
                ['label' => 'Blog', 'url' => 'blog']
            ]);

        $posts = $cat !== '' ? Post::byCategory($cat) : Post::published();

        $this->render('pages/blog/index', [
            'posts'           => $posts,
            'categorias'      => Post::categories(),
            'categoriaActual' => $cat,
            'whatsappMessage' => 'Hola Alex, estuve leyendo el blog de Carnelli Studio y quiero hacerte una consulta.',
        ]);
    }

    public function show(string $slug): void
    {
        $post = Post::findBySlug($slug);
        if (!$post || empty($post['publicado'])) {
            http_response_code(404);
            (new PaginasController())->notFound();
            return;
        }

        $this->seo
            ->setTitle($post['meta_title'] ?: $post['titulo'], false)
            ->setDescription($post['meta_description'] ?: $post['extracto'])
            ->setCanonical('blog/' . $post['slug'])
            ->setOgType('article')
            ->setOgImage($post['portada'] ?: 'assets/brand/og-default.png')
            ->setBreadcrumbs([
                ['label' => 'Blog', 'url' => 'blog'],
                ['label' => $post['titulo'], 'url' => 'blog/' . $post['slug']]
            ]);

        $this->seo->addSchema([
            '@type'            => 'BlogPosting',
            'headline'         => $post['titulo'],
            'description'      => $post['respuesta_directa'] ?: $post['extracto'],
            'image'            => View::asset($post['portada'] ?: 'assets/brand/og-default.png', false),
            'datePublished'    => date('c', strtotime($post['fecha_publicacion'] ?? 'now')),
            'dateModified'     => date('c', strtotime($post['updated_at'] ?? ($post['fecha_publicacion'] ?? 'now'))),
            'author'           => [
                '@id' => rtrim(BASE_URL, '/') . '/#founder'
            ],
            'publisher'        => [
                '@id' => rtrim(BASE_URL, '/') . '/#organization'
            ],
            'mainEntityOfPage' => View::url('blog/' . $post['slug'])
        ]);

        $this->render('pages/blog/show', [
            'post'            => $post,
            'servicios'       => array_slice(Servicio::active(), 0, 4),
            'whatsappMessage' => 'Hola Alex, leí tu artículo "' . $post['titulo'] . '" y quiero consultar para mi negocio.',
        ]);
    }
}

