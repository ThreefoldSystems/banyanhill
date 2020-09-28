jQuery(document).ajaxComplete(function () {
    jQuery(document).off('click', '.csd_ext_renewal_price_change');

    jQuery(document).on('click', '.csd_ext_renewal_price_change', function (e) {
        e.preventDefault();
        data = {};
        var lifetime = jQuery(this).data('lifetime');
        if( lifetime == true ){
            data['lifetime'] = jQuery(this).data('lifetime');
            openCsdExtPopup('csd_ext_lifetime', data);
        } else {
            data['url'] = jQuery(this).data('url');
            data['rate'] = jQuery(this).data('rate');
            data['price'] = jQuery(this).data('price');
            openCsdExtPopup('csd_ext_change_renewal_price', data);
        }

    });

    jQuery('#csd_ext_renewal_price_confirm').click( function () {
        var url = jQuery(this).data('url');
        window.open(url, '_blank');
        jQuery(".featherlight-close").click();
    });
});