<!--
* Customer Self Service Plugin

* Template: css-change-email

* @param $customer_email string Customer email
* @param $request_pwd_on_email_update string 1 or empty
* @param $actual_url string URL
* @param $email_change_time_remaining string Time remaining email change

It is highly recommended that you move this file to your theme folder.
Leave this template in the /theme/ folder of the plugin for future reference
-->

<?php
// Check if email has been changed recently and if there is time left to wait
if ( !empty($email_change_time_remaining) ) { ?>
	<div class="tfs_css_input_section">
		<label>Contact Email Address</label>
		<div class="subs_change_email">
			<a href="#" data-featherlight="#tfs_css_prompt_email_enter" class="listing csd_ext_now_button">Change</a>
		</div>
		<input type="text" value="PENDING (<?php echo $email_change_time_remaining ?> remaining)" placeholder="PENDING (<?php echo $email_change_time_remaining ?> remaining)" disabled>

		<!-- Confirm email popup -->
		<div style="display: none">		
			<div id="tfs_css_prompt_email_enter">
				<form id="tfs_css_change_email_address_form">
					<h2>Change Email Pending</h2>

					<div class="tfs_css_input_section">
						<p><?php echo tfs_css()->core->get_language_variable('txt_css_email_changed_recently', array( 'time' => $email_change_time_remaining, 'email' => $customer_email ) ); ?></p>
					</div>
				</form>
			</div>
		</div>		
	</div>	

<?php } else { ?>
<div class="tfs_css_input_section">
	<label>Contact Email Address</label>
	<div class="subs_change_email">
		<a href="#" data-featherlight="#tfs_css_prompt_email_page" class="listing csd_ext_now_button">Change</a>
	</div>
	<input type="text" value="<?php echo $customer_email; ?>" placeholder="<?php echo $customer_email; ?>" disabled>

	<!-- Confirm password popup -->
	<div style="display: none">
		<div id="tfs_css_prompt_password_enter">
			<div class="tfs_css_input_section">
				<label for="sec_email"><?php echo tfs_css()->core->get_language_variable('txt_css_modal_pwd'); ?></label>

				<input type="password" value="" name="userPassword" id="sec_email" placeholder="<?php echo tfs_css()->core->get_language_variable('txt_css_pwd_existing_pwd'); ?>" required autofocus >
			</div>

			<button class="tfs_css_button tfs_css_change_email_submit">
				<?php echo tfs_css()->core->get_language_variable('txt_css_modal_pwd_submit');?>
			</button>

		</div>
	</div>	
	
	<!-- Confirm email popup -->
	<div style="display: none">	
		<div id="tfs_css_prompt_email_page">
			<form id="tfs_css_change_email_address_form">
				<div class="tfs_css_input_section">
					<label for="new_email">
						New Email Adddress
						<b class="tfs_css_mandatory">*</b>
					</label>

					<input type="email" value="" name="new_email" id="new_email" required placeholder="Please enter your new email address">
				</div>

				<div class="tfs_css_input_section">
					<label for="new_email_repeat">
						Repeat New Email Adddress
						<b class="tfs_css_mandatory">*</b>
					</label>

					<input type="email" value="" name="new_email_repeat" id="new_email_repeat" required placeholder="Please re-enter your new email address">
				</div>
				
			<?php
			if ( !empty($request_pwd_on_email_update) ) {
				?>
				<button class="tfs_css_button tfs_css_change_email_submit_prompt">
					<?php echo tfs_css()->core->get_language_variable('txt_css_mail_send_btn'); ?>
				</button>
				<?php
			} else {
				?>
				<button class="tfs_css_button tfs_css_change_email_submit">
					<?php echo tfs_css()->core->get_language_variable('txt_css_mail_send_btn'); ?>
				</button>
				<?php
			}
			?>
			</form>
		</div>
	</div>		
</div>
<?php
}
?>