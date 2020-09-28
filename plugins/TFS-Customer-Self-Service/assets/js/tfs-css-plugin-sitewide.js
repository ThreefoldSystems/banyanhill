// Subscriptions renewals popup
jQuery(document).ready(function() {
    if ( jQuery('#tfs_css_subscription_renewals' ).length ) {
        var tfs_subscription_renewal_cookie = Cookies.get('css_subscription_renewal');

        if ( ! tfs_subscription_renewal_cookie ) {
            jQuery.featherlight(jQuery('#tfs_css_subscription_renewals'), {
                afterClose: hide_renewal_popup
            });
        }
    }
});


function hide_renewal_popup() {
    var expire_cookie = parseInt(tfs_css_localized_sitewide_data.subscription_renewals_save_for);

    if ( expire_cookie ) {
        if ( expire_cookie == 'session' ) {
            Cookies.set('css_subscription_renewal', '1', {});
        } else {
            Cookies.set('css_subscription_renewal', '1', { expires: expire_cookie });
        }
    }
}
