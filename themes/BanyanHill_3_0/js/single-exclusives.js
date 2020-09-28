var mw_social_login_data = {"fb_login_src":"https:\/\/connect.facebook.net\/en_US\/sdk.js#xfbml=1&version=v2.11&appId=154704538519037"};
var EXTRA = {"images_uri":"\/wp-content\/themes\/Extra\/images\/","ajaxurl":"\/wp-admin\/admin-ajax.php","your_rating":"Your Rating:","item_in_cart_count":"%d Item in Cart","items_in_cart_count":"%d Items in Cart","item_count":"%d Item","items_count":"%d Items","rating_nonce":"1b6ff0709e","timeline_nonce":"65f5c05efb","blog_feed_nonce":"f7b7c0f739","error":"There was a problem, please try again.","contact_error_name_required":"Name field cannot be empty.","contact_error_email_required":"Email field cannot be empty.","contact_error_email_invalid":"Please enter a valid email address.","is_ab_testing_active":"","is_cache_plugin_active":"no"};
var et_pb_custom = {"ajaxurl":"\/wp-admin\/admin-ajax.php","images_uri":"\/wp-content\/themes\/Extra\/images","builder_images_uri":"\/wp-content\/themes\/Extra\/includes\/builder\/images","et_frontend_nonce":"bbd3afb8b6","subscription_failed":"Please, check the fields below to make sure you entered the correct information.","et_ab_log_nonce":"5bd3969c3d","fill_message":"Please, fill in the following fields:","contact_error_message":"Please, fix the following errors:","invalid":"Invalid email","captcha":"Captcha","prev":"Prev","previous":"Previous","next":"Next","wrong_captcha":"You entered the wrong number in captcha.","ignore_waypoints":"no","is_divi_theme_used":"","widget_search_selector":".widget_search","ab_tests":[],"is_ab_testing_active":"","page_id":"406722","unique_test_id":"","ab_bounce_rate":"5","is_cache_plugin_active":"no","is_shortcode_tracking":"","tinymce_uri":""}; var et_frontend_scripts = {"builderCssContainerPrefix":"#et-boc","builderCssLayoutPrefix":"#et-boc .et-l"};

var isUserLoggedIn = true;
var isMobile = window.matchMedia('only screen and (max-width: 767px)');
var lazyLoadImages = function() {
  /*!
  hey, [be]Lazy.js - v1.8.2 - 2016.10.25
  A fast, small and dependency free lazy load script (https://github.com/dinbror/blazy)
  (c) Bjoern Klinggaard - @bklinggaard - http://dinbror.dk/blazy
  */
  (function(q,m){"function"===typeof define&&define.amd?define(m):"object"===typeof exports?module.exports=m():q.Blazy=m()})(this,function(){function q(b){var c=b._util;c.elements=E(b.options);c.count=c.elements.length;c.destroyed&&(c.destroyed=!1,b.options.container&&l(b.options.container,function(a){n(a,"scroll",c.validateT)}),n(window,"resize",c.saveViewportOffsetT),n(window,"resize",c.validateT),n(window,"scroll",c.validateT));m(b)}function m(b){for(var c=b._util,a=0;a<c.count;a++){var d=c.elements[a],e;a:{var g=d;e=b.options;var p=g.getBoundingClientRect();if(e.container&&y&&(g=g.closest(e.containerClass))){g=g.getBoundingClientRect();e=r(g,f)?r(p,{top:g.top-e.offset,right:g.right+e.offset,bottom:g.bottom+e.offset,left:g.left-e.offset}):!1;break a}e=r(p,f)}if(e||t(d,b.options.successClass))b.load(d),c.elements.splice(a,1),c.count--,a--}0===c.count&&b.destroy()}function r(b,c){return b.right>=c.left&&b.bottom>=c.top&&b.left<=c.right&&b.top<=c.bottom}function z(b,c,a){if(!t(b,a.successClass)&&(c||a.loadInvisible||0<b.offsetWidth&&0<b.offsetHeight))if(c=b.getAttribute(u)||b.getAttribute(a.src)){c=c.split(a.separator);var d=c[A&&1<c.length?1:0],e=b.getAttribute(a.srcset),g="img"===b.nodeName.toLowerCase(),p=(c=b.parentNode)&&"picture"===c.nodeName.toLowerCase();if(g||void 0===b.src){var h=new Image,w=function(){a.error&&a.error(b,"invalid");v(b,a.errorClass);k(h,"error",w);k(h,"load",f)},f=function(){g?p||B(b,d,e):b.style.backgroundImage='url("'+d+'")';x(b,a);k(h,"load",f);k(h,"error",w)};p&&(h=b,l(c.getElementsByTagName("source"),function(b){var c=a.srcset,e=b.getAttribute(c);e&&(b.setAttribute("srcset",e),b.removeAttribute(c))}));n(h,"error",w);n(h,"load",f);B(h,d,e)}else b.src=d,x(b,a)}else"video"===b.nodeName.toLowerCase()?(l(b.getElementsByTagName("source"),function(b){var c=a.src,e=b.getAttribute(c);e&&(b.setAttribute("src",e),b.removeAttribute(c))}),b.load(),x(b,a)):(a.error&&a.error(b,"missing"),v(b,a.errorClass))}function x(b,c){v(b,c.successClass);c.success&&c.success(b);b.removeAttribute(c.src);b.removeAttribute(c.srcset);l(c.breakpoints,function(a){b.removeAttribute(a.src)})}function B(b,c,a){a&&b.setAttribute("srcset",a);b.src=c}function t(b,c){return-1!==(" "+b.className+" ").indexOf(" "+c+" ")}function v(b,c){t(b,c)||(b.className+=" "+c)}function E(b){var c=[];b=b.root.querySelectorAll(b.selector);for(var a=b.length;a--;c.unshift(b[a]));return c}function C(b){f.bottom=(window.innerHeight||document.documentElement.clientHeight)+b;f.right=(window.innerWidth||document.documentElement.clientWidth)+b}function n(b,c,a){b.attachEvent?b.attachEvent&&b.attachEvent("on"+c,a):b.addEventListener(c,a,{capture:!1,passive:!0})}function k(b,c,a){b.detachEvent?b.detachEvent&&b.detachEvent("on"+c,a):b.removeEventListener(c,a,{capture:!1,passive:!0})}function l(b,c){if(b&&c)for(var a=b.length,d=0;d<a&&!1!==c(b[d],d);d++);}function D(b,c,a){var d=0;return function(){var e=+new Date;e-d<c||(d=e,b.apply(a,arguments))}}var u,f,A,y;return function(b){if(!document.querySelectorAll){var c=document.createStyleSheet();document.querySelectorAll=function(a,b,d,h,f){f=document.all;b=[];a=a.replace(/\[for\b/gi,"[htmlFor").split(",");for(d=a.length;d--;){c.addRule(a[d],"k:v");for(h=f.length;h--;)f[h].currentStyle.k&&b.push(f[h]);c.removeRule(0)}return b}}var a=this,d=a._util={};d.elements=[];d.destroyed=!0;a.options=b||{};a.options.error=a.options.error||!1;a.options.offset=a.options.offset||100;a.options.root=a.options.root||document;a.options.success=a.options.success||!1;a.options.selector=a.options.selector||".loading";a.options.separator=a.options.separator||"|";a.options.containerClass=a.options.container;a.options.container=a.options.containerClass?document.querySelectorAll(a.options.containerClass):!1;a.options.errorClass=a.options.errorClass||"b-error";a.options.breakpoints=a.options.breakpoints||!1;a.options.loadInvisible=a.options.loadInvisible||!1;a.options.successClass=a.options.successClass||"b-loaded";a.options.validateDelay=a.options.validateDelay||25;a.options.saveViewportOffsetDelay=a.options.saveViewportOffsetDelay||50;a.options.srcset=a.options.srcset||"data-srcset";a.options.src=u=a.options.src||"data-src";y=Element.prototype.closest;A=1<window.devicePixelRatio;f={};f.top=0-a.options.offset;f.left=0-a.options.offset;a.revalidate=function(){q(a)};a.load=function(a,b){var c=this.options;void 0===a.length?z(a,b,c):l(a,function(a){z(a,b,c)})};a.destroy=function(){var a=this._util;this.options.container&&l(this.options.container,function(b){k(b,"scroll",a.validateT)});k(window,"scroll",a.validateT);k(window,"resize",a.validateT);k(window,"resize",a.saveViewportOffsetT);a.count=0;a.elements.length=0;a.destroyed=!0};d.validateT=D(function(){m(a)},a.options.validateDelay,a);d.saveViewportOffsetT=D(function(){C(a.options.offset)},a.options.saveViewportOffsetDelay,a);C(a.options.offset);l(a.options.breakpoints,function(a){if(a.width>=window.screen.width)return u=a.src,!1});setTimeout(function(){q(a)})}});
  var bLazy = new Blazy({
    success: function(element){
      setTimeout(function(){
        element.className = element.className.replace(/\bloading\b/,'');
      }, 200);
    }
  });
};

jQuery( window ).on( "load", function() {
  if (document.cookie.split(';').filter(function(item) {
    return item.indexOf('is_signed_up=') >= 0
  }).length) {
    document.getElementsByTagName('body')[0].className += ' bh_signed_up';
  }
  
  var bLazy = new Blazy({
      selector: '.loading-iframe, .loading', //ad iframes
      success: function(element){
      setTimeout(function(){
        element.className = element.className.replace(/\bloading-iframe\b/,'');
        element.className = element.className.replace(/\bloading\b/,'');
      }, 200);
    }
  });

  jQuery('.menu-item-has-children > a').click(function(e) {
      e.preventDefault();
      location.href = jQuery(this).attr('href');
  });

  jQuery('.init-tour').on('click', function() {
    Cookies.remove('is_tour_first_time_user');
    if (window.location.pathname === '/') {
      initTour();
      return false;
    } else {
      window.location.href = '/';
    }
  });

  jQuery("#et-search-icon").on('click', function (){
    jQuery('.et-top-search ').toggle();
    jQuery('.et-search-field').select();
  });

  ! function(menu, append_to, menu_id, menu_class) {
      var $cloned_nav;
      menu.clone().attr("id", menu_id).removeClass().attr("class", menu_class).appendTo(append_to);
      ($cloned_nav = append_to.find("> ul")).find(".menu_slide").remove();
      $cloned_nav.find("li:first").addClass("et_first_mobile_item");
      append_to.find("a").click(function(event) {
          event.stopPropagation()
      })
  }(jQuery("#et-navigation ul.nav"), jQuery("#et-mobile-navigation nav"), "et-extra-mobile-menu", "et_extra_mobile_menu");
  jQuery("#top-header #et-info").length && jQuery("#et-navigation #et-extra-mobile-menu").before(jQuery("#top-header #et-info").clone());
  0 < jQuery("#et-secondary-menu").length && jQuery("#et-navigation #et-extra-mobile-menu").append(jQuery("#et-secondary-menu").clone().html());
  jQuery(".show-menu").on("click", function(e) {
      e.preventDefault();
      jQuery(this).children(".show-menu-button").toggleClass("toggled");
      jQuery("#et-mobile-navigation nav").stop().animate({
          height: "toggle",
          opacity: "toggle"
      }, 300)
  });

  window.onscroll = function() {
    if(window.pageYOffset > 0) {
      if(!jQuery('.page-container').hasClass('et-fixed-header')){
        jQuery('.page-container').addClass('et-fixed-header');
      }
    } else {
      if(jQuery('.page-container').hasClass('et-fixed-header')){
        jQuery('.page-container').removeClass('et-fixed-header');
      }
    }
  };

  // Load script on exclusives template only
  if ( jQuery( '.exclusives_div').length > 0 ) {
    // Check if 'z' var exists in url
    var zcodeinurl = getParameterByName('z');
    var promo_links = [
      "http://pro1.strategicinvestment.com/",
      "http://pro1.sovereignsociety.com/",
      "http://pro.sovereignsociety.com/m/",
      "http://pro.banyanhill.com/m/"
    ];

    // if 'z' code exists in url, add it to all 'http://pro1.strategicinvestment.com/' urls on the page
    if ( zcodeinurl != null ) {
      jQuery('a').each( function() {
        var current_url = jQuery( this );

        promo_links.forEach(function(promo_link) {
          if ( current_url.attr('href') == promo_link ) {
            current_url.attr( 'href', promo_link + zcodeinurl );
          }
        });
      });

      if (jQuery('.ipt-eform-hidden-field-xcode').length > 0 && getParameterByName('z') !== null) {
        jQuery('.ipt-eform-hidden-field-xcode').val(getParameterByName('z'));
      }
      if (jQuery('.ipt-eform-hidden-field-oneclick').length > 0) {
        if (getParameterByName('oc') !== null) {
          jQuery('.ipt-eform-hidden-field-oneclick').val(getParameterByName('oc'));
        } else {
          var oneClickCodes = [];

          for (var i = 0; i < oneClickCodes.length; i++) {
            if (oneClickCodes[i] === getParameterByName('z')) {
              jQuery('.ipt-eform-hidden-field-oneclick').val('true');
              break;
            }
          }
        }
      }
    } else {
      // Add z code from backend
      if ( exclusive_post_site_excode != "" ) {
        jQuery('a').each( function() {
          var current_url = jQuery( this );

          promo_links.forEach(function(promo_link) {
            if ( current_url.attr('href') == promo_link ) {
              current_url.attr( 'href', promo_link + exclusive_post_site_excode );
            }
          });
        });
      }
    }
  }
});
lazyLoadImages();

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

/*! loadCSS. [c]2017 Filament Group, Inc. MIT License */
!function(a){"use strict";var b=function(b,c,d){function e(a){return h.body?a():void setTimeout(function(){e(a)})}function f(){i.addEventListener&&i.removeEventListener("load",f),i.media=d||"all"}var g,h=a.document,i=h.createElement("link");if(c)g=c;else{var j=(h.body||h.getElementsByTagName("head")[0]).childNodes;g=j[j.length-1]}var k=h.styleSheets;i.rel="stylesheet",i.href=b,i.media="only x",e(function(){g.parentNode.insertBefore(i,c?g:g.nextSibling)});var l=function(a){for(var b=i.href,c=k.length;c--;)if(k[c].href===b)return a();setTimeout(function(){l(a)})};return i.addEventListener&&i.addEventListener("load",f),i.onloadcssdefined=l,l(f),i};"undefined"!=typeof exports?exports.loadCSS=b:a.loadCSS=b}("undefined"!=typeof global?global:this);

/*! loadCSS rel=preload polyfill. [c]2017 Filament Group, Inc. MIT License */
!function(a){if(a.loadCSS){var b=loadCSS.relpreload={};if(b.support=function(){try{return a.document.createElement("link").relList.supports("preload")}catch(b){return!1}},b.poly=function(){for(var b=a.document.getElementsByTagName("link"),c=0;c<b.length;c++){var d=b[c];"preload"===d.rel&&"style"===d.getAttribute("as")&&(a.loadCSS(d.href,d,d.getAttribute("media")),d.rel=null)}},!b.support()){b.poly();var c=a.setInterval(b.poly,300);a.addEventListener&&a.addEventListener("load",function(){b.poly(),a.clearInterval(c)}),a.attachEvent&&a.attachEvent("onload",function(){a.clearInterval(c)})}}}(this);
