jQuery(document).ready(function($){
  if(bhMagicLinkGetParameterByName('token') !== null) {
    $.get(bhMagicLink.url + '?action=bh_verify_magic_link&token='+bhMagicLinkGetParameterByName('token'), function(res) {
      if(res.success) {
        var redirect_to = '/';

        if(bhMagicLinkGetParameterByName('password_reset') !== null) {
          redirect_to = '/customer-self-service/#css-account-landing';
          localStorage.setItem("pwd", res.password);
        } else if (bhMagicLinkGetParameterByName('redirect_to') !== null) {
          redirect_to = bhMagicLinkGetParameterByName('redirect_to');
        }

        bhMagLinkpost('/wp-login.php', {
          'wp-submit': 'Sign In',
          'redirect_to': redirect_to,
          'log': res.username,
          'pwd': res.password
        });
      }
    });
  }

  if(localStorage.getItem('pwd') !== null && location.pathname == '/customer-self-service/') {
    var pwd = localStorage.getItem('pwd');
    localStorage.removeItem('pwd');

    //https://stackoverflow.com/a/38517525
    function onElementInserted(containerSelector, elementSelector, callback) {
      var onMutationsObserved = function(mutations) {
          mutations.forEach(function(mutation) {
              if (mutation.addedNodes.length) {
                  var elements = $(mutation.addedNodes).find(elementSelector);
                  for (var i = 0, len = elements.length; i < len; i++) {
                      callback(elements[i]);
                  }
              }
          });
      };

      var target = $(containerSelector)[0];
      var config = { childList: true, subtree: true };
      var MutationObserver = window.MutationObserver || window.WebKitMutationObserver;
      var observer = new MutationObserver(onMutationsObserved);
      observer.observe(target, config);
    }

    onElementInserted('body', '.subs_change_password', function(element) {
        $('.subs_change_password').find('a').attr("data-featherlight-close-on-esc", "false");
        $('.subs_change_password').find('a').attr("data-featherlight-close-on-click","false");

        $('.subs_change_password').find('a').trigger('click');

        $('.featherlight').find('.tfs_css_input_section:first').css('visibility', 'hidden');
        $('.featherlight').find('.tfs_css_input_section:first').css('height', '0px');
        $('.featherlight').find('.featherlight-close').hide();
        $('.featherlight').css('cursor', 'inherit');

        $('[name="existingPassword"]').val(pwd);
    });
  }
});

function bhMagicLinkToast(msg) {
  var x = document.getElementById("bh-magic-link-snackbar");
  x.innerHTML = msg;
  x.className = "show";
  setTimeout(function(){ x.className = x.className.replace("show", ""); }, 3000);
}

function bhMagicLinkCopyToClipboard(containerid) {
  var magicLink = document.getElementById("bh-token-url-link");
  magicLink.select();
  document.execCommand("copy");

  bhMagicLinkToast("Copy success!");
}

function bhMagicLinkGetParameterByName(name, url) {
  if (!url) url = window.location.href;
  name = name.replace(/[\[\]]/g, '\\$&');
  var regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)'),
      results = regex.exec(url);
  if (!results) return null;
  if (!results[2]) return '';
  return decodeURIComponent(results[2].replace(/\+/g, ' '));
}

function bhMagLinkpost(path, params, method='post') {
  var form = document.createElement('form');
  form.method = method;
  form.action = path;

  for (var key in params) {
    if (params.hasOwnProperty(key)) {
      var hiddenField = document.createElement('input');
      hiddenField.type = 'hidden';
      hiddenField.name = key;
      hiddenField.value = params[key];

      form.appendChild(hiddenField);
    }
  }

  document.body.appendChild(form);
  form.submit();
}
