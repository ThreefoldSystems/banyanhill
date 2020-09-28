<!--
* Customer Self Service Plugin

* Template: css-listings

* @param $mw_lists array agora()->mw->get_customer_list_signups_by_id()
* @param $customer_email string
* @param $all_lists_codes array
* @param $eletter_multidimensional array
* @param $allowed_list array
* @param $allowed_listings_checkbox string
* @param $display_listings_recomendations string

It is highly recommended that you move this file to your theme folder.
Leave this template in the /theme/ folder of the plugin for future reference
-->

<?php
// Check if mw lists have been retrieved
if ( !empty($mw_lists) ) {
    ?>
    <div id="tfs_css_my_listings" class="csd_ext_my_subscriptions">
        <?php
        // Get Local listings
        $not_in_list = array();

        // Filtered array if option is activated and the textbox is not empty
        if ( !empty($allowed_listings_checkbox) && !empty( $allowed_list ) ) {
            $all_lists_codes = array_intersect( $allowed_list, $all_lists_codes );
        }
	?>
	<h2 class="section-head freeLetter">Your Free E-Letters</h2>		
	<div class="tfs_css_subs_list_container">
	<?php
        if ( !empty( $mw_lists ) && !empty($all_lists_codes)) {
            foreach ( $mw_lists as $item ) {
                if ( in_array( strtoupper($item->listCode), array_map('strtoupper', $all_lists_codes ) ) ) {
                    // List of arrays that have been displayed
                    array_push( $not_in_list, $item->listCode );

                    ?>
                    <article data-list-code="<?php echo $item->listCode; ?>">
                        <div class="subs_content">
                            <a href="<?php echo $eletter_multidimensional[$item->listCode]['postUrl']; ?>" target="_blank">
                                <h2><?php echo $eletter_multidimensional[$item->listCode]['title']; ?></h2>
                            </a>
							<span class="subs_content_more_info">
								<div class="subs_content_modal">
									<?php 
									if ( !empty($eletter_multidimensional[$item->listCode]['featured_image']) )  {
										?>
										<div class="subs_content_modal_image">
										<?php
										echo $eletter_multidimensional[$item->listCode]['featured_image'];
										?>
										</div>
										<?php
									} ?>
									<div class="subs_content_modal_text">
										<?php echo apply_filters('the_content', $eletter_multidimensional[$item->listCode]['content']); ?>
									</div>
								</div>							
								<i class="fa fa-ellipsis-v"></i>
							</span>
                            <?php echo $eletter_multidimensional[$item->listCode]['content']; ?>
                        </div>
						<div class="subs_table">
							<div class="subs_row">
								<div class="subs_key_name">Subscription Status:</div>
								<div class="subs_change_link">
									<a href="#" onclick="css_add_remove_customer_list(this)"
                                            data-list-action="remove"
                                            data-list-email="<?php echo $item->emailAddress; ?>"
                                            data-list-code="<?php echo $item->listCode; ?>"
                                            data-list-xcode="<?php echo $eletter_multidimensional[$item->listCode]['xcode']; ?>"
											class="csd_ext_now_button">Unsubscribe</a>
								</div>
							</div>
							<div class="subs_row">
								<div class="subs_property_name"><?php echo tfs_css()->css_update_api->resolve_circ_status( $item->status );?></div>
							</div>
							<div class="subs_row">
								<div class="subs_key_name">Email:</div>
								<div class="subs_change_link">
									<a href="#" data-featherlight="#tfs_css_change_email_listing" class="listing csd_ext_now_button"
                                            data-list-listcode="<?php echo $item->listCode; ?>"
                                            data-block="<?php echo strtolower($item->emailAddress); ?>"
                                            data-list-oldmail="<?php echo strtolower($item->emailAddress); ?>"
                                            href="javascript:void(0);">Change</a>
								</div>
							</div>
							<div class="subs_row">
								<div class="subs_property_name"><span id="email<?php echo $item->listCode;?>"><?php echo strtolower($item->emailAddress); ?></span></div>
							</div>							
						</div>
                    </article>
                    <?php
                }
            }
			
			if ( count($not_in_list) === 0 ) {
			?>
				<p class="eletter-subtitle">You are not currently subscribed to any E-Letters.</p>
			<?php }
        } ?>
		</div>
		<?php
        if ( !empty($display_listings_recomendations) && !empty($eletter_multidimensional) && ( count($not_in_list) !== count($all_lists_codes) ) ) {
		?>
			<h2 class="section-head availLetter">Available Free E-Letters</h2>
			<?php
				if ( ( !empty( $mw_lists ) && !empty($all_lists_codes) ) ||
				 ( !empty($display_listings_recomendations) && !empty($eletter_multidimensional) ) ) {
					$subtitle =  tfs_css()->core->get_language_variable('txt_csd_ext_eletter_subtitle');
					if ( !empty( $subtitle ) ) {
						echo '<p class="eletter-subtitle">' . $subtitle . '</p>';
					}
				}			
			?>
			<div class="tfs_css_subs_list_container">
		<?php
            foreach ( $eletter_multidimensional as $key => $value ) {
                // Check that we are displaying allowed eletters
                if ( ! in_array( $value['code'], $not_in_list ) && in_array( $value['code'], $all_lists_codes ) ) {
                    ?>
                    <article>
                        <div class="subs_content">
                            <a href="<?php echo $value['postUrl']; ?>" target="_blank">
                                <h2><?php echo $value['title']; ?></h2>
                            </a>
							<span class="subs_content_more_info">
								<div class="subs_content_modal">
									<?php 
									if ( !empty($value['featured_image']) )  {
										?>
										<div class="subs_content_modal_image">
										<?php
										echo $value['featured_image'];
										?>
										</div>
										<?php
									} ?>
									<div class="subs_content_modal_text">
										<?php echo apply_filters('the_content', $value['content']); ?>
									</div>
								</div>							
								<i class="fa fa-ellipsis-v"></i>
							</span>
                        </div>
						<div class="subs_table">
							<div class="subs_row">
								<div class="subs_key_name">Subscription Status:</div>
								<div class="subs_change_link">
									<a href="#" onclick="css_add_remove_customer_list(this)"
                                            data-list-action="add"
                                            data-list-email="<?php echo $customer_email; ?>"
											data-list-code="<?php echo $value['code']; ?>"
                                            data-list-xcode="<?php echo $value['xcode']; ?>"
											class="csd_ext_now_button">Subscribe</a>
								</div>
							</div>
							<div class="subs_row">
								<div class="subs_property_name">Not Subscribed</div>
							</div>							
						</div>
                    </article>
                    <?php
                }
            }
        }
        ?>
	</div>

    <!-- Change email popup -->
    <div style="display: none">
        <div id="tfs_css_change_email_listing">
			<div class="tfs_css_input_section">
				<label for="lists_newEmail"><?php echo tfs_css()->core->get_language_variable('txt_css_modal_change_listing_email'); ?></label>
				<input type="text" value="" name="lists_newEmail" id="lists_newEmail" placeholder="<?php echo tfs_css()->core->get_language_variable('txt_css_mail_new_email'); ?>" required autofocus>
			</div>

            <button class="tfs_css_button" onclick="css_change_listings_email();"><?php echo tfs_css()->core->get_language_variable('txt_css_text_submit'); ?></button>
            <input type="hidden" name="lists_oldMail" value="">
            <input type="hidden" name="lists_listCode" value="">
        </div>
    </div>
    <!-- Temporarily disabled popup -->
    <div style="display: none">
        <div id="tfs_css_disabled_listing">
            <div class='tfs_css_header_modal'><?php echo tfs_css()->core->get_language_variable('txt_css_modal_change_listing_email'); ?></div>
            <?php
                 echo tfs_css()->core->get_language_variable('txt_css_default_changed_recently', array( 'time' => 'at least fifteen minutes' ) )
            ?>
        </div>
    </div>
    <?php
} else {
    echo tfs_css()->core->get_language_variable('txt_css_advantage_user_error');
    ?>
            <button class="tfs_css_button" onclick="window.location.reload()">Reload This Page</button>
    <?php
}
?>