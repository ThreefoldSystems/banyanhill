<?php
?>
<div class='csd_ext_notice_msg_modal tfs_css_success_msg_modal'></div>
<div class="tfs_css_error_msg_modal_content">
	<div class="tfs_css_input_section">
    <?php if ( !empty( $auto_status ) && strtolower($auto_status) === 'on' ) { ?>
        <p>
            Your <?php echo !empty( $subname ) ? '<em>' . $subname . '</em>' : ''; ?> subscription currently comes with our automatic-renewal feature. This feature ensures that you will never miss an issue and allows you to lock in our lowest available price each year.
        </p>
        <p>
            Do you wish to deactivate this feature?
        </p>
		
		<div class="csd_ext_button_container">
			<button class="tfs_css_button csd_ext_button" id="csd_ext_status_end">No</button>
			<button class="tfs_css_button csd_ext_button" id="csd_ext_auto_renew_confirm"
					data-auto="<?php echo !empty( $auto_status) ? $auto_status : ''; ?>"
					data-subref="<?php echo !empty( $subref ) ? $subref : ''; ?>"
					data-expire="<?php echo !empty( $expire ) ? $expire : ''; ?>"
					data-subname="<?php echo !empty( $subname ) ? $subname : ''; ?>">
				Yes
			</button>
		</div>		
    <?php } else { ?>
        <p>
            Your <?php echo !empty( $subname ) ? '<em>' . $subname . '</em>' : ''; ?> subscription is available to upgrade to our automatic-renewal program. This feature ensures that you will never miss an issue and allows you to lock in our lowest available price each year.
        </p>
		<p>
			If you would like to enable the automatic-renewal status of <?php echo !empty( $subname ) ? '<em>' . $subname . '</em>' : ''; ?>, please call our customer service team at 866-584-4096 and one of our phone agents will assist you.
		</p>
		<p>
			We are available between 8 am and 8 pm ET, Monday through Friday.
		</p>		
    <?php } ?>
	</div>
</div>