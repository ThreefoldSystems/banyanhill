jQuery(function () {
  jQuery('.chart-display').each(function () {
    jQuery(this).one('mouseenter', function () {
      var ticker = jQuery(this).data('ticker');
      jQuery.post(admin_ajax_url, {
        action: 'get_symbol_widget',
        name: ticker
      }, function (res) {
        jQuery('.rwc-container[data-symbol="' + ticker + '"]').html(res);
      });
    });
  });
});