<?php
?>
<div class="tfs_css_input_section">
	<label>A Brief Message</label>
</div>

<?php if ( !empty($video_embed) ) { ?>
	<div style="position: relative; display: block; max-width: 640px; margin: 0 auto;" class="cancel-container">
		<div style="padding-top: 56.25%;">
		<?php echo $video_embed; ?>
		</div>
	</div>
	<style>
		.cancel-container iframe {
			position: absolute;
			top: 0px;
			right: 0px;
			bottom: 0px;
			left: 0px;
			width: 100%;
			height: 100%;			
		}
	</style>
<?php } ?>
<input type="hidden" id="csd_ext_status_flow_index"
	   value="<?php echo !empty($status_flow_index) ? $status_flow_index : ''; ?>">

<div class="tfs_css_input_section">
	<div <?php echo !empty($video_proceed) ? 'class="wait"' : ''?>>
		<?php echo !empty($video_proceed) ? '<input name="video_proceed" type="hidden" value="' . $video_proceed . '">' : ''?>
		<button class="tfs_css_button csd_ext_button "
				id="csd_ext_status_change_next"
				data-subref="<?php echo !empty($sub_ref) ? $sub_ref : ''; ?>"
				data-lifetime="<?php echo !empty($lifetime) ? $lifetime : 'false' ?>">
			Proceed
		</button>
	</div>	
</div>