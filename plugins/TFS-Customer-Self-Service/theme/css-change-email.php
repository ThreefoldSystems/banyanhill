<!--
* Customer Self Service Plugin

* Template: css-change-email

* @param $customer_email string Customer email
* @param $request_pwd_on_email_update string 1 or empty
* @param $actual_url string URL
* @param $email_change_time_remaining string Time remaining email change

It is highly recommended that you move this file to your theme folder.
Leave this template in the /theme/ folder of the plugin for future reference
-->

<div class="tfs_css_content_area">
    <h2><?php echo tfs_css()->core->get_language_variable('txt_css_change_email'); ?></h2>

    <?php
    // Check if email has been changed recently and if there is time left to wait
    if ( !empty($email_change_time_remaining) ) {
        echo tfs_css()->core->get_language_variable('txt_css_email_changed_recently', array( 'time' => $email_change_time_remaining ) );
    } else {
        ?>
        <div class="tfs_css_change_email_address_container">
            <form id="tfs_css_change_email_address_form">
                <p>
                    <?php echo tfs_css()->core->get_language_variable('txt_css_mail_actual_email');
                    if (!empty($customer_email)) { ?>:
                    <strong><?php echo $customer_email; ?></strong>
                    <?php } ?>
                </p>

                <div class="tfs_css_input_section">
                    <label for="new_email"><?php echo tfs_css()->core->get_language_variable('txt_css_mail_new_email'); ?>
                        <b class="tfs_css_mandatory">*</b>
                    </label>
                    <input type="email" value="" name="new_email" id="new_email"
                           placeholder="Please enter your new email address" required>
                </div>

                <div class="tfs_css_input_section">
                    <label for="new_email_repeat"><?php echo tfs_css()->core->get_language_variable('txt_css_mail_new_email_repeat'); ?>
                        <b class="tfs_css_mandatory">*</b>
                    </label>
                    <input type="email" value="" name="new_email_repeat" id="new_email_repeat"
                           placeholder="Please re-enter your new email address" required>
                </div>

                <div class="text-right">
                    <?php
                    if ( !empty($request_pwd_on_email_update) ) {
                        ?>
                        <button class="tfs_css_button tfs_css_change_email_submit_prompt">
                            <?php echo tfs_css()->core->get_language_variable('txt_css_mail_send_btn'); ?>
                        </button>
                        <?php
                    } else {
                        ?>
                        <button class="tfs_css_button tfs_css_change_email_submit">
                            <?php echo tfs_css()->core->get_language_variable('txt_css_mail_send_btn'); ?>
                        </button>
                        <?php
                    }
                    ?>
                </div>

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

                    <button class="tfs_css_button tfs_css_change_email_submit" disabled>
                        <?php echo tfs_css()->core->get_language_variable('txt_css_modal_pwd_submit'); ?>
                    </button>

                </div>
            </div>
        </div>
        <?php
    }
    ?>
</div>