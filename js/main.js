(function($) {
	'use strict';

	// Los plugins opcionales solo se cargan en las páginas que los usan
	if ($.fn.stellar) $(window).stellar({
		responsive: true,
		parallaxBackgrounds: true,
		parallaxElements: true,
		horizontalScrolling: false,
		hideDistantElements: false,
		scrollProperty: 'scroll'
	});

	var fullHeight = function() {
		$('.js-fullheight').css('height', $(window).height());
		$(window).resize(function() {
			$('.js-fullheight').css('height', $(window).height());
		});
	};
	fullHeight();

	// loader
	var loader = function() {
		setTimeout(function() {
			if ($('#ftco-loader').length > 0) {
				$('#ftco-loader').removeClass('show');
			}
		}, 1);
	};
	loader();

	var carousel = function() {
		if (!$.fn.owlCarousel) return;
		$('.carousel-testimony').owlCarousel({
			center: true,
			loop: true,
			autoplay: true,
			autoplaySpeed: 2000,
			items: 1,
			margin: 30,
			stagePadding: 0,
			nav: false,
			navText: [ '<span class="ion-ios-arrow-back">', '<span class="ion-ios-arrow-forward">' ],
			responsive: {
				0: {
					items: 1
				},
				600: {
					items: 2
				},
				1000: {
					items: 3
				}
			}
		});
	};
	carousel();

	$('nav .dropdown').hover(
		function() {
			var $this = $(this);
			// 	 timer;
			// clearTimeout(timer);
			$this.addClass('show');
			$this.find('> a').attr('aria-expanded', true);
			// $this.find('.dropdown-menu').addClass('animated-fast fadeInUp show');
			$this.find('.dropdown-menu').addClass('show');
		},
		function() {
			var $this = $(this);
			// timer;
			// timer = setTimeout(function(){
			$this.removeClass('show');
			$this.find('> a').attr('aria-expanded', false);
			// $this.find('.dropdown-menu').removeClass('animated-fast fadeInUp show');
			$this.find('.dropdown-menu').removeClass('show');
			// }, 100);
		}
	);


	// scroll
	var scrollWindow = function() {
		$(window).scroll(function() {
			var $w = $(this),
				st = $w.scrollTop(),
				navbar = $('.ftco_navbar'),
				sd = $('.js-scroll-wrap');

			if (st > 150) {
				if (!navbar.hasClass('scrolled')) {
					navbar.addClass('scrolled');
				}
			}
			if (st < 150) {
				if (navbar.hasClass('scrolled')) {
					navbar.removeClass('scrolled sleep');
				}
			}
			if (st > 350) {
				if (!navbar.hasClass('awake')) {
					navbar.addClass('awake');
				}

				if (sd.length > 0) {
					sd.addClass('sleep');
				}
			}
			if (st < 350) {
				if (navbar.hasClass('awake')) {
					navbar.removeClass('awake');
					navbar.addClass('sleep');
				}
				if (sd.length > 0) {
					sd.removeClass('sleep');
				}
			}
		});
	};
	scrollWindow();

	var counter = function() {
		$('#section-counter, .hero-wrap, .ftco-counter').waypoint(
			function(direction) {
				if (direction === 'down' && !$(this.element).hasClass('ftco-animated') && $.animateNumber) {
					var comma_separator_number_step = $.animateNumber.numberStepFactories.separator(',');
					$('.number').each(function() {
						var $this = $(this),
							num = $this.data('number');
						$this.animateNumber(
							{
								number: num,
								numberStep: comma_separator_number_step
							},
							2500
						);
					});
				}
			},
			{ offset: '95%' }
		);
	};
	counter();

	var contentWayPoint = function() {
		var i = 0;
		$('.ftco-animate').waypoint(
			function(direction) {
				if (direction === 'down' && !$(this.element).hasClass('ftco-animated')) {
					i++;

					$(this.element).addClass('item-animate');
					setTimeout(function() {
						$('body .ftco-animate.item-animate').each(function(k) {
							var el = $(this);
							setTimeout(
								function() {
									var effect = el.data('animate-effect');
									if (effect === 'fadeIn') {
										el.addClass('fadeIn ftco-animated');
									} else if (effect === 'fadeInLeft') {
										el.addClass('fadeInLeft ftco-animated');
									} else if (effect === 'fadeInRight') {
										el.addClass('fadeInRight ftco-animated');
									} else {
										el.addClass('fadeInUp ftco-animated');
									}
									el.removeClass('item-animate');
								},
								k * 80,
								'easeInOutExpo'
							);
						});
					}, 100);
				}
			},
			{ offset: '95%' }
		);
	};
	contentWayPoint();

	// magnific popup
	if ($.fn.magnificPopup) $('.image-popup').magnificPopup({
		type: 'image',
		closeOnContentClick: true,
		closeBtnInside: false,
		fixedContentPos: true,
		mainClass: 'mfp-no-margins mfp-with-zoom', // class to remove default margin from left and right side
		gallery: {
			enabled: true,
			navigateByImgClick: true,
			preload: [ 0, 1 ] // Will preload 0 - before current, and 1 after the current image
		},
		image: {
			verticalFit: true
		},
		zoom: {
			enabled: true,
			duration: 300 // don't foget to change the duration also in CSS
		}
	});

	if ($.fn.magnificPopup) $('.popup-youtube, .popup-vimeo, .popup-gmaps').magnificPopup({
		disableOn: 700,
		type: 'iframe',
		mainClass: 'mfp-fade',
		removalDelay: 160,
		preloader: false,

		fixedContentPos: false
	});

	$('[data-toggle="popover"]').popover();
	$('[data-toggle="tooltip"]').tooltip();
})(jQuery);

$('.submenu .nav-link').on('click', function() {
	$('.submenu li a').removeClass('active');
});

// En móvil el contenido de la pestaña queda debajo de la lista: lo llevamos a la vista
$('.tabulation [data-toggle="tab"]').on('shown.bs.tab', function() {
	if (window.matchMedia('(max-width: 767.98px)').matches) {
		var destino = document.querySelector(this.getAttribute('href'));
		if (destino) destino.scrollIntoView({ behavior: 'smooth', block: 'start' });
	}
});

// Año actual en el copyright
$('.js-year').text(new Date().getFullYear());

// Mapa de Google: se inserta el iframe solo cuando el visitante pulsa "Ver mapa"
$('.js-cargar-mapa').on('click', function() {
	var $contenedor = $(this).closest('.map-facade');
	$('<iframe>', {
		src: $(this).data('src'),
		title: 'Ubicación del Centro Psicológico Magusa Arcoiris en Google Maps',
		allowfullscreen: '',
		referrerpolicy: 'no-referrer-when-downgrade'
	}).appendTo($contenedor.empty()).trigger('focus');
});

// Imágenes de fondo diferidas: se descargan al acercarse a la pantalla (data-bg="ruta")
(function() {
	var fondos = document.querySelectorAll('[data-bg]');
	var cargar = function(el) {
		el.style.backgroundImage = 'url("' + el.getAttribute('data-bg') + '")';
		el.removeAttribute('data-bg');
	};
	if (!('IntersectionObserver' in window)) {
		Array.prototype.forEach.call(fondos, cargar);
		return;
	}
	var observador = new IntersectionObserver(function(entradas) {
		entradas.forEach(function(entrada) {
			if (entrada.isIntersecting) {
				cargar(entrada.target);
				observador.unobserve(entrada.target);
			}
		});
	}, { rootMargin: '300px 0px' });
	Array.prototype.forEach.call(fondos, function(el) {
		observador.observe(el);
	});
})();

var subserviciosPorServicio = {
	'Servicio psicológico para niños y adolescentes': [
		'Consulta psicológica infantil',
		'Evaluación psicológica infantil',
		'Terapia emocional',
		'Terapia de modificación de conducta',
		'Terapia de atención y concentración',
		'Terapia de aprendizaje',
		'Evaluación de orientación vocacional'
	],
	'Servicio psicológico para adultos': [
		'Consulta psicológica adulto',
		'Consulta psicológica de pareja',
		'Consulta psicológica familiar',
		'Evaluación psicológica adulto',
		'Terapia psicológica para adulto',
		'Terapia de pareja',
		'Terapia familiar'
	],
	'Talleres': [
		'Taller de plastilina',
		'Taller de plastilina en alto relieve',
		'Taller de atrapasueños',
		'Taller de habilidades sociales',
		'Taller de arteterapia',
		'Escuela de padres'
	]
};

var reiniciarSubservicios = function(opciones) {
	var $sub = $('#subservicios').empty().append('<option value="">Tipo de consulta (opcional)</option>');
	$.each(opciones || [], function(i, nombre) {
		$sub.append($('<option>').val(nombre).text(nombre));
	});
};

$('#SERVICIOS').on('change', function() {
	reiniciarSubservicios(subserviciosPorServicio[this.value]);
});

$('#contactForm').on('submit', function(e) {
	e.preventDefault();

	var $form = $(this);
	var $submit = $form.find('[type="submit"]');
	var $feedback = $form.find('.submitting');

	$submit.prop('disabled', true);
	$feedback.removeClass('text-success text-danger').text('Enviando...');

	$.ajax({
		method: 'POST',
		url: 'contacto.php',
		data: $form.serializeArray()
	}).done(function(respuesta) {
		if (respuesta === 'Correo enviado') {
			$feedback.removeClass('text-danger').addClass('text-success').text('¡Mensaje enviado! Nos pondremos en contacto pronto.');
			$form[0].reset();
			reiniciarSubservicios();
		} else {
			// Solo mostramos los mensajes propios de contacto.php; cualquier otra salida
			// (avisos de PHP, HTML de error del servidor) se reemplaza por un texto genérico
			var esMensajePropio = typeof respuesta === 'string' && respuesta.length < 200 && !/[<>]|warning|error:|fatal/i.test(respuesta);
			$feedback.removeClass('text-success').addClass('text-danger').text(
				esMensajePropio ? respuesta : 'No se pudo enviar el mensaje. Intenta nuevamente o contáctanos por WhatsApp.'
			);
		}
	}).fail(function() {
		$feedback.removeClass('text-success').addClass('text-danger').text('No se pudo enviar el mensaje. Intenta nuevamente o contáctanos por WhatsApp.');
	}).always(function() {
		$submit.prop('disabled', false);
	});
});
