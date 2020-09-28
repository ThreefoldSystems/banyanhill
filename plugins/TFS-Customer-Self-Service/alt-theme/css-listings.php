<!--
* Customer Self Service Plugin

* Template: css-listings

* @param $mw_lists array agora()->mw->get_customer_list_signups_by_id()
* @param $customer_email string
* @param $all_lists_codes array
* @param $eletter_multidimensional array
* @param $allowed_list array
* @param $allowed_listings_checkbox string
* @param $display_listings_recomendations string

It is highly recommended that you move this file to your theme folder.
Leave this template in the /theme/ folder of the plugin for future reference
-->

<?php
// Check if mw lists have been retrieved
if ( !empty($mw_lists) ) {
    ?>
    <div id="tfs_css_my_listings">
        <?php
        // Get Local listings
        $not_in_list = array();

        // Filtered array if option is activated and the textbox is not empty
        if ( !empty($allowed_listings_checkbox) && !empty( $allowed_list ) ) {
            $all_lists_codes = array_intersect( $allowed_list, $all_lists_codes );
        }

        if ( !empty( $mw_lists ) && !empty($all_lists_codes) ) {
            foreach ( $mw_lists as $item ) {
                  if ( in_array( strtoupper($item->listCode), array_map('strtoupper', $all_lists_codes ) ) ) {
                    // List of arrays that have been displayed
                    array_push( $not_in_list, $item->listCode );

                    ?>
                    <article data-list-code="<?php echo $item->listCode; ?>">
                        <div class="subscribe_holder">
                            <input onclick="css_add_remove_customer_list(this)" data-list-action="remove"
                                   data-list-email="<?php echo $item->emailAddress; ?>"
                                   data-list-code="<?php echo $item->listCode; ?>"
                                   data-list-xcode="<?php echo $eletter_multidimensional[$item->listCode]['xcode']; ?>"
                                   type="checkbox" name="onoffswitch"
                                   class="onoffswitch-checkbox" id="switch_<?php echo $item->listCode; ?>" checked>
                            <label class="onoffswitch-label" for="switch_<?php echo $item->listCode; ?>">
                                <span class="onoffswitch-inner"></span>
                                <span class="onoffswitch-switch"></span>
                            </label>
                        </div>

                        <div class="post_image">
                        <?php if(!empty($eletter_multidimensional) && $eletter_multidimensional[$item->listCode]['featured_image']) {
                                echo $eletter_multidimensional[$item->listCode]['featured_image'];
                            } else if (isset($placeholder_img) )  {
                                echo '<img alt="featured-image" src="' . $placeholder_img . '">';
                            }
                        ?>
                        </div>

                        <div class="subs_content">
                            <h3><a href="<?php echo $eletter_multidimensional[$item->listCode]['postUrl']; ?>" target="_blank">
                                <?php echo $eletter_multidimensional[$item->listCode]['title']; ?>
                            </a></h3>
                            <?php echo $eletter_multidimensional[$item->listCode]['content']; ?>
                        </div>
                        <ul class='tfs_css_subs_ul tfs_csd_container__ul'>
                            <li class="tfs_csd_container__ul__li--subs">
                                <strong>Status:</strong> <span style="color:green">&nbsp;<?php echo tfs_css()->css_update_api->resolve_circ_status( $item->status );?></span>
                            </li>
                            <li class="li-arrow tfs_csd_container__ul__li--subs">
                                <strong>Emailed to:</strong>&nbsp; <span id="email<?php echo $item->listCode;?>"><?php echo strtolower($item->emailAddress); ?></span>

                                <?php
                                if ( ! $item->email_changed ) {
                                    ?>
                                    <div class="arrow">
                                        <a data-featherlight="#tfs_css_change_email_listing" class="listing"
                                            data-list-listcode="<?php echo $item->listCode; ?>"
                                            data-list-newmail="<?php echo strtolower($item->emailAddress); ?>"
                                            data-list-oldmail="<?php echo strtolower($item->emailAddress); ?>"
                                            href="javascript:();">
                                            change
                                        </a>
                                    </div>
                                    <?php
                                } else {
                                ?>
                                    <div class="disabled-arrow">
                                        <a data-featherlight="#tfs_css_disabled_listing" class="disabled-listing"
                                            href="javascript:void(0);">
                                            change
                                        </a>
                                    </div>
                                    <?php
                                }
                                ?>
                            </li>
                        </ul>
                    </article>
                    <?php
                }
            }
        }


        if ( !empty($display_listings_recomendations) && !empty($eletter_multidimensional) ) {
            foreach ( $eletter_multidimensional as $key => $value ) {
                // Check that we are displaying allowed eletters
                if ( ! in_array( $value['code'], $not_in_list ) && in_array( $value['code'], $all_lists_codes ) ) {
                    ?>
                    <article>
                        <div class="subscribe_holder">
                            <input onclick="css_add_remove_customer_list(this)"
                                   data-list-action="add"
                                   data-list-email="<?php echo $customer_email; ?>"
                                   data-list-code="<?php echo $value['code']; ?>"
                                   data-list-xcode="<?php echo $value['xcode']; ?>"
                                   type="checkbox" name="onoffswitch"
                                   class="onoffswitch-checkbox"
                                   id="switch_<?php echo $value['code']; ?>">

                            <label class="onoffswitch-label" for="switch_<?php echo $value['code']; ?>">
                                <span class="onoffswitch-inner"></span>
                                <span class="onoffswitch-switch"></span>
                            </label>
                        </div>
                        <div class="post_image">
                        <?php if($eletter_multidimensional[$value['code']]['featured_image']) {
                                echo $eletter_multidimensional[$value['code']]['featured_image'];
                            } else if (isset($placeholder_img) )  {
                                echo '<img alt="featured-image" src="' . $placeholder_img . '">';
                            }
                        ?>
                        </div>
                        <div class="subs_content">
                            <h3><?php echo $value['title']; ?></h3>

                            <?php echo $value['content']?> </div>
                        <ul class='tfs_css_subs_ul tfs_csd_container__ul'>
                            <li class="tfs_csd_container__ul__li--subs">
                                <strong>Status:</strong> &nbsp;<span class="tfs_css_not_subscribed">Not subscribed</span>
                            </li>
                        </ul>
                    </article>
                    <?php
                }
            }
        }
        ?>



    <!-- Change email popup -->
    <div style="display: none">
        <div id="tfs_css_change_email_listing">
            <div class='tfs_css_header_modal'><?php echo tfs_css()->core->get_language_variable('txt_css_modal_change_listing_email'); ?></div>

            <div class="tfs_css_prompt_form">
                <div class="tfs_css_input_section">
                    <label for="lists_newEmail"><?php echo tfs_css()->core->get_language_variable('txt_css_mail_new_email'); ?></label>
                    <input type="text" value="" name="lists_newEmail" id="lists_newEmail" required autofocus>
                </div>
            </div>

            <button class="tfs_css_button" onclick="css_change_listings_email();"><?php echo tfs_css()->core->get_language_variable('txt_css_text_submit'); ?></button>
            <input type="hidden" name="lists_oldMail" value="">
            <input type="hidden" name="lists_listCode" value="">
        </div>
    </div>
     <!-- Temporarily disabled popup -->
    <div style="display: none">
        <div id="tfs_css_disabled_listing">
            <div class='tfs_css_header_modal'><?php echo tfs_css()->core->get_language_variable('txt_css_modal_change_listing_email'); ?></div>
            <?php
                 echo tfs_css()->core->get_language_variable('txt_css_default_changed_recently', array( 'time' => 'at least fifteen minutes' ) )
            ?>
        </div>
    </div>
    <?php
} else {
    echo tfs_css()->core->get_language_variable('txt_css_advantage_user_error');
    ?>
            <button class="tfs_css_button" onclick="window.location.reload()">Reload This Page</button>
    <?php
}
?>