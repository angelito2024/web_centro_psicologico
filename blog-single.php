<?php
require_once __DIR__ . '/inc/blog.php';

$slug = trim((string) ($_GET['slug'] ?? ''));
$posts = load_posts();
$post = find_post_by_slug($posts, $slug);

if (!$post) {
    http_response_code(404);
}

$date = $post ? format_post_date($post['created_at'] ?? '') : null;
$pageTitle = $post ? $post['title'] . ' | CP MAGUSA Blog' : 'Publicación no encontrada | CP MAGUSA';
?>
<!DOCTYPE html>
<html lang="es">
  <head>
    <title><?= h($pageTitle) ?></title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="<?= h($post ? ($post['excerpt'] ?? '') : 'Publicación no encontrada.') ?>">
    <meta name="theme-color" content="#1f7a66">
    <link rel="icon" href="favicon.ico" sizes="any">
    <link rel="icon" href="images/favicon-32.png" type="image/png" sizes="32x32">
    <link rel="apple-touch-icon" href="apple-touch-icon.png">

    <link rel="preload" as="image" href="images/bg_5-900.webp" media="(max-width: 767.98px)" fetchpriority="high">
    <link rel="preload" as="image" href="images/bg_5.webp" media="(min-width: 768px)" fetchpriority="high">
    <link rel="preload" href="fonts/lato/lato-400.woff2" as="font" type="font/woff2" crossorigin>

    <link rel="stylesheet" href="css/style.min.css">
    <link rel="stylesheet" href="css/enhancements.css">
    <noscript><style>.ftco-animate{opacity:1;visibility:visible}</style></noscript>
    <style>
      .blog-video-wrap { position: relative; width: 100%; padding-top: 56.25%; margin: 1.5rem 0; background:#000; }
      .blog-video-wrap iframe { position: absolute; inset: 0; width: 100%; height: 100%; border: 0; }
      .blog-body p { margin-bottom: 1.2em; }
      .blog-gallery { display: flex; flex-wrap: wrap; gap: 14px; margin: 1.5rem 0; }
      .blog-gallery img { width: 100%; max-width: 340px; border-radius: 6px; object-fit: cover; }
    </style>
  </head>
  <body>
    <a href="#contenido" class="sr-only sr-only-focusable skip-link">Saltar al contenido</a>

  	<div class="wrap">
      <div class="container">
        <div class="row">
          <div class="col-md-6 d-flex align-items-center">
            <p class="mb-0 phone pl-md-2">
              <a href="tel:+51922570139" class="mr-2"><span class="fa fa-phone mr-1" aria-hidden="true"></span>922 570 139</a>
              <a href="index.html#SectionInformacion" class="d-none d-md-inline"><span class="fa fa-clock-o mr-1" aria-hidden="true"></span>L-V: 8:00 a.m. – 10:00 p.m. | S: 9:00 a.m. – 6:00 p.m. | D: previa coordinación</a>
            </p>
          </div>
          <div class="col-md-6 d-flex justify-content-md-end">
            <div class="social-media">
              <p class="mb-0 d-flex">
                <a href="https://www.facebook.com/profile.php?id=61574303079409" class="d-flex align-items-center justify-content-center" target="_blank" rel="noopener" aria-label="Facebook"><span class="fa fa-facebook" aria-hidden="true"></span></a>
                <a href="https://wa.me/51922570139" class="d-flex align-items-center justify-content-center" target="_blank" rel="noopener" aria-label="WhatsApp"><span class="fa fa-whatsapp" aria-hidden="true"></span></a>
                <a href="https://www.instagram.com/magusaarcoiris/" class="d-flex align-items-center justify-content-center" target="_blank" rel="noopener" aria-label="Instagram"><span class="fa fa-instagram" aria-hidden="true"></span></a>
                <a href="https://www.youtube.com/@centropsicologicomaguesperanza" class="d-flex align-items-center justify-content-center" target="_blank" rel="noopener" aria-label="YouTube"><span class="fa fa-youtube" aria-hidden="true"></span></a>
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <nav class="navbar navbar-expand-lg navbar-dark ftco_navbar bg-dark ftco-navbar-light" id="ftco-navbar">
	    <div class="container">
	      <a class="navbar-brand" href="index.html">
			  <div class="cont-logo">
				  <img src="images/magusa3-nav.webp" alt="Centro Psicológico Magusa Arcoiris" width="223" height="60">
			  </div>
		  </a>
	      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#ftco-nav" aria-controls="ftco-nav" aria-expanded="false" aria-label="Abrir menú">
	        <span class="fa fa-bars" aria-hidden="true"></span> Menú
	      </button>

	      <div class="collapse navbar-collapse" id="ftco-nav">
	        <ul class="navbar-nav ml-auto">
	          <li class="nav-item"><a href="index.html" class="nav-link">Inicio</a></li>
	          <li class="nav-item"><a href="about.html" class="nav-link">Nosotros</a></li>
	          <li class="nav-item"><a href="services.html" class="nav-link">Servicios</a></li>
	          <li class="nav-item active"><a href="blog.php" class="nav-link">Blog</a></li>
	          <li class="nav-item"><a href="contact.html" class="nav-link">Contacto</a></li>
	        </ul>
	      </div>
	    </div>
	</nav>
    <!-- END nav -->

    <main id="contenido">

    <section class="hero-wrap hero-wrap-2 hero-bg5">
      <div class="overlay"></div>
      <div class="container">
        <div class="row no-gutters slider-text align-items-end justify-content-center">
          <div class="col-md-9 ftco-animate mb-5 text-center">
          	<p class="breadcrumbs mb-0"><span class="mr-2"><a href="index.html">Inicio <i class="fa fa-chevron-right" aria-hidden="true"></i></a></span> <span class="mr-2"><a href="blog.php">Blog <i class="fa fa-chevron-right"></i></a></span> <span><?= $post ? h($post['title']) : 'No encontrado' ?></span></p>
            <h1 class="mb-0 bread"><?= $post ? h($post['title']) : 'Publicación no encontrada' ?></h1>
          </div>
        </div>
      </div>
    </section>

    <section class="ftco-section ftco-degree-bg">
      <div class="container">
        <?php if (!$post): ?>
          <div class="row justify-content-center">
            <div class="col-md-8 text-center py-5">
              <h2 class="mb-3">No encontramos esta publicación</h2>
              <p>Puede que el enlace esté mal escrito o que la publicación haya sido eliminada.</p>
              <a href="blog.php" class="btn btn-primary py-3 px-4 mt-3">Volver al blog</a>
            </div>
          </div>
        <?php else: ?>
          <div class="row justify-content-center">
            <div class="col-lg-8 ftco-animate">
              <p class="mb-4" style="color:#999; font-size:14px;">
                <?= h($date['day'] . ' de ' . $date['mon'] . ' de ' . $date['year']) ?>
              </p>

              <?php if (!empty($post['images'])): ?>
                <div class="blog-gallery">
                  <?php foreach ($post['images'] as $img): ?>
                    <img src="<?= h($img) ?>" alt="<?= h($post['title']) ?>" loading="lazy">
                  <?php endforeach; ?>
                </div>
              <?php endif; ?>

              <?= render_video_embed_html($post['video'] ?? null) ?>

              <div class="blog-body">
                <p><?= nlbr_h($post['body']) ?></p>
              </div>

              <div class="mt-5">
                <a href="blog.php" class="btn btn-white py-3 px-4">&larr; Volver al blog</a>
              </div>
            </div>
          </div>
        <?php endif; ?>
      </div>
    </section>

    </main>

    <footer class="ftco-footer">
      <div class="container">
        <div class="row mb-5">
          <div class="col-sm-12 col-md">
            <div class="ftco-footer-widget mb-4">
              <h2 class="ftco-heading-2 logo"><a href="index.html">Centro Psicológico Magusa Arcoiris</a></h2>
              <p>Comprometidos 100 % con tu bienestar.</p>
              <ul class="ftco-footer-social list-unstyled mt-2">
                <li class="ftco-animate"><a href="https://www.facebook.com/profile.php?id=61574303079409" class="social-fb" target="_blank" rel="noopener" aria-label="Facebook"><span class="fa fa-facebook" aria-hidden="true"></span></a></li>
                <li class="ftco-animate"><a href="https://wa.me/51922570139" class="social-wsp" target="_blank" rel="noopener" aria-label="WhatsApp"><span class="fa fa-whatsapp" aria-hidden="true"></span></a></li>
                <li class="ftco-animate"><a href="https://www.instagram.com/magusaarcoiris/" class="social-inst" target="_blank" rel="noopener" aria-label="Instagram"><span class="fa fa-instagram" aria-hidden="true"></span></a></li>
                <li class="ftco-animate"><a href="https://www.youtube.com/@centropsicologicomaguesperanza" class="social-yt" target="_blank" rel="noopener" aria-label="YouTube"><span class="fa fa-youtube" aria-hidden="true"></span></a></li>
              </ul>
            </div>
          </div>
          <div class="col-sm-12 col-md">
            <div class="ftco-footer-widget mb-4 ml-md-4">
              <h2 class="ftco-heading-2">Explorar</h2>
              <ul class="list-unstyled">
                <li><a href="index.html"><span class="fa fa-chevron-right mr-2" aria-hidden="true"></span>Inicio</a></li>
                <li><a href="about.html"><span class="fa fa-chevron-right mr-2" aria-hidden="true"></span>Nosotros</a></li>
                <li><a href="services.html"><span class="fa fa-chevron-right mr-2" aria-hidden="true"></span>Servicios</a></li>
              </ul>
            </div>
          </div>
          <div class="col-sm-12 col-md">
            <div class="ftco-footer-widget mb-4">
              <h2 class="ftco-heading-2">Compañía</h2>
              <ul class="list-unstyled">
                <li><a href="blog.php"><span class="fa fa-chevron-right mr-2" aria-hidden="true"></span>Blog</a></li>
                <li><a href="contact.html"><span class="fa fa-chevron-right mr-2" aria-hidden="true"></span>Contacto</a></li>
                <li><a href="privacidad.html"><span class="fa fa-chevron-right mr-2" aria-hidden="true"></span>Política de privacidad</a></li>
              </ul>
            </div>
          </div>
          <div class="col-sm-12 col-md">
            <div class="ftco-footer-widget mb-4">
              <h2 class="ftco-heading-2">¿Tienes alguna pregunta?</h2>
              <div class="block-23 mb-3">
                <ul>
                  <li><a href="https://www.google.com/maps/search/?api=1&amp;query=-11.980595,-77.017625" target="_blank" rel="noopener"><span class="icon fa fa-map-marker" aria-hidden="true"></span><span class="text">Nevado Coropuna 364, San Juan de Lurigancho, Lima</span></a></li>
                  <li><a href="tel:+51922570139"><span class="icon fa fa-phone" aria-hidden="true"></span><span class="text">922 570 139</span></a></li>
                  <li><a href="mailto:informes@centropsicologicomagusa.com"><span class="icon fa fa-paper-plane pr-4" aria-hidden="true"></span><span class="text">informes@centropsicologicomagusa.com</span></a></li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="container-fluid px-0 py-5 bg-black">
        <div class="container">
          <div class="row">
            <div class="col-md-12">
              <p class="mb-0 copyright">Copyright &copy; <span class="js-year">2026</span> Todos los derechos reservados | Centro Psicológico Magusa Arcoiris · Plantilla por <a href="https://colorlib.com" target="_blank" rel="noopener">Colorlib</a></p>
            </div>
          </div>
        </div>
      </div>
    </footer>

    <a href="https://wa.me/51922570139?text=Hola%2C%20quisiera%20informaci%C3%B3n%20sobre%20sus%20servicios." class="wa-float" target="_blank" rel="noopener" aria-label="Escríbenos por WhatsApp"><span class="fa fa-whatsapp" aria-hidden="true"></span></a>

  <script src="js/jquery-3.7.1.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script src="js/jquery.waypoints.min.js"></script>
  <script src="js/main.js"></script>

  </body>
</html>
