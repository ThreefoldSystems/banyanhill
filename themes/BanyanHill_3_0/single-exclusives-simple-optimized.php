<?php
/*
Template Name: Simple Optimized
Template Post Type: exclusives
*/
	get_header('single-exclusives-simple-optimized');

	// Get xcode value from the backend
	if ( get_post_meta(get_the_ID(), 'site_xcode', true) ) {
		$site_xcode = get_post_meta(get_the_ID(), 'site_xcode', true);
	} else {
		$site_xcode = '';
	}

	if ( get_post_meta(get_the_ID(), 'is_indexed', true) === '1' ) {
		add_filter( 'wpseo_robots', function() { return 'index, follow'; } );
	}

	global $bh_sua_plugin_exists;
	$bh_sua_plugin_exists = false;
?>
		<script type="text/javascript">
			var exclusive_post_site_excode = '<?php echo $site_xcode; ?>';
		</script>
		<div class="ad_txt"><?php the_field('advertorialtxt'); ?></div>
		<div class="exclusives_div"></div>
		<div id="main-content">
			<?php
				if ( et_builder_is_product_tour_enabled() ):

					while ( have_posts() ): the_post(); ?>

						<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
							<div class="entry-content">
							<?php
								the_content();
							?>
							</div><!-- .entry-content -->

						</article> <!-- .et_pb_post -->

				<?php endwhile;
				else:
			?>
			<div class="container">
				<div id="content-area" class="clearfix">
					<div class="et_pb_extra_column_main et_pb_extra_column_main-na">

						<?php
						if ( have_posts() ) :
							while ( have_posts() ) : the_post(); ?>
						<article id="post-<?php the_ID(); ?>" <?php post_class( 'module single-post-module' ); ?>>
							<div class="post-header">
								<div class="smallHeader">
									<img src="https://cdn.banyanhill.com/wp-content/uploads/banyan-logo-New.png" alt="Banyan Hill Publishing" id="logo">
								</div>
								<h1 class="entry-title"><?php the_title(); ?></h1>
							<?php
								$exclusive_author = get_user_by('slug', str_replace('/ /g', '-', strtolower( get_post_meta(get_the_ID(), 'exclusive_author', true) ) ) );

								if ($exclusive_author) {
							?>
								<div class="post-meta vcard">
									<p>Written by <a href="<?php echo get_author_posts_url($exclusive_author->ID); ?>" class="url fn" title="Posts by <?php echo $exclusive_author->display_name; ?>" rel="author"><?php echo $exclusive_author->display_name; ?></a><span> | <?php echo date('M j, Y'); ?></span>
									</p>
								</div>
							<?php } ?>
							</div>
							<div class="post-wrap">
								<div class="post-content entry-content">
									<?php
									$bh_sua_plugin_exists = has_shortcode(get_the_content(), 'bh-sua');

									the_content();
									?>
									<?php
										wp_link_pages( array(
											'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'extra' ),
											'after'  => '</div>',
										) );
									?>
								</div>
							</div><!-- /.post-wrap -->

						</article>
						<?php
							endwhile;
						else :
							?>
							<h2><?php esc_html_e( 'Post not found', 'extra' ); ?></h2>
							<?php
						endif;
						?>
					</div><!-- /.et_pb_extra_column.et_pb_extra_column_main -->
				</div> <!-- #content-area -->
			</div> <!-- .container -->
			<?php endif; ?>
		</div> <!-- #main-content -->

	<?php
	$current_user = wp_get_current_user();
	if (user_can( $current_user, 'administrator' )) { ?>
			</div> <!-- #page-container -->
			<div class="exclusiveFooter"><!-- Simple footer -->
				<img src="https://cdn.banyanhill.com/wp-content/uploads/banyan-logo-New.png" alt="Banyan Hill Publishing" id="logofooter"><div class="exclusiveLinks"><a href="https://banyanhill.com/about-us/" target="_blank" rel="noopener noreferrer">ABOUT US</a> | <a href="https://banyanhill.com/privacy-policy/" target="_blank" rel="noopener noreferrer">PRIVACY POLICY</a> | <a href="https://banyanhill.com/disclaimer/" target="_blank" rel="noopener noreferrer">TERMS &amp; CONDITIONS</a> | <a href="https://privacyportal-cdn.onetrust.com/dsarwebform/90ddaa87-9d70-4282-9d4f-d6cbd96bd224/3c18808a-ad77-4f45-953c-5e4a84d8dcf1.html" target="_blank" rel="noopener noreferrer">DO NOT SELL MY INFO</a> | <a href="https://banyanhill.com/contact-us/" target="_top" rel="noopener noreferrer">CONTACT US</a></div>
				<span>Banyan Hill Publishing is dedicated to providing the best experience for our customers and prospective customers. If you have any questions please contact our customer service department. You can reach us by phone at 866-584-4096. You can reach us by email by filling out the form <a href="https://banyanhill.com/contact-us/" target="_blank" rel="noopener noreferrer">here</a>. Or, you can reach us by mail at P.O. Box 8378 Delray Beach, FL 33482 US.</span>
				<span><strong>*Disclaimer:</strong> While the examples above are real, the results may not be typical. All investing involves risk and you should never invest more than you're prepared to lose.</span>
				<span class="copyright"><strong>Banyan Hill Publishing</strong> © <?php echo date("Y");?></span>
			</div>
			<span title="Back To Top" id="back_to_top"></span>

			<?php wp_footer(); ?>
			<script async defer type='text/javascript' src='/wp-content/themes/BanyanHill_3_0/js/jquery.matchHeight.js'></script>
			<script async defer type='text/javascript' src='/wp-content/themes/BanyanHill_3_0/js/jQuery.verticalCarousel.js'></script>
			<script async defer type='text/javascript' src='/wp-content/themes/BanyanHill_3_0/js/banyanhill.js'></script>
			<script>
				var isUserLoggedIn = <?php echo is_user_logged_in() ? 'true' : 'false'; ?>;
				var isMobile = window.matchMedia('only screen and (max-width: 767px)');
				var lazyLoadImages = function() {
					/*!
					  hey, [be]Lazy.js - v1.8.2 - 2016.10.25
					  A fast, small and dependency free lazy load script (https://github.com/dinbror/blazy)
					  (c) Bjoern Klinggaard - @bklinggaard - http://dinbror.dk/blazy
					*/
					  (function(q,m){"function"===typeof define&&define.amd?define(m):"object"===typeof exports?module.exports=m():q.Blazy=m()})(this,function(){function q(b){var c=b._util;c.elements=E(b.options);c.count=c.elements.length;c.destroyed&&(c.destroyed=!1,b.options.container&&l(b.options.container,function(a){n(a,"scroll",c.validateT)}),n(window,"resize",c.saveViewportOffsetT),n(window,"resize",c.validateT),n(window,"scroll",c.validateT));m(b)}function m(b){for(var c=b._util,a=0;a<c.count;a++){var d=c.elements[a],e;a:{var g=d;e=b.options;var p=g.getBoundingClientRect();if(e.container&&y&&(g=g.closest(e.containerClass))){g=g.getBoundingClientRect();e=r(g,f)?r(p,{top:g.top-e.offset,right:g.right+e.offset,bottom:g.bottom+e.offset,left:g.left-e.offset}):!1;break a}e=r(p,f)}if(e||t(d,b.options.successClass))b.load(d),c.elements.splice(a,1),c.count--,a--}0===c.count&&b.destroy()}function r(b,c){return b.right>=c.left&&b.bottom>=c.top&&b.left<=c.right&&b.top<=c.bottom}function z(b,c,a){if(!t(b,a.successClass)&&(c||a.loadInvisible||0<b.offsetWidth&&0<b.offsetHeight))if(c=b.getAttribute(u)||b.getAttribute(a.src)){c=c.split(a.separator);var d=c[A&&1<c.length?1:0],e=b.getAttribute(a.srcset),g="img"===b.nodeName.toLowerCase(),p=(c=b.parentNode)&&"picture"===c.nodeName.toLowerCase();if(g||void 0===b.src){var h=new Image,w=function(){a.error&&a.error(b,"invalid");v(b,a.errorClass);k(h,"error",w);k(h,"load",f)},f=function(){g?p||B(b,d,e):b.style.backgroundImage='url("'+d+'")';x(b,a);k(h,"load",f);k(h,"error",w)};p&&(h=b,l(c.getElementsByTagName("source"),function(b){var c=a.srcset,e=b.getAttribute(c);e&&(b.setAttribute("srcset",e),b.removeAttribute(c))}));n(h,"error",w);n(h,"load",f);B(h,d,e)}else b.src=d,x(b,a)}else"video"===b.nodeName.toLowerCase()?(l(b.getElementsByTagName("source"),function(b){var c=a.src,e=b.getAttribute(c);e&&(b.setAttribute("src",e),b.removeAttribute(c))}),b.load(),x(b,a)):(a.error&&a.error(b,"missing"),v(b,a.errorClass))}function x(b,c){v(b,c.successClass);c.success&&c.success(b);b.removeAttribute(c.src);b.removeAttribute(c.srcset);l(c.breakpoints,function(a){b.removeAttribute(a.src)})}function B(b,c,a){a&&b.setAttribute("srcset",a);b.src=c}function t(b,c){return-1!==(" "+b.className+" ").indexOf(" "+c+" ")}function v(b,c){t(b,c)||(b.className+=" "+c)}function E(b){var c=[];b=b.root.querySelectorAll(b.selector);for(var a=b.length;a--;c.unshift(b[a]));return c}function C(b){f.bottom=(window.innerHeight||document.documentElement.clientHeight)+b;f.right=(window.innerWidth||document.documentElement.clientWidth)+b}function n(b,c,a){b.attachEvent?b.attachEvent&&b.attachEvent("on"+c,a):b.addEventListener(c,a,{capture:!1,passive:!0})}function k(b,c,a){b.detachEvent?b.detachEvent&&b.detachEvent("on"+c,a):b.removeEventListener(c,a,{capture:!1,passive:!0})}function l(b,c){if(b&&c)for(var a=b.length,d=0;d<a&&!1!==c(b[d],d);d++);}function D(b,c,a){var d=0;return function(){var e=+new Date;e-d<c||(d=e,b.apply(a,arguments))}}var u,f,A,y;return function(b){if(!document.querySelectorAll){var c=document.createStyleSheet();document.querySelectorAll=function(a,b,d,h,f){f=document.all;b=[];a=a.replace(/\[for\b/gi,"[htmlFor").split(",");for(d=a.length;d--;){c.addRule(a[d],"k:v");for(h=f.length;h--;)f[h].currentStyle.k&&b.push(f[h]);c.removeRule(0)}return b}}var a=this,d=a._util={};d.elements=[];d.destroyed=!0;a.options=b||{};a.options.error=a.options.error||!1;a.options.offset=a.options.offset||100;a.options.root=a.options.root||document;a.options.success=a.options.success||!1;a.options.selector=a.options.selector||".loading";a.options.separator=a.options.separator||"|";a.options.containerClass=a.options.container;a.options.container=a.options.containerClass?document.querySelectorAll(a.options.containerClass):!1;a.options.errorClass=a.options.errorClass||"b-error";a.options.breakpoints=a.options.breakpoints||!1;a.options.loadInvisible=a.options.loadInvisible||!1;a.options.successClass=a.options.successClass||"b-loaded";a.options.validateDelay=a.options.validateDelay||25;a.options.saveViewportOffsetDelay=a.options.saveViewportOffsetDelay||50;a.options.srcset=a.options.srcset||"data-srcset";a.options.src=u=a.options.src||"data-src";y=Element.prototype.closest;A=1<window.devicePixelRatio;f={};f.top=0-a.options.offset;f.left=0-a.options.offset;a.revalidate=function(){q(a)};a.load=function(a,b){var c=this.options;void 0===a.length?z(a,b,c):l(a,function(a){z(a,b,c)})};a.destroy=function(){var a=this._util;this.options.container&&l(this.options.container,function(b){k(b,"scroll",a.validateT)});k(window,"scroll",a.validateT);k(window,"resize",a.validateT);k(window,"resize",a.saveViewportOffsetT);a.count=0;a.elements.length=0;a.destroyed=!0};d.validateT=D(function(){m(a)},a.options.validateDelay,a);d.saveViewportOffsetT=D(function(){C(a.options.offset)},a.options.saveViewportOffsetDelay,a);C(a.options.offset);l(a.options.breakpoints,function(a){if(a.width>=window.screen.width)return u=a.src,!1});setTimeout(function(){q(a)})}});

					var bLazy = new Blazy({
						success: function(element){
							setTimeout(function(){
								element.className = element.className.replace(/\bloading\b/,'');
							}, 200);
						}
				   });

					<?php if(is_singular('post')) { ?>bLazy.load(document.querySelectorAll('.post-nav .attachment-extra-image-small.size-extra-image-small'));<?php } ?>
				};

		<?php if ( is_home() ) { ?>
				if (!isMobile.matches) {
					jQuery.getScript('<?php echo get_stylesheet_directory_uri() ?>/js/intro-js/intro.min.js', function() {
						jQuery.getScript('<?php echo get_stylesheet_directory_uri() ?>/js/intro-js/intro.homepage.js', function() {
							jQuery( window ).on( "load", function() {
								initTour();
							});
						});
					});
					jQuery('head').append( jQuery('<link rel="preload" type="text/css" as="style" onload="this.onload=null; this.rel=\'stylesheet\'" />').attr('href', '/wp-content/themes/BanyanHill_3_0/js/intro-js/introjs.min.css') );
				}
		<?php } ?>

				jQuery( window ).on( "load", function() {
					var bLazy = new Blazy({
						selector: '.loading-iframe, .loading', //ad iframes
						success: function(element){
							setTimeout(function(){
								element.className = element.className.replace(/\bloading-iframe\b/,'');
								element.className = element.className.replace(/\bloading\b/,'');
							}, 200);
						}
					});
		<?php
			if(!is_singular('exclusives')) { ?>
					//init alphaspace
					jQuery.getScript('//myalphaspace1.com/www/dlv/bhsyncjs.php');
		<?php } ?>

					jQuery('.init-tour').on('click', function() {
						Cookies.remove('is_tour_first_time_user');

						if (window.location.pathname === '/') {
							initTour();
							return false;
						} else {
							window.location.href = '/';
						}
					});
				});
				lazyLoadImages();

				jQuery(function ($) {
				// Add a class to hide the signups if user has signed up
				if (document.cookie.split(';').filter(function(item) {
					return item.indexOf('is_signed_up=') >= 0
				}).length) {
					document.getElementsByTagName('body')[0].className += ' bh_signed_up';
				}

					var checkCount = 0;
					var checkLimit = 10;
					var checkTimer = setInterval(function() {
						if (document.querySelector('body').className.indexOf('et_mobile_device') > -1) {
							var mobileMenu = document.querySelectorAll('#et-mobile-navigation .mega-menu>a');

							for (var i = 0; i<mobileMenu.length; i++) {
								mobileMenu[i].onclick = function() {
									lazyLoadImages();
								}
							}

							// handle mobile menu expansion
							jQuery('#et-mobile-navigation #et-extra-mobile-menu>li>ul:last-of-type').on('click', function() {
								if (jQuery(this).hasClass('active')) {
									jQuery(this).removeClass('active').animate({height: '36px'});
								} else {
									jQuery(this).addClass('active').animate({height: jQuery(this).get(0).scrollHeight});
								}
							});

							jQuery('#et-mobile-navigation #et-extra-mobile-menu>li>ul:last-of-type .menu-item-has-children a').on('click', function() {
								if(jQuery(this).hasClass('selected')) {
									jQuery('#et-mobile-navigation #et-extra-mobile-menu>li>ul.active:last-of-type').animate({height:jQuery('#et-mobile-navigation #et-extra-mobile-menu>li>ul.active:last-of-type').get(0).scrollHeight + jQuery(this).siblings().get(0).scrollHeight});
								} else {
									jQuery('#et-mobile-navigation #et-extra-mobile-menu>li>ul.active:last-of-type').animate({height:jQuery('#et-mobile-navigation #et-extra-mobile-menu>li>ul.active:last-of-type').get(0).scrollHeight - jQuery(this).siblings().get(0).scrollHeight});
								}
							});
							clearInterval(checkTimer);
						} else if (checkCount <= checkLimit) {
							checkCount++;
						} else {
							// is not mobile
							clearInterval(checkTimer);
						}
					}, 100); // check every 100ms for 1 second
				});

		<?php
			if(is_singular('post')) { ?>
				if (!isMobile.matches) {

					if (!document.cookie.split(';').filter(function(item) {
						return item.indexOf('wpproads-popup-') >= 0
					}).length) {
						if (jQuery('ins[data-id="pollContainer"]').length === 0) {
							// Members
							var revZone = '28'; // Zone does not exist
							if (document.cookie.split(';').filter(function(item) {
								return item.indexOf('is_signed_up=') >= 0
							}).length) {
								// Non-Members
								revZone = '27'; // Zone does not exist
							}

							jQuery('footer').before('<ins data-revive-zoneid="' + 2 + '" data-revive-id="59707835e8a3c2ffb26e9abe05387ea4" data-id="pollContainer" style="display: flex"></ins>');
						}
					}
				}
		<?php } ?>

		<?php
			if (is_singular('archives'))	{
				if (get_the_terms(get_the_ID(), 'archives-category')[0]->taxonomy === 'archives-category') {
				?>
				// Open archive links in a new window
				jQuery('article[class*="archives-category-"] a').attr('target', '_blank');
				<?php
				}
		} ?>

			// Target Menu on Mobile
			// if (jQuery(window).width() <= 575.98) {
			//    jQuery('#menuToggle ul#menu').width(jQuery('.titleRow').width());
			// };

		</script>

		<script>
		jQuery(window).load(function(){
			// Target Menu on Mobile
			if (jQuery(window).width() <= 479) {
				jQuery('#menuToggle ul#menu').width(jQuery('.titleRow').width());
			};
		});
		</script>

			<?php if($bh_sua_plugin_exists) { ?>
				<link rel='preload' href='/wp-content/plugins/bh_extras/css/bh-extras.css' type='text/css' media='all' as='style' onload='this.onload=null; this.rel="stylesheet"'/>
				<script type='text/javascript' src='/wp-content/plugins/bh_extras/js/bh-extras.js?date=2020-01-29-022'></script>
			<?php } ?>
			</body>
		</html>
		<?php
	} else {
		get_footer('single-exclusives-simple-optimized');
	}

	?>
