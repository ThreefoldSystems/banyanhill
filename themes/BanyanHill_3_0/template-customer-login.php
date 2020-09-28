<?php
/**
 * Template Name: Customer Login
 *
 * @package DW Focus
 * @since DW Focus 1.3.1
 */

// Remove sticky footer
remove_action('wp_footer', 'simple_sf');

remove_action('wp_head', 'simple_sf_ban_init');
remove_action('admin_init', 'simple_sf_ban_init');
remove_action('admin_menu', 'register_simple_sf_ban_submenu_page');
?>

<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
	<?php wp_head(); ?>
	<script src="https://banyanhill.com/tfs-facebook-signup.js"></script>

<script>
	jQuery( document ).ready(function() {
		tfsAddFacebookButton('#lwa-form',  '154704538519037', 'EN_US', '#email', '/facebook.svg');
	});
</script>
	
</head>

<body <?php body_class(); ?>>

<div class="customer-login-page">
	<div class="container">
		<?php
		$logo = ( $user_logo = et_get_option( 'extra_logo' ) ) && '' != $user_logo ? $user_logo : $template_directory_uri . '/images/logo.svg';
		
		$show_logo = extra_customizer_el_visible( extra_get_dynamic_selector( 'logo' ) ) || is_customize_preview();
		
		if ( $show_logo ) {
			?>
			<div class="customer-login-container">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" class="site-logo"><img src="<?php echo esc_attr( $logo ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>"></a>
			</div>

			<hr />
			<?php
		}
		?>

		<div class="customer-login-container">
			<h3>Sign In</h3>
			<?php
			if ( have_posts() ) {
				while ( have_posts() ) {
					the_post();

					the_content();
				}
			}
			?>
		</div>

		<div class="customer-login-leadgen">
			<?php
			// echo do_shortcode('[ad_customer_login]');
			?>
		</div>
	</div>
</div>

<?php wp_footer(); ?>
</body>
</html>