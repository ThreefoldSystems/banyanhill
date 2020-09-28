<?php
?>
<div class='csd_ext_notice_msg_modal tfs_css_success_msg_modal'></div>
<div class="tfs_css_error_msg_modal_content">
	<div class="tfs_css_input_section">
		<p>
			Do you wish to receive a reminder email prior to your renewal date?
		</p>

		<div class="csd_ext_button_container">
			<button class="tfs_css_button csd_ext_button" id="csd_ext_status_end">No</button>
			<button class="tfs_css_button csd_ext_button" id="csd_ext_auto_renew_remind"
					data-subref="<?php echo !empty( $sub_ref ) ? $sub_ref : ''; ?>"
					data-expire="<?php echo !empty( $expire ) ? $expire : ''; ?>"
					data-subname="<?php echo !empty( $sub_name ) ? $sub_name : ''; ?>">
				Yes
			</button>
		</div>
	</div>
</div>