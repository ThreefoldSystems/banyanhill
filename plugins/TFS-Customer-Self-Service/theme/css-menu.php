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

<div id="tfs_css_menu" class="tfs_1_4 tfs_csd_container__menu">
    <ul id="tfs_css_tabs tfs_csd_container__ul tfs_csd_container__ul--tabs">
        <?php
        // Display "my accounts"
        if ( !empty($display_account) ) {
            ?>
            <li id="css_my_account" class="css_open_url account tfs_csd_container__ul__li tfs_csd_container__ul--tabs__li" data-url="css-account-landing"
                data-title="<?php echo tfs_css()->core->get_language_variable('txt_css_my_acccount'); ?>"
                title="tfs_css_my_account"><?php echo tfs_css()->core->get_language_variable('txt_css_my_acccount'); ?>
                <i class="tfs_csd_container__ul__li__i fa "></i>
            </li>

            <li class="submenu tfs_csd_container__ul__li tfs_csd_container__submenu tfs_csd_container__ul--tabs__li">
                <ul class="tfs_csd_container__submenu__ul">
                    <li class="css_open_url address-tab tfs_csd_container__submenu__ul__li tfs_csd_container__ul--tabs__li" data-url="css-change-address"
                        data-title="<?php echo tfs_css()->core->get_language_variable('txt_css_change_address_h1'); ?>">
                        <span class="tfs_csd_container__ul__li__span"><?php echo tfs_css()->core->get_language_variable('txt_css_change_address'); ?></span>
                        <i class="sub tfs_csd_container__ul__li__i fa "></i>
                    </li>
                    <li class="css_open_url email-tab tfs_csd_container__submenu__ul__li tfs_csd_container__ul--tabs__li" data-url="css-change-email"
                        data-title="<?php echo tfs_css()->core->get_language_variable('txt_css_mail_h1'); ?>">
                        <span class="tfs_csd_container__ul__li__span"><?php echo tfs_css()->core->get_language_variable('txt_css_change_email'); ?></span>
                        <i class="sub tfs_csd_container__ul__li__i fa "></i>
                    </li>
                    <li class="css_open_url username-tab tfs_csd_container__submenu__ul__li tfs_csd_container__ul--tabs__li" data-url="css-change-username"
							 data-title="<?php echo tfs_css()->core->get_language_variable('txt_css_username_h1'); ?>">
						<span class="tfs_csd_container__ul__li__span"><?php echo tfs_css()->core->get_language_variable('txt_css_change_username'); ?></span>
						<i class="sub tfs_csd_container__ul__li__i fa "></i>
					</li>

                    <li class="css_open_url password-tab tfs_csd_container__submenu__ul__li tfs_csd_container__ul--tabs__li" data-url="css-change-password"
                        data-title="<?php echo tfs_css()->core->get_language_variable('txt_css_pwd_h1'); ?>">
                        <span class="tfs_csd_container__ul__li__span"><?php echo tfs_css()->core->get_language_variable('txt_css_change_password'); ?></span>
                        <i class="sub tfs_csd_container__ul__li__i fa "></i>
                    </li>
                    <?php
                    if( defined('CSD_CC_INFO')) {
                        ?>
                        <li class="css_open_url payment-tab tfs_csd_container__submenu__ul__li
					tfs_csd_container__ul--tabs__li"
                            data-url="css-payment" title="tfs_css_my_payment"
                            data-title="Payment Information">
                            Payment Information
                        </li>
                        <?php
                    }
                    ?>
                </ul>
            </li>
            <?php
        }

        // Display subscription
        if ( !empty($display_subscriptions) ) {
            ?>
            <li class="css_open_url tfs_csd_container__ul__li tfs_csd_container__ul--tabs__li" data-url="css-subscriptions" title="tfs_css_my_subscriptions"
                data-title="<?php echo tfs_css()->core->get_language_variable('txt_css_subscr_h1'); ?>">
                <?php echo tfs_css()->core->get_language_variable('txt_css_my_subscriptions'); ?>
            </li>
        <?php
        }

        // Display listings
        if ( !empty($display_listings) ) {
            ?>
            <li class="css_open_url tfs_csd_container__ul__li tfs_csd_container__ul--tabs__li" data-url="css-listings" title="My Free Listings"
                data-title="<?php echo tfs_css()->core->get_language_variable('txt_css_list_h1'); ?>">
                <?php echo tfs_css()->core->get_language_variable('txt_css_my_free_listings'); ?>
            </li>
        <?php
        }

        // Display Contact
        if ( !empty($display_contact) ) {
            ?>
            <li class="css_open_url tfs_csd_container__ul__li tfs_csd_container__ul--tabs__li" data-url="css-contact-support"
                title="Contact Support" data-title="<?php echo tfs_css()->core->get_language_variable('txt_css_contact_h1'); ?>">
                <?php echo tfs_css()->core->get_language_variable('txt_css_contact_support'); ?>
            </li>
            <?php
        }
        ?>
    </ul>
</div>