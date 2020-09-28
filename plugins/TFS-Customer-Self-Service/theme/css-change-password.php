<!--
* Customer Self Service Plugin

* Template: css-change-password

* @param $password_change_time_remaining string Time remaining password change

It is highly recommended that you move this file to your theme folder.
Leave this template in the /theme/ folder of the plugin for future reference
-->

<div class="tfs_css_content_area">
    <h2><?php echo tfs_css()->core->get_language_variable('txt_css_change_password'); ?></h2>

    <?php
//     Check if password has been changed recently and if there is time left to wait
    if ( !empty($password_change_time_remaining) ) {
        echo tfs_css()->core->get_language_variable('txt_css_pwd_changed_recently', array( 'time' => $password_change_time_remaining ) );
    } else {
        ?>
        <form id="tfs_css_change_password_form">
            <div class="tfs_css_input_section">
                <label for="existingPassword">
                    <?php echo tfs_css()->core->get_language_variable('txt_css_pwd_existing_pwd'); ?>
                    <b class="tfs_css_mandatory">*</b>
                </label>

                <input type="password" value="" name="existingPassword" id="existingPassword" required placeholder="<?php echo tfs_css()->core->get_language_variable('txt_css_pwd_existing_pwd_placeholder'); ?>">
            </div>

            <div class="tfs_css_input_section">
                <label for="newPassword">
                    <?php echo tfs_css()->core->get_language_variable('txt_css_pwd_new_pwd'); ?>
                    <b class="tfs_css_mandatory">*</b>
                </label>

                <input type="password" value="" name="newPassword" id="newPassword" required placeholder="<?php echo tfs_css()->core->get_language_variable('txt_css_pwd_new_pwd_placeholder'); ?>">
            </div>

            <div class="tfs_css_input_section">
                <label for="newPassword_repeat">
                    <?php echo tfs_css()->core->get_language_variable('txt_css_pwd_repeat_new_pwd'); ?>
                    <b class="tfs_css_mandatory">*</b>
                </label>

                <input type="password" value="" name="newPassword_repeat" id="newPassword_repeat" required placeholder="<?php echo tfs_css()->core->get_language_variable('txt_css_pwd_repeat_new_pwd_placeholder'); ?>">
            </div>

            <div class="text-right">
                <button class="tfs_css_button css_password_send_form">
                    <?php echo tfs_css()->core->get_language_variable('txt_css_pwd_save_btn'); ?>
                </button>
            </div>

        </form>
        <?php
    }
    ?>
</div>
