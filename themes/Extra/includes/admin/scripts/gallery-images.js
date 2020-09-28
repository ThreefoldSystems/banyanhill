/*! This minified app bundle contains open source software from several third party developers. Please review CREDITS.md in the root directory or LICENSE.md in the current directory for complete licensing, copyright and patent information. This bundle.js file and the included code may not be redistributed without the attributions listed in LICENSE.md, including associate copyright notices and licensing information. */
!function(e){var t={};function n(r){if(t[r])return t[r].exports;var a=t[r]={i:r,l:!1,exports:{}};return e[r].call(a.exports,a,a.exports,n),a.l=!0,a.exports}n.m=e,n.c=t,n.d=function(e,t,r){n.o(e,t)||Object.defineProperty(e,t,{enumerable:!0,get:r})},n.r=function(e){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},n.t=function(e,t){if(1&t&&(e=n(e)),8&t)return e;if(4&t&&"object"==typeof e&&e&&e.__esModule)return e;var r=Object.create(null);if(n.r(r),Object.defineProperty(r,"default",{enumerable:!0,value:e}),2&t&&"string"!=typeof e)for(var a in e)n.d(r,a,function(t){return e[t]}.bind(null,a));return r},n.n=function(e){var t=e&&e.__esModule?function(){return e.default}:function(){return e};return n.d(t,"a",t),t},n.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},n.p="",n(n.s=1)}([,function(e,t){var n;(n=jQuery)(document).ready((function(){var e,t=n("#et_gallery_images"),r=n("#et_gallery_images_ids");function a(){var e=[];t.find("li.gallery_image").each((function(){e.push(n(this).data("id"))})),e=e.join(","),r.val(e)}function i(){t.sortable({items:".gallery_image",cursor:"move",forcePlaceholderSize:!0,update:function(){a()}})}i(),t.on("click","span.delete",(function(e){var t=n(this).closest("li.gallery_image");e.preventDefault(),t.slideUp("fast",(function(){t.remove(),a()}))})),n("#et_gallery_add_images").click((function(r){var l=n(this);r.preventDefault(),void 0===e?((e=wp.media.frames.gallery_images=wp.media({title:l.data("title"),button:{text:l.data("title")},states:[new wp.media.controller.Library({title:l.data("title"),multiple:!0})]})).open(),e.on("select",(function(){e.state().get("selection").each((function(e){var n,r;e.has("id")&&(r=(n=e.get("sizes")).hasOwnProperty("thumbnail")?n.thumbnail.url:n.full.url,t.append('<li class="gallery_image" data-id="'+e.get("id")+'">\t\t\t\t\t\t\t\t<span class="delete">-</span>\t\t\t\t\t\t\t\t<img src="'+r+'" />\t\t\t\t\t\t\t</li>'))})),a()})),i()):e.open()}))}))}]);