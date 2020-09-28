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
    <h2 class="section-head">Paid Subsciptions</h2>
    <?php
    $count = 0;
    if ( ! empty( $tfs_subscriptions ) ) {
        foreach ( $tfs_subscriptions as $key => $item ) {

            if (is_int($key)) {
                // Check if subscription exists in subscription returned from the get_local_subscriptions_dimensional
                if ( !empty($subscriptions_info[$item->pubcode] ) ) {
                    $count++;
                    $auto_status = "Off";
                    $renewal_price =  get_post_meta( intval($subscriptions_info[$item->pubcode]['post_id']),
                        'tfs_subscription_renewal_price', true );
                    if (!empty($item->renewMethod) && $item->renewMethod == 'C') {
                        $auto_status = "On";
                    }
                    ?>
                    <article data-list-pubcode="<?php echo $subscriptions_info[$item->pubcode][code] ?>">
                        <div class="subs_content">
                            <a href="<?php echo $subscriptions_info[$item->pubcode]['info_url']; ?>" target="_blank">
                                <h2><?php echo nl2br($subscriptions_info[$item->pubcode]['title']); ?></h2>
                                <?php if ( !empty($subscriptions_info[$item->pubcode]['featured_image']) )  {
                                    echo $subscriptions_info[$item->pubcode]['featured_image'];
                                } ?>
                            </a>
                            <p><?php echo $subscriptions_info[$item->pubcode]['content']; ?></p>
                            <hr>
                        </div>
                        <table class="subs_table">
                            <tbody>
                                <tr>
                                    <td>
                                        Status:
                                    </td>
                                    <td class="<?php echo $item->subref; ?>-status">
                                        <?php
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
                                    </td>
                                    <td>
                                        <button class="csd_ext_status_change <?php echo $item->subref; ?>-button"
                                                data-subref="<?php echo $item->subref; ?>"
                                                data-lifetime="<?php echo $item->is_lifetime ? 'true' : 'false' ?>"
                                                data-auto="<?php echo $auto_status; ?>"
                                                data-postid="<?php echo $subscriptions_info[$item->pubcode]['post_id']; ?>"
                                                data-pubcode="<?php echo $item->pubcode; ?>"
                                                data-subname="<?php echo $subscriptions_info[$item->pubcode]['title']; ?>"
                                                data-expire="<?php echo $item->final_expiration_date; ?>"
                                                <?php echo $item->status == 'R' && isset( $_COOKIE[$item->subref] ) ? 'disabled' : '' ?>
                                                >
                                            Change
                                        </button>
                                    </td>
                                </tr>
                                <?php if( empty($item->is_lifetime) ) { ?>
                                    <tr>
                                        <td>
                                            Renewal Date:
                                        </td>
                                        <td>
                                            <?php echo date('d M Y', strtotime($item->final_expiration_date)); ?>
                                        </td>
                                        <td>
                                            <button class="csd_ext_renewal_date_change"
                                                    data-lifetime="<?php echo !empty( $item->is_lifetime ) ? 'true' : ''; ?>"
                                                    data-url="<?php echo $subscriptions_info[$item->pubcode]['buy_url']; ?>">
                                                Change
                                            </button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            Automatic Renewal:
                                        </td>
                                        <td class="<?php echo $item->subref; ?>-auto_renew">
                                            <?php echo $auto_status; ?>
                                        </td>
                                        <td>
                                            <button class="csd_ext_auto_renewal_change <?php echo $item->subref; ?>-auto_renew_button"
                                                    data-subname="<?php echo $subscriptions_info[$item->pubcode]['title']; ?>"
                                                    data-expire="<?php echo $item->final_expiration_date; ?>"
                                                    data-lifetime="<?php echo !empty( $item->is_lifetime ) ? 'true' : ''; ?>"
                                                    data-subref="<?php echo $item->subref; ?>"
                                                    data-auto="<?php echo $auto_status; ?>">
                                                Change
                                            </button>
                                        </td>
                                    </tr>
                                    <?php if( !empty($renewal_price))  { ?>
                                        <tr>
                                            <td>
                                                Renewal Price:
                                            </td>
                                            <td>
                                            <?php if( !empty($item->rate))  { ?>
                                                <span class="csd_ext_renewal_price_span">
                                                    $<?php echo $item->rate ?>
                                                </span>
                                                    ...
                                            <?php } ?>
                                                $<?php echo !empty($renewal_price) ? $renewal_price : ''; ?>
                                            </td>
                                            <td>
                                                <button class="csd_ext_renewal_price_change"
                                                        data-rate="<?php echo !empty($item->rate) ? $item->rate : ''; ?>"
                                                        data-lifetime="<?php echo !empty( $item->is_lifetime ) ? 'true' : ''; ?>"
                                                        data-url="<?php echo $subscriptions_info[$item->pubcode]['buy_url']; ?>"
                                                        data-price="<?php echo !empty($renewal_price) ? $renewal_price : ''; ?>">
                                                    Change
                                                </button>
                                            </td>
                                        </tr>
                                    <?php }
                                } ?>
                                <tr>
                                    <td>
                                        Email:
                                    </td>
                                    <td class="<?php echo $item->subref; ?>-email">
                                        <?php $subs_email = strtolower(end(
                                            agora()->mw->findSubscriptionEmailAddressBySubRef($item->subref))->emailAddress
                                        );
                                        echo $subs_email; ?>
                                    </td>
                                    <td>
                                        <button class="csd_ext_email_change <?php echo $item->subref; ?>-email_button"
                                                data-subref="<?php echo $item->subref; ?>"
                                                data-subs-email="<?php echo $subs_email; ?>">
                                            Change
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Phone:
                                    </td>
                                    <td class="text-alert-phone">
                                        <?php
                                            echo !empty( $item->phone ) ? $item->phone : '<em>Not set</em>';
                                        ?>
                                    </td>
                                    <td>
                                        <button class="csd_ext_text_alert_change"
                                                data-phone="<?php echo !empty($item->phone) ? $item->phone : ''; ?>"
                                                data-subref="<?php echo $item->subref; ?>"
                                                data-addrcode="<?php echo $item->addrcode; ?>"
                                        >
                                            Change
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </article>
                    <?php
                    unset($subscriptions_info[$item->pubcode]);
                }
            }
        }
    } else {
        echo tfs_css()->core->get_language_variable('txt_css_subscr_not_found');
    }

    if(isset($subscriptions_info)  && empty($hide_nonsubscribed)) {
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
                            <?php if ( !empty($subs_item['featured_image']) )  {
                                echo $subs_item['featured_image'];
                            } ?>
                        </a>
                        <p><?php echo $subs_item['content'];?></p>
                        <hr>
                    </div>
                    <table class="subs_table">
                        <tbody>
                        <tr>
                            <td>
                                Status:
                            </td>
                            <td>
                                Not Subscribed
                            </td>
                            <td>
                                <button onclick="window.open(
                                    '<?php echo $subs_item['buy_url']; ?>',
                                    '_blank'
                                    )" class="tfs_css_button csd_ext_now_button">
                                    Subscribe
                                </button>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </article>
                <?php
            }
        }
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
            if(window.location.hash == '#css-subscriptions') {
                console.log(window.location.hash);
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
                            console.log('success');
                            // Hack to avoid expired nonces
                            if (data === "-1" || data === "0") {
                                location.reload();
                            } else {
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