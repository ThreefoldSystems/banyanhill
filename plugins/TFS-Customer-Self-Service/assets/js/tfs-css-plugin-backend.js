jQuery( document ).ready(function () {
    // Hide/show subscription renewal option based on selection - post edit screen
    if ( jQuery( 'input[name="tfs_subscription_renewals_enable"]:checked' ).val() == '1') {
        jQuery( '.tfs_subscription_renewals').show();
    }

    jQuery( 'input[name="tfs_subscription_renewals_enable"]' ).on('change', function() {
        if( jQuery( 'input[name="tfs_subscription_renewals_enable"]:checked' ).val() == '1') {
            jQuery( '.tfs_subscription_renewals').show();
        } else {
            jQuery( '.tfs_subscription_renewals').hide();
        }
    });

    // Css admin page ======================================================
    jQuery(document).on('click', '.tfs_css_warning', function(event) {
        jQuery(".tfs_css_warning").hide();
    });

    jQuery("#css_contact_mode").change(function () {
        css_contact_mode(jQuery("#css_contact_mode  option:selected").val());
    });

    // Hide/show subscription renewals options
    if ( jQuery( 'input[name="' + tfs_css_localized_backend_data.subscription_renewals + '"]:checked' ).val() == '1') {
        jQuery( '.tfs_subscription_renewal_options').show();
    }

    jQuery( 'input[name="' + tfs_css_localized_backend_data.subscription_renewals + '"]' ).on('change', function() {
        if( jQuery( 'input[name="'+ tfs_css_localized_backend_data.subscription_renewals +'"]:checked' ).val() == '1') {
            jQuery( '.tfs_subscription_renewal_options').show();
        } else {
            jQuery( '.tfs_subscription_renewal_options').hide();
        }
    });

    jQuery('#' + tfs_css_localized_backend_data.custom_templates_name).click(function () {
        jQuery("#tfsCssCustomTemplates").toggle();
    });

    if ( ! tfs_css_localized_backend_data.custom_templates) {
        jQuery('#tfsCssCustomTemplates').hide();
    }

    jQuery('#' + tfs_css_localized_backend_data.allowed_listings_checkbox_name).click(function () {
        jQuery("#tfsCssAllowedListings").toggle();
    });

    jQuery('#' + tfs_css_localized_backend_data.allowed_subscriptions_checkbox_name).click(function () {
        jQuery("#tfsCssAllowedSubscriptions").toggle();
    });

    if ( ! tfs_css_localized_backend_data.allowed_listings_checkbox) {
        jQuery('#tfsCssAllowedListings').hide();
    }

    if ( ! tfs_css_localized_backend_data.allowed_subscriptions_checkbox) {
        jQuery('#tfsCssAllowedSubscriptions').hide();
    }

    css_contact_mode(tfs_css_localized_backend_data.css_contact_mode);
});

function css_contact_mode( content ) {
    if (content == "Display text") {
        jQuery("#tfss_css_backend_shortcode").hide();
        jQuery("#tfss_css_backend_display").show();
    } else {
        jQuery("#tfss_css_backend_display").hide();
        jQuery("#tfss_css_backend_shortcode").show();
    }
}
