jQuery( document ).ready(function() {
  jQuery( '#find-by-symbol' ).autocomplete({
    source: function( request, response ) {
      if(request.term.length == 0) {
        response(bhTicker.topstock.slice(0,10));
      }else if(request.term.length < 3) {
        var builder = [];

        for (var i = 0; i < bhTicker.topstock.length; i++){
          if(bhTicker.topstock[i].value.toUpperCase().startsWith(request.term.toUpperCase())) {
            if (builder.length < 10) {
              builder.push(bhTicker.topstock[i]);
            }
          }
        }

        for (var i = 0; i < bhTicker.topstock.length; i++){
          if(bhTicker.topstock[i].name.toUpperCase().startsWith(request.term.toUpperCase())) {
            if (builder.filter(function(e) { return e.value === bhTicker.topstock[i].value; }).length === 0 && builder.length < 10) {
              builder.push(bhTicker.topstock[i]);
            }
          }
        }

        for (var i = 0; i < bhTicker.topstock.length; i++){
          if(bhTicker.topstock[i].label.toUpperCase().search(request.term.toUpperCase()) !== -1) {
            if (builder.filter(function(e) { return e.value === bhTicker.topstock[i].value; }).length === 0 && builder.length < 10) {
              builder.push(bhTicker.topstock[i]);
            }
          }
        }

        if(builder.length > 0){
          response(builder);
        } else {
          getStockTicker(request.term, response);
        }

      } else {
        getStockTicker(request.term, response);
      }

    },
    open: function() {
      jQuery('.ui-autocomplete').css('width', parseInt(jQuery('#symbol_Search > form').css('width')) + 4 + 'px');
    },
    position: {
      my: "left-2 top+10", at: "left bottom", collision: "none"
    },
    select: function( event, ui ) {
      upsertStockTickerSearch(jQuery('#symbol_Search input').val());
      window.location.href = window.location.origin + '/stock/symbol/?id=' + ui.item.value + '&checkSymbol=false';
    },
    delay: 100,
    minLength: 0,
  }).focus(function () {
    jQuery(this).autocomplete('search');
  });

  jQuery(document).on('click','.suggested-symbol-search',function() {
    window.location.href = window.location.origin + '/stock/symbol/?id=' + jQuery(this).data('symbol') + '&checkSymbol=false';
  });

  jQuery(document).on('click','#modal-not-found-close',function() {
    jQuery.modal.close();
  });

  jQuery('#symbol_Search form').on('submit', function(e) {
    e.preventDefault();
    upsertStockTickerSearch(jQuery('#symbol_Search input').val());
    window.location.href = window.location.origin + '/stock/symbol/?id=' + jQuery('#symbol_Search input').val() + '&checkSymbol=true';
  });

  if(bhTicker.checkSymbol === 'true') {
    jQuery.post(bhTicker.url, {
      action: 'bh_ajax_ticker_check_symbol',
      symbol: bhTicker.symbol,
    }).done(function(rsp) {
      if(!rsp.data.exists) {
        jQuery('#modal-not-found').on(jQuery.modal.AFTER_CLOSE, function(event, modal) {
          jQuery('#find-by-symbol').focus();
        });

        jQuery( '#modal-not-found' ).modal();
      }
    }).fail(function(xhr, textStatus, e) {
      console.log(xhr.responseText);
    });
  }

});
