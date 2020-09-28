<?php 
	wp_enqueue_style( 'bh-login', get_stylesheet_directory_uri() . '/css/bh-login.css' );
?>
<div class="tfs-mw-wrapper-block">
<?php 
	$user = wp_get_current_user();

	if( is_user_logged_in() ) {
?>
	<div id="mw_login" class="bootstrap-wrapper">
		<div class="row">
			<div class="col-lg-2 col-3"><?php echo get_avatar( $user->ID, $size = '50' );  ?></div>
			<div class="col-lg-10 col-9">Hi <?php echo $user->display_name; ?>, you are currently logged in. Would you like to <a id="wp-logout" href="<?php echo wp_logout_url(); ?>"> log out?</a></div>
		</div>
	</div>
<?php
	} else {
		if ( isset( $_GET[ 'login' ] ) && $_GET[ 'login' ] === 'failed') {
			$message = '<div class="error">';
			$message .= '<h2>Error!</h2>';
			$message .= '<p>Invalid username or incorrect password.</p>';
			$message .= '</div>';
			echo $message;
		}
?>
	<div id="mw_login" >
		<h2><?php echo (isset($title)) ? $title : "Login" ; ?></h2>
		<?php
			if ( isset( $_GET[ 'redirect_to' ] ) ) {
				$form_parameters[ 'redirect' ] = $_GET[ 'redirect_to' ];
			}

			wp_login_form($form_parameters); 
		?>
	</div>	
<?php } ?>
</div>	

