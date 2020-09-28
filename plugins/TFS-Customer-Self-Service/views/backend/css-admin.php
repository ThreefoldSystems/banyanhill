<?php
// Display HTTPS error message
if( ! $is_secure ) {
    echo '<div class="tfs_css_warning">';
    echo 'Your site is currently UNSECURE. We strongly recommend updating to HTTPS. Please contact your hosting provider for more information.';
    echo '</div>';
}
?>

<div class="grid_half">
    <form method="post" action="options.php">
        <?php settings_fields( $config_name . '_group' ); ?>
        <table class="form-table">
            <tbody>
                <!-- Email notifications -->
                <tr>
                    <th scope="row">
                        <label><?php _e('Email notifications'); ?></label>
                    </th>

                    <td>
                        <input type="checkbox" id="<?php echo $config_name; ?>[send_email_on_addr_update]"
                               name="<?php echo $config_name; ?>[send_email_on_addr_update]"
                               value="1" <?php if ( $config['send_email_on_addr_update'] ) {
                            echo "checked";
                        } ?>>

                        <label for="<?php echo $config_name; ?>[send_email_on_addr_update]">
                            Send email on address update
                        </label><br>

                        <input type="checkbox" id="<?php echo $config_name; ?>[send_email_on_pwd_update]"
                               name="<?php echo $config_name; ?>[send_email_on_pwd_update]"
                               value="1" <?php if ($config['send_email_on_pwd_update']) {
                            echo "checked";
                        } ?>>

                        <label for="<?php echo $config_name; ?>[send_email_on_pwd_update]">
                            Send email on password update
                        </label><br>
                    </td>
                </tr>

                <!--Security-->
                <tr>
                    <th scope="row">
                        <label><?php _e('Security'); ?></label>
                    </th>

                    <td>
                        <input type="checkbox" id="<?php echo $config_name; ?>[request_pwd_on_addr_update]"
                               name="<?php echo $config_name; ?>[request_pwd_on_addr_update]"
                               value="1" <?php if ($config['request_pwd_on_addr_update']) {
                            echo "checked";
                        } ?>>

                        <label for="<?php echo $config_name; ?>[request_pwd_on_addr_update]">
                            Request password to update address
                        </label><br>

                        <input type="checkbox" id="<?php echo $config_name; ?>[request_pwd_on_email_update]"
                               name="<?php echo $config_name; ?>[request_pwd_on_email_update]"
                               value="1" <?php if ($config['request_pwd_on_email_update']) {
                            echo "checked";
                        } ?>>

                        <label for="<?php echo $config_name; ?>[request_pwd_on_email_update]">
                            Request password on email change
                        </label><br>

                        <input type="checkbox" id="<?php echo $config_name; ?>[request_pwd_on_username_update]"
                               name="<?php echo $config_name; ?>[request_pwd_on_username_update]"
                               value="1" <?php if ($config['request_pwd_on_username_update']) {
                            echo "checked";
                        } ?>>

                        <label for="<?php echo $config_name; ?>[request_pwd_on_username_update]">
                            Request password on username change
                        </label><br>
                    </td>
                </tr>

                <!--Listings and Subscriptions-->
                <tr>
                    <th scope="row">
                        <label for="mc_url">
                            <?php _e('Listings and Subscriptions'); ?>
                        </label>
                    </th>
                    <td>
                        <input type="checkbox" id="<?php echo $config_name; ?>_allowed_subscriptions_checkbox"
                               name="<?php echo $config_name; ?>[allowed_subscriptions_checkbox]"
                               value="1" <?php if ($config['allowed_subscriptions_checkbox']) {
                            echo "checked";
                        } ?>>

                        <label for="<?php echo $config_name; ?>_allowed_subscriptions_checkbox">
                            Display only certain subscriptions if available
                        </label><br>

                        <input type="text" id="tfsCssAllowedSubscriptions"
                               value="<?php echo esc_attr($config['allowed_subscriptions']); ?>"
                               name="<?php echo $config_name; ?>[allowed_subscriptions]" class="regular-text"
                               placeholder="">

                        <p class="description"><?php _e('Leave empty to display all the subscriptions'); ?></p>

                        <input type="checkbox" id="<?php echo $config_name; ?>_hide_nonsubscribed"
                               name="<?php echo $config_name; ?>[hide_nonsubscribed]"
                               value="1" <?php echo empty($config['hide_nonsubscribed']) ? "" : "checked"; ?>>

                        <label for="<?php echo $config_name; ?>_hide_nonsubscribed">
                            Hide Subscriptions which the user is not subscribed to
                        </label><br>

                        <input type="checkbox" id="<?php echo $config_name; ?>_show_remaining_issues"
                               name="<?php echo $config_name; ?>[show_remaining_issues]"
                               value="1" <?php if ($config['show_remaining_issues']) {
                            echo "checked";
                        } ?>>

                        <label for="<?php echo $config_name; ?>_show_remaining_issues">
                            Display remaining issues instead of status and expiry on 'My Paid Subscriptions' page
                        </label><br>

                        <input type="text" value="<?php echo $config['issues_to_renew']; ?>" id="<?php echo $config_name; ?>_issues_to_renew"
                               name="<?php echo $config_name; ?>[issues_to_renew]" class="small-text">
                        <label for="<?php echo $config_name; ?>_issues_to_renew">
                            Number of issues remaining before 'Renew Now' button is displayed on 'My Paid Subscriptions' page
                        </label><br>

                        <p class="description"><?php _e('The button will show when the user has this number of issues remaining or fewer'); ?></p>

                        <?php if ( defined( 'ALLOW_AUTO_RENEW' ) && ALLOW_AUTO_RENEW == 'yes' ) { ?>
                            <input type="checkbox" id="<?php echo $config_name; ?>_allow_toggle_auto_renew"
                                   name="<?php echo $config_name; ?>[allow_toggle_auto_renew]"
                                   value="1" <?php if ($config['allow_toggle_auto_renew']) {
                                echo "checked";
                            } ?>>

                            <label for="<?php echo $config_name; ?>_allow_toggle_auto_renew">
                                Allow users switch off auto renewal on subscriptions
                            </label><br>
                        <?php } ?>

                        <input type="checkbox" id="<?php echo $config_name; ?>_allowed_listings_checkbox"
                               name="<?php echo $config_name; ?>[allowed_listings_checkbox]"
                               value="1" <?php if ($config['allowed_listings_checkbox']) {
                            echo "checked";
                        } ?>>

                        <label for="<?php echo $config_name; ?>_allowed_listings_checkbox">
                            Display only certain newsletters if available
                        </label><br>

                        <input type="text" value="<?php echo esc_attr($config['allowed_listings']); ?>"
                               name="<?php echo $config_name; ?>[allowed_listings]" id="tfsCssAllowedListings"
                               class="regular-text" placeholder="">

                        <p class="description"><?php _e('Leave empty to display all the listings'); ?></p>

                        <input type="checkbox" name="<?php echo $config_name; ?>[display_listings_recomendations]"
                               id="<?php echo $config_name; ?>[display_listings_recomendations]"
                               value="1" <?php if ($config['display_listings_recomendations']) {
                            echo "checked";
                        } ?>>

                        <label for="<?php echo $config_name; ?>[display_listings_recomendations]">
                            Show newsletters which the user is not subscribed to
                        </label><br>

                        <input type="url" value="<?php echo $config['placeholder_img']; ?>" id="<?php echo $config_name; ?>_placeholder_img"
                               name="<?php echo $config_name; ?>[placeholder_img]"  class="regular-text">
                        <label for="<?php echo $config_name; ?>_placeholder_img">
                           Placeholder image to be used if an eletter or subscription does not have one set
                        </label><br>

                    </td>
                </tr>
                <!-- Opium pre-pop settings -->
                    <tr>
                        <th scope="row">
                            <label for="opium_url">
                                <?php _e('Default Opium Domain');?>
                            </label>
                        </th>
                        <td>
                            <input type="text" value="<?php echo $config['opium_url']; ?>" id="opium_url" name="<?php echo $config_name;?>[opium_url]" class="regular-text">
                            <p class="description"><?php _e('If the \'baseurl\' attribute is not set in the shortcode, use this opium domain'); ?></p>
                        </td>
                    </tr>
                <tr>
                    <th scope="row">
                        <label for="opium_promo">
                            <?php _e('Default Opium Promo Code');?>
                        </label>
                    </th>
                    <td>
                        <input type="text" value="<?php echo $config['opium_promo']; ?>" id="opium_promo" name="<?php echo $config_name;?>[opium_promo]" class="regular-text">
                        <p class="description"><?php _e('If the \'urlnick\' attribute is not set in the shortcode, use this promocode'); ?></p>
                    </td>
                </tr>

                <!--Subscription Renewals-->
                <tr>
                    <th scope="row">
                        <label for="mc_url">
                            <?php _e('Subscription Renewals'); ?>
                        </label>
                    </th>

                    <td>
                        <input type="checkbox" id="<?php echo $config_name; ?>[subscription_renewals]"
                               name="<?php echo $config_name; ?>[subscription_renewals]"
                               value="1" <?php if ($config['subscription_renewals']) {
                            echo "checked";
                        } ?>>

                        <label for="<?php echo $config_name; ?>[subscription_renewals]">
                            Enable
                        </label><br>

                        <div class="tfs_subscription_renewal_options" style="display: none;">
                            <hr />

                            <p class="description">
                                <?php _e('Do not show renewal popup again for:'); ?>
                            </p>

                            <select name="<?php echo $config_name; ?>[subscription_renewals_save_for]" id="<?php echo $config_name; ?>[subscription_renewals_save_for]"
                                    class="regular-text">
                                <option value="session" <?php if($config['subscription_renewals_save_for'] == "session") { echo 'selected="selected"'; } ?>>Session</option>
                                <option value="1" <?php if($config['subscription_renewals_save_for'] == "1") { echo 'selected="selected"'; } ?>>1 day</option>
                                <option value="2" <?php if($config['subscription_renewals_save_for'] == "2") { echo 'selected="selected"'; } ?>>2 days</option>
                                <option value="3" <?php if($config['subscription_renewals_save_for'] == "3") { echo 'selected="selected"'; } ?>>3 days</option>
                                <option value="4" <?php if($config['subscription_renewals_save_for'] == "4") { echo 'selected="selected"'; } ?>>4 days</option>
                                <option value="5" <?php if($config['subscription_renewals_save_for'] == "5") { echo 'selected="selected"'; } ?>>5 days</option>
                                <option value="6" <?php if($config['subscription_renewals_save_for'] == "6") { echo 'selected="selected"'; } ?>>6 days</option>
                                <option value="7" <?php if($config['subscription_renewals_save_for'] == "7") { echo 'selected="selected"'; } ?>>7 days</option>
                            </select>
                        </div>
                    </td>
                </tr>

                <!--Use Alternative Templates-->
                <tr>
                    <th scope="row">
                        <label for="mc_url">
                            <?php _e('Use Alternative Template'); ?>
                        </label>
                    </th>

                    <td>
                        <input type="checkbox" id="<?php echo $config_name; ?>_alt_templates"
                               name="<?php echo $config_name; ?>[alt_templates]"
                               value="1" <?php if ($config['alt_templates']) {
                            echo "checked";
                        } ?>>

                        <label for="<?php echo $config_name; ?>_alt_templates">
                            Enable
                        </label><br>

                        <input type="url" value="<?php echo $config['alt_img']; ?>" id="<?php echo $config_name; ?>_alt_img"
                               name="<?php echo $config_name; ?>[alt_img]"  class="regular-text">
                        <label for="<?php echo $config_name; ?>_alt_img">
                            Set the image url for the profile icon in the alternative template
                        </label><br>
                        
                        <input type="checkbox" id="<?php echo $config_name; ?>_alt_template_profile"
                               name="<?php echo $config_name; ?>[alt_template_profile]"
                               value="1" <?php if ($config['alt_template_profile']) {
                            echo "checked";
                        } ?>>

                        <label for="<?php echo $config_name; ?>_alt_template_profile">
                            Use 'My Profile' heading section
                        </label><br>
                    </td>

                </tr>

                <!--Use Custom Templates-->
                <tr>
                    <th scope="row">
                        <label for="mc_url">
                            <?php _e('Use Custom Templates'); ?>
                        </label>
                    </th>

                    <td>
                        <input type="checkbox" id="<?php echo $config_name; ?>_custom_templates"
                               name="<?php echo $config_name; ?>[custom_templates]"
                               value="1" <?php if ($config['custom_templates']) {
                            echo "checked";
                        } ?>>

                        <label for="<?php echo $config_name; ?>_custom_templates">
                            Enable
                        </label><br>

                        <div id="tfsCssCustomTemplates">
                            <input type="text"
                                   value="<?php echo esc_attr($config['templates_directory']); ?>"
                                   name="<?php echo $config_name; ?>[templates_directory]" class="regular-text"
                                   placeholder="">

                            <p class="description"><?php _e('The name of folder inside of your theme\'s folder where you keep templates for custom self service pages.'); ?></p>
                        </div>
                    </td>
                </tr>

                <!--Display settings-->
                <tr>
                    <th scope="row">
                        <label for="mc_mailing_id">
                            <?php _e('Display settings'); ?>
                        </label>
                    </th>
                    <td>
                        <input type="checkbox" id="<?php echo $config_name; ?>[display_account]"
                               name="<?php echo $config_name; ?>[display_account]"
                               value="1" <?php if ($config['display_account']) {
                            echo "checked";
                        } ?>>

                        <label for="<?php echo $config_name; ?>[display_account]">
                            Display Account
                        </label><br>

                        <input type="checkbox" id="<?php echo $config_name; ?>[display_subscriptions]"
                               name="<?php echo $config_name; ?>[display_subscriptions]"
                               value="1" <?php if ($config['display_subscriptions']) {
                            echo "checked";
                        } ?>>

                        <label for="<?php echo $config_name; ?>[display_subscriptions]">
                            Display Subscriptions
                        </label><br>

                        <input type="checkbox" id="<?php echo $config_name; ?>[display_listings]"
                               name="<?php echo $config_name; ?>[display_listings]"
                               value="1" <?php if ($config['display_listings']) {
                            echo "checked";
                        } ?>>

                        <label for="<?php echo $config_name; ?>[display_listings]">
                            Display Listings
                        </label><br>

                        <input type="checkbox" id="<?php echo $config_name; ?>[display_contact]"
                               name="<?php echo $config_name; ?>[display_contact]"
                               value="1" <?php if ($config['display_contact']) {
                            echo "checked";
                        } ?>>

                        <label for="<?php echo $config_name; ?>[display_contact]">
                            Display Contact
                        </label><br>

                        <hr>

                        <input type="checkbox" name="<?php echo $config_name; ?>[allow_change_country]"
                               id="<?php echo $config_name; ?>[allow_change_country]"
                               value="1" <?php if ( $config['allow_change_country'] ) {
                            echo "checked";
                        } ?>>

                        <label for="<?php echo $config_name; ?>[allow_change_country]">
                            Allow to change Country / State
                        </label>

                        <p class="tfs_description_red">
                            <?php _e('Due to a limitation in advantage, if this option is disabled and a user with no 
                            country code on their account attempts to enter a non-US address containing a zip code an 
                            error will occur preventing the address from updating.'); ?>
                        </p>
                    </td>
                </tr>

                <!--User minimum password length-->
                <tr>
                    <th scope="row"><label for="min_length_pwd">User minimum password length</label></th>

                    <td>
                        <select name="<?php echo $config_name; ?>[min_length_pwd]" id="min_length_pwd"
                                class="regular-text">
                            <?php

                            $pwd_length = array( 1,2,3,4,5,6,7,8,10,11,12,13,14,15 );

                            foreach ( $pwd_length as $length ) {
                                if ( $config['min_length_pwd'] == $length ) {
                                    ?>
                                    <option selected value="<?php echo $length ?>"><?php echo $length ?>  characters</option>
                                    <?php
                                } else {
                                    ?>
                                    <option value="<?php echo $length ?>"><?php echo $length ?> characters</option>
                                    <?php
                                }
                            }
                            ?>
                        </select>
                    </td>
                </tr>

                <!-- Contact support-->
                <tr>
                    <th scope="row">
                        <label><?php _e('Contact support'); ?></label>
                    </th>
                    <td>
                        <select name="<?php echo $config_name; ?>[css_contact_mode]" id="css_contact_mode" class="regular-text">
                            <?php
                            foreach ( $plugin_mode as $mode) {
                                if ( $config['css_contact_mode'] == $mode ) {
                                    ?>
                                    <option selected value="<?php echo $mode ?>"><?php echo $mode ?></option>
                                    <?php
                                } else {
                                    ?>
                                    <option value="<?php echo $mode ?>"><?php echo $mode ?></option>
                                    <?php
                                }
                            }
                            ?>
                        </select>

                        <hr>

                        <div id="tfss_css_backend_shortcode">
                            <p class="description"><?php _e('Shortcode for contact'); ?></p>
                            <input type="text" value="<?php echo esc_attr($config['css_contact_shortcode']); ?>"
                                   id="css_contact_shortcode" name="<?php echo $config_name; ?>[css_contact_shortcode]"
                                   class="regular-text">
                        </div>

                        <div id="tfss_css_backend_display">
                            <p class="description">
                                <?php _e('Customer Service Phone Message'); ?>
                            </p>

                            <textarea rows="4" cols="50" id="css_phone_data"
                                      name="<?php echo $config_name; ?>[css_phone_data]" class="regular-text"
                                      placeholder="Please phone customer service to cancel"><?php echo esc_html( $config['css_phone_data'] ); ?></textarea>
                            <p class="description"><?php _e('Displays the special members message'); ?>&nbsp;
                                <small><?php _e('HTML supported'); ?></small>
                            </p>
                        </div>
                    </td>


                    <!-- Landing Text -->
                <tr>
                    <th scope="row">
                        <label><?php _e('Dashboard Landing Page Text'); ?></label>
                    </th>
                    <td>
                        <textarea rows="4" cols="50" id="css_account_landing"
                              name="<?php echo $config_name; ?>[css_account_landing]" class="regular-text"
                              placeholder="A default message is displayed if you do not fill this box"><?php echo esc_html( $config['css_account_landing'] ); ?></textarea>

                        <p class="description">&nbsp;<small><?php _e('HTML supported'); ?></small>
                        </p>
                    </td>
                </tr>
            </tbody>
        </table>

        <?php submit_button(__('Save'), 'primary', 'submit'); ?>
    </form>
</div>