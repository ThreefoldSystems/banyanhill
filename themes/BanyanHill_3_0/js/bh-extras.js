// https://stackoverflow.com/a/38517525
function onElementInserted(containerSelector, elementSelector, callback) {
  var onMutationsObserved = function (mutations) {
    mutations.forEach(function (mutation) {
      if (mutation.addedNodes.length) {
        var elements = jQuery(mutation.addedNodes).find(elementSelector);
        for (var i = 0, len = elements.length; i < len; i++) {
          callback(elements[i]);
        }
      }
    });
  };

  var target = jQuery(containerSelector)[0];
  var config = { childList: true, subtree: true };
  var MutationObserver = window.MutationObserver || window.WebKitMutationObserver;
  var observer = new MutationObserver(onMutationsObserved);
  observer.observe(target, config);
}

function upsertStockTickerSearch(s) {
  jQuery.post('/wp-admin/admin-ajax.php', {
    action: 'bh_ajax_company_ticker_tracker',
    search: s,
  }).done(function(rsp) {
    //continue
  }).fail(function(xhr, textStatus, e) {
    console.log(xhr.responseText);
  });
}

function getStockTicker(search, response) {
  jQuery.post('/wp-admin/admin-ajax.php', {
    action: 'bh_ajax_company_ticker',
    search: search,
  }).done(function(rsp) {
    response(rsp.data.values);
  }).fail(function(xhr, textStatus, e) {
    console.log(xhr.responseText);
  });
}
