jQuery(document).ajaxComplete( function () {
    jQuery(document).off('click', '.csd_ext_email_change');

    jQuery(document).on('click', '.csd_ext_email_change', function (e) {
        e.preventDefault();
        data = {};
        data['sub_ref'] = jQuery(this).data('subref');
        data['old_email'] = jQuery(this).data('subs-email');
        openCsdExtPopup('csd_ext_change_email_address', data);
    });

    jQuery('#csd_ext_email_change_confirm').click( function (e) {
        e.preventDefault();
        if ( jQuery("#csd_ext_change_email_form" ).valid() ){
            jQuery(".featherlight-close").click();
            data = {};
            data['sub_ref'] = jQuery('#csd_ext_submit_subref').val();
            data['new_email'] = jQuery('#csd_ext_new_email').val();
            data['new_email_repeat'] = jQuery('#csd_ext_new_email_repeat').val();
            openCsdExtPopup('csd_ext_change_email_address_confirm', data);
        }
    });
});

jQuery(document).change( function () {
    jQuery("#csd_ext_change_email_form").validate( {
        rules: {
            csd_ext_new_email: {
                required: true,
                email: true
            },
            csd_ext_new_email_repeat: {
                equalTo : '[name="csd_ext_new_email"]'
            }
        },
        messages: {
            csd_ext_new_email: {
                required: 'Please enter a valid email address'
            },
            csd_ext_new_email_repeat: {
                equalTo: 'These emails do not match'
            }
        }
    });
});