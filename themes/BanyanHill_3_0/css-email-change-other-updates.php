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
<?php echo tfs_css()->core->get_language_variable('txt_css_email_connected_items'); ?>

<ul class="tfs_css_change_email_other_updates tfs_csd_container__ul">
<?php
if ( !empty($eletter) && $eletter === true ) {
	?>
	<li id='ce_free' class='tfs_css_change_email_other_updates_item selected tfs_csd_container__ul__li tfs_css_input_section'>
		<label for="free"><?php echo tfs_css()->core->get_language_variable('txt_css_my_free_listings'); ?></label>
		<input type="checkbox" id="free" class="product" name="free" value="free" checked="checked">
	</li>
<?php
}

if ( !empty($subs) && $subs === true ) {
	?>
	<li id='ce_paid' class='tfs_css_change_email_other_updates_item selected tfs_csd_container__ul__li tfs_css_input_section'>
		<label for="paid"> <?php echo tfs_css()->core->get_language_variable('txt_css_my_subscriptions'); ?> </label>
		<input type="checkbox" id="paid" class="product" name="paid" value="paid" checked="checked">
	</li>
<?php
}
?>

<?php
if ( $username === true ) {
	?>
	<li id='ce_username' class='tfs_css_change_email_other_updates_item selected tfs_csd_container__ul__li tfs_css_input_section'>
		<label for="username"> <?php echo tfs_css()->core->get_language_variable('txt_css_email_change_username') ?> </label>
		<input type='checkbox' id='username' class='username' name='username' value='username' checked='checked'>
	</li>
<?php
}
?>
</ul>

<div class="text-right text-float-right">
    <button class="tfs_css_button" onclick="css_request_change_updates('<?php echo $old_email ?>')">
        <?php echo tfs_css()->core->get_language_variable('txt_css_text_update') ?>
    </button>
</div>
