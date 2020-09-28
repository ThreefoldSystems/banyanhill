jQuery(document).ready(function($){

  if($('.bh-watchlist-wrapper').length > 0) {
    for (var i = 0; i < $('.bh-watchlist-wrapper').length; i++) {
      onElementInserted('.bh-watchlist-wrapper:eq('+i+')', '.bh-watchlist-symbol-info', function(element) {
        processSymbolGenericInserted(element, 'watchlist-symbol');
      });
    }
    $('body').append(`
      <div class="modal bh-watchlist-modal-symbol" style="max-height:inherit!important;">
        <div class="bh-modal-header">
          <div class="bh-modal-watchlist-row">
            <div class="bh-modal-watchlist-col-search">
              <input class="bh-watchlist-search-symbol" type="text" name="search" placeholder="Search for Symbols"/>
            </div>
            <div class="bh-modal-watchlist-col-action">
              <button>Search</button>
            </div>
          </div>
        </div>
        <div class="bh-modal-watchlist-content">
          <div class="bh-lds-dual-ring bh-symbol-loading"></div>
          <div class="bh-modal-watchlist-symbols">
          </div>
        </div>
      </div>
    `);
    bhWatchlistInit();
  }

  if($('.bh-watchlist-modal-symbol').length > 0) {
    onElementInserted('.bh-watchlist-modal-symbol', '.bh-modal-watchlist-info', function(element) {
      processSymbolGenericInserted(element, 'modal-watchlist');
    });
  }

  $(document).on('touchstart click', '.bh-watchlist-add', function() {
    $('.bh-watchlist-modal-symbol').modal({fadeDuration: 250,fadeDelay: 0.80})
  });

  $(document).on('touchstart click', '.bh-watchlist-symbol-first', function() {
    $('.bh-watchlist-modal-symbol').modal({fadeDuration: 250,fadeDelay: 0.80});
  });

  $(document).on('touchstart click', '.bh-watchlist-symbol-action-remove', function() {
    $(this).html('&#10060; Removing...');
    bhSendWatchlist(jQuery(this).attr('data-id'), 'delete', null, null, null);
  });

  $(document).on('touchstart click', '.bh-watchlist-symbol-remove', function() {
    $('.bh-watchlist-symbol-info-graph').fadeOut();
    $('.bh-watchlist-symbol-action-overlay').fadeOut();
    removeTouchEventsToMouseEvents();
  });

  $(document).on('touchstart', '.bh-watchlist-symbol-drag', function(e) {
    addTouchEventsToMouseEvents();

    touchHandlerTouchEventsToMouseEvents(e.originalEvent);
  });

  $(document).on('touchstart', '.bh-watchlist-symbol', function() {
    if(!$(this).find('.bh-watchlist-symbol-info-graph').is(":visible")){
      $('.bh-watchlist-symbol-info-graph').fadeOut();
      $('.bh-watchlist-symbol-action-overlay').fadeOut();
      removeTouchEventsToMouseEvents();

      $(this).find('.bh-watchlist-symbol-info-graph').fadeIn();
      $(this).find('.bh-watchlist-symbol-action-overlay').fadeIn();

      if($(this).width() < 600) {
        var width = $(this).find('.bh-watchlist-symbol-info-graph-container').width();
        $(this).find('.bh-watchlist-symbol-info-graph').css('max-width', width + 'px');
        $(this).find('.symbols').css('max-width', width + 'px');
      }
    }
  });

  $(document).on('mouseenter', '.bh-watchlist-symbol', function() {
    $(this).find('.bh-watchlist-symbol-info-graph').fadeIn();
    $(this).find('.bh-watchlist-symbol-action-overlay').fadeIn();

    if($(this).width() < 600) {
      var width = $(this).find('.bh-watchlist-symbol-info-graph-container').width();
      $(this).find('.bh-watchlist-symbol-info-graph').css('max-width', width + 'px');
      $(this).find('.symbols').css('max-width', width + 'px');
    }
  });

  $(document).on('mouseleave', '.bh-watchlist-symbol', function() {
    $(this).find('.bh-watchlist-symbol-info-graph').fadeOut();
    $(this).find('.bh-watchlist-symbol-action-overlay').fadeOut();
  });

  $(document).on('touchstart click', '.bh-modal-watchlist', function() {
    bhSendWatchlist(0, 'upsert', $(this).find('.bh-modal-watchlist-info').attr('data-symbol'), $(this).find('.bh-modal-watchlist-name-label').text(), null);
    $.modal.close();
    $('.bh-watchlist-search-symbol').val('');
    $('.bh-watchlist-modal-symbol').find('.bh-modal-watchlist-symbols').html('');
  });

  if($.modal) {
    $(document).on($.modal.BEFORE_OPEN, '.bh-watchlist-modal-symbol', function() {
      $('.bh-symbol-loading').hide();
    });

    $(document).on($.modal.OPEN, '.bh-watchlist-modal-symbol', function() {
      if($('.bh-watchlist-search-symbol').length > 0) {
        $('.bh-watchlist-search-symbol').focus();
      }
    });
  }

  if($('.bh-watchlist-search-symbol').length > 0) {
    $( '.bh-watchlist-search-symbol' ).autocomplete({
      source: function( request, response ) {
        $('.bh-symbol-loading').show();
        if(request.term.length == 0) {
          response(bhSearchExtras.topstock.slice(0, 3));
        }else if(request.term.length < 3) {
          var builder = [];

          for (var i = 0; i < bhSearchExtras.topstock.length; i++){
            if(bhSearchExtras.topstock[i].value.toUpperCase().startsWith(request.term.toUpperCase())) {
              if (builder.length < 3) {
                builder.push(bhSearchExtras.topstock[i]);
              }
            }
          }

          for (var i = 0; i < bhSearchExtras.topstock.length; i++){
            if(bhSearchExtras.topstock[i].name.toUpperCase().startsWith(request.term.toUpperCase())) {
              if (builder.filter(function(e) { return e.value === bhSearchExtras.topstock[i].value; }).length === 0 && builder.length < 3) {
                builder.push(bhSearchExtras.topstock[i]);
              }
            }
          }

          for (var i = 0; i < bhSearchExtras.topstock.length; i++){
            if(bhSearchExtras.topstock[i].label.toUpperCase().search(request.term.toUpperCase()) !== -1) {
              if (builder.filter(function(e) { return e.value === bhSearchExtras.topstock[i].value; }).length === 0 && builder.length < 3) {
                builder.push(bhSearchExtras.topstock[i]);
              }
            }
          }

          if(builder.length > 0){
            response(builder);
          } else {
            getStockTicker(request.term, response, 3);
          }

        } else {
          getStockTicker(request.term, response, 3);
        }

      },
      create: function () {
        $(this).data('ui-autocomplete')._renderItem = function(ul, item) {
          return $(`<div class="bh-modal-watchlist">
              <div class="bh-modal-watchlist-name">
                <div class="bh-modal-watchlist-name-label">${item.name}</div>
                <div class="bh-modal-watchlist-company-label">${item.value} | US</div>
              </div>
              <div class="bh-modal-watchlist-info" data-symbol="${item.value}">
                <div class="bh-modal-watchlist-info-price">_.__</div>
                <div class="bh-modal-watchlist-info-stats">
                  <span class="bh-modal-watchlist-info-stats-points">0.00</span>
                  (<span class="bh-modal-watchlist-info-stats-percentage">0.0%</span>)
                </div>
              </div>
            </div>`)
            .appendTo(ul);
        }

        $(this).data('ui-autocomplete')._renderMenu = function (ul, items) {
          var that = this;
          $.each( items, function( index, item ) {
            that._renderItemData( ul, item );
          });
          $('.bh-watchlist-modal-symbol').find('.bh-modal-watchlist-symbols').html(ul);
        };
      },
      open: function() {
      },
      focus: function (event, ui) {
      },
      position: {
        my: "left-2 top+10", at: "left bottom", collision: "none"
      },
      select: function( event, ui ) {
        event.preventDefault();
      },
      minLength: 0,
    });
  }

  $.ajax({
    url: 'https://s3.amazonaws.com/BanyanHill_com_webimages/freshaddress-client-7.2_WP.js?token=a81a000a6259c9f211958d55a2c0930d681bc5c3c7d75110d44ba4765048a9445634c41803872d15c4ce757124413fb2',
    dataType: 'script'
  });

  $(document).on('submit', '#bh-register', function(event) {
    event.preventDefault();

    $('#bh-register-error').hide();
    $('#bh-register-error').html('');

    var error = '';
    if($('[name="email"]').val() == '') {
      error = 'Please enter an email address to create an account';
    } else if($('[name="first_name"]').val() == '') {
      error = 'Please enter a first name to create an account';
    } else if($('[name="password_one"]').val() == '') {
      error = 'Please enter a password to create an account';
    } else if($('[name="password_one"]').val().length < 6) {
      error = 'Please enter a password with at least 6 characters to create an account';
    } else if($('[name="password_one"]').val() != $('[name="password_two"]').val()) {
      error = 'Please enter passwords that match to create an account';
    }

    if(error != '') {
      $('#bh-register-error').html(error);
      $('#bh-register-error').show();
    } else {
      $('#bh-register-action').text('CREATING...');
      $('#bh-register-action').prop('disabled', true);
      $.post( '/wp-admin/admin-ajax.php',
        {
          email: $('[name="email"]').val(),
          password: $('[name="password_one"]').val(),
          first_name: $('[name="first_name"]').val(),
          last_name: $('[name="last_name"]').val(),
          action: 'bh_register_new_account'
        }).done(function( rsp ) {
          if (rsp.success) {
            if ($('[name="email"]').val().indexOf('gmail.com') !== -1) {
              location.href = '/login/?notification=confirm_gmail';
            } else {
              location.href = '/login/?notification=confirm_email';
            }
          } else {
            $('#bh-register-action').text('CREATE ACCOUNT');
            $('#bh-register-action').prop('disabled', false);

            $('#bh-register-error').html(rsp.message);
            $('#bh-register-error').show();
          }
      });
    }

  });

  $(document).on('click', '.bh-sua-cta-button', function() {
    $this = $(this);
    $('.bh-sua-cta-initial-wrapper').slideUp(87.5, function (){
      $('.bh-sua-cta-' + $this.data('op')).slideDown(175);
    });
  });

  $(document).on('click', '.bh-sua-cta-input-action', function() {
    var $this = $(this);

    var email = $(this).closest('.bh-sua-cta-input-action-wrapper').find('[name="email"]').val();
    var listCode = $(this).closest('.bh-sua-cta-input-action-wrapper').find('[name="list-code"]').val();

    $('.bh-sua-cta-error').slideUp(87.5);
    $('.bh-sua-cta-error').html('');

    if (!/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(email)) {
      $('.bh-sua-cta-error').html('Please check your email address.');
      $('.bh-sua-cta-error').slideDown(175);
    } else {
      $(this).text('Loading...');

      if(getParameterByName('z') !== null && getParameterByName('z') !== ''){
        var sourceId = getParameterByName('z');
      } else if (typeof exclusive_post_site_excode !== 'undefined') {
        var sourceId = exclusive_post_site_excode;
      } else {
        var sourceId = '';
      }

      var options = { };
      var callback = function(x) { }

      FreshAddress.validateEmail(email, options, callback).then(function (x) {
        var formResult = processValidationExtras(x);

        if (formResult.passedValidation == true) {
          $.ajax({
              url: 'https://banyanhillweb.com/fb_webhook/sua2.php',
              type: 'post',
              data: {
                listCode: listCode,
                email: email,
                sourceId: sourceId
              },
              success: function (data, textStatus, jqXHR) {
                if(data.status === 'success') {
                  $('.bh-sua-cta').slideUp(87.5, function (){
                    $('.bh-sua-cta-thank-you').slideDown(175);
                    if($this.data('redirect') !== '') {
                      location.href = $this.data('redirect');
                    }
                  });
                } else {
                  $this.text('Continue');

                  $('.bh-sua-cta-error').html('Sorry, unable to add your information.');
                  $('.bh-sua-cta-error').slideDown(175);
                }
              },
              error: function(jqXHR, textStatus, errorThrown) {
                console.log(errorThrown);
              }
            });
        } else {
          $this.text('Continue');

          $('.bh-sua-cta-error').html(`${formResult.userMessage1} <br> ${formResult.userMessage2}`);
          $('.bh-sua-cta-error').slideDown(175);
        }
      });

    }
  });

});

function processSymbolGenericInserted(element, ident) {
  jQuery.post('/wp-admin/admin-ajax.php', {
    action: 'bh_symbol',
    symbol: element.getAttribute('data-symbol'),
  }).done(function(rsp) {
    var $raw = jQuery(rsp.data.raw);

    element.getElementsByClassName('bh-'+ident+'-info-price')[0].innerText = $raw.find('ul:eq(0)').find('div:contains(\'Last price\')').siblings('.right:eq(0)').text();
    element.getElementsByClassName('bh-'+ident+'-info-stats-points')[0].innerText = $raw.find('ul:eq(0)').find('div:contains(\'Change\')').siblings('.right:eq(0)').text();
    element.getElementsByClassName('bh-'+ident+'-info-stats-percentage')[0].innerText = $raw.find('ul:eq(0)').find('div:contains(\'% Change\')').siblings('.right:eq(1)').text();

    if($raw.find('ul:eq(0)').find('div:contains(\'Change\')').siblings('.right:eq(0)').text() < 0) {
      element.getElementsByClassName('bh-'+ident+'-info-stats')[0].classList.add("bh-"+ident+"-info-stats-negative");
    } else {
      element.getElementsByClassName('bh-'+ident+'-info-stats')[0].classList.add("bh-"+ident+"-info-stats-positive");
    }

    jQuery('.bh-watchlist-symbol-info-graph[data-symbol="'+element.getAttribute('data-symbol')+'"]').html(`
        <div class="bh-watchlist-symbol-info-graph-wrapper">
          <span class="bh-watchlist-symbol-remove"></span>${rsp.data.raw}
        </div>`);

    if(jQuery('.bh-'+ident+'-info-price:contains("_.__")').length == 0) {
      jQuery('.bh-symbol-loading').hide();
    }

  }).fail(function(xhr, textStatus, e) {
    console.error(xhr.responseText);
  });
}

function processValidationExtras(x) {
  /* Sample custom return object which can be used to control form behavior [optional] */
  var formResult = {
    passedValidation: false, // default false, prevent form submission
    userMessage1: '', // store message to user
    userMessage2: '' // store message to user
  };

  /* ERROR HANDLING: Let through in case of a service error. Enable form submission. */
  if (x.isServiceError()) {
    formResult.userMessage1 = x.getServiceError();
    formResult.passedValidation = true; // Enable form submission
    console.error("FreshAddress isServiceError()", x, x.getServiceError());

    return formResult; // Return custom response object.
  }

  /* CHECK RESULT: */
  if (x.isValid()) {

    /* Check if is suggestion available */
    if (x.hasSuggest()) {
      // Valid, with Suggestion: Provide opportunity for user to correct.
      formResult.userMessage1 = 'We may have detected a typo.';
      formResult.userMessage2 = '- Did you mean to type ' + x.getSuggEmail() + '?';
    } else {
      // Valid, No Suggestion: Enable form submission.
      formResult.passedValidation = true;
    }
  } else if (x.isError() || x.isWarning()) {

    /* Check for Suggestion */
    if (x.hasSuggest()) {
      // Set response message. Provide opportunity for user to correct.
      formResult.userMessage1 = x.getErrorResponse() + '.';
      formResult.userMessage2 = '- Did you mean to type ' + x.getSuggEmail() + '?';
    } else {
      // Set response message. Provide opportunity for user to correct.
      formResult.userMessage1 = x.getErrorResponse() + '.';
    }
  } else {
    // Error Condition 2 - the service should always respond with finding E/W/V
    formResult.passedValidation = true;
  }
  return formResult; // Return custom response object.
}

function bhSnackBarShared(notification, timer) {
  jQuery('body').append('<div id="snackbar-shared-notification">'+notification+'</div>');
  setTimeout(function(){
    jQuery('#snackbar-shared-notification').remove();
  }, 3000);
}

function bhSendWatchlist(id, op, symbol, name, data) {
  jQuery.post('/wp-admin/admin-ajax.php', {
    action: 'bh_watchlist_ajax',
    nonce: bhSearchExtras.nonce,
    id: id,
    op: op,
    symbol: symbol,
    name: name,
    data: data,
  }).done(function( res ) {
    if (res.data.success) {
      bhWatchlistInit();
    } else {
      if(res.data.msg) {
        bhSnackBarShared(res.data.msg, 3000);
        if (res.data.op == 'reload'){
          setTimeout(function(){ location.reload(); }, 3000);
        }
      } else {
        bhSnackBarShared('Unknown error', 3000);
      }
    }
  });
}

function bhWatchlistInit() {
  jQuery.get('/wp-admin/admin-ajax.php?action=bh_watchlist_ajax&op=rt&nonce='+bhSearchExtras.nonce, function(res) {
    if(res.data.success) {
      var htmlBuilder = '';

      if(res.data.rt.length == 0) {
        htmlBuilder = '<div class="bh-watchlist-symbol-first">Click <span>here</span> to add your first symbol</div>';
      } else {
        for(var i = 0; i < res.data.rt.length; i++) {
          htmlBuilder += `
            <div class="bh-watchlist-symbol" data-id="${res.data.rt[i].id}">
              <div class="bh-watchlist-symbol-name">
                <div class="bh-watchlist-symbol-name-label">${res.data.rt[i].name}</div>
                <div class="bh-watchlist-symbol-company-label">${res.data.rt[i].symbol} | US</div>
              </div>
              <div class="bh-watchlist-symbol-info" data-symbol="${res.data.rt[i].symbol}">
                <div class="bh-watchlist-symbol-info-price">_.__</div>
                <div class="bh-watchlist-symbol-info-stats">
                  <span class="bh-watchlist-symbol-info-stats-points">0.00</span>
                  (<span class="bh-watchlist-symbol-info-stats-percentage">0.0%</span>)
                </div>
              </div>
              <div class="bh-watchlist-symbol-info-graph-container">
                <div class="bh-watchlist-symbol-info-graph" data-symbol="${res.data.rt[i].symbol}" data-id="${res.data.rt[i].id}"></div>
              </div>
              <div class="bh-watchlist-symbol-action-overlay-container">
                <div class="bh-watchlist-symbol-action-overlay"><span class="bh-watchlist-symbol-drag"><i class="fa fa-arrows"></i> Drag </span>| <span class="bh-watchlist-symbol-action-remove" data-id="${res.data.rt[i].id}">&#10060; Remove<span></div>
              </div>
            </div>`;
        }
      }

      jQuery('.bh-watchlist-wrapper').html(htmlBuilder);
      jQuery('.bh-watchlist-wrapper').sortable({
        stop: function(e, ui) {
            var payload = jQuery.map(jQuery(this).find('.bh-watchlist-symbol'), function(el) {
              return { id: el.getAttribute('data-id'), position: jQuery(el).index() };
            });

            bhSendWatchlist(0, 'position', null, null, payload);

            document.documentElement.style.overflow = 'auto';
        },
        start: function(e, ui) {
          document.documentElement.style.overflow = 'hidden';
        }
      });
    } else {
      if(res.data.msg) {
        bhSnackBarShared(res.data.msg, 3000);
        if (res.data.op == 'reload'){
          setTimeout(function(){ location.reload(); }, 3000);
        }
      } else {
        bhSnackBarShared('Unknown error', 3000);
      }
    }
  });
}
