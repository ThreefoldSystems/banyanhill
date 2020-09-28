<!--
* Customer Self Service Plugin

* Template: css-change-address

* @param $customer_address array agora()->user->get_address()/empty
* @param $allow_change_country string 1/empty
* @param $request_pwd_on_addr_update string 1/empty

It is highly recommended that you move this file to your theme folder.
Leave this template in the /theme/ folder of the plugin for future reference
-->

<?php
// Check if customer address has been passed to the view
if ( !empty($customer_address) ) {
    // Check if it's allowed to change country
    if ( empty($allow_change_country) ) {
        ?>
        <div class="tfs_css_message_alert"><?php echo tfs_css()->core->get_language_variable('txt_css_addr_country'); ?></div>
        <?php
    }
    ?>

    <div class="tfs_css_content_area">
        <form id="tfs_css_change_address_form">
            <div class="tfs_name_container">
                <div class="tfs_css_input_section first_name">
                    <label for="firstName">
                        <?php echo tfs_css()->core->get_language_variable('txt_css_first_name'); ?>
                        <b class="tfs_css_mandatory">*</b>
                    </label>

                    <input type="text" value="<?php echo ucwords( strtolower( $customer_address->firstName ) ); ?>"
                           name="firstName" id="firstName" placeholder="Please enter your first name" required>
                </div>

                <div class="tfs_css_input_section last_name">
                    <label for="lastName">
                        <?php echo tfs_css()->core->get_language_variable('txt_css_last_name'); ?>
                        <b class="tfs_css_mandatory">*</b>
                    </label>

                    <input type="text" value="<?php echo ucwords( strtolower( $customer_address->lastName ) ); ?>"
                           name="lastName" id="lastName" placeholder="Please enter your last name"  required>
                </div>

            </div>

            <div class="tfs_css_input_section clear-both">
                <label for="phoneNumber">
                    <?php echo tfs_css()->core->get_language_variable('txt_css_phone'); ?>
                </label>

                <input type="text" value="<?php echo $customer_address->phoneNumber; ?>"
                       name="phoneNumber" id="phoneNumber" placeholder="Please enter your phone number" >
            </div>

            <div class="tfs_css_input_section">
                <label for="street">
                    <?php echo tfs_css()->core->get_language_variable('txt_css_street'); ?>
                </label>

                <input type="text" value="<?php echo ucwords( strtolower( $customer_address->street ) ); ?>"
                       name="street" id="street" placeholder="Please enter address line 1" >
            </div>

            <div class="tfs_css_input_section">
                <label for="street2">
                    <?php echo tfs_css()->core->get_language_variable('txt_css_street_two'); ?>
                </label>

                <input type="text" value="<?php echo ucwords( strtolower( $customer_address->street2 ) ); ?>"
                       name="street2" id="street2" placeholder="Please enter address line 2">
            </div>

            <div class="tfs_css_input_section">
                <label for="street3">
                    <?php echo tfs_css()->core->get_language_variable('txt_css_street_three'); ?>
                </label>

                <input type="text" value="<?php echo ucwords( strtolower( $customer_address->street3 ) ); ?>"
                       name="street3" id="street3" placeholder="Please enter address line 3">
            </div>

            <div class="tfs_css_input_section">
                <label for="city">
                    <?php echo tfs_css()->core->get_language_variable('txt_css_city'); ?>
                </label>

                <input type="text" value="<?php echo ucwords( strtolower( $customer_address->city ) ); ?>"
                       name="city" id="city" placeholder="Please enter your city">
            </div>

            <div class="tfs_css_input_section">
                <label for="postalCode">
                    <?php echo tfs_css()->core->get_language_variable('txt_css_postal_code'); ?>
                </label>

                <input type="text" value="<?php echo $customer_address->postalCode; ?>"
                       name="postalCode" placeholder="Please enter your zip code">
            </div>

            <?php
            // Allow users to edit the country and the state
            if ( !empty($allow_change_country) ) {
                ?>
                <div class="tfs_css_input_section">
                    <label for="tfs_css_countryCode">
                        <?php echo tfs_css()->core->get_language_variable('txt_css_country'); ?>
                    </label>

                    <?php tfs_css()->css_update_api->css_country_selector( $customer_address->countryCode ); ?>
                </div>

                <div id="tfs_css_state_display" class="tfs_css_input_section">
                    <label for="tfs_css_countryCode">
                        <?php echo tfs_css()->core->get_language_variable('txt_css_state'); ?>
                    </label>

                    <div id="tfs_css_state">
                        <?php echo tfs_css()->css_update_api->css_get_state( $customer_address->countryCode, $customer_address->state ); ?>
                    </div>
                </div>
                <?php
            } else {
                echo '<input type="hidden" value="' . $customer_address->countryCode . '" id="tfs_css_countryCode" name="countryCode">';
                echo '<input type="hidden" value="' . $customer_address->state . '" name="state" id="tfs_css_state">';
            }?>

            <div class="text-right">
                <?php
                if ( !empty($request_pwd_on_addr_update) ) {
                    ?>
                    <button class="tfs_css_button tfs_css_change_address_submit_prompt">
                        <?php echo tfs_css()->core->get_language_variable('txt_css_save_btn'); ?>
                    </button>
                    <?php
                } else {
                    ?>
                    <button class="tfs_css_button tfs_css_change_address_submit">
                        <?php echo tfs_css()->core->get_language_variable('txt_css_save_btn'); ?>
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
                        <label for="sec_pwd"><?php echo tfs_css()->core->get_language_variable('txt_css_pwd_existing_pwd'); ?></label>
                        <input type="password" value="" name="userPassword" id="sec_pwd" required autofocus>
                    </div>
                </div>

                <button class="tfs_css_button tfs_css_change_address_submit">
                    <?php echo tfs_css()->core->get_language_variable('txt_css_save_btn'); ?>
                </button>

            </div>
        </div>
    </div>
    <?php
} else {
    echo tfs_css()->core->get_language_variable('txt_css_advantage_user_error');
}
?>