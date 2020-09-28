<!--
* Customer Self Service Plugin

* Template: css-customer-self-service

* @param $subscription_renewals_save_for string
* @param $subscription_renewals_save_for string
* @param $request_pwd_on_addr_update string

It is highly recommended that you move this file to your theme folder.
Leave this template in the /theme/ folder of the plugin for future reference
-->

<div id="tfs_css_body" class="tfs_csd_container">
    <!--div id="tfs_css_header">
        <?php echo tfs_css()->core->get_language_variable('txt_css_css'); ?>
    </div-->

    <?php tfs_css()->template_manager->process_template( 'css-menu' ); ?>

    <div id="tfs_css_content">
        <?php echo tfs_css()->core->get_language_variable('txt_css_loading'); ?>
    </div>
</div>