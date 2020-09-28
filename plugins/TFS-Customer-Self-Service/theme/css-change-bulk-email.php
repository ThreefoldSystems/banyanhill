<!--
* Customer Self Service Plugin

* Template: css-change-bulk-email

* @param $response string/bool

It is highly recommended that you move this file to your theme folder.
Leave this template in the /theme/ folder of the plugin for future reference
-->

<div class="tfs_4_4 tfs_css_change_all">
    <?php
    if ( !empty($response) ) {
        if ( $response === true ) {
            ?>
            <div class="tfs_css_success_msg"><?php echo tfs_css()->core->get_language_variable('txt_css_email_change_correctly'); ?></div>
            <?php
        } else {
            echo $response;
        }
    }
    ?>
</div>