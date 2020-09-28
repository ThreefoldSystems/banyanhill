jQuery(document).ajaxComplete(function () {
    jQuery(document).off('click', '.csd_ext_auto_renewal_change');

    jQuery(document).on('click', '.csd_ext_auto_renewal_change', function (e) {
        jQuery(".featherlight-close").click();
        e.preventDefault();
        data = {};
        var lifetime = jQuery(this).data('lifetime');
        if( lifetime == true ){
            data['lifetime'] = jQuery(this).data('lifetime');
            openCsdExtPopup('csd_ext_lifetime', data);
        } else {
            data['auto_status'] = jQuery(this).data('auto');
            data['subref'] = jQuery(this).data('subref');
            data['expire'] = jQuery(this).data('expire');
            data['subname'] = jQuery(this).data('subname');
            openCsdExtPopup('csd_ext_change_auto_renew', data);
        }
    });

    jQuery('#csd_ext_auto_renew_confirm').click( function () {
        jQuery(".featherlight-close").click();
        data = {};
        data['auto_status'] = jQuery(this).data('auto');
        data['subref'] = jQuery(this).data('subref');
        data['expire'] = jQuery(this).data('expire');
        data['subname'] = jQuery(this).data('subname');
        openCsdExtPopup('csd_ext_change_auto_renew_confirm', data);
    });

    jQuery('#csd_ext_auto_renew_remind').click( function () {
        jQuery(".featherlight-close").click();
        data = {};
        data['sub_ref'] = jQuery(this).data('subref');
        data['expire'] = jQuery(this).data('expire');
        data['sub_name'] = jQuery(this).data('subname');
        openCsdExtPopup('csd_ext_auto_renew_remind', data);
    });

});
