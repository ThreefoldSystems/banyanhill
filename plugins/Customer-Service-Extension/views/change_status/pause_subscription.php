<?php
?>
<div class='csd_ext_notice_msg_modal tfs_css_success_msg_modal'></div>
<div class="tfs_css_error_msg_modal_content">
	<div class="tfs_css_input_section">
    	<p>Do you wish to pause your subscription?</p>
		<div class="csd_ext_button_container">
			<button class="tfs_css_button csd_ext_button" id="csd_ext_status_end">No</button>
			<button class="tfs_css_button csd_ext_button" id="csd_ext_pause_status"
					data-subref="<?php echo !empty($sub_ref) ? $sub_ref : ''; ?>">Yes</button>
		</div>
	</div>
</div>