# Centro Psicológico Magusa Arcoiris — sitio web

Sitio institucional en HTML/CSS/JS con backend PHP para el formulario de contacto y un blog administrable.

## Configuración necesaria en cada máquina/servidor nuevo

Dos archivos con credenciales **no se suben a git** (están en `.gitignore`) porque contienen contraseñas reales. Al clonar el repositorio en otra estación o servidor, hay que recrearlos a mano:

### 1. Correo del formulario de contacto (`mail-config.php`)

```bash
cp mail-config.sample.php mail-config.php
```

Edita `mail-config.php` y completa:
- `smtp_user` / `smtp_pass`: cuenta de Gmail y su **contraseña de aplicación** (no la contraseña normal de la cuenta — genera una en Gmail → Seguridad → Verificación en 2 pasos → Contraseñas de aplicaciones).
- `to_email`: buzón donde deben llegar los mensajes del formulario (actualmente `centropsicologicomaguesperanza@gmail.com`).

> La contraseña que originalmente estaba escrita en `contacto.php` quedó expuesta en el historial de git de este proyecto. Si aún no la cambiaste en la cuenta de Gmail, hazlo cuanto antes.

### 2. Panel del blog (`admin/admin-config.php`)

```bash
cp admin/admin-config.sample.php admin/admin-config.php
```

Genera el hash de tu contraseña de administrador:

```bash
php -r "echo password_hash('TU_CONTRASEÑA', PASSWORD_DEFAULT), PHP_EOL;"
```

y pégalo en `admin/admin-config.php`. La contraseña inicial configurada en este proyecto es `Magusa2026*` — cámbiala cuanto antes.

### 3. Permisos de carpetas (en el servidor de producción)

El servidor necesita permiso de escritura en:
- `blog-data/` (donde se guarda `posts.json`)
- `uploads/blog/` (imágenes subidas desde el panel)

## Estructura del proyecto

```
index.html, about.html, services.html, contact.html, pricing.html, counselor.html   → páginas estáticas
blog.php, blog-single.php                                                            → blog público (dinámico)
admin/                                                                                → panel privado para publicar en el blog
inc/blog.php                                                                          → funciones compartidas del blog
blog-data/posts.json                                                                  → publicaciones del blog (no editar a mano)
uploads/blog/                                                                         → imágenes subidas desde el panel
contacto.php                                                                          → procesa el formulario de contacto (PHPMailer)
mail-config.php, admin/admin-config.php                                              → credenciales locales (gitignored)
images/                                                                               → todas en formato WebP
```

## Cómo publicar en el blog

1. Entra a `/admin/` en el navegador e inicia sesión con la contraseña configurada.
2. "+ Nueva publicación" → título, texto, imágenes (opcional) y un link de YouTube o Instagram (opcional) para videos cortos.
3. Publicar. Aparece de inmediato en `/blog.php`.
4. Para videos: pega el link tal cual del navegador (YouTube normal, YouTube Shorts, o Reel/publicación de Instagram) — el sistema reconoce el link y arma el video incrustado, no hace falta ningún código.

## Registro de cambios de esta sesión (2026-08-29)

- **Seguridad**: la contraseña SMTP de `contacto.php` se movió a `mail-config.php` (fuera de git); el formulario de contacto ahora valida y sanea los datos antes de enviarlos.
- **Formulario de contacto**: arreglado el bug real (el envío no prevenía el comportamiento nativo del botón, lo que a veces cancelaba el envío); ambos formularios (home y página de Contacto) ahora comparten la misma lógica corregida en `js/main.js`.
- **Datos de contacto actualizados en todo el sitio**: teléfono (946484908), Facebook, Instagram (@magusaarcoiris), YouTube y WhatsApp (generado desde el número oficial).
- **Imágenes**: las 49 JPG/JPEG + 3 PNG del proyecto se convirtieron a WebP (43% menos peso, ~1.3 MB ahorrados), corrigiendo de paso una imagen rota (`plastilina-2.jpg` inexistente).
- **Blog nuevo**: sección completa para publicar texto, imágenes y videos cortos en cualquier momento, sin tocar código (ver arriba). Reemplaza las páginas de plantilla `blog.html`/`blog-single.html` que tenían contenido de relleno.
- **Publicidad para Facebook**: se diseñó un anuncio cuadrado (1080×1080) con la identidad visual real del sitio, publicado como Artifact de Claude (pídele el link a Claude si lo necesitas de nuevo).

## Pendientes conocidos (no resueltos aún)

- Restringir por dominio la API key de Google Maps expuesta en el HTML (`AIzaSyBVWaKrjvy3MaE7SQ74_uJiULgl1JY0H2s`), vía Google Cloud Console.
- Hay archivos `.DS_Store` y un `.bak` viejos ya versionados en git (el `.gitignore` actual evita que se agreguen nuevos, pero no quita los existentes).
- Falta favicon y `<meta name="description">` en las páginas para SEO.
- Contenido casi duplicado entre las descripciones de los 6 talleres en `index.html`/`services.html`.
- Sección "Horarios Disponibles" en `contact.html` está oculta (comentada) porque tenía datos de ejemplo — reactivar cuando haya profesionales y horarios reales.
