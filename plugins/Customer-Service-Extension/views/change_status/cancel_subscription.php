<?php
?>
<div class='csd_ext_notice_msg_modal tfs_css_success_msg_modal'></div>
<div class="tfs_css_error_msg_modal_content">
	<div class="tfs_css_input_section">
    	<p>Do you wish to cancel your subscription?</p>
		<input type="hidden" id="csd_ext_status_flow_index"
			   value="<?php echo !empty($status_flow_index) ? $status_flow_index : ''; ?>">
		<div class="csd_ext_button_container">
			<button class="tfs_css_button csd_ext_button" id="csd_ext_status_end">No</button>
			<button class="tfs_css_button csd_ext_button" id="csd_ext_status_change_next"
					data-lifetime="<?php echo !empty($lifetime) ? $lifetime : 'false' ?>"
					data-subref="<?php echo !empty($sub_ref) ? $sub_ref : ''; ?>">
				Yes
			</button>
		</div>
	</div>
</div>