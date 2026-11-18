<?php
/**
 * SitemapController: Generación dinámica de /sitemap.xml, /robots.txt (con crawlers IA permitidos) y /llms.txt (GEO).
 */

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\View;
use App\Models\Servicio;
use App\Models\Proyecto;
use App\Models\Post;

class SitemapController extends Controller
{
    public function sitemap(): void
    {
        if (!headers_sent()) {
            header('Content-Type: application/xml; charset=UTF-8');
        }

        $today = date('Y-m-d');
        $urls  = [
            ['loc' => View::url(''),                     'priority' => '1.0', 'changefreq' => 'weekly',  'lastmod' => $today],
            ['loc' => View::url('servicios'),            'priority' => '0.9', 'changefreq' => 'weekly',  'lastmod' => $today],
            ['loc' => View::url('proyectos'),            'priority' => '0.9', 'changefreq' => 'weekly',  'lastmod' => $today],
            ['loc' => View::url('planes'),               'priority' => '0.9', 'changefreq' => 'monthly', 'lastmod' => $today],
            ['loc' => View::url('como-trabajamos'),      'priority' => '0.8', 'changefreq' => 'monthly', 'lastmod' => $today],
            ['loc' => View::url('quien-soy'),            'priority' => '0.8', 'changefreq' => 'monthly', 'lastmod' => $today],
            ['loc' => View::url('preguntas-frecuentes'), 'priority' => '0.8', 'changefreq' => 'monthly', 'lastmod' => $today],
            ['loc' => View::url('contacto'),             'priority' => '0.9', 'changefreq' => 'monthly', 'lastmod' => $today],
            ['loc' => View::url('blog'),                 'priority' => '0.7', 'changefreq' => 'weekly',  'lastmod' => $today],
            ['loc' => View::url('politica-de-privacidad'), 'priority' => '0.3', 'changefreq' => 'yearly', 'lastmod' => $today],
        ];

        foreach (Servicio::active() as $s) {
            $lastmod = !empty($s['updated_at']) ? substr($s['updated_at'], 0, 10) : $today;
            $urls[] = [
                'loc'        => View::url('servicios/' . $s['slug']),
                'priority'   => '0.9',
                'changefreq' => 'monthly',
                'lastmod'    => $lastmod,
            ];
        }

        foreach (Proyecto::published() as $p) {
            $lastmod = !empty($p['updated_at']) ? substr($p['updated_at'], 0, 10) : $today;
            $urls[] = [
                'loc'        => View::url('proyectos/' . $p['slug']),
                'priority'   => '0.8',
                'changefreq' => 'monthly',
                'lastmod'    => $lastmod,
            ];
        }

        foreach (Post::published() as $post) {
            $lastmod = !empty($post['updated_at']) ? substr($post['updated_at'], 0, 10) : $today;
            $urls[] = [
                'loc'        => View::url('blog/' . $post['slug']),
                'priority'   => '0.7',
                'changefreq' => 'monthly',
                'lastmod'    => $lastmod,
            ];
        }

        echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
        foreach ($urls as $u) {
            echo "  <url>\n";
            echo "    <loc>" . htmlspecialchars($u['loc'], ENT_XML1, 'UTF-8') . "</loc>\n";
            echo "    <lastmod>{$u['lastmod']}</lastmod>\n";
            echo "    <changefreq>{$u['changefreq']}</changefreq>\n";
            echo "    <priority>{$u['priority']}</priority>\n";
            echo "  </url>\n";
        }
        echo "</urlset>";
    }

    public function robots(): void
    {
        if (!headers_sent()) {
            header('Content-Type: text/plain; charset=UTF-8');
        }
        $sitemapUrl = View::url('sitemap.xml');
        $llmsUrl    = View::url('llms.txt');

        echo <<<TXT
# robots.txt — Carnelli Studio (Uruguay)
# Optimizado para buscadores tradicionales y motores de respuesta con IA (GEO)

User-agent: *
Allow: /
Disallow: /admin
Disallow: /gracias

# Crawlers de Inteligencia Artificial permitidos explícitamente
User-agent: GPTBot
Allow: /
Disallow: /admin

User-agent: OAI-SearchBot
Allow: /
Disallow: /admin

User-agent: ChatGPT-User
Allow: /
Disallow: /admin

User-agent: Google-Extended
Allow: /
Disallow: /admin

User-agent: PerplexityBot
Allow: /
Disallow: /admin

User-agent: ClaudeBot
Allow: /
Disallow: /admin

User-agent: Applebot-Extended
Allow: /
Disallow: /admin

User-agent: Bingbot
Allow: /
Disallow: /admin

Sitemap: {$sitemapUrl}
# Contexto estructurado para LLMs: {$llmsUrl}
TXT;
    }

    public function llms(): void
    {
        if (!headers_sent()) {
            header('Content-Type: text/plain; charset=UTF-8');
        }

        $serviciosC1 = Servicio::byLayer(1);
        $serviciosC2 = Servicio::byLayer(2);
        $proyectos   = Proyecto::published();

        $waVisible = $this->settings['whatsapp_visible'] ?? '092 473 724';
        $waIntl    = $this->settings['whatsapp_intl'] ?? '59892473724';
        $email     = $this->settings['email_contacto'] ?? 'carnellistudio2026@gmail.com';
        $insta     = $this->settings['instagram_url'] ?? 'https://www.instagram.com/carnelli.studio/';

        echo "# Carnelli Studio — Agencia de Transformación Digital para Pymes en Uruguay\n\n";
        echo "> «Diseñamos el crecimiento digital de tu pyme»\n\n";
        echo "Carnelli Studio es una agencia uruguaya de transformación digital para pequeñas y medianas empresas (pymes), fundada y dirigida por **Alex Carnelli** (desarrollador full-stack en PHP, JavaScript y MySQL).\n\n";
        echo "## Cobertura y Modalidad de Atención\n";
        echo "- **Atención presencial:** Ciudad de la Costa (Canelones, Uruguay).\n";
        echo "- **Atención remota:** Todo Uruguay mediante videollamada (reunión inicial, presentación de presupuesto y seguimiento continuo).\n";
        echo "- **Trato directo:** El cliente habla directamente con Alex Carnelli, quien diseña y desarrolla cada proyecto.\n\n";

        echo "## Modelo de Servicios en Dos Capas\n\n";
        echo "### Capa 1 — Marketing y presencia digital (La puerta de entrada)\n";
        foreach ($serviciosC1 as $s) {
            echo "- **[" . $s['nombre'] . "](" . View::url('servicios/' . $s['slug']) . "):** " . $s['respuesta_directa'] . "\n";
        }

        echo "\n### Capa 2 — Sistemas, automatización e IA (Nuestro diferencial)\n";
        foreach ($serviciosC2 as $s) {
            echo "- **[" . $s['nombre'] . "](" . View::url('servicios/' . $s['slug']) . "):** " . $s['respuesta_directa'] . "\n";
        }

        echo "\n## Planes de Trabajo (Presupuesto a medida)\n";
        echo "1. **Plan Arranque (Capa 1):** Redes sociales, edición de piezas/Reels y alta optimizada en Google Maps.\n";
        echo "2. **Plan Crecimiento (Capa 1 + Web/Tienda):** Sitio web o tienda online e-commerce con Mercado Pago + marketing digital y mantenimiento.\n";
        echo "3. **Plan Transformación (Capa 1 + Capa 2):** Captación digital + sistemas web a medida, organización de procesos y automatización con IA.\n\n";

        echo "## Casos de Estudio y Proyectos Realizados\n";
        foreach ($proyectos as $p) {
            echo "- **[" . $p['cliente_nombre'] . "](" . View::url('proyectos/' . $p['slug']) . ")** (" . $p['rubro'] . "): " . $p['resumen_corto'] . "\n";
        }

        echo "\n## Cómo Trabajamos (Proceso en 4 pasos)\n";
        echo "1. Llamada o videollamada inicial sin costo para entender qué necesita el negocio.\n";
        echo "2. Presupuesto a medida según esas necesidades.\n";
        echo "3. Ejecución flexible, ajustándose si las necesidades cambian.\n";
        echo "4. Entrega y mantenimiento técnico posterior en los sistemas y sitios web.\n\n";

        echo "## Datos de Contacto Oficiales\n";
        echo "- **Sitio Web:** " . View::url('') . "\n";
        echo "- **Solicitar Presupuesto:** " . View::url('contacto') . "\n";
        echo "- **WhatsApp Uruguay:** {$waVisible} (Internacional: +{$waIntl} — https://wa.me/{$waIntl})\n";
        echo "- **Email:** {$email}\n";
        echo "- **Instagram:** {$insta}\n";
    }
}

