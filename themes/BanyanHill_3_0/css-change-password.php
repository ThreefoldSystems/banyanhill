<!--
* Customer Self Service Plugin

* Template: css-change-password

* @param $password_change_time_remaining string Time remaining password change

It is highly recommended that you move this file to your theme folder.
Leave this template in the /theme/ folder of the plugin for future reference
-->

<?php
// Check if password has been changed recently and if there is time left to wait
if ( !empty($password_change_time_remaining) ) { ?>
	<div class="tfs_css_input_section">
		<label>Password</label>
		<div class="subs_change_password">
			<a href="#" data-featherlight="#tfs_css_prompt_password_page" class="listing csd_ext_now_button">Change</a>
		</div>
		<input type="text" value="PENDING (<?php echo $password_change_time_remaining ?> remaining)" placeholder="PENDING (<?php echo $password_change_time_remaining ?> remaining)" disabled>
		
		<!-- Change password popup -->
		<div style="display: none">	
			<div id="tfs_css_prompt_password_page">
				<form id="tfs_css_change_password_form">
					<h2>Change Password Pending</h2>

					<div class="tfs_css_input_section">
						<p><?php echo tfs_css()->core->get_language_variable('txt_css_pwd_changed_recently', array( 'time' => $password_change_time_remaining ) ); ?></p>
					</div>
				</form>
			</div>
		</div>		
	</div>	

<?php } else { ?>

	<div class="tfs_css_input_section">
		<label>Password</label>
		<div class="subs_change_password">
			<a href="#" data-featherlight="#tfs_css_prompt_password_page" class="listing csd_ext_now_button">Change</a>
		</div>
		<input type="text" value="*****" placeholder="<?php echo tfs_css()->core->get_language_variable('txt_css_pwd_existing_pwd_placeholder'); ?>" disabled>
		
		<!-- Change password popup -->
		<div style="display: none">	
			<div id="tfs_css_prompt_password_page">
				<form id="tfs_css_change_password_form">
					<div class="tfs_css_input_section">
						<label for="existingPassword">
							<?php echo tfs_css()->core->get_language_variable('txt_css_pwd_existing_pwd'); ?>
							<b class="tfs_css_mandatory">*</b>
						</label>

						<input type="password" value="" name="existingPassword" id="existingPassword" required placeholder="<?php echo tfs_css()->core->get_language_variable('txt_css_pwd_existing_pwd_placeholder'); ?>">
					</div>

					<div class="tfs_css_input_section">
						<label for="newPassword">
							<?php echo tfs_css()->core->get_language_variable('txt_css_pwd_new_pwd'); ?>
							<b class="tfs_css_mandatory">*</b>
						</label>

						<input type="password" value="" name="newPassword" id="newPassword" required placeholder="<?php echo tfs_css()->core->get_language_variable('txt_css_pwd_new_pwd_placeholder'); ?>">
					</div>

					<div class="tfs_css_input_section">
						<label for="newPassword_repeat">
							<?php echo tfs_css()->core->get_language_variable('txt_css_pwd_repeat_new_pwd'); ?>
							<b class="tfs_css_mandatory">*</b>
						</label>

						<input type="password" value="" name="newPassword_repeat" id="newPassword_repeat" required placeholder="<?php echo tfs_css()->core->get_language_variable('txt_css_pwd_repeat_new_pwd_placeholder'); ?>">
					</div>

					<button class="tfs_css_button css_password_send_form">
						<?php echo tfs_css()->core->get_language_variable('txt_css_pwd_save_btn'); ?>
					</button>
				</form>
			</div>
		</div>		
	</div>

<?php
}
?>