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

<?php
	wp_enqueue_style( 'customer-self-service', get_stylesheet_directory_uri() . '/css/customer-self-service.css' );
	wp_enqueue_style( 'customer-self-service-datepicker', get_stylesheet_directory_uri() . '/css/customer-self-service-datepicker.css' );
?>

<div id="tfs_css_menu" class="tfs_1_4 tfs_csd_container__menu">
    <ul id="tfs_css_tabs tfs_csd_container__ul tfs_csd_container__ul--tabs">
		<li class="init tfs_csd_container__ul__li tfs_csd_container__ul--tabs__li"></li>
        <?php
        // Display "my accounts"
        if ( !empty($display_account) ) {
            ?>
            <li id="css_my_account" class="css_open_url tfs_csd_container__ul__li tfs_csd_container__ul--tabs__li" data-url="css-account-landing"
                data-title="<?php echo tfs_css()->core->get_language_variable('txt_css_my_acccount'); ?>"
                title="tfs_css_my_account"><?php echo tfs_css()->core->get_language_variable('txt_css_my_acccount'); ?>
                <!--i class="tfs_csd_container__ul__li__i fa "></i-->
            </li>
        <?php
        // Display subscription
        if ( !empty($display_subscriptions) ) {
            ?>
            <li class="css_open_url tfs_csd_container__ul__li tfs_csd_container__ul--tabs__li" data-url="css-subscriptions" title="tfs_css_my_subscriptions"
                data-title="<?php echo tfs_css()->core->get_language_variable('txt_css_my_subscriptions'); ?>">
                <?php echo tfs_css()->core->get_language_variable('txt_css_my_subscriptions'); ?>
            </li>
        <?php
        }	
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
		<!--li class="css_open_url tfs_csd_container__ul__li tfs_csd_container__ul--tabs__li" data-url="css-goals" title="tfs_css_my_goals"
			data-title="My Goals">
			My Goals
		</li>
		<li class="css_open_url tfs_csd_container__ul__li tfs_csd_container__ul--tabs__li" data-url="css-referrals" title="tfs_css_my_referrals"
			data-title="My Referrals">
			My Referrals
		</li>
		<li class="css_open_url tfs_csd_container__ul__li tfs_csd_container__ul--tabs__li" data-url="css-rewards" title="tfs_css_my_rewards"
			data-title="My Rewards">
			My Rewards
		</li-->		
    </ul>
</div>
<script type="text/javascript">
jQuery(function ($) {
	if (isMobile.matches) {
		if (window.location.hash === '') {
			jQuery('#tfs_css_menu li.init').text(jQuery('#tfs_css_menu li.init').next().data('title'));
		} else {
			jQuery('#tfs_css_menu li.init').text(jQuery('#tfs_css_menu li[data-url="' + window.location.hash.replace('#','') + '"]').data('title'));
		}
		
		jQuery("#tfs_css_menu ul").on("click", ".init", function() {
			jQuery(this).closest("ul").children('li:not(.init)').toggle();
			jQuery(this).toggleClass('active');
		});
		
		var allOptions = jQuery("#tfs_css_menu ul").children('li:not(.init)');
		jQuery("#tfs_css_menu ul").on("click", "li:not(.init)", function() {
			allOptions.removeClass('selected');
			jQuery(this).addClass('selected');
			jQuery("#tfs_css_menu ul").children('.init').html(jQuery(this).html()).toggleClass('active');;
			allOptions.toggle();
		});	
	}
});
</script>