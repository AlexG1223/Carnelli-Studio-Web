# Checklist SEO Técnico y GEO (Motores de IA) — Carnelli Studio

## ✅ Implementado en el Código
- [x] **Clase `Seo` centralizada (`app/core/Seo.php`):** `<title>`, `meta description`, `canonical`, `hreflang="es-UY"` y `x-default`, Open Graph (`es_UY`) y Twitter Cards dinámicas por página.
- [x] **Imagen Open Graph por defecto (`public_html/assets/brand/og-default.png` y `.webp`):** Generada en 1200×630px con el degradé oficial, imagotipo blanco y tagline.
- [x] **Tipografías WOFF2 self-hosted:** `Quicksand` y `Open Sans` servidas localmente con `font-display: swap` y `<link rel="preload">` en los pesos críticos (sin llamadas externas a Google Fonts).
- [x] **Datos estructurados JSON-LD (`app/views/partials/schema-jsonld.php`):**
  - `Organization` + `ProfessionalService` como negocio de área de servicio (sin dirección física expuesta; `areaServed`: Ciudad de la Costa, Canelones y Uruguay).
  - `sameAs` dinámico (omite automáticamente redes vacías).
  - `Person` (Alex Carnelli) vinculado como `founder`.
  - `WebSite` con `SearchAction`.
  - `Service` en las 11 páginas individuales de servicios.
  - `CreativeWork` en cada caso de estudio del portfolio.
  - `FAQPage` en Inicio, páginas de servicios y `/preguntas-frecuentes`.
  - `BlogPosting` en cada artículo del blog.
  - `BreadcrumbList` en todas las páginas interiores.
- [x] **GEO (`/llms.txt` dinámico):** Resumen en Markdown estructurado con quién es Carnelli Studio, las dos capas, los planes, los proyectos realizados y datos de contacto.
- [x] **Crawlers de IA habilitados en `/robots.txt`:** `GPTBot`, `OAI-SearchBot`, `ChatGPT-User`, `Google-Extended`, `PerplexityBot`, `ClaudeBot`, `Applebot-Extended` y `Bingbot` con `Allow: /` y bloqueo de `/admin`.
- [x] **Bloques Answer-First (40–60 palabras):** Presentes al inicio de los 11 servicios y de los 3 artículos del blog.
- [x] **Caché HTML automática (`app/core/Cache.php`):** Invalida la caché y actualiza `/sitemap.xml` cada vez que guardás cambios en el panel `/admin`.

## 📌 Queda en tus manos completar desde `/admin`
- [ ] Subir las **capturas reales** de cada proyecto desde `/admin/proyectos` (se convierten solas a WebP).
- [ ] Completar qué se hizo en el proyecto **FisioLink** y marcarlo como *Publicado*.
- [ ] Cargar **testimonios reales** en `/admin/testimonios` a medida que los clientes te los envíen (la sección se activa sola en la web cuando hay al menos uno publicado).
- [ ] Completar en `/admin/ajustes`: foto de Alex Carnelli para `/quien-soy`, link para agendar videollamada, Facebook/LinkedIn (si los abrís) e IDs de GA4 / Meta Pixel.
- [ ] Dar de alta el dominio en **Google Search Console**, **Bing Webmaster Tools** y **Perfil de Empresa en Google (Google Maps)**.
