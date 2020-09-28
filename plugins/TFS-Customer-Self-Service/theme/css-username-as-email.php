<!--
* Customer Self Service Plugin

* Template: css-username-as-email

* @param $customer_username string Customer username
* @param $request_pwd_on_email_update string 1 or empty

It is highly recommended that you move this file to your theme folder.
Leave this template in the /theme/ folder of the plugin for future reference
-->

<div class="tfs_css_content_area">

<div class="tfs_css_change_email_address_container">
    <form id="tfs_css_change_email_address_form">
    <?php echo tfs_css()->core->get_language_variable('txt_css_username_email_chg'); ?>
    <input type='hidden' value='<?php echo $customer_username; ?>' required name='new_email' id='new_email'>
    <input type='hidden' value='<?php echo $customer_username; ?>' required  name='new_email_repeat'>
    <input name='actual_url' id='actual_url' type='hidden' value='" . $url . "'>
    <?php
    if ( !empty($request_pwd_on_email_update) ) {
        ?>
        <button class="tfs_css_button tfs_css_change_email_submit_prompt">
            <?php echo tfs_css()->core->get_language_variable('txt_css_username_email_chg_btn'); ?>
        </button>
        <?php
    } else {
        ?>
        <button class="tfs_css_button tfs_css_change_email_submit">
            <?php echo tfs_css()->core->get_language_variable('txt_css_username_email_chg_btn'); ?>
        </button>
        <?php
    }
    ?>
    <button class='tfs_css_button' onclick='window.location.reload();'><?php echo tfs_css()->core->get_language_variable('txt_css_username_email_chg_btn_reload') ?></button>
    </form>

    <!-- Confirm password popup -->
    <div style="display: none">
        <div id="tfs_css_prompt_password_enter">
            <div class='tfs_css_header_modal'><?php echo tfs_css()->core->get_language_variable('txt_css_modal_pwd'); ?></div>

            <div class="tfs_css_prompt_form">
                <div class="tfs_css_input_section">
                    <label for="sec_email"><?php echo tfs_css()->core->get_language_variable('txt_css_pwd_existing_pwd'); ?></label>
                    <input type="password" value="" name="userPassword" id="sec_email" required autofocus>
                </div>
            </div>

            <button class="tfs_css_button tfs_css_change_email_submit">
                <?php echo tfs_css()->core->get_language_variable('txt_css_modal_pwd_submit'); ?>
            </button>

        </div>
    </div>
</div>

</div>
