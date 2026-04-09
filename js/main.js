(function($) {
	'use strict';

	$(window).stellar({
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

	$('#dropdown04').on('show.bs.dropdown', function() {
		console.log('show');
	});

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
				if (direction === 'down' && !$(this.element).hasClass('ftco-animated')) {
					var comma_separator_number_step = $.animateNumber.numberStepFactories.separator(',');
					$('.number').each(function() {
						var $this = $(this),
							num = $this.data('number');
						console.log(num);
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
	$('.image-popup').magnificPopup({
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

	$('.popup-youtube, .popup-vimeo, .popup-gmaps').magnificPopup({
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
$('#SERVICIOS').on('change', function() {
	idservicio = $('#SERVICIOS').val();
	if (idservicio === '1') {
		$('#subservicios').html(
			'<option value="0">Servicios</option><option value="CONSULTA PSICOLÓGICA INFANTIL">CONSULTA PSICOLÓGICA INFANTIL</option> <option value="EVALUACIÓN PSICOLÓGICA INFANTIL">EVALUACIÓN PSICOLÓGICA INFANTIL</option> <option value="TERAPIA EMOCIONAL">TERAPIA EMOCIONAL</option> <option value="TERAPIA DE MODIFICACIÓN DE CONDUCTA">TERAPIA DE MODIFICACIÓN DE CONDUCTA</option> <option value="TERAPIA DE ATENCIÓN Y CONCENTRACIÓN">TERAPIA DE ATENCIÓN Y CONCENTRACIÓN</option> <option value="TERAPIA DE APRENDIZAJE">TERAPIA DE APRENDIZAJE</option> <option value="EVALUACIÓN DE ORIENTACIÓN VOCACIONAL">EVALUACIÓN DE ORIENTACIÓN VOCACIONAL</option>'
		);
	} else if (idservicio === '2') {
		$('#subservicios').html(
			'<option value="0">Servicios</option><option value="CONSULTA PSICOLÓGICA ADULTO">CONSULTA PSICOLÓGICA ADULTO</option> <option value="CONSULTA PSICOLÓGICA DE PAREJA">CONSULTA PSICOLÓGICA DE PAREJA</option> <option value="CONSULTA PSICOLÓGICA FAMILIAR">CONSULTA PSICOLÓGICA FAMILIAR</option> <option value="EVALUACIÓN PSICOLÓGICA ADULTO">EVALUACIÓN PSICOLÓGICA ADULTO</option> <option value="TERAPIA PSICOLÓGICA PARA ADULTO">TERAPIA PSICOLÓGICA PARA ADULTO</option> <option value="TERAPIA DE PAREJA">TERAPIA DE PAREJA</option> <option value="TERAPIA FAMILIAR">TERAPIA FAMILIAR</option>'
		);
	} else if (idservicio === '3') {
		$('#subservicios').html(
			'<option value="0">Servicios</option><option value="TALLER DE PLASTILINA">TALLER DE PLASTILINA</option> <option value="TALLER DE PLASTILINA EN ALTO RELIEVE">TALLER DE PLASTILINA EN ALTO RELIEVE</option> <option value="TALLER DE ATRAPA SUEÑOS">TALLER DE ATRAPA SUEÑOS</option> <option value="TALLER DE HABILIDADES SOCIALES">TALLER DE HABILIDADES SOCIALES</option> <option value="TALLER DE ARTETERAPIA">TALLER DE ARTETERAPIA</option> <option value="TALLER DE ESCEULA DE PADRES">TALLER DE ESCEULA DE PADRES</option>'
		);
	}
});

function EnviarEmail() {
	//	e.preventDefault();
	datax = $('#contactForm').serializeArray();
	$.ajax({
		method: 'POST',
		url: 'contacto.php',
		data: datax
	}).done(function(respuesta) {
		alert(respuesta);
		if (respuesta === 'Correo enviado') {
			document.getElementById('contactform').reset();
		}
	});
}
