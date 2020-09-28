<?php
?>
<div class='csd_ext_notice_msg_modal tfs_css_success_msg_modal'></div>
<div class="tfs_css_error_msg_modal_content">
	<div class="tfs_css_input_section">
		<p>Are you sure you want to lose access to my invaluable recommendations and analysis?</p>
		<input type="hidden" id="csd_ext_status_flow_index"
			   value="<?php echo !empty($status_flow_index) ? $status_flow_index : ''; ?>">
		<div class="csd_ext_button_container csd_ext_button_still_cancel">
			<button class="tfs_css_button csd_ext_button" id="csd_ext_status_end">
				NO - Please Don’t Cancel My Subscription
			</button>
			<button class="tfs_css_button csd_ext_button" id="csd_ext_status_change_next"
					data-subref="<?php echo !empty($sub_ref) ? $sub_ref : ''; ?>"
					data-lifetime="<?php echo !empty($lifetime) ? $lifetime : 'false' ?>">
				Yes - Please Cancel My Subscription
			</button>
		</div>
	</div>
</div>