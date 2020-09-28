<!--
* Customer Self Service Plugin

* Template: css-email-change-other-updates

* @param $eletter bool
* @param $subs bool
* @param $old_email string
* @param $username string

It is highly recommended that you move this file to your theme folder.
Leave this template in the /theme/ folder of the plugin for future reference
-->

<ul class="tfs_css_change_email_other_updates tfs_csd_container__ul">
    <h4><?php echo tfs_css()->core->get_language_variable('txt_css_email_connected_items'); ?></h4>

    <?php
    if ( !empty($eletter) && $eletter === true ) {
        ?>
        <li id='ce_free' class='tfs_css_change_email_other_updates_item selected tfs_csd_container__ul__li'>
            <input type="checkbox" id="free" class="product" name="free" value="free" checked="checked">
            <label for="free"><?php echo tfs_css()->core->get_language_variable('txt_css_my_free_listings'); ?></label>
        </li>
    <?php
    }

    if ( !empty($subs) && $subs === true ) {
        ?>
        <li id='ce_paid' class='tfs_css_change_email_other_updates_item selected tfs_csd_container__ul__li'>
            <input type="checkbox" id="paid" class="product" name="paid" value="paid" checked="checked">
            <label for="paid"> <?php echo tfs_css()->core->get_language_variable('txt_css_my_subscriptions'); ?> </label>
        </li>
    <?php
    }
    ?>

    <?php
    if ( $username === true ) {
        ?>

        <h4 class="tfs_css_margin_top"><?php echo tfs_css()->core->get_language_variable('txt_css_email_change_username') ?></h4>

        <li id='ce_username' class='tfs_css_change_email_other_updates_item selected tfs_csd_container__ul__li'>
            <input type='checkbox' id='username' class='username' name='username' value='username' checked='checked'>
            <label for="username"><?php echo tfs_css()->core->get_language_variable('txt_css_text_yes') ?></label>
        </li>
    <?php
    }
    ?>
</ul>

<div class="text-right tfs_css_margin_top">
    <button class="tfs_css_button" onclick="css_request_change_updates('<?php echo $old_email ?>')">
        <?php echo tfs_css()->core->get_language_variable('txt_css_text_update') ?>
    </button>
</div>
