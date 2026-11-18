# Guía de Deploy en Hostinger — Carnelli Studio

Esta guía explica paso a paso cómo publicar la web de **Carnelli Studio** en tu cuenta de **Hostinger** (hPanel). Como el proyecto funciona con almacenamiento JSON (`app/data/*.json`), **no necesitás crear ni importar ninguna base de datos MySQL**.

---

## 1. Preparar PHP 8.2+ y SSL en hPanel
1. Entrá a tu **hPanel de Hostinger** → **Sitios web** → **Administrar** en tu dominio.
2. En el menú lateral izquierdo, andá a **Avanzado** → **Configuración de PHP**.
3. Seleccioná **PHP 8.2** (o superior) y verificá que la extensión `gd` esté activa (viene activa por defecto). Guardá los cambios.
4. En **Seguridad** → **SSL**, verificá que el certificado SSL gratuito esté activo.

---

## 2. Subir los Archivos (Estructura Recomendada)
En el **Administrador de Archivos** de Hostinger (o desplegando desde Git):

1. En la raíz de tu dominio (`/domains/tudominio.com.uy/`):
   - Subí la carpeta **`app/`** completa **al mismo nivel** que `public_html/` (es decir, **fuera** de `public_html` para máxima seguridad).
   - Subí el contenido de la carpeta **`public_html/`** del repositorio adentro de la carpeta `public_html/` de Hostinger.
   *(Nota: Si usás el despliegue automático por Git de hPanel que clona todo dentro de `public_html/`, el sitio también funciona automáticamente gracias al detector de `APP_PATH` en `index.php` y a la protección de `app/.htaccess` con `Require all denied`).*
2. Asegurate de que las carpetas `app/data/`, `app/storage/` y `public_html/uploads/` tengan permisos de escritura (`755` o `775`).

---

## 3. Configurar el archivo `app/.env` y HTTPS
1. Copiá el archivo `app/.env.example` como **`app/.env`** en el servidor y completá tus datos reales:
   ```ini
   APP_NAME="Carnelli Studio"
   APP_ENV="production"
   APP_DEBUG="false"
   APP_URL="https://tudominio.com.uy"
   APP_TIMEZONE="America/Montevideo"
   SECURITY_SALT="cadena_aleatoria_larga_unica"

   SMTP_ENABLED="true"
   SMTP_HOST="smtp.hostinger.com"
   SMTP_PORT="465"
   SMTP_ENCRYPTION="ssl"
   SMTP_USER="hola@tudominio.com.uy"
   SMTP_PASS="tu_clave_de_correo_hostinger"
   SMTP_FROM_EMAIL="hola@tudominio.com.uy"
   SMTP_FROM_NAME="Alex Carnelli · Carnelli Studio"
   ```
2. En `public_html/.htaccess`, descomentá las 2 líneas de redirección HTTPS para forzar conexión segura.

---

## 4. Primer Ingreso al Admin y Post-Lanzamiento
1. Entrá a `https://tudominio.com.uy/admin/login` con `carnellistudio2026@gmail.com` y `Carnelli2026!`.
2. Andá a **Ajustes (`/admin/ajustes`)** y **cambiá la contraseña del administrador** de inmediato.
3. Completá los campos marcados con `[COMPLETAR]` cuando los tengas listos (Facebook, LinkedIn, link de agenda, foto y el caso de estudio FisioLink).
4. **Post-lanzamiento SEO & GEO:**
   - Dá de alta la propiedad en **Google Search Console** y **Bing Webmaster Tools** (fundamental porque Bing alimenta las búsquedas de ChatGPT y Copilot) y enviá el sitemap: `https://tudominio.com.uy/sitemap.xml`.
   - Pegá tu ID de **Google Analytics 4 (`G-...`)** en `/admin/ajustes`.
   - Creá/optimizá tu **Perfil de Empresa en Google (Google Maps)** como negocio de área de servicio (Ciudad de la Costa y Uruguay) apuntando a tu dominio.
