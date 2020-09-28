<!--
* Customer Self Service Plugin

* Template: css-menu

* @param $display_account string
* @param $display_subscriptions string
* @param $display_listings string
* @param $display_contact string

It is highly recommended that you move this file to your theme folder.
Leave this template in the /theme/ folder of the plugin for future reference
-->

<div id="tfs_css_alt_theme">
    <?php if(isset($alt_template_profile)){ ?>
        <div id="tfs_css_profile">
            <h2>My Profile</h2>
            <?php if(isset($alt_icon)){
                echo '<div><img class="profile-icon" alt="profile-icon" src="' . $alt_icon . '"></div>';
            }
           ?>
            <div class="tfs_csd_container__div--profile">
                <div>
                    <ul class="tfs_csd_container__ul tfs_csd_container__ul--profile-name">
                        <?php
                        echo '<li class="tfs_csd_container__ul__li tfs_csd_container__ul--profile-name__li tfs_css_alt_theme_toggle">';
                        if (!empty($css_user->middleware_data->subscriptionsAndOrders->subscriptions)) {
                            echo $css_user->wp_user->data->display_name . ' <i class="sub profile_toggle fa fa-chevron-down"></i>';
                        } else {
                            echo $css_user->wp_user->data->display_name;
                        }
                        echo '</li>';
                        ?>
                    </ul>
                </div>

                <?php
                    if (!empty($css_user->middleware_data->subscriptionsAndOrders->subscriptions)) {
                        foreach ($css_user->middleware_data->subscriptionsAndOrders->subscriptions as $sub) {
                            echo '<ul class="tfs_csd_container__ul tfs_csd_container__ul--profile-sub">';
                            echo '<li class="tfs_csd_container__ul__li tfs_csd_container__ul--profile-sub__li">' . $sub->id->item->itemDescription . ' Member</li>';
                            echo '<li class="tfs_csd_container__ul__li tfs_csd_container__ul--profile-sub__li">Member Since ' . date('Y', strtotime($sub->startDate)) . '</li></ul>';
                        }
                    }
                ?>
            </div>

        </div>
    <?php } ?>

    <div id="tfs_css_account">
        <h2>Update My Account Information</h2>

        <?php
        // Display "my accounts"
        if ( !empty($display_account) ) {
            ?>
            <div class="css_open_url address-tab" data-url="css-change-address"
                data-title="<?php echo tfs_css()->core->get_language_variable('txt_css_change_address_h1'); ?>">
                <span><?php echo tfs_css()->core->get_language_variable('txt_css_change_address'); ?></span>
                <i class="sub fa fa-chevron-down"></i>
            </div>
            <div class="css-change-address tfs_css_content">
                <?php echo tfs_css()->core->get_language_variable('txt_css_loading'); ?>
            </div>
            <div class="css_open_url email-tab" data-url="css-change-email"
                data-title="<?php echo tfs_css()->core->get_language_variable('txt_css_mail_h1'); ?>">
                <span><?php echo tfs_css()->core->get_language_variable('txt_css_change_email'); ?></span>
                <i class="sub fa fa-chevron-down"></i>
            </div>
            <div class="css-change-email tfs_css_content">
                <?php echo tfs_css()->core->get_language_variable('txt_css_loading'); ?>
            </div>
            <div class="css_open_url username-tab" data-url="css-change-username"
                data-title="<?php echo tfs_css()->core->get_language_variable('txt_css_username_h1'); ?>">
                <span><?php echo tfs_css()->core->get_language_variable('txt_css_change_username'); ?></span>
                <i class="sub fa fa-chevron-down"></i>
            </div>
            <div class="css-change-username tfs_css_content">
                <?php echo tfs_css()->core->get_language_variable('txt_css_loading'); ?>
            </div>
            <div class="css_open_url password-tab" data-url="css-change-password"
                data-title="<?php echo tfs_css()->core->get_language_variable('txt_css_pwd_h1'); ?>">
                <span><?php echo tfs_css()->core->get_language_variable('txt_css_change_password'); ?></span>
                <i class="sub fa fa-chevron-down"></i>
            </div>
            <div class="css-change-password tfs_css_content">
                <?php echo tfs_css()->core->get_language_variable('txt_css_loading'); ?>
            </div>
            <?php
        }
        // Display subscription
        if ( !empty($display_subscriptions) ) {
            ?>
            <div class="css_open_url subscriptions-tab" data-url="css-subscriptions" title="tfs_css_my_subscriptions"
                data-title="<?php echo tfs_css()->core->get_language_variable('txt_css_subscr_h1'); ?>">
                <?php echo tfs_css()->core->get_language_variable('txt_css_my_subscriptions'); ?>
                <i class="sub fa fa-chevron-down"></i>
            </div>
            <div class="css-subscriptions tfs_css_content">
                <?php echo tfs_css()->core->get_language_variable('txt_css_loading'); ?>
            </div>
            <?php
        }

        // Display listings
        if ( !empty($display_listings) ) {
            ?>
            <div class="css_open_url listings-tab" data-url="css-listings" title="My Free Listings"
                data-title="<?php echo tfs_css()->core->get_language_variable('txt_css_list_h1'); ?>">
                <?php echo tfs_css()->core->get_language_variable('txt_css_my_free_listings'); ?>
                <i class="sub fa fa-chevron-down"></i>
            </div>
            <div class="css-listings tfs_css_content">
                <?php echo tfs_css()->core->get_language_variable('txt_css_loading'); ?>
            </div>
            <?php
        }

        // Display Contact
        if ( !empty($display_contact) ) {
            ?>
            <div class="css_open_url support-tab" data-url="css-contact-support"
                title="Contact Support" data-title="<?php echo tfs_css()->core->get_language_variable('txt_css_contact_h1'); ?>">
                <?php echo tfs_css()->core->get_language_variable('txt_css_contact_support'); ?>
                <i class="sub fa fa-chevron-down"></i>
            </div>
            <div class="css-contact-support tfs_css_content">
                <?php echo tfs_css()->core->get_language_variable('txt_css_loading'); ?>
            </div>
            <?php
        }
        ?>
    </div>
</div>