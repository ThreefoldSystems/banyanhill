<!--
* Customer Self Service Plugin

* Template: css-subscription-renewals

* @param $subscription_renewals array renewal notices

It is highly recommended that you move this file to your theme folder.
Leave this template in the /theme/ folder of the plugin for future reference
-->

<?php
if ( !empty($subscription_renewals) ) {
?>
    <div style="display: none;">
        <div id="tfs_css_subscription_renewals">
            <div class='tfs_css_header_modal'><?php echo tfs_css()->core->get_language_variable('txt_css_subscription_renewals_title');?></div>
            <?php echo tfs_css()->core->get_language_variable('txt_css_subscription_renewals');?>
            <?php
                foreach ( $subscription_renewals as $subscription_renewal ) {
                    echo '<p>' . $subscription_renewal . '</p>';
                }
            ?>
        </div>
    </div>
    <?php
}
?>

