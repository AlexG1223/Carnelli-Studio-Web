# Guía de Autodeploy con Git en Hostinger — Carnelli Studio

El repositorio está estructurado directamente en la raíz para que el **Autodeploy de Git en hPanel de Hostinger** funcione sin pasos manuales cuando despliega la rama **`main`** dentro de `public_html`.

---

## 1. Estructura de Ramas del Repositorio
- **`main`**: Rama de **Producción** (la que Hostinger despliega automáticamente en `public_html`).
- **`staging`**: Rama de **Pre-producción / QA** para validar cambios antes de pasarlos a `main`.
- **`develop`**: Rama de **Desarrollo** diario.

---

## 2. Cómo queda la estructura dentro de `public_html` al hacer Autodeploy
```text
public_html/ (Raíz del repositorio clonado por Hostinger)
├── index.php               ← Front Controller principal
├── .htaccess               ← URLs limpias, fuerza HTTPS en producción y bloquea acceso a app/ y .git/
├── favicon.ico
├── manifest.webmanifest
├── assets/                 ← CSS, JS (ES Modules), fuentes WOFF2 y Kit de Marca optimizado
├── uploads/                ← Imágenes subidas desde el Admin (protegido contra ejecución PHP)
└── app/                    ← Núcleo MVC y datos JSON (bloqueado por .htaccess con 403 Forbidden)
    ├── config/
    ├── core/
    ├── controllers/
    ├── models/
    ├── views/
    ├── data/               ← Almacenamiento flat-file JSON (servicios, proyectos, leads, settings)
    └── storage/            ← Caché HTML, logs y rate-limit
```

---

## 3. Configuración en hPanel de Hostinger (Git Autodeploy)
1. Entrá a **hPanel** → **Avanzado** → **Git**.
2. En **Crear un nuevo repositorio**:
   - **Repositorio:** `https://github.com/AlexG1223/Carnelli-Studio-Web.git`
   - **Rama:** `main`
   - **Ruta de instalación (Directorio):** dejalo vacío (para que apunte directo a `public_html`).
3. Hacé clic en **Implementar (Deploy)** y activá **Implementación automática (Auto-Deployment)** con el Webhook en GitHub.
4. *(Opcional para SMTP)*: Creá el archivo `public_html/app/.env` a partir de `app/.env.example` desde el Administrador de Archivos de Hostinger con la contraseña de tu casilla de correo para el envío SMTP. Si todavía no creaste `app/.env`, la web igual detecta automáticamente el dominio y HTTPS en producción.
