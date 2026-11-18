<?php
/**
 * Definición de rutas públicas, API JSON, SEO/GEO y Panel de Administración (/admin).
 * @var \App\Core\Router $router
 */

use App\Controllers\HomeController;
use App\Controllers\ServiciosController;
use App\Controllers\PlanesController;
use App\Controllers\ProyectosController;
use App\Controllers\BlogController;
use App\Controllers\ContactoController;
use App\Controllers\PaginasController;
use App\Controllers\SitemapController;
use App\Controllers\Admin\AuthController;
use App\Controllers\Admin\DashboardController;
use App\Controllers\Admin\LeadsController;
use App\Controllers\Admin\ProyectosController as AdminProyectosController;
use App\Controllers\Admin\ServiciosController as AdminServiciosController;
use App\Controllers\Admin\PlanesController as AdminPlanesController;
use App\Controllers\Admin\PostsController as AdminPostsController;
use App\Controllers\Admin\FaqsController as AdminFaqsController;
use App\Controllers\Admin\TestimoniosController as AdminTestimoniosController;
use App\Controllers\Admin\ClientesController as AdminClientesController;
use App\Controllers\Admin\SettingsController as AdminSettingsController;

// --- PÁGINAS PÚBLICAS ---
$router->get('/', [HomeController::class, 'index']);
$router->get('/servicios', [ServiciosController::class, 'index']);
$router->get('/servicios/{slug}', [ServiciosController::class, 'show']);
$router->get('/planes', [PlanesController::class, 'index']);
$router->get('/proyectos', [ProyectosController::class, 'index']);
$router->get('/api/proyectos', [ProyectosController::class, 'apiFilter']);
$router->get('/proyectos/{slug}', [ProyectosController::class, 'show']);
$router->get('/como-trabajamos', [PaginasController::class, 'comoTrabajamos']);
$router->get('/quien-soy', [PaginasController::class, 'quienSoy']);
$router->get('/blog', [BlogController::class, 'index']);
$router->get('/blog/{slug}', [BlogController::class, 'show']);
$router->get('/preguntas-frecuentes', [PaginasController::class, 'preguntasFrecuentes']);
$router->get('/contacto', [ContactoController::class, 'index']);
$router->post('/contacto/enviar', [ContactoController::class, 'submit']);
$router->get('/gracias', [PaginasController::class, 'gracias']);
$router->get('/politica-de-privacidad', [PaginasController::class, 'privacidad']);

// --- SEO & GEO DINÁMICOS ---
$router->get('/sitemap.xml', [SitemapController::class, 'sitemap']);
$router->get('/robots.txt', [SitemapController::class, 'robots']);
$router->get('/llms.txt', [SitemapController::class, 'llms']);

// --- PANEL DE ADMINISTRACIÓN (/admin) ---
$router->get('/admin/login', [AuthController::class, 'loginForm']);
$router->post('/admin/login', [AuthController::class, 'login']);
$router->post('/admin/logout', [AuthController::class, 'logout']);
$router->get('/admin/logout', [AuthController::class, 'logout']);

$router->get('/admin', [DashboardController::class, 'index']);

// Leads CRM
$router->get('/admin/leads', [LeadsController::class, 'index']);
$router->get('/admin/leads/exportar-csv', [LeadsController::class, 'exportCsv']);
$router->get('/admin/leads/{id}', [LeadsController::class, 'show']);
$router->post('/admin/leads/{id}/actualizar', [LeadsController::class, 'update']);
$router->post('/admin/leads/{id}/eliminar', [LeadsController::class, 'delete']);

// Proyectos CRUD
$router->get('/admin/proyectos', [AdminProyectosController::class, 'index']);
$router->get('/admin/proyectos/crear', [AdminProyectosController::class, 'create']);
$router->post('/admin/proyectos/guardar', [AdminProyectosController::class, 'store']);
$router->get('/admin/proyectos/{id}/editar', [AdminProyectosController::class, 'edit']);
$router->post('/admin/proyectos/{id}/actualizar', [AdminProyectosController::class, 'update']);
$router->post('/admin/proyectos/{id}/eliminar', [AdminProyectosController::class, 'delete']);

// Servicios CRUD
$router->get('/admin/servicios', [AdminServiciosController::class, 'index']);
$router->get('/admin/servicios/crear', [AdminServiciosController::class, 'create']);
$router->post('/admin/servicios/guardar', [AdminServiciosController::class, 'store']);
$router->get('/admin/servicios/{id}/editar', [AdminServiciosController::class, 'edit']);
$router->post('/admin/servicios/{id}/actualizar', [AdminServiciosController::class, 'update']);
$router->post('/admin/servicios/{id}/eliminar', [AdminServiciosController::class, 'delete']);

// Planes CRUD
$router->get('/admin/planes', [AdminPlanesController::class, 'index']);
$router->get('/admin/planes/{id}/editar', [AdminPlanesController::class, 'edit']);
$router->post('/admin/planes/{id}/actualizar', [AdminPlanesController::class, 'update']);

// Blog / Posts CRUD
$router->get('/admin/blog', [AdminPostsController::class, 'index']);
$router->get('/admin/blog/crear', [AdminPostsController::class, 'create']);
$router->post('/admin/blog/guardar', [AdminPostsController::class, 'store']);
$router->get('/admin/blog/{id}/editar', [AdminPostsController::class, 'edit']);
$router->post('/admin/blog/{id}/actualizar', [AdminPostsController::class, 'update']);
$router->post('/admin/blog/{id}/eliminar', [AdminPostsController::class, 'delete']);

// FAQs CRUD
$router->get('/admin/faqs', [AdminFaqsController::class, 'index']);
$router->get('/admin/faqs/crear', [AdminFaqsController::class, 'create']);
$router->post('/admin/faqs/guardar', [AdminFaqsController::class, 'store']);
$router->get('/admin/faqs/{id}/editar', [AdminFaqsController::class, 'edit']);
$router->post('/admin/faqs/{id}/actualizar', [AdminFaqsController::class, 'update']);
$router->post('/admin/faqs/{id}/eliminar', [AdminFaqsController::class, 'delete']);

// Testimonios CRUD
$router->get('/admin/testimonios', [AdminTestimoniosController::class, 'index']);
$router->get('/admin/testimonios/crear', [AdminTestimoniosController::class, 'create']);
$router->post('/admin/testimonios/guardar', [AdminTestimoniosController::class, 'store']);
$router->get('/admin/testimonios/{id}/editar', [AdminTestimoniosController::class, 'edit']);
$router->post('/admin/testimonios/{id}/actualizar', [AdminTestimoniosController::class, 'update']);
$router->post('/admin/testimonios/{id}/eliminar', [AdminTestimoniosController::class, 'delete']);

// Clientes CRUD
$router->get('/admin/clientes', [AdminClientesController::class, 'index']);
$router->get('/admin/clientes/crear', [AdminClientesController::class, 'create']);
$router->post('/admin/clientes/guardar', [AdminClientesController::class, 'store']);
$router->get('/admin/clientes/{id}/editar', [AdminClientesController::class, 'edit']);
$router->post('/admin/clientes/{id}/actualizar', [AdminClientesController::class, 'update']);
$router->post('/admin/clientes/{id}/eliminar', [AdminClientesController::class, 'delete']);

// Ajustes generales y cambio de contraseña
$router->get('/admin/ajustes', [AdminSettingsController::class, 'index']);
$router->post('/admin/ajustes/guardar', [AdminSettingsController::class, 'save']);
$router->post('/admin/ajustes/password', [AdminSettingsController::class, 'updatePassword']);

