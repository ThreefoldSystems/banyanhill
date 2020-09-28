<!--
* Customer Self Service Plugin

* Template: css-change-address

* @param $customer_address array agora()->user->get_address()/empty
* @param $allow_change_country string 1/empty
* @param $request_pwd_on_addr_update string 1/empty

It is highly recommended that you move this file to your theme folder.
Leave this template in the /theme/ folder of the plugin for future reference
-->

<?php
// Check if customer address has been passed to the view

if ( !empty($customer_address) ) {
    // Check if it's allowed to change country
    if ( empty($allow_change_country) ) {
        ?>
        <div class="tfs_css_message_alert"><?php echo tfs_css()->core->get_language_variable('txt_css_addr_country'); ?></div>
        <?php
    } 
?>

	<div class="tfs_user_info_wrapper">
		<div class="tfs_user_info_container">
			<?php tfs_css()->template_manager->process_template( 'css-change-username' ); ?>
			<?php tfs_css()->template_manager->process_template( 'css-change-password' ); ?>
		</div>
	</div>

	<div class="spacer"></div>
	<div class="tfs_user_address_container">
		<div class="text-right text-float-right">
			<?php
			if ( !empty($request_pwd_on_addr_update) ) {
				?>
				<button class="tfs_css_button tfs_css_change_address_submit_prompt">
					<?php echo tfs_css()->core->get_language_variable('txt_css_save_btn'); ?>
				</button>
				<?php
			} else {
				?>
				<button class="tfs_css_button tfs_css_change_address_submit">
					<?php echo tfs_css()->core->get_language_variable('txt_css_save_btn'); ?>
				</button>
				<?php
			}
			?>
		</div>
		<h2><?php echo tfs_css()->core->get_language_variable('txt_css_change_address'); ?></h2>

		<form id="tfs_css_change_address_form" class="address-tab">
			<div class="tfs_name_wrapper">
				<div class="tfs_name_container">
					<div class="tfs_css_input_section">
						<label for="firstName">
							<?php echo tfs_css()->core->get_language_variable('txt_css_first_name'); ?>
							<b class="tfs_css_mandatory">*</b>
						</label>

						<input type="text" value="<?php echo ucwords( strtolower( $customer_address->firstName ) ); ?>"
							   name="firstName" id="firstName" placeholder="First Name" required>
					</div>
				</div>
				<div class="tfs_name_container">
					<div class="tfs_css_input_section">
						<label for="lastName">
							<?php echo tfs_css()->core->get_language_variable('txt_css_last_name'); ?>
							<b class="tfs_css_mandatory">*</b>
						</label>

						<input type="text" value="<?php echo ucwords( strtolower( $customer_address->lastName ) ); ?>"
							   name="lastName" id="lastName" placeholder="Last Name" required>
					</div>			
				</div>
			</div>

			<div class="tfs_address_wrapper">
				<div class="tfs_address_container">

					<div class="tfs_css_input_section">
						<label for="street">
							<?php echo tfs_css()->core->get_language_variable('txt_css_street'); ?>
						</label>

						<input type="text" value="<?php echo ucwords( strtolower( $customer_address->street ) ); ?>"
							   name="street" id="street" placeholder="Address Line 1" >
					</div>

					<div class="tfs_css_input_section">
						<label for="street2">
							<?php echo tfs_css()->core->get_language_variable('txt_css_street_two'); ?>
						</label>

						<input type="text" value="<?php echo ucwords( strtolower( $customer_address->street2 ) ); ?>"
							   name="street2" id="street2" placeholder="Address Line 2">
					</div>

					<div class="tfs_css_input_section">
						<label for="street3">
							<?php echo tfs_css()->core->get_language_variable('txt_css_street_three'); ?>
						</label>

						<input type="text" value="<?php echo ucwords( strtolower( $customer_address->street3 ) ); ?>"
							   name="street3" id="street3" placeholder="Address Line 3">
					</div>	

				</div>
				<div class="tfs_address_container">
					<div class="tfs_address_container tfs_country_container">
				<?php
				// Allow users to edit the country and the state
				if ( !empty($allow_change_country) ) {
					?>
					<div class="tfs_css_input_section">
						<label for="tfs_css_countryCode">
							<?php echo tfs_css()->core->get_language_variable('txt_css_country'); ?>
						</label>

						<?php tfs_css()->css_update_api->css_country_selector( $customer_address->countryCode ); ?>
					</div>

					<div id="tfs_css_state_display" class="tfs_css_input_section">
						<label for="tfs_css_countryCode">
							<?php echo tfs_css()->core->get_language_variable('txt_css_state'); ?>
						</label>

						<div id="tfs_css_state">
							<?php echo tfs_css()->css_update_api->css_get_state( $customer_address->countryCode, $customer_address->state ); ?>
						</div>
					</div>
					<?php
				} else {
					echo '<input type="hidden" value="' . $customer_address->countryCode . '" id="tfs_css_countryCode" name="countryCode">';
					echo '<input type="hidden" value="' . $customer_address->state . '" name="state" id="tfs_css_state">';
				}?>
					</div>
					<div class="tfs_address_container">

						<div class="tfs_css_input_section">
							<label for="city">
								<?php echo tfs_css()->core->get_language_variable('txt_css_city'); ?>
							</label>

							<input type="text" value="<?php echo ucwords( strtolower( $customer_address->city ) ); ?>"
								   name="city" id="city" placeholder="City">
						</div>

						<div class="tfs_css_input_section">
							<label for="postalCode">
								<?php echo tfs_css()->core->get_language_variable('txt_css_postal_code'); ?>
							</label>

							<input type="text" value="<?php echo $customer_address->postalCode; ?>"
								   name="postalCode" placeholder="Zip code">
						</div>					

					</div>

					<div class="tfs_extras_container">
						<div class="tfs_css_input_section">
							<label for="phoneNumber">
								<?php echo tfs_css()->core->get_language_variable('txt_css_phone'); ?>
							</label>

							<input type="text" value="<?php echo $customer_address->phoneNumber; ?>"
								   name="phoneNumber" id="phoneNumber" placeholder="Phone Number" >
						</div>

						<div class="tfs_css_input_section">
							<label for="birthDate">Date Of Birth</label>
							<input type="text" value="<?php echo $customer_address->birthDate ? date( 'F j, Y', strtotime($customer_address->birthDate)) : ''; ?>" placeholder="Date Of Birth" id="dateOfBirth" name="birthDate" class="tfs_css_change_dob"/>
						</div>				
					</div>
				</div> 
			</div>
		</form>
	</div>

	<div class="spacer"></div>
	<h2>Account Preferences</h2>

	<!--form id="tfs_css_change_preferences_form"-->			
		<div class="tfs_css_contact_info">
			<?php tfs_css()->template_manager->process_template( 'css-change-email' ); ?>			
		</div>
		
		<!--div class="tfs_css_social_info">			
			<div class="tfs_css_input_section">
				<label for="socialMedia">
					Social Media Contact
				</label>				
				<select id="socialMedia">
					<option selected>Select Social Network</option>
					<option value="facebook">Facebook</option>
					<option value="twitter">Twitter</option>
					<option value="instagram">Instagram</option>
					<option value="reddit">Reddit</option>
					<option value="linkedin">LinkedIn</option>
					<option value="tumblr">Tumblr</option>
				</select>
			</div>
			<div class="tfs_css_input_section">
				<div class="subs_change_social">
					<a href="#" class="add_social">Add</a>
				</div>				
				<input type="text" value=""
					   name="socialNetwork" id="socialNetwork" placeholder="Username" >
			</div>				
		</div-->
		<?php $social_meta = get_social_profile(); ?>
		<!--div class="tfs_css_social_container">
			<div class="tfs_css_input_section">
				<?php
				foreach ( $social_meta as $k => $v ) { 
					echo '<div class="social_display ' . $k . '">'. $v . '</div>';
					echo '<div class="subs_delete_social">';
					echo '<a href="#" class="delete_social" data-network="' . $k . '">Remove</a></div>';
				} ?>
			</div>
		</div>
		<div class="tfs_css_text_sub_info">
			<div class="tfs_css_input_section">
				<label for="textAlert">
					Text Alert Status
				</label>
			</div>
		</div-->
		<?php
			$sani_phone = preg_replace('/[^0-9]/', '', $customer_address->phoneNumber);
			
			$listSignups_SMS = !empty($sani_phone) ? agora()->mw->get_customer_list_signups_by_email('1' . $sani_phone  . '@190.USA.TXT.LOCAL') : '';
			if ( !is_wp_error($listSignups_SMS) ) {
		?>
		<?php
			$smsIndex = 1;

			foreach ($listSignups_SMS as $key => $item) {
		?>
		<!--div class="tfs_css_text_sub_container">
			<div class="tfs_css_input_section">							
				<div class="tfs_css_text_sub_service"><?php echo $item->listDescription; ?></div>
			</div>
			<div class="tfs_css_input_section">				
				<div class="tfs_css_text_sub_service"><?php echo $item->status !== 'A' ? 'Inactive' : 'Active' ; ?></div>		
			</div>
		</div-->			
		<?php 
				$index++;
			}	
		?>	
		<?php } ?>
		<!--div class="text-right text-float-right">
			<?php
			if ( !empty($request_pwd_on_addr_update) ) {
				?>
				<button class="tfs_css_button tfs_css_change_address_submit_prompt">
					<?php echo tfs_css()->core->get_language_variable('txt_css_save_btn'); ?>
				</button>
				<?php
			} else {
				?>
				<button class="tfs_css_button tfs_css_change_address_submit">
					<?php echo tfs_css()->core->get_language_variable('txt_css_save_btn'); ?>
				</button>
				<?php
			}
			?>
		</div-->

	<!--/form-->

	<!-- Confirm password popup -->
	<div style="display: none">
		<div id="tfs_css_prompt_password_enter">
			<div class="tfs_css_input_section">
				<label for="sec_pwd"><?php echo tfs_css()->core->get_language_variable('txt_css_modal_pwd'); ?></label>
				
				<input type="password" value="" name="userPassword" id="sec_pwd" placeholder="<?php echo tfs_css()->core->get_language_variable('txt_css_pwd_existing_pwd'); ?>" required autofocus >
			</div>

			<button class="tfs_css_button tfs_css_change_address_submit">
				<?php echo tfs_css()->core->get_language_variable('txt_css_save_btn'); ?>
			</button>

		</div>
	</div>
    <?php
	// Check transient for email change
	$customer_number = tfs_css()->core->user->get_customer_number();
	$email_transient = get_transient( 'email_changed_' . $customer_number );
	$user_transient = get_transient( get_current_user_id() . '_username_email' );		

	if ( !empty($user_transient) && empty($email_transient) ) {
		tfs_css()->template_manager->process_template( 'css-username-as-email' );
	}	
	
} else {
    echo tfs_css()->core->get_language_variable('txt_css_advantage_user_error');
}
?>
<script type="text/javascript">
	jQuery(document).ready(function(e){e.getScript("/wp-includes/js/jquery/ui/datepicker.min.js",function(){if(e('#ui-datepicker-div').length > 0){e('#ui-datepicker-div').remove();}e("#dateOfBirth").datepicker({changeMonth:!0,changeYear:!0,yearRange:"-100:+0"}),e.datepicker.setDefaults({closeText:"Close",currentText:"Today",monthNames:["January","February","March","April","May","June","July","August","September","October","November","December"],monthNamesShort:["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"],nextText:"Next",prevText:"Previous",dayNames:["Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"],dayNamesShort:["Sun","Mon","Tue","Wed","Thu","Fri","Sat"],dayNamesMin:["S","M","T","W","T","F","S"],dateFormat:"MM d, yy",firstDay:1,isRTL:!1})})});
</script>