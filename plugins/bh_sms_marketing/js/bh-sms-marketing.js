jQuery(document).ready(function($){
  $(document).on('keyup', '#bh-sms-marketing-phone', function() {
    $(this).val(bhSmsMarketingPhoneFormat($(this).val()));
  });

  $(document).on('click', '#bh-sms-marketing-submit', function() {
    $('#bh-sms-marketing-error').hide();

    var phone = $('#bh-sms-marketing-phone').val().replace( /\D+/g, '');

    if($('#bh-sms-marketing-name').val() == '') {
      bhSmsMarketingShowError('Please enter in your first name.');
    } else if (phone.length !== 10) {
      bhSmsMarketingShowError('Please enter in your 10 digit cell phone number.');
    } else if (!jQuery('#bh-sms-marketing-optin').is(':checked')) {
      bhSmsMarketingShowError('Please read disclaimer below and check the checkbox to agree to terms.');
    } else {
      $(this).html("Loading ...");
      $.post( bhSmSMarketing.url ,
        {
          SMSList: $(this).data('list'),
          PhoneNumber: phone,
          FirstName: $('#bh-sms-marketing-name').val(),
          action: 'bh_procedure_sms_sign_up',
          Disclaimer: $('#bh-sms-marketing-disclaimer-text').text(),
          ReqURL: location.href,
          CtaSms: $(this).data('cta-sms'),
          IncludeBoilerplateWelcome: $(this).data('include-boilerplate-welcome'),
          ThankYou: bhSmsMarketingThankYouSms
        }).done(function( data ) {
          var neverExpire = new Date(2100,0,1);
          localStorage.setItem(bhSmsMarketingListKey, neverExpire);

          jQuery('#bh-sms-marketing-signup').hide();
          jQuery('#bh-sms-marketing-thank-you').slideDown();
          if(bhSmsMarketingThankYouRedirect !== '') {
            jQuery('#bh-sms-marketing-thank-you-redirect').show();
            setTimeout(function(){
              $.modal.close();
              location.href = bhSmsMarketingThankYouRedirect;
            }, 5000);
          } else {
            setTimeout(function(){
              $.modal.close();
            }, 5000);
          }
      });
    }
  });
});

var bhSmsMarketingListKey = '';
var bhSmsMarketingThankYouRedirect = '';
var bhSmsMarketingThankYouSms = '';

function bhSmsMarketingInit(list, timer, thankYourRedirect, thankYouSms) {
  bhSmsMarketingListKey = 'bh-sms-marketing-expire-' + list;
  bhSmsMarketingThankYouRedirect = thankYourRedirect;
  bhSmsMarketingThankYouSms = thankYouSms;

  var today = new Date();
  var bhSmsMarketingLaunchPopup = false;

  var bhSmsMarketingExpire = localStorage.getItem(bhSmsMarketingListKey);
  if (bhSmsMarketingExpire === null) {
    bhSmsMarketingLaunchPopup = true;
    localStorage.setItem(bhSmsMarketingListKey, today);
  } else {
    var compareDate = new Date(Date.parse(bhSmsMarketingExpire) + 2592000000); // 30 days
    if (today > compareDate) {
      localStorage.setItem(bhSmsMarketingListKey, today);
      bhSmsMarketingLaunchPopup = true;
    }
  }

  var timeout = 1000 * timer;

  if(bhSmsMarketingLaunchPopup) {
    setTimeout(function(){
      jQuery('#bh-sms-marketing').modal();
    }, timeout);
  }
}

function bhSmsMarketingShowError(err){
  jQuery('#bh-sms-marketing-error').text(err);
  jQuery('#bh-sms-marketing-error').slideDown();
}

function bhSmsMarketingPhoneFormat(input){
  // Strip all characters from the input except digits
  input = input.replace(/\D/g,'');

  // Trim the remaining input to ten characters, to preserve phone number format
  input = input.substring(0,10);

  // Based upon the length of the string, we add formatting as necessary
  var size = input.length;
  if(size == 0){
    input = input;
  }else if(size < 4){
    input = '('+input;
  }else if(size < 7){
    input = '('+input.substring(0,3)+') '+input.substring(3,6);
  }else{
    input = '('+input.substring(0,3)+') '+input.substring(3,6)+' - '+input.substring(6,10);
  }
  return input;
}

// Get query var from url
function getParameterByName(name, url) {
    if (!url) url = window.location.href;
    name = name.replace(/[\[\]]/g, "\\$&");
    var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
        results = regex.exec(url);
    if (!results) return null;
    if (!results[2]) return '';
    return decodeURIComponent(results[2].replace(/\+/g, " "));
}
