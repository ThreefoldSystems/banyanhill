<?php
?>
<div class='csd_ext_notice_msg_modal tfs_css_success_msg_modal'></div>
<div class="tfs_css_error_msg_modal_content">
	<div class="tfs_css_input_section">
		<p>
			Please click the button below to renew your subscription early <?php echo !empty( $savings ) ? '&ndash; and save $' . $savings . '!' : '' ; ?>
		</p>

		<div class="csd_ext_button_container">
			<button class="tfs_css_button csd_ext_button" id="csd_ext_renewal_date_confirm"
					data-url="<?php echo !empty( $url ) ? $url : '' ; ?>">
				Renew Now
			</button>
		</div>
	</div>
</div>