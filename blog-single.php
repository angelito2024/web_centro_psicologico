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
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700;900&family=Playfair+Display:wght@400;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/flaticon.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/open-iconic/1.1.1/font/css/open-iconic-bootstrap.min.css">
    <link rel="stylesheet" href="css/animate.css">

    <link rel="stylesheet" href="css/owl.carousel.min.css">
    <link rel="stylesheet" href="css/owl.theme.default.min.css">
    <link rel="stylesheet" href="css/magnific-popup.css">

    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/enhancements.css">
    <style>
      .blog-video-wrap { position: relative; width: 100%; padding-top: 56.25%; margin: 1.5rem 0; background:#000; }
      .blog-video-wrap iframe { position: absolute; inset: 0; width: 100%; height: 100%; border: 0; }
      .blog-body p { margin-bottom: 1.2em; }
      .blog-gallery { display: flex; flex-wrap: wrap; gap: 14px; margin: 1.5rem 0; }
      .blog-gallery img { width: 100%; max-width: 340px; border-radius: 6px; object-fit: cover; }
    </style>
  </head>
  <body>

  	<div class="wrap">
		<div class="container">
			<div class="row">
				<div class="col-md-6 d-flex align-items-center">
					<p class="mb-0 phone pl-md-2">
						<a href="#" class="mr-2"><span class="fa fa-phone mr-1"></span>946484908</a>
						<a href="#"><span class="fa fa-paper-plane mr-1"></span> L-V: 8:00 a.m. – 10:00 p.m. | S: 9:00 a.m. – 6:00 p.m. | D: previa coordinación </a>
					</p>
				</div>
				<div class="col-md-6 d-flex justify-content-md-end">
					<div class="social-media">
						<p class="mb-0 d-flex">
							<a href="https://www.facebook.com/profile.php?id=61574303079409" class="d-flex align-items-center justify-content-center"  target="_blank"><span class="fa fa-facebook"><i class="sr-only">Facebook</i></span></a>
							<a href="https://wa.me/51946484908" class="d-flex align-items-center justify-content-center"  target="_blank"><span class="fa fa-whatsapp"><i class="sr-only"></i></span></a>
							<a href="https://www.instagram.com/magusaarcoiris/" class="d-flex align-items-center justify-content-center"  target="_blank"><span class="fa fa-instagram"><i class="sr-only">Instagram</i></span></a>
							<a href="https://www.youtube.com/@centropsicologicomaguesperanza" class="d-flex align-items-center justify-content-center" target="_blank"><span class="fa fa-youtube"><i class="sr-only">youtube</i></span></a>
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
				  <img src="images/magusa3.webp" alt="Logo Centro Psicológico Magusa Arcoiris">
			  </div>
		  </a>
	      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#ftco-nav" aria-controls="ftco-nav" aria-expanded="false" aria-label="Toggle navigation">
	        <span class="oi oi-menu"></span> Menu
	      </button>

	      <div class="collapse navbar-collapse" id="ftco-nav">
	        <ul class="navbar-nav ml-auto">
	          <li class="nav-item"><a href="index.html" class="nav-link">Inicio</a></li>
	          <li class="nav-item"><a href="about.html" class="nav-link">Nosotros</a></li>
	          <li class="nav-item"><a href="services.html" class="nav-link">Servicios</a></li>
	          <li class="nav-item active"><a href="blog.php" class="nav-link">Blog</a></li>
	          <li class="nav-item"><a href="contact.html" class="nav-link">Citas</a></li>
	        </ul>
	      </div>
	    </div>
	</nav>
    <!-- END nav -->

    <section class="hero-wrap hero-wrap-2" style="background-image: url('images/bg_5.webp');">
      <div class="overlay"></div>
      <div class="container">
        <div class="row no-gutters slider-text align-items-end justify-content-center">
          <div class="col-md-9 ftco-animate mb-5 text-center">
          	<p class="breadcrumbs mb-0"><span class="mr-2"><a href="index.html">Home <i class="fa fa-chevron-right"></i></a></span> <span class="mr-2"><a href="blog.php">Blog <i class="fa fa-chevron-right"></i></a></span> <span><?= $post ? h($post['title']) : 'No encontrado' ?></span></p>
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

    <footer class="ftco-footer">
      <div class="container">
        <div class="row mb-5">
          <div class="col-sm-12 col-md">
            <div class="ftco-footer-widget mb-4">
              <h2 class="ftco-heading-2 logo"><a href="index.html">Centro Psicológico Magusa Arcoiris</a></h2>
              <p>Comprometidos 100% con tu bienestar.</p>
              <ul class="ftco-footer-social list-unstyled mt-2">
                <li class="ftco-animate"><a href="https://www.facebook.com/profile.php?id=61574303079409" class="social-fb"  target="_blank"><span class="fa fa-facebook"></span></a></li>
				<li class="ftco-animate"><a href="https://wa.me/51946484908" class="social-wsp"  target="_blank"><span class="fa fa-whatsapp"></span></a></li>
                <li class="ftco-animate"><a href="https://www.instagram.com/magusaarcoiris/" class="social-inst"  target="_blank"><span class="fa fa-instagram"></span></a></li>
				<li class="ftco-animate"><a href="https://www.youtube.com/@centropsicologicomaguesperanza" class="social-yt"  target="_blank"><span class="fa fa-youtube"></span></a></li>
              </ul>
            </div>
          </div>
          <div class="col-sm-12 col-md">
            <div class="ftco-footer-widget mb-4 ml-md-4">
              <h2 class="ftco-heading-2">Explorar</h2>
              <ul class="list-unstyled">
                <li><a href="about.html"><span class="fa fa-chevron-right mr-2"></span>Nosotros</a></li>
                <li><a href="contact.html"><span class="fa fa-chevron-right mr-2"></span>Contacto</a></li>
                <li><a href="services.html"><span class="fa fa-chevron-right mr-2"></span>¿Qué Hacemos?</a></li>
              </ul>
            </div>
          </div>
          <div class="col-sm-12 col-md">
             <div class="ftco-footer-widget mb-4">
              <h2 class="ftco-heading-2">Compañía</h2>
              <ul class="list-unstyled">
                <li><a href="about.html"><span class="fa fa-chevron-right mr-2"></span>Nosotros</a></li>
                <li><a href="blog.php"><span class="fa fa-chevron-right mr-2"></span>Blog</a></li>
                <li><a href="contact.html"><span class="fa fa-chevron-right mr-2"></span>Contacto</a></li>
                <li><a href="services.html"><span class="fa fa-chevron-right mr-2"></span>Nuestros Servicios</a></li>
              </ul>
            </div>
          </div>
          <div class="col-sm-12 col-md">
            <div class="ftco-footer-widget mb-4">
            	<h2 class="ftco-heading-2">¿Tienes alguna Pregunta?</h2>
            	<div class="block-23 mb-3">
	              <ul>
	                <li><span class="icon fa fa-map marker"></span><span class="text">nevado coropuna 364 san juan de lurigancho</span></li>
	                <li><a href="tel:+51946484908"><span class="icon fa fa-phone"></span><span class="text">946484908</span></a></li>
	                <li><a href="mailto:informes@centropsicologicomagusa.com"><span class="icon fa fa-paper-plane pr-4"></span><span class="text">informes@centropsicologicomagusa.com</span></a></li>
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
	            <p class="mb-0" style="color: rgba(255,255,255,.5);">
	  Copyright &copy;<script>document.write(new Date().getFullYear());</script> Todos los derechos reservados | Centro Psicológico Magusa Arcoiris</p>
	          </div>
	        </div>
      	</div>
      </div>
    </footer>

  <!-- loader -->
  <div id="ftco-loader" class="show fullscreen"><svg class="circular" width="48px" height="48px"><circle class="path-bg" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke="#eeeeee"/><circle class="path" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke-miterlimit="10" stroke="#F96D00"/></svg></div>

  <script src="https://code.jquery.com/jquery-3.2.1.min.js" crossorigin="anonymous"></script>
  <script src="https://code.jquery.com/jquery-migrate-3.0.1.min.js" crossorigin="anonymous"></script>
  <script src="js/popper.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script src="js/jquery.easing.1.3.js"></script>
  <script src="js/jquery.waypoints.min.js"></script>
  <script src="js/jquery.stellar.min.js"></script>
  <script src="js/owl.carousel.min.js"></script>
  <script src="js/jquery.magnific-popup.min.js"></script>
  <script src="js/jquery.animateNumber.min.js"></script>
  <script src="js/main.js"></script>

  </body>
</html>
