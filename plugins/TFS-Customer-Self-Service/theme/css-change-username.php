<!--
* Customer Self Service Plugin

* Template: css-change-username

* @param $customer_username string Customer username
* @param $request_pwd_on_username_update string
* @param $username_change_time_remaining string Time remaining username change

It is highly recommended that you move this file to your theme folder.
Leave this template in the /theme/ folder of the plugin for future reference
-->

<div class="tfs_css_content_area">
    <h2><?php echo tfs_css()->core->get_language_variable('txt_css_change_username'); ?></h2>

    <?php
    // Check if username has been changed recently and if there is time left to wait
    if ( !empty($username_change_time_remaining) ) {
        echo tfs_css()->core->get_language_variable('txt_css_username_changed_recently', array( 'time' => $username_change_time_remaining ) );
    } else {
        ?>
        <form id="tfs_css_change_username_form">
            <p>
                <?php echo tfs_css()->core->get_language_variable('txt_css_actual_username'); ?>:
                <span class="username_wrap"><strong><?php echo $customer_username; ?></strong></span>
            </p>

            <div class="tfs_css_input_section">
                <label for="new_username"><?php echo tfs_css()->core->get_language_variable('txt_css_desired_username'); ?><b class="tfs_css_mandatory">*</b></label>
                <input type="text" value="" required name="new_username"
                       placeholder="Please enter your new username" id="new_username">
            </div>

            <div class="text-right">
                <?php
                if ( !empty($request_pwd_on_username_update) ) {
                    ?>
                    <button class="tfs_css_button tfs_css_change_username_submit_prompt">
                        <?php echo tfs_css()->core->get_language_variable('txt_css_submit_username'); ?>
                    </button>
                    <?php
                } else {
                    ?>
                    <button class="tfs_css_button tfs_css_change_username_submit">
                        <?php echo tfs_css()->core->get_language_variable('txt_css_submit_username'); ?>
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

                <button class="tfs_css_button tfs_css_change_username_submit">
                    <?php echo tfs_css()->core->get_language_variable('txt_css_submit_username');?>
                </button>
            </div>
        </div>
    <?php
    }
    ?>
</div>