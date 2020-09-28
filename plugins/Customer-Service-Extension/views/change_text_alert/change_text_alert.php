<?php
?>

<?php if ( !empty($phone) ) { ?>
	<div class="tfs_css_input_section">
		<label for="csd_ext_new_email">Current Phone Number</label>
		<input type="text" value="<?php echo $phone; ?>" placeholder="<?php echo $phone; ?>" disabled>
	</div>	
 <?php } ?>		
<form id="csd_ext_change_text_alert_form">	
	<div class="tfs_css_input_section">
		<label for="csd_ext_new_email">New Phone Number</label>
		<input type="text" value="" name="csd_ext_new_phone" id="csd_ext_new_phone"
			   placeholder="Please enter your new cell phone number" required>
	</div>

	<div class="tfs_css_input_section">
		<label for="csd_ext_new_email_repeat">Confirm New Phone Number</label>
		<input type="text" value="" name="csd_ext_new_phone_repeat" id="csd_ext_new_phone_repeat"
			   placeholder="Please confirm your new cell phone number" required>
	</div>
	<div class="tfs_css_input_section">
		<button class="tfs_css_button csd_ext_button" id="csd_ext_text_alert_change_confirm"
				data-subref="<?php echo !empty($sub_ref) ? $sub_ref : ''; ?>"
				data-addrcode="<?php echo !empty($addr_code) ? $addr_code : ''; ?>">
			Change
		</button>
	</div>
</form>