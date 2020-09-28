<?php
?>
<div class='csd_ext_notice_msg_modal tfs_css_success_msg_modal'></div>
<div class="tfs_css_error_msg_modal_content">
	<div class="tfs_css_input_section">
		<label for="csd_ext_new_email">Current Email Address</label>
		<input type="text" value="<?php echo $old_email; ?>" placeholder="<?php echo $old_email; ?>" disabled>
	</div>	
	
    <form id="csd_ext_change_email_form">
        <div class="tfs_css_input_section">
            <label for="csd_ext_new_email">New Email</label>
            <input type="email" value="" name="csd_ext_new_email" id="csd_ext_new_email"
					   placeholder="Please enter your new email address" required>
		</div>

		<div class="tfs_css_input_section">			
            <label class="input_label" for="csd_ext_new_email_repeat">Confirm New Email</label>
            <input type="email" value="" name="csd_ext_new_email_repeat" id="csd_ext_new_email_repeat"
                   placeholder="Please confirm your new email address" required>
		</div>

		<div class="tfs_css_input_section">		
			<input type="hidden" name="submit_subref" id="csd_ext_submit_subref" value="<?php echo $sub_ref; ?>">
			<button class="tfs_css_button csd_ext_button" id="csd_ext_email_change_confirm">Change</button>
        </div>
    </form>
</div>