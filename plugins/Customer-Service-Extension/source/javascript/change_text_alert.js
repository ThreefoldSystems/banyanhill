jQuery(document).ajaxComplete(function () {
    jQuery(document).off('click', '.csd_ext_text_alert_change');

    jQuery(document).on('click', '.csd_ext_text_alert_change', function (e) {
        e.preventDefault();
        data = {};
        data['sub_ref'] = jQuery(this).data('subref');
        data['phone'] = jQuery(this).data('phone');
        data['addr_code'] = jQuery(this).data('addrcode');
        openCsdExtPopup('csd_ext_change_text_alert', data);
    });

    jQuery('#csd_ext_text_alert_change_confirm').click( function (e) {
        e.preventDefault();
        if ( jQuery("#csd_ext_change_text_alert_form" ).valid() ){
            jQuery(".featherlight-close").click();
            data = {};
            data['sub_ref'] = jQuery(this).data('subref');
            data['new_phone'] = jQuery('#csd_ext_new_phone').val();
            data['new_phone_repeat'] = jQuery('#csd_ext_new_phone_repeat').val();
            data['addr_code'] = jQuery(this).data('addrcode');
            openCsdExtPopup('csd_ext_change_text_alert_confirm', data);
        }
    });
    jQuery.validator.addMethod(
        "textAlertRegEx",
        function(value, element, regexpr) {
            return regexpr.test(value);
        },
        'Please enter a valid phone number'
    );
});

jQuery(document).change( function () {
    jQuery("#csd_ext_change_text_alert_form").validate( {
        rules: {
            csd_ext_new_phone: {
                textAlertRegEx: /^(?=.*[0-9])[- +()0-9]+$|^$/
            },
            csd_ext_new_phone_repeat: {
                equalTo : '[name="csd_ext_new_phone"]'
            }
        },
        messages: {
            csd_ext_new_phone: 'Please enter a valid phone number',
            csd_ext_new_phone_repeat: {
                equalTo: 'These phone numbers do not match'
            }
        }
    });
});