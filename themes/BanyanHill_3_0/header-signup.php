<!DOCTYPE html>
<!--[if IE 6]>
<html id="ie6" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 7]>
<html id="ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html id="ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 6) | !(IE 7) | !(IE 8)  ]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="robots" content="noindex, nofollow">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=1">
	
	<?php $template_directory_uri = get_template_directory_uri(); ?>
	<!--[if lt IE 9]>
	<script src="<?php echo esc_url( $template_directory_uri . '/scripts/ext/html5.js"' ); ?>" type="text/javascript"></script>
	<![endif]-->

	<script type="text/javascript">
	document.documentElement.className = 'js';

	/* <![CDATA[ */
	//var TotalPoll = {"AJAX":"\/wp-admin\/admin-ajax.php","AJAX_ACTION":"tp_action","VERSION":"3.3.3","settings":{"limitations":{"captcha":{"enabled":false,"sitekey":false,"hl":"en"}},"sharing":{"enabled":false,"expression":"","networks":false}}};
	/* ]]> */
	</script>
	<!--link rel="stylesheet" id="tosrus-style-css" href="/wp-content/plugins/totalpoll/templates/mamdefault/assets/css/jquery.tosrus.min.css?ver=3.3.3" type="text/css" media="all"-->
	<link rel="stylesheet" id="extra-css" href="/wp-content/themes/Extra/style.css?ver=4.9.6" type="text/css" media="all">
	<link rel="stylesheet" id="extra-fonts-css" href="//fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800&amp;subset=latin,latin-ext" type="text/css" media="all">
	
	<script type="text/javascript" src="/wp-includes/js/jquery/jquery.js?ver=1.12.4"></script>
	<!--script type="text/javascript" src="/wp-content/plugins/totalpoll/assets/js/min/front.js?ver=3.3.3" defer="defer"></script-->
	<!--script type="text/javascript" src="/wp-content/plugins/totalpoll/templates/mamdefault/assets/js/min/jquery.tosrus.js?ver=3.3.3" defer="defer"></script-->
	<!--script type="text/javascript" src="/wp-content/plugins/totalpoll/templates/mamdefault/assets/js/min/main.js?ver=3.3.3" defer="defer"></script-->
	<script>
		var renderCount = 0;
		var renderLimit = 10;
//		var pollRenderInt = setInterval(function () {
//			if(document.querySelector('[id^="totalpoll-async"]').childElementCount > 0) {
//				pollRender();
//				clearInterval(pollRenderInt);
//			} else if (renderCount <= renderLimit) {
//				renderCount++;
//			} else {
//				clearInterval(pollRenderInt);
//			}
//		}, 500);

		var adjust_iframe_height = function() {
			var actual_height = document.querySelector('.mam_wppaszone').scrollHeight;
			parent.postMessage({event_id: 'poll_resize', data: {height: actual_height, elm: '.resize-poll-iframe'}} ,"*"); 
			//* allows this to post to any parent iframe regardless of domain
		}	
		
		window.onload = function() {
			adjust_iframe_height();
		}
		window.onresize = function() {
			adjust_iframe_height();
		}
		window.onorientationchange = function() {
			adjust_iframe_height();
		}		
	</script>
	<style>
		#page-container > div {
			width: 50%;
			min-width: 50%;
		}

		.Newsletter_side {
			background: #113752;
		}

		#page-container {
			display: flex;
		}

		.signup_logo {
			width:35%;
			margin: 0 auto;
			align-self: flex-end;
    		opacity: 0.25;
		}

		.Newsletter_new, .Newsletter_side {
			display: flex;
			flex-flow: wrap;
			padding: 20px;
			color: #222;
			justify-content: center;
		}

		.Newsletter_side h1 {
			color: #fff;
			font-size: 120px;
			line-height: 1em;
			padding: 0;
		}

		.Newsletter_side h1 span {
			color: rgb(255,102,0);
		}

		.Newsletter_new h2 {
			color: #113752;
			text-align: center;
			font-size: 26px;
		}	
	</style>
</head>	
<body <?php body_class(); ?> style="color: rgba(0,0,0,0.7); background: #fff; width: 100%; box-shadow: none;">
	<div id="page-container" class="page-container mam_wppaszone">
