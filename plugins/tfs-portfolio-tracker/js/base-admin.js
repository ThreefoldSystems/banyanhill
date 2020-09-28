jQuery(function(){
    jQuery('#cache_clear').click(function(){
        jQuery.ajax({
            url: admin.ajaxurl,
            type: 'post',
            data: {
            'action' : 'clear_portfolio_cache'
            },
            success: function (data) {
                jQuery('#message').html('Cache Cleared Successfully!');
                jQuery('#message').show(100);
                jQuery('#message').delay(3000).hide(200);
            }
        });
    });
});


