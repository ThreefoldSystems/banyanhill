jQuery(document).ready(function($){
  if(getParameterByName('notification') != '') {
    var notification = '';
    if(getParameterByName('notification') == 'account_confirmed') {
      notification = 'Thank you for confirming your account! Please login to continue.';
    } else if (getParameterByName('notification') == 'confirm_email') {
      notification = 'Please confirm your email address before login.';
    } else if (getParameterByName('notification') == 'confirm_gmail') {
      notification = 'Please confirm your email address before by clicking <a href="https://mail.google.com/mail/u/0/#search/from%3A%40'+window.location.hostname+'+in%3Aanywhere">here</a> before login.';
    } else if (getParameterByName('notification') == 'account_needs_confirmation') {
      notification = 'Please confirm your email address to continue. Click <a href="/?notification=account_needs_confirmation&resend_confirm_act='+getParameterByName('hash')+'">here</a> to resend confirmation email.';
    } else if (getParameterByName('notification') == 'error_agora') {
      notification = 'Error confirming an account. Please contact our support team 866-584-4096.';
    }

    if (notification != '') {
      $('body').append('<div id="snackbar-shared-notification">'+notification+'</div>');
    }
  }

  function getParameterByName(name, url) {
    if (!url) url = window.location.href;
    name = name.replace(/[\[\]]/g, "\\$&");
    var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
      results = regex.exec(url);
    if (!results) return null;
    if (!results[2]) return '';
    return decodeURIComponent(results[2].replace(/\+/g, " "));
  }
});
