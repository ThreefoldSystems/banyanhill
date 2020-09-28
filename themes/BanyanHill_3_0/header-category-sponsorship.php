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

	<?php $template_directory_uri = get_template_directory_uri(); ?>
	<!--[if lt IE 9]>
	<script src="<?php echo esc_url( $template_directory_uri . '/scripts/ext/html5.js"' ); ?>" type="text/javascript"></script>
	<![endif]-->

	<script type="text/javascript">
		document.documentElement.className = 'js';
	</script>
	<link rel="stylesheet" id="extra-fonts-css" href="//fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800&amp;subset=latin,latin-ext" type="text/css" media="all">
	<style>
		* {
			box-sizing: border-box;
		}
		body {
			margin: 0px; 
			overflow: hidden;
			font-family: "Open Sans",sans-serif;
		}
		.notice-text {
			text-align: center;
			font-size: 11px;
			color: rgba(0,0,0,0.5);
			margin: 0 0 8px 0;
			width: 100%;
		}	
		.category-sponsorship {
			display: flex;
			padding: 0px 20px 20px 0px;
			border-bottom: 1px solid rgba(0,0,0,0.1);
		}
		.et_pb_extra_overlay:before {
			content: "";
			position: absolute;
			top: 60%;
			left: 50%;
			display: inline-block;
			transition: .3s ease;
			-webkit-transform: translate(-50%,-50%);
			transform: translate(-50%,-50%);
			font-size: 32px;
			line-height: 32px;
		}
		.et_pb_extra_overlay:hover {
			opacity: 1;
		}	
		.et_pb_extra_overlay {
			z-index: 3;
			position: absolute;
			top: 0;
			left: 0;
			display: block;
			width: 100%;
			height: 100%;
			opacity: 0;
			background: -moz-radial-gradient(center, ellipse cover, rgba(0,0,0,0) 0%, rgba(0,0,0,0) 50%, rgba(0,0,0,0.25) 100%); /* FF3.6-15 */
			background: -webkit-radial-gradient(center, ellipse cover, rgba(0,0,0,0) 0%,rgba(0,0,0,0) 50%,rgba(0,0,0,0.25) 100%); /* Chrome10-25,Safari5.1-6 */
			background: radial-gradient(ellipse at center, rgba(0,0,0,0) 0%,rgba(0,0,0,0) 50%,rgba(0,0,0,0.25) 100%); /* W3C, IE10+, FF16+, Chrome26+, Opera12+, Safari7+ */
			filter: progid:DXImageTransform.Microsoft.gradient( startColorstr="#00000000", endColorstr="#a6000000",GradientType=1 ); /* IE6-9 fallback on horizontal gradient */
			transition: all 0.5s ease;
			border-radius: 3px;
		}
		.header {
			width: 50%;
			display: block; 
			float: left; 
			overflow: hidden;
			margin-right: 10px;
			border-radius: 3px;
		}
		.header:hover img {
			transform: scale(1.035);
		}
		.header img {
			transition: transform 0.5s ease;
		}	
		.header a {
			position: relative;
			display: block;
			text-decoration: none;
		}
		a.featured-image:before {
			background: rgba(0,0,0,0.5);
			border-radius: 0 0 3px 0;	
			content: "Sponsored Post";
			position: absolute;
			top: 0;
			left: 0;
			color: rgba(255,255,255,1);
			font-size: 11px;
			padding: 5px 10px;
			z-index: 4;
		}	
		.featured-image img {
			width: 100%;
		}
		.post-content {
			width: 50%;
			float: right;
			padding-left: 10px;
			background-color: rgba(0,0,0,0.05);
			border-radius: 3px;		
		}
		.post-title.entry-title {
			margin: 0px;
		}
		.post-title.entry-title a {
			color: #000000;
			font-size: 39px;
			line-height: 1.2em;
			font-weight: bold;
			font-style: normal;
			text-transform: none;
			text-decoration: none;
		}
		.post-meta {
			padding: 0;
			margin: 0 0 5px;
			color: rgba(0,0,0,.5);
			font-size: 12px;
		}
		.post-meta a {
			color: inherit;
			text-decoration: none;
		}	
		a.url.fn {
			color: #eb1922;
			font-weight: 600;
		}
		a.et-accent-color:hover {
			color: #eb1922;
		}
		.excerpt p {
			padding: 0 0 20px;
			font-size: 18px;
			line-height: 1.8em;
		}
		.excerpt p:last-of-type {
			margin-bottom: 0px;
			padding-bottom: 0px;
		}

		@media all and (max-device-width: 1024px) and (min-device-width: 768px) {
			.category-sponsorship {
				padding: 0 20px 20px 20px;
			}		
		}
		@media all and (max-device-width: 980px) {
			.post-title.entry-title a {
				font-size: 22px;
				line-height: 1.2em;
			}
			.excerpt p {
				font-size: 16px;
			}
			.category-sponsorship {
				padding: 20px;
			}
			.header, .post-content {
				width: 50%;
			}			
		}
		@media only screen and (max-device-width: 480px) {
			.category-sponsorship {
				display: inline-block;
				padding: 20px;
			}	
			.header {
				margin-bottom: 20px;
			}		
		}
		@media only screen and (max-width: 480px) {
			.header, .post-content {
				padding: 0px;
				width: 100%;
			}	
		}
	</style>
</head>	
<body>