<?php 
	wp_enqueue_style( 'bh-login', get_stylesheet_directory_uri() . '/css/bh-login.css' );

if(isset($status_message)): ?>
    <p><?php echo $status_message; ?></p>
<?php endif; ?>

<div class="tfs-mw-wrapper-block">
	
<?php 
	if ( empty( $message_class ) ) {
		$message_class = 'error';
	}

	if ( isset($message) ) {
		echo '<div ' . (isset($message_class) ? 'class="' . $message_class . '"' : '') . '>' . $message . '</div>';
	}
	
	if( isset($_GET['t']) ) {
		if ( isset($successful) ) {
            if ( ! empty( $password_reset_successful ) ) {
                echo '<div class="success">' . $password_reset_successful . '<p><br>You will be redirected in ' . get_post_meta(get_the_ID(), 'redirect_after_success_time', true) . ' seconds.</p></div>';
			?>
			<script type="text/javascript">
				setTimeout(function () {
				   window.location.href = <?php echo '"' . get_post_meta(get_the_ID(), 'redirect_after_success_url', true) . '"'; ?>
				}, <?php echo get_post_meta(get_the_ID(), 'redirect_after_success_time', true); ?> * 1000);				
			</script>
            <?php }			
		}
		else if ( get_transient($_GET['t']) ) {
			if ( isset($temp_message) ) {
				echo $temp_message;
			} ?>
			<div id="new-password-form">
				<fieldset>
					<legend><?php echo (isset($title)) ? $title : "Login Helper" ; ?></legend>
					<p>Change Your password:</p>
					<div class="fields-container">
						<form name="password_change_form" id="password-change-form" action="" method="post">
							<div class="reset-email user-email">
								<input type="hidden" id="passed_email" value="<?php echo $get_email; ?>">
								<?php if(isset($_GET['mode']) && $_GET['mode'] == 'temp'){
									$user = wp_get_current_user();
									echo '<input id="username" type="hidden" name="username" value="' . $user->data->user_login .'">';
								} else { ?>
									<label for="username">Enter Your Username</label>
									<input id="username" type="text" name="username" class="input" placeholder="<?php _e('Enter Your Username'); ?>" required>
								<?php } ?>
							</div>
							<div class="reset-email user-password">
								<label for="new-password">Enter New Password</label>
								<input id="new-password" type="password" name="new-password" class="input" placeholder="<?php _e('Enter Your Password'); ?>" required>
							</div>
							<div class="reset-email user-email">
								<label for="confirm-password">Confirm New Password</label>
								<input id="confirm-password" type="password" name="confirm-password" class="input" placeholder="<?php _e('Confirm Your Password'); ?>" required>
							</div>
							<div class="new-pw-submit reset-submit">
								<input type="submit" name="wp-submit" id="wp-submit" class="button-primary mw-reset-password-button" value="<?php _e('Send'); ?>">
							</div>
							<?php wp_nonce_field( $nonce_action, $nonce_field ); ?>
						</form>						
					</div>
				</fieldset>
			</div>
		<?php } else {
			echo "<p>We're sorry, but the reset link you've followed has expired.</p>";
		}
	} elseif ($message_class !== 'success') { ?>
	<div id="mw_login">
		<fieldset>
			<legend><?php echo (isset($title)) ? $title : "Login Helper" ; ?></legend>
			<p>Please choose one of the options below:</p>
			<div class="options-container">
				<div>
					<input id="forgot-username" type="radio" name="reset-options" value=""  <?php echo isset($invalidu) ? 'class="pre-click"' : ''; ?>>
					<label for="forgot-username">Request Username</label>
					<div class="fields-container">
						<fieldset id="show-username-form">
							<!--legend>Request Username</legend-->
							<form name="username_reset_form" id="username-reset-form" action="" method="post">
								<div class="reset-email user-email">
									<label for="user_email"><?php _e('Your Email Address'); ?></label>
									<input type="text" name="user_email" id="user_email mw-user-email-input" class="input <?php echo isset($invalidu) ? 'invalid' : ''; ?>" value="" size="20" placeholder="<?php _e('Your Email Address'); ?>" required>
									<input type="hidden" name="mode" value="u">
								</div>
								<div class="reset-submit">
									<input type="submit" name="wp-submit" id="wp-submit" class="button-primary mw-forgot-username-button" value="<?php _e('Send'); ?>">
								</div>
								<?php wp_nonce_field( $nonce_action, $nonce_field ); ?>
							</form>
						</fieldset>					
					</div>
				</div>
				<div>
					<input id="forgot-password" type="radio" name="reset-options" value=""  <?php echo isset($invalidp) ? 'class="pre-click"' : ''; ?>>
					<label for="forgot-password">Set Up or Reset Password</label>
					<div class="fields-container">
						<fieldset id="show-password-form">
							<!--legend>Reset Password</legend-->
							<form name="password_reset_form" id="password-reset-form" action="" method="post">
								<div class="reset-email user-email">
									<label for="user_email"><?php _e('Your Email Address'); ?></label>
									<input type="text" name="user_email" id="user_email" class="mw-user-email-input input <?php echo isset($invalidp) ? 'invalid' : ''; ?>" value="" size="20" placeholder="<?php _e('Your Email Address'); ?>" required>
									<input type="hidden" name="mode" value="p">
								</div>
								<div class="reset-submit">
									<input type="submit" name="wp-submit" id="wp-submit" class="button-primary mw-forgot-username-button" value="<?php _e('Send'); ?>">
								</div>
								<?php wp_nonce_field( $nonce_action, $nonce_field ); ?>
							</form>
						</fieldset>
					</div>					
				</div>
			</div>
		</fieldset>
	</div>
	<?php } ?>
</div>
<script type="text/javascript">
    jQuery(document).ready(function(){
		
        jQuery('input[type="radio"]').click(function(){
            if(jQuery(this).attr("id")=="forgot-username"){
                jQuery("#show-password-form").slideUp();
                jQuery("#show-username-form").slideDown();
            }
            if(jQuery(this).attr("id")=="forgot-password"){
                jQuery("#show-username-form").slideUp();
                jQuery("#show-password-form").slideDown();
            }
        });
		
		jQuery('form').on('submit', function() {
			if(jQuery(this).find('.input').val() === '') {
				jQuery(this).addClass('error');
				return false;
			} else {
				jQuery(this).removeClass('error');
			}
		});
		
		jQuery('input[type="text"]').on('blur', function() {
			if(jQuery(this).val !== '') {
				jQuery(this).parents('form').removeClass('error');
			}
		});	

        jQuery('.pre-click').trigger('click');
    });
</script>
