<!--
* Customer Service Extension Plugin

* @param $tfs_subscriptions array Filtered user subscriptions
* @param $subscriptions_info array Local subscriptions

It is highly recommended that you move this file to your theme folder.
Leave this template in the /theme/ folder of the plugin for future reference
-->
<div class="tfs_eletter_container">

</div>
<div id="tfs_css_my_subscriptions" class="csd_ext_my_subscriptions">
    <h2 class="section-head premNews">Your Premium Newsletters</h2>
	<div class="tfs_css_subs_list_container">
    <?php
    $count = 0;
	$customer_address = agora()->user->get_address();
	
	if ( !empty($customer_address->phoneNumber) ) {
		$sani_phone = preg_replace('/[^0-9]/', '', $customer_address->phoneNumber);
		$listSignups_SMS = !empty($sani_phone) ? agora()->mw->get_customer_list_signups_by_email('1' . $sani_phone  . '@190.USA.TXT.LOCAL') : '';
	}
		
    if ( ! empty( $tfs_subscriptions ) ) {
        foreach ( $tfs_subscriptions as $key => $item ) {

            if (is_int($key)) {
		// Check for TWF
		if ($item->pubcode === 'SOV' && $item->member_cat === 'GM') {
			$item->pubcode = 'TWF';
		}
				
                // Check if subscription exists in subscription returned from the get_local_subscriptions_dimensional
                if ( !empty($subscriptions_info[$item->pubcode] ) ) {
                    $count++;
                    $auto_status = "Off";
                    $renewal_price =  get_post_meta( intval($subscriptions_info[$item->pubcode]['post_id']),
                        'tfs_subscription_renewal_price', true );
                    $renewal_savings =  get_post_meta( intval($subscriptions_info[$item->pubcode]['post_id']),
                        'tfs_subscription_renewal_savings', true );					
					$mw_phoneNumber = tfs_css()->core->user->middleware_data->postalAddresses[0]->phoneNumber;
					$subs_email = strtolower(end(agora()->mw->findSubscriptionEmailAddressBySubRef($item->subref))->emailAddress);
					
                    if (!empty($item->renewMethod) && $item->renewMethod == 'C') {
                        $auto_status = "On";
                    }

                    ?>
                    <article data-list-pubcode="<?php echo $subscriptions_info[$item->pubcode][code] ?>" >
                        <div class="subs_content">
                            <a href="<?php echo $subscriptions_info[$item->pubcode]['info_url']; ?>" target="_blank">
                                <h2><?php echo nl2br($subscriptions_info[$item->pubcode]['title']); ?></h2>
                            </a>							
							<span class="subs_content_more_info">
								<div class="subs_content_modal">
									<?php 
									if ( !empty($subscriptions_info[$item->pubcode]['featured_image']) )  {
										?>
										<div class="subs_content_modal_image">
										<?php
										echo $subscriptions_info[$item->pubcode]['featured_image'];
										?>
										</div>
										<?php
									} ?>
									<div class="subs_content_modal_text">
										<?php echo apply_filters('the_content', $subscriptions_info[$item->pubcode]['content']); ?>
									</div>
								</div>							
								<i class="fa fa-ellipsis-v"></i>
							</span>							
                        </div>
						<div class="subs_table">
							<div class="subs_row">
								<div class="subs_key_name">Subscription Status:</div>
								<div class="subs_change_link">
									<?php 
										$reports_link_args = array(
											'post_type' => 'page',
											'posts_per_page' => -1,
											'post_parent' => get_page_by_title( $subscriptions_info[$item->pubcode]['title'] )->ID
										);
					
										$reports_link = '';
					
										$reports_parent = new WP_Query( $reports_link_args );
									
										if ( $reports_parent->have_posts() ) :

											while ( $reports_parent->have_posts() ) : $reports_parent->the_post(); 

												if (strpos($reports_parent->post->post_title, 'Reports') !== false ) {
													$reports_link = $reports_parent->post->ID;
												}

											endwhile;
					
										endif; 
					
										wp_reset_postdata();
					
									?>
									<a href="#" data-subref="<?php echo $item->subref; ?>"
                                                data-lifetime="<?php echo $item->is_lifetime ? 'true' : 'false' ?>"
                                                data-auto="<?php echo $auto_status; ?>"
									   			data-listemail="<?php echo $subs_email; ?>"
									   			<?php echo $reports_link ? 'data-reportslinkid="' . $reports_link . '" ' : '' ?> 
                                                data-postid="<?php echo $subscriptions_info[$item->pubcode]['post_id']; ?>"
                                                data-pubcode="<?php echo $item->pubcode; ?>"
                                                data-subname="<?php echo $subscriptions_info[$item->pubcode]['title']; ?>"
                                                data-expire="<?php echo $item->final_expiration_date; ?>"
                                                <?php echo $item->status == 'R' && isset( $_COOKIE[$item->subref] ) ? 'disabled' : '' ?>
									   			class="csd_ext_status_change <?php echo $item->subref; ?>-button">Change</a>
								</div>
							</div>
							<div class="subs_row">
								<div class="subs_property_name <?php echo $item->subref; ?>-status">
								<?php
									$future_subType = agora()->mw->get_future_subscriptions_by_subref($item->subref);
									if (!is_wp_error( $future_subType ) && strtoupper( $future_subType[0]->subType ) === 'LIFE') {
										$item->is_lifetime = TRUE;
									}

									if( !empty($item->is_lifetime) ) {
										echo 'Lifetime';
									} else {
										if ( $item->status == 'R' && isset( $_COOKIE[$item->subref] ) ) {
											echo $_COOKIE[$item->subref];
										} else {
											switch ( $item->status ) {
												case 'R':
													echo 'Active';
													break;
												case 'C':
													echo 'Cancelled';
													break;
												case 'E':
													echo 'Expired';
													break;
												case 'P':
													echo 'Perpetual';
													break;
												case 'Q':
													echo 'Controlled';
													break;
												case 'W':
													echo 'Future';
													break;
												default:
													echo 'Active';
											}
										}
									}
								?>
								</div>
							</div>
							<?php if( empty($item->is_lifetime) ) { ?>
							<div class="subs_row">
								<div class="subs_key_name">Renewal Date: <?php if ( $subscriptions_info[$item->pubcode]['renew_savings'] && ( (strtotime($item->final_expiration_date) - time()) < 15552000 ) ) { ?>
									<span class="subs_content_offer savings">
										<div class="subs_content_modal">
											<div class="subs_content_modal_image"><h2>Special Offer</h2></div>
											<div class="subs_content_modal_text">
												<p>Save $<?php echo $subscriptions_info[$item->pubcode]['renew_savings']; ?> by <a href="<?php echo $subscriptions_info[$item->pubcode]['renew_url'] ? $subscriptions_info[$item->pubcode]['renew_url'] : $subscriptions_info[$item->pubcode]['buy_url']; ?>" target="_blank">renewing</a> your <em><?php echo nl2br($subscriptions_info[$item->pubcode]['title']); ?></em> subscription now!
												</p>
												<div class="savings_button"><a href="<?php echo $subscriptions_info[$item->pubcode]['renew_url'] ? $subscriptions_info[$item->pubcode]['renew_url'] : $subscriptions_info[$item->pubcode]['buy_url']; ?>" target="_blank">Renew Now!</a></div>
											</div>
										</div>
										<span class="starburst"><div class="text"><span class="number">Save $<?php echo $subscriptions_info[$item->pubcode]['renew_savings']; ?> now!</span></div></span>
									</span>						
								<?php } ?>
								</div>
								<div class="subs_change_link">
									<a href="#" data-lifetime="<?php echo !empty( $item->is_lifetime ) ? 'true' : ''; ?>"
												data-url="<?php echo $subscriptions_info[$item->pubcode]['renew_url'] && ( (strtotime($item->final_expiration_date) - time()) < 15552000 ) ? $subscriptions_info[$item->pubcode]['renew_url'] : $subscriptions_info[$item->pubcode]['buy_url']; ?>"
									   			data-savings="<?php echo $subscriptions_info[$item->pubcode]['renew_savings'] && ( (strtotime($item->final_expiration_date) - time()) < 15552000 ) ? $subscriptions_info[$item->pubcode]['renew_savings'] : '' ; ?>"
									  			class="csd_ext_renewal_date_change">Change</a>
								</div>
							</div>
							<div class="subs_row">
								<div class="subs_property_name"><?php echo date('d M Y', strtotime($item->final_expiration_date)); ?></div>
							</div>
									
							<div class="subs_row">
								<div class="subs_key_name">Automatic Renewal:</div>
								<div class="subs_change_link">
									<a href="#" data-subname="<?php echo $subscriptions_info[$item->pubcode]['title']; ?>"
												data-expire="<?php echo $item->final_expiration_date; ?>"
												data-lifetime="<?php echo !empty( $item->is_lifetime ) ? 'true' : ''; ?>"
												data-subref="<?php echo $item->subref; ?>"
												data-auto="<?php echo $auto_status; ?>"
												class="csd_ext_auto_renewal_change <?php echo $item->subref; ?>-auto_renew_button">Change</a>
								</div>
							</div>
							<div class="subs_row">
								<div class="subs_property_name <?php echo $item->subref; ?>-auto_renew"><?php echo $auto_status; ?></div>
							</div>									
									
							<?php if( !empty($renewal_price)) { ?>
							<div class="subs_row">
								<div class="subs_key_name">Renewal Price:</div>
								<div class="subs_change_link">
									<a href="#" data-rate="<?php echo !empty($item->rate) ? $item->rate : ''; ?>"
												data-lifetime="<?php echo !empty( $item->is_lifetime ) ? 'true' : ''; ?>"
												data-url="<?php echo $subscriptions_info[$item->pubcode]['renew_url'] ? $subscriptions_info[$item->pubcode]['renew_url'] : $subscriptions_info[$item->pubcode]['buy_url']; ?>"
												data-price="<?php echo !empty($renewal_price) ? $renewal_price : ''; ?>"
												class="csd_ext_renewal_price_change">Change</a>
								</div>
							</div>
							<div class="subs_row">
								<div class="subs_property_name">
									<?php if( !empty($item->rate))  { ?>
										<span class="csd_ext_renewal_price_span">
											$<?php echo $item->rate ?>
										</span>
											...
									<?php } ?>
										$<?php echo !empty($renewal_price) ? $renewal_price : ''; ?>										
								</div>
							</div>
							<?php }
							} ?>
							<div class="subs_row">
								<div class="subs_key_name">Email:</div>
								<div class="subs_change_link <?php echo $item->subref; ?>-email">
									<a href="#" class="csd_ext_email_change <?php echo $item->subref; ?>-email_button"
                                            data-subref="<?php echo $item->subref; ?>"
											data-subs-email="<?php echo $subs_email; ?>"
                                            href="javascript:void(0);">Change</a>
								</div>
							</div>
							<div class="subs_row">
								<div class="subs_property_name"><span id="email<?php echo $item->listCode;?>"><?php echo $subs_email; ?></span></div>
							</div>
							<?php
							if( !empty($subscriptions_info[$item->pubcode]['is_backend']) ) { 
								$smsStatus = false;
								$emailId = '';
								
								foreach ( $listSignups_SMS as $smsItem ) {
									if ( strtoupper($smsItem->listCode) === strtoupper($subscriptions_info[$item->pubcode]['code']). '_SMS' && ($smsItem->status !== 'I' && $smsItem->status !== 'B' ) ) {
										$smsStatus = true;
										$emailId = $smsItem->emailId;
									}
								}
							?>
							<div class="subs_row">
								<div class="subs_key_name">SMS Alerts:</div>
								<div class="subs_change_link">
									<a href="#" onclick="css_add_remove_customer_list(this)"
									   			data-list-action="<?php echo $smsStatus ? 'remove' : 'add' ; ?>"
                                                data-list-email="<?php echo !empty($sani_phone) ? '1' . $sani_phone  . '@190.USA.TXT.LOCAL' : ''; ?>"
									   			data-list-sms="<?php echo !empty($sani_phone) ? $sani_phone : ''; ?>" 
                                                <?php echo !empty($emailId) ? 'data-email-id="' . $emailId. '"' : ''; ?>
                                                data-list-code="<?php echo strtoupper($subscriptions_info[$item->pubcode]['code']). '_SMS' ?>"
									   			data-list-xcode="XMISSING"><?php echo $smsStatus ? 'UNSUBSCRIBE' : 'SUBSCRIBE' ; ?></a>
								</div>
							</div>
							<div class="subs_row">
								<div class="subs_property_name">
									<?php echo $smsStatus ? ( !empty( $customer_address->phoneNumber ) ? $customer_address->phoneNumber : '' ) : ( !empty( $customer_address->phoneNumber ) ? 'Inactive' : 'Phone Number Not Set' ) ; ?>									
								</div>
							</div>
							<?php } ?>							
						</div>
                    </article>
                    <?php
                    unset($subscriptions_info[$item->pubcode]);
                }
            }
        }
    } else {
        echo tfs_css()->core->get_language_variable('txt_css_subscr_not_found');
    }
		
	?>
	</div>		
	<?php
    	if(isset($subscriptions_info)  && empty($hide_nonsubscribed)) {
	?>
			<h2 class="section-head">Available Premium Newsletters</h2>
			<div class="tfs_css_subs_list_container">
	<?php
        foreach ($subscriptions_info as $subs_item) {
             if (!in_array($subs_item['code'], $tfs_subscriptions['displayed_pubcodes'])
                && empty($is_hidden_meta)
                && (empty($tfs_subscriptions['allowed_subscriptions'])
                    || in_array($subs_item['code'], $tfs_subscriptions['allowed_subscriptions']))
            ) { ?>
                <article>
                    <div class="subs_content">
                        <a href="<?php echo $subs_item['info_url'];?>" target="_blank">
                            <h2><?php echo nl2br( $subs_item['title'] );?></h2>
                        </a>							
						<span class="subs_content_more_info">
							<div class="subs_content_modal">
								<?php 
								if ( !empty($subs_item['featured_image']) )  {
									?>
									<div class="subs_content_modal_image">
									<?php
									echo $subs_item['featured_image'];
									?>
									</div>
									<?php
								} ?>
								<div class="subs_content_modal_text">
									<?php echo apply_filters('the_content', $subs_item['content']); ?>
								</div>
							</div>							
							<i class="fa fa-ellipsis-v"></i>
						</span>	
                    </div>
					<div class="subs_table">
						<div class="subs_row">
							<div class="subs_key_name">Subscription Status:</div>
							<?php if (!empty($subs_item['buy_url'])) { ?>
							<div class="subs_change_link <?php echo $item->subref; ?>-email">
								<a href="#" onclick="javascript:void(0); window.open(
                                    '<?php echo $subs_item['buy_url']; ?>',
                                    '_blank'
                                    )" class="csd_ext_now_button">Learn More</a>
							</div>	
							<?php }?>						
						</div>
						<div class="subs_row">
							<div class="subs_property_name">
								Not Subscribed							
							</div>
						</div>						
					</div>
                </article>
			<?php
            }
        }
		?>
		</div>
	<?php
		} elseif ($count === 0) {
			echo tfs_css()->core->get_language_variable('txt_css_subscr_not_found');
		}
    ?>

    <!-- Popup Modal -->
    <div style="display: none">
        <div id="csd_ext_modal">

        </div>
    </div>
    <script>
				//# sourceURL=subscriptions.js
		if(window.location.hash == '#css-subscriptions') {
			jQuery.ajax({
				url: tfs_css_localized_frontend_data.css_ajax_url,
				type: "POST",
				data: {
					'action': 'css_open_url',
					'security': tfs_css_localized_frontend_data.security_css_open_url,
					'template': 'css-listings'
				},

				beforeSend: function (data) {
					jQuery(".tfs_eletter_container").html(tfs_css_localized_frontend_data.txt_css_loading);
					jQuery(".tfs_eletter_container").fadeIn(500);
				},

				success: function (data) {  //result
					// Hack to avoid expired nonces
					if (data === "-1" || data === "0") {
						location.reload();
					} else {
						if (typeof targetPubcode !== 'undefined' && targetPubcode !== '') {
							jQuery('.tfs_eletter_container').hide();
							jQuery('article[data-list-pubcode="' + targetPubcode + '"]').siblings('article').hide();
							targetPubcode = '';
						}								

						jQuery(".tfs_eletter_container").html(data);
						jQuery(".csd_ext_my_subscriptions").show();
					}
				},

				error: function (errorThrown) {
					console.log(errorThrown);
				}
			});
		}
    </script>
	</div>