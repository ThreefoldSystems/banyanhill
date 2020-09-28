<!--
* Customer Self Service Plugin

* Template: css-contact-support

* @param $css_contact_mode string Contact mode
* @param $css_phone_data string Contact text
* @param $css_contact_shortcode string Value of contact shortcode

It is highly recommended that you move this file to your theme folder.
Leave this template in the /theme/ folder of the plugin for future reference
-->

<div class="tfs_css_content_area">
    <?php
    if ( !empty($css_contact_mode) && $css_contact_mode == "displaytext" ) {
        echo html_entity_decode( $css_phone_data );
    } else {
        if( ! empty( $css_contact_shortcode ) ) {
            echo do_shortcode( $css_contact_shortcode );
        } else {
            echo "Error: Empty Shortcode";
        }
    }
    ?>
</div>
