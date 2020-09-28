<!--
* Customer Self Service Plugin

* Template: css-change-address

* @param $tfs_subscriptions array Filtered user subscriptions
* @param $subscriptions_info array Local subscriptions

It is highly recommended that you move this file to your theme folder.
Leave this template in the /theme/ folder of the plugin for future reference
-->

<div id="tfs_css_my_subscriptions">
    <?php
    $count = 0;
    if ( ! empty( $tfs_subscriptions ) ) {
        foreach ( $tfs_subscriptions as $key => $item ) {

            if ( is_int( $key ) ) {
                // Check if subscription exists in subscription returned from the get_local_subscriptions_dimensional
                if( !empty($subscriptions_info) && $subscriptions_info[$item->pubcode] ) {
                    $count ++;
                    ?>
                    <article>
                        <div class="post_image">
                            <?php if($subscriptions_info[$item->pubcode]['featured_image']) {
                                echo $subscriptions_info[$item->pubcode]['featured_image'];
                            } else if (isset($placeholder_img) )  {
                                echo '<img alt="featured-image" src="' . $placeholder_img . '">';
                            }
                            ?>
                        </div>

                        <div class="subs_content">
                            <a href="<?php echo $subscriptions_info[$item->pubcode]['info_url'];?>" target="_blank"><h3><?php echo nl2br( $subscriptions_info[$item->pubcode]['title'] );?></h3></a>
                            <?php echo $subscriptions_info[$item->pubcode]['content'];?>
                        </div>

                        <ul class='tfs_css_subs_ul tfs_csd_container__ul'>
                            <?php if ( isset ( $remaining_issues ) ) { ?>
                                <li class="tfs_csd_container__ul__li--subs">
                                    <strong>Issues Remaining</strong> <?php echo $item->issuesRemaining; ?>
                                </li>
                                <?php if ( isset ( $issues_to_renew ) && $item->issuesRemaining <= $issues_to_renew ) {?>
                                    <li class="tfs_csd_container__ul__li--subs">
                                        <button onclick="window.open('<?php echo $subscriptions_info[$item->pubcode]['buy_url']; ?>','_blank')" class="tfs_css_button">Renew Now</button>
                                    </li>
                                <?php } ?>
                            <?php } else { ?>
                                <li class="tfs_csd_container__ul__li tfs_csd_container__ul__li--subs">
                                    <strong>Status:</strong>&nbsp; <span class="green-span"><?php echo tfs_css()->css_update_api->resolve_circ_status( $item->status );?></span>
                                </li>

                                <li class="tfs_csd_container__ul__li--subs">
                                    <strong><?php echo tfs_css()->core->get_language_variable('txt_css_subscr_valid_until');?>:</strong>
                                    <?php echo date( 'M Y', strtotime( $item->final_expiration_date ) ); ?>
                                </li>
                            <?php }
                            if( !empty( $allow_toggle_auto_renew ) && !empty( $item->renewMethod ) && $item->renewMethod == 'C' && empty($item->no_auto) ) { ?>
                                <li class="li-arrow tfs_csd_container__ul__li--subs">
                                    <span><strong>Auto Renew:</strong></span>
                                    <div class="arrow auto_renew_arrow_on" onclick="css_auto_renew_prompt(this)"
                                         data-list-action="remove"
                                         data-sub="<?php echo $item->subref ?>">
                                        On
                                    </div>
                                </li>
                            <?php } ?>

                            <li class="li-arrow tfs_csd_container__ul__li--subs">
                                <strong>Emailed to:</strong>&nbsp;
                                <span id="email<?php echo $item->subref;?>">
                                    <?php $subs_email = strtolower( end( agora()->mw->findSubscriptionEmailAddressBySubRef($item->subref) )->emailAddress );
                                    echo $subs_email;?>
                                </span>

                                <?php
                                if ( ! $item->email_changed ) {
                                    ?>
                                    <div class="arrow">
                                        <a data-featherlight="#tfs_css_change_email_subscription"
                                           class="subscription"
                                           data-subref="<?php echo $item->subref; ?>"
                                           data-subs-email="<?php echo $subs_email;?>"
                                           href="javascript:();">
                                            change
                                        </a>
                                    </div>
                                    <?php
                                }
                                ?>
                            </li>
                        </ul>
                    </article>
                    <?php
                }
            }
        }
    } else {
        echo tfs_css()->core->get_language_variable('txt_css_subscr_not_found');
    }

    if(isset($subscriptions_info)  && empty($hide_nonsubscribed)) {
        foreach ($subscriptions_info as $subs_item) {
            $is_hidden_meta = get_post_meta ($subs_item['post_id'], 'tfs_subscription_hide', true );
            if (!in_array($subs_item['code'], $tfs_subscriptions['displayed_pubcodes'])
                && empty($is_hidden_meta)
                && (empty($tfs_subscriptions['allowed_subscriptions'])
                    || in_array($subs_item['code'], $tfs_subscriptions['allowed_subscriptions']))
            ) { ?>
                <article>
                    <div class="post_image">
                        <?php if($subs_item['featured_image']) {
                            echo $subs_item['featured_image'];
                        } else if (isset($placeholder_img) )  {
                            echo '<img src="' . $placeholder_img . '">';
                        }
                        ?>
                    </div>

                    <div class="subs_content">
                        <h3><a href="<?php echo $subs_item['post_url']; ?>"><?php echo $subs_item['title']; ?></a></h3>
                        <?php echo $subs_item['content']; ?>
                    </div>

                    <ul class='tfs_css_subs_ul tfs_csd_container__ul'>
                        <li class="tfs_csd_container__ul__li--subs">
                            <strong>Status:</strong> <span style="color:#E2747E">&nbsp; Not subscribed</span>
                            <?php
                            if (isset($subs_item['buy_url'])) {
                                ?>
                                <button onclick="window.open('<?php echo $subs_item['buy_url']; ?>','_blank')"
                                        class='css_buy tfs_css_button'>Subscribe
                                </button>
                                <?php
                            }
                            ?>
                        </li>
                    </ul>
                </article>
                <?php
            }
        }
    } elseif ($count === 0) {
        echo tfs_css()->core->get_language_variable('txt_css_subscr_not_found');
    }
    ?>

    <!-- Change email popup -->
    <div style="display: none">
        <div id="tfs_css_change_email_subscription">
            <div class='tfs_css_header_modal'><?php echo tfs_css()->core->get_language_variable('txt_css_modal_change_subscription_email'); ?></div>

            <div class="tfs_css_prompt_form">
                <div class="tfs_css_input_section">
                    <label for="subs_chg_email_addr"><?php echo tfs_css()->core->get_language_variable('txt_css_mail_new_email'); ?></label>
                    <input type="text" value="" name="subs_chg_email_addr" id="subs_chg_email_addr" required autofocus>
                </div>
            </div>

            <button class="tfs_css_button" onclick="css_change_subscription_email();"><?php echo tfs_css()->core->get_language_variable('txt_css_text_submit'); ?></button>
            <input type="hidden" name="submit_subref" value="">
        </div>
    </div>

    <!-- Confirm password popup -->
    <div style="display: none">
        <div id="tfs_css_prompt_auto_renew">
            <div class='tfs_css_header_modal'><?php echo tfs_css()->core->get_language_variable('txt_auto_renew_confirm_head');?></div>
            <p><?php echo tfs_css()->core->get_language_variable('txt_auto_renew_confirm');?></p>
            <input onclick="css_auto_renew_stop()"
                   id="css_auto_renew_item_stop"
                   type="button"
                   value="No"
                   class="auto-renew-input">

            <input onclick="css_auto_renew()"
                   data-list-action="remove"
                   data-sub=""
                   id="css_auto_renew_item"
                   type="button"
                   value="Yes"
                   class="auto-renew-input">
        </div>
    </div>