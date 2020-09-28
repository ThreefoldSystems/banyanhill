jQuery(document).ajaxComplete(function () {
    jQuery(document).off('click', '.csd_ext_status_change');

    jQuery(document).on('click', '.csd_ext_status_change', function (e) {
        e.preventDefault();
        data = {};
        data['sub_ref'] = jQuery(this).data('subref');
        data['auto_status'] = jQuery(this).data('auto');
        data['post_id'] = jQuery(this).data('postid');
        data['lifetime'] = jQuery(this).data('lifetime');
        data['pubcode'] = jQuery(this).data('pubcode');
        data['expire'] = jQuery(this).data('expire');
        data['subname'] = jQuery(this).data('subname');
        openCsdExtPopup('csd_ext_change_status', data);
    });

    jQuery('#csd_ext_status_change_next').on('click', function () {
        jQuery(".featherlight-close").click();
        data = {};
        data['status_flow_index'] = jQuery('#csd_ext_status_flow_index').val();
        data['sub_ref'] = jQuery(this).data('subref');
        data['post_id'] = jQuery(this).data('postid');
        data['lifetime'] = jQuery(this).data('lifetime');
        data['pubcode'] = jQuery(this).data('pubcode');
        openCsdExtPopup('csd_ext_change_status', data);
    });

    jQuery('#csd_ext_pause_status').click( function () {
        jQuery(".featherlight-close").click();
        data = {};
        data['sub_ref'] = jQuery(this).data('subref');
        openCsdExtPopup('csd_ext_pause_status', data);
    });

    jQuery('#csd_ext_status_refund').click( function () {
        jQuery(".featherlight-close").click();
        data = {};
        data['sub_ref'] = jQuery(this).data('subref')
        openCsdExtPopup('csd_ext_status_refund', data);
    });

    jQuery('#csd_ext_status_end').click( function () {
        jQuery(".featherlight-close").click();
    });

    if ( jQuery('input[name="video_proceed"]').length ) {
        var show_button = jQuery('input[name="video_proceed"]').val();
        setTimeout( function () {
            jQuery('.wait').removeClass('wait');
        }, show_button * 1000);
    }
});
