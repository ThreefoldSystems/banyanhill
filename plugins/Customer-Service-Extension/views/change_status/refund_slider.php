<div class='csd_ext_notice_msg_modal tfs_css_success_msg_modal'></div>
<div class="tfs_css_error_msg_modal_content">
	<div class="tfs_css_input_section">
		<?php
			if(!empty($refund)){
				$split = explode('.', $refund);
				if(strlen($split[1]) == 1){
					$refund .= '0';
				}
			} else {
				$refund = '0.00';
			}

		?>
		<p>There is a remaining balance of $<?php echo $refund; ?> on your account which will be
			refunded.</p>
		<input type="hidden" id="csd_ext_status_flow_index"
			   value="<?php echo !empty($status_flow_index) ? $status_flow_index : ''; ?>">
		<div class="csd_ext_button_container">
			<button class="tfs_css_button csd_ext_button" id="csd_ext_status_refund"
					data-subref="<?php echo !empty($sub_ref) ? $sub_ref : ''; ?>">Continue</button>
		</div>
	</div>
</div>