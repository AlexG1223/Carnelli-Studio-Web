# Carnelli Studio — Web Oficial

> **«Diseñamos el crecimiento digital de tu pyme»**
> Agencia de transformación digital para pymes uruguayas fundada por **Alex Carnelli**.

## Stack Técnico
- **Backend:** PHP 8.2+ puro con arquitectura MVC liviana propia (`app/core/`).
- **Almacenamiento:** Flat-file JSON estructurado con bloqueo atómico (`JsonStore` en `app/data/*.json`), sin requerir base de datos MySQL inicialmente.
- **Frontend:** HTML5 semántico, CSS moderno con custom properties (`base.css`, `components.css`, `pages.css`, `admin.css`), fuentes **Quicksand** y **Open Sans** self-hosted en `.woff2` y JavaScript vanilla (ES Modules).
- **SEO & GEO:** Clase `Seo` centralizada, ecosistema completo `Schema.org` JSON-LD, `/sitemap.xml` dinámico, `/robots.txt` optimizado para crawlers de IA y `/llms.txt` en Markdown.

## Instalación Local (XAMPP)
1. Cloná este repositorio dentro de `C:\xampp\htdocs\carnelliStudioWeb`.
2. Copiá `app/.env.example` a `app/.env` y verificá `APP_URL="http://localhost/carnelliStudioWeb"`.
3. Iniciá **Apache** en el panel de XAMPP (no hace falta iniciar MySQL).
4. Abrí en el navegador: `http://localhost/carnelliStudioWeb/`

## Acceso al Panel de Administración (`/admin`)
- **URL:** `http://localhost/carnelliStudioWeb/admin/login`
- **Email inicial:** `carnellistudio2026@gmail.com`
- **Contraseña inicial:** `Carnelli2026!` *(Cambiala al primer ingreso desde `/admin/ajustes`)*.
