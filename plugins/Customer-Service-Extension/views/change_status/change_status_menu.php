<?php
?>
<div class='csd_ext_notice_msg_modal tfs_css_success_msg_modal'></div>
<div class="tfs_css_error_msg_modal_content">
	<div class="tfs_css_input_section tfs_css_cancel_options">
		<p>We&rsquo;re sorry that you&rsquo;re thinking about cancelling your subscription to <?php echo !empty($subname) ? '<em>' . $subname . '</em>' : 'this subscription' ?>. Can you tell us, is it for one of the reasons listed below, or another?</p>
		<input type="hidden" id="csd_ext_status_flow_index" value="<?php echo !empty($status_flow_index) ? $status_flow_index : ''; ?>">
		<div class="divider"></div>
		<div class="tfs_css_choice_container" data-pubcode="<?php echo !empty($pubcode) ? $pubcode : 'false' ?>">
			<div id="main">
				<div class="choices">
					<div data-value="A"><p>I&rsquo;m receiving too many emails.</p></div>
					<?php if ( !empty($reports_link) ) { ?>
					<div data-value="B"><p>I haven&rsquo;t received the special reports I was promised.</p></div>
					<?php } ?>
					<div data-value="C"><p>I believe I was charged an incorrect amount.</p></div>
					<div data-value="D"><p>It&rsquo;s something else.</p></div>
				</div>
			</div>
			<div id="main_A" class="hide">
				<p>Would you like to remain a subscriber, but get LESS emails from us? Receive only your updates and trade alerts?</p>
				<p>After clicking the button below, we will remove <?php echo !empty($email_address) ? $email_address : ''; ?> from all <?php echo !empty($subname) ? '<em>' . $subname . '</em>' : 'this subscription' ?> promotional emails. Please allow up to 48 hours for changes to be reflected on your account.</p>
				<div class="choices">					
					<div data-value="E"><button
											onclick="css_add_remove_customer_list(this)" 
											data-list-action="remove"
											data-list-subname="<?php echo !empty($subname) ? $subname : ''; ?>"
                                            data-list-email="<?php echo !empty($email_address) ? $email_address : ''; ?>"
                                            data-list-code="<?php echo !empty($pubcode) ? $pubcode . 'PROMO' : ''; ?>"
                                            >Keep my subscription active and send me less emails.</button></div>
				</div>
			</div>
			<div id="main_B" class="hide">
				<p>Please note, as your special reports contain time-sensitive information, they are available exclusively in a digital format via your confirmation email and stored on this secure website. Click the button below to access your special reports.</p>
				<div class="choices">
					<div data-value="G" class="tfs_css_choice_solution"><a href="/?p=<?php echo $reports_link; ?>">Access My Special Reports</a></div>
				</div>
			</div>
			<div id="main_C" class="hide">
				<p>We are sorry to hear that your order was processed at a rate that was different than you intended. Please contact us by phone, email or chat and our Customer Care Representatives will be happy to assist you in resolving this issue.</p>
				<div class="choices">
					<div data-value="I" class="tfs_css_choice_solution"><a href="/contact-us/">Contact Our Customer Care Representatives</a></div>
				</div>
			</div>
			<div id="main_D" class="hide">
				<p>We are sorry to hear that you&rsquo;re having an issue with this publication. Please call us at 1-866-584-4096 or click the button below so that we may help you remedy it.</p>
				<div class="choices">
					<div data-value="K" class="tfs_css_choice_solution"><a href="/contact-us/">Contact Our Customer Care Representatives</a></div>
				</div>
			</div>
			<p id="csd_ext_status_change_next" class="hide" data-subref="<?php echo !empty($sub_ref) ? $sub_ref : ''; ?>"
				data-postid="<?php echo !empty($post_id) ? $post_id : ''; ?>"
				data-lifetime="<?php echo !empty($lifetime) ? $lifetime : 'false' ?>"
				data-pubcode="<?php echo !empty($pubcode) ? $pubcode : 'false' ?>">
			Cancel my subscription.
			</p>			
		</div>
		
		<!--p>I no longer wish to receive <br/><?php echo !empty($subname) ? '<em>' . $subname . '</em>' : 'this subscription' ?>.</p>
		<input type="hidden" id="csd_ext_status_flow_index"
			   value="<?php echo !empty($status_flow_index) ? $status_flow_index : ''; ?>">
		
		<div class="modal-respone-btn-container">
			<button id="csd_ext_status_change_next" data-subref="<?php echo !empty($sub_ref) ? $sub_ref : ''; ?>"
					data-postid="<?php echo !empty($post_id) ? $post_id : ''; ?>"
					data-lifetime="<?php echo !empty($lifetime) ? $lifetime : 'false' ?>"
					data-pubcode="<?php echo !empty($pubcode) ? $pubcode : 'false' ?>">
				Select
			</button>
		</div-->
		<style>
			.tfs_css_cancel_options p {
				text-align: left;
			}

			.tfs_css_choice_container .choices > div p, .tfs_css_choice_container a, #csd_ext_status_change_next {
				text-decoration: underline;
				color: #f60;
				cursor: pointer;
			}
			
			#csd_ext_status_change_next {
				text-align: center;
				font-size: 15px;
			}

			.tfs_css_choice_container .choices > div {
				padding: 0 0 10px 0;
			}

			.tfs_css_choice_container .hide {
				display: none;
			}

			.tfs_css_choice_container .choices {
				padding: 10px 10px 0 10px;
			}
			
			.tfs_css_choice_container .disclaimer {
				font-style: italic;
				font-size: 13px;
			}
			.tfs_css_cancel_options .divider {
				border-bottom: 1px solid #d9edf7;
				margin: 10px 0 20px 0;
			}
			.tfs_css_choice_container .choices .tfs_css_choice_solution {
				background-color: #0d3659!important;
				margin: 10px 0!important;
				text-transform: uppercase;
				border: 0;
				padding: 13px 20px;
				font-size: 12px;
				height: auto;
				min-height: 40px;
				transition: background-color 0.5s ease;
				letter-spacing: 1px;
				line-height: 15px;
				border-radius: 3px;
			}
			.tfs_css_choice_container .tfs_css_choice_solution a {
				color: #fff !important;
				text-decoration: none;
				font-family: "Montserrat" !important;
				font-weight: 600;				
			}
		</style>
		<script type="text/javascript">
			jQuery('.tfs_css_choice_container #main p').on('click', function() {
				var element = this;
				jQuery(element).parents('#main').slideUp(400, function() {
					jQuery(this).siblings('#main_' + jQuery(element).parent().data('value')).slideDown(400, function() {
						jQuery('#csd_ext_status_change_next').removeClass('hide');
					});
				});
			});		
		</script>
	</div>
</div>	