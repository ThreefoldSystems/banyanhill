<!--
* Customer Self Service Plugin

* Template: css-account-landing

* @param

It is highly recommended that you move this file to your theme folder.
Leave this template in the /theme/ folder of the plugin for future reference
-->

<div class="tfs_css_content_area">
    <div class="tfs_css_account_landing">
        <?php if (!empty($content['account-landing'])) {
            echo html_entity_decode( $content['account-landing'] );
        } else {
        ?>
            <h2>Welcome to your dashboard!</h2>

            <p>Here you can update your home address, change your password or renew subscriptions.</p>

            <p>Have a look and see that your information correct. Everything in your dashboard can be updated online, anytime.</p>

            <p>And as always, if you have any questions or need help, please give us a call.</p>
        <?php } ?>
    </div>
</div>