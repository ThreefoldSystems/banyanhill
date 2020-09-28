<!--
* Customer Self Service Plugin

* Template: css-change-username

* @param $customer_username string Customer username
* @param $request_pwd_on_username_update string
* @param $username_change_time_remaining string Time remaining username change

It is highly recommended that you move this file to your theme folder.
Leave this template in the /theme/ folder of the plugin for future reference
-->


<?php
// Check if username has been changed recently and if there is time left to wait
if ( !empty($username_change_time_remaining) ) { ?>
	<div class="tfs_css_input_section">
		<label for="current_username">Username</label>
		<div class="subs_change_username">
			<a href="#" data-featherlight="#tfs_css_prompt_username_enter" class="listing csd_ext_now_button">Change</a>
		</div>
		<input type="text" value="PENDING (<?php echo $username_change_time_remaining ?> remaining)" placeholder="PENDING (<?php echo $username_change_time_remaining ?> remaining)" disabled>

		<!-- Confirm username popup -->
		<div style="display: none">		
			<div id="tfs_css_prompt_username_enter">
				<form id="tfs_css_change_username_form">
					<h2>Change Username Pending</h2>

					<div class="tfs_css_input_section">
						<p><?php echo tfs_css()->core->get_language_variable('txt_css_username_changed_recently', array( 'time' => $username_change_time_remaining, 'username' => $customer_username ) ); ?></p>
					</div>
				</form>
			</div>
		</div>		
	</div>	
	
<?php } else { ?>

	<div class="tfs_css_input_section">
		<label for="current_username">Username</label>
		<div class="subs_change_username">
			<a href="#" data-featherlight="#tfs_css_prompt_username_enter" class="listing csd_ext_now_button">Change</a>
		</div>
		<input type="text" value="<?php echo $customer_username; ?>" required name="current_username"
			   placeholder="<?php echo $customer_username; ?>" id="current_username" disabled>
			
		<!-- Confirm password popup -->
		<div style="display: none">
			<div id="tfs_css_prompt_password_enter">
				<div class="tfs_css_input_section">
					<label for="sec_email"><?php echo tfs_css()->core->get_language_variable('txt_css_modal_pwd'); ?></label>

					<input type="password" value="" name="userPassword" id="sec_email" placeholder="<?php echo tfs_css()->core->get_language_variable('txt_css_pwd_existing_pwd'); ?>" required autofocus >
				</div>

				<button class="tfs_css_button tfs_css_change_username_submit">
					<?php echo tfs_css()->core->get_language_variable('txt_css_submit_username');?>
				</button>

			</div>
		</div>		

		<!-- Confirm username popup -->
		<div style="display: none">		
			<div id="tfs_css_prompt_username_enter">
				<form id="tfs_css_change_username_form">
					<div class="tfs_css_input_section">
						<label for="new_username"><?php echo tfs_css()->core->get_language_variable('txt_css_desired_username'); ?></label>
						
						<input type="text" value="" required name="new_username"
							   placeholder="Please enter your new username" id="new_username">
					</div>
				<?php
				if ( !empty($request_pwd_on_username_update) ) {
					?>
					<button class="tfs_css_button tfs_css_change_username_submit_prompt">
						<?php echo tfs_css()->core->get_language_variable('txt_css_submit_username'); ?>
					</button>
					<?php
				} else {
					?>
					<button class="tfs_css_button tfs_css_change_username_submit">
						<?php echo tfs_css()->core->get_language_variable('txt_css_submit_username'); ?>
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