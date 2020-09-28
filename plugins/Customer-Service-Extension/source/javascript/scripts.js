openCsdExtPopup = function ( action, data ) {
    jQuery.featherlight(jQuery('#csd_ext_modal'), {});
    jQuery.ajax({ //ajax request
        url: csd_ext_js_localize_frontend.csd_ext_ajax_url,
        type: "POST",
        data: {
            'data': data,
            'action': action
        },
        beforeSend: function (data) {
            jQuery(".featherlight-inner").html("<div class='tfs_css_preloader'><i class='fa fa-spin fa-spinner'></i></div>");
        },
        success: function (data) {
            data = JSON.parse(data);
            jQuery(".featherlight-inner").html(data['html']);
            if (data['email_address']) {
                jQuery("." + data['sub_ref'] + "-email").html(data['email_address']);
                jQuery("." + data['sub_ref'] + "-email_button").data('subs-email', data['email_address']);
            }
            if (data['auto_renew_change']) {
                jQuery("." + data['sub_ref'] + "-auto_renew").html(data['auto_renew_change']);
                jQuery("." + data['sub_ref'] + "-auto_renew_button").data('auto', data['auto_renew_change']);
            }
            if (data['new_status']) {
                jQuery("." + data['sub_ref'] + "-status").html(data['new_status']);
                jQuery("." + data['sub_ref'] + "-button").prop("disabled", true);
            }
            if (data['phone']) {
                jQuery(".text-alert-phone").html(data['phone']);
            }
        },

        error: function (errorThrown) {
            jQuery(".featherlight-inner").html(errorThrown);
        }
    });
};

jQuery(document).ready(function () {
    if ( jQuery('#tfs_css_header').length ) {
        jQuery('.navigation-wrap').css('z-index', '9');
        jQuery('.col-lg-9').css('z-index', '8'); 
    }
});

