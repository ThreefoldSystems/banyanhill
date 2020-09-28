jQuery(document).ready(function() {
    if ( jQuery('#tfs_css_body').length ) {
        jQuery(document).on('click', '.tfs_css_alt_theme_toggle', function(event) {
            jQuery('.tfs_csd_container__ul--profile-sub').toggle(500);

            if(jQuery('.profile_toggle').hasClass('fa-chevron-down')){
                jQuery('.profile_toggle').removeClass('fa-chevron-down');
                jQuery('.profile_toggle').addClass('fa-chevron-up');
            } else {
                jQuery('.profile_toggle').removeClass('fa-chevron-up');
                jQuery('.profile_toggle').addClass('fa-chevron-down');
            }
        });

        jQuery(document).on('click', '.css_open_url', function(event) {
            event.stopPropagation();
            event.preventDefault();
            if(jQuery(this).hasClass('disabled')){
                return;
            }

            var css_link = jQuery(this);
            var css_url = css_link.data('url');
            if( jQuery('#tfs_css_alt_theme').length ){
                jQuery('.tfs_css_content').fadeOut(500);
                if( css_link.hasClass('active-tab') ) {
                    css_link.removeClass('active-tab');

                    var tabClass = getTabClass(css_url);
                    tabClass = tabClass + ' .sub';
                    toggleChevronsClose(tabClass);
                    return;
                }
            }

            var css_title = jQuery("[data-url='" + css_url + "']").data('title');
            jQuery("#tfs_css_header").html(css_title);

            history.pushState(null, null, "#" + css_url);
            css_check_url();
        });

        //load content for in every reload
        css_check_url();

        // Hide menu on resolutions lower than 780
        jQuery(window).bind('resize', function () {
            if (jQuery(window).innerWidth() < 780) {
                jQuery("#tfs_css_tabs").slideUp('slow');
            } else {
                jQuery("#tfs_css_tabs").slideDown('slow');
            }
        });

        jQuery(document).on('click', '#css_my_account', function(event) {
            if (jQuery(window).innerWidth() < 780) {
                event.stopPropagation();
            }
        });

        jQuery(document).on('click', '#tfs_css_tabs', function(event) {
            if (jQuery(window).innerWidth() < 780) {
                jQuery("#tfs_css_tabs").slideUp();
            }
        });

        jQuery(document).on('click', '#tfs_css_header', function(event) {
            if (jQuery(window).innerWidth() < 780) {
                jQuery("#tfs_css_tabs").slideToggle();
            }
        });
		
		jQuery(document).on('click', '.subs_content_more_info', function() {
			jQuery(this).toggleClass('active');
		});
    }

    jQuery.validator.addMethod(
        "phoneRegEx",
        function(value, element, regexpr) {
            return regexpr.test(value);
        },
        tfs_css_localized_frontend_data.txt_css_phonenumber
    );
});

// jQuery functions
function css_check_url() {
    jQuery('.css_open_url').addClass("disabled");
    // Get the url, read only the anchor and remove any possible query string
    var css_url_section = window.location.href;
    var css_actual_segment = css_url_section.substr(css_url_section.lastIndexOf('#') + 1);
    var css_actual_segment = css_actual_segment.split("?")[0];

    if (css_url_section.lastIndexOf('#') == -1) {
        css_actual_segment = false;
    }

    // Add class 'selected' to the actual content segment
    jQuery("li").removeClass('selected');

    if (jQuery('#tfs_css_alt_theme').length) {
        jQuery("div .sub").removeClass('fa-chevron-up');
        jQuery("div .sub").addClass('fa-chevron-down');

        var tabClass = getTabClass(css_actual_segment);
        if (tabClass != null) {
            jQuery('.active-tab').removeClass('active-tab');
            jQuery(tabClass).addClass('active-tab');
            tabClass = tabClass + ' .sub';
            toggleChevronsOpen(tabClass);
        }

    } else {
        if (!css_actual_segment) {
            css_actual_segment = 'css-account-landing';
        }

        jQuery("li .sub").removeClass('fa-chevron-right');

        jQuery(".tfs_csd_container__ul--tabs__li.account").addClass('selected');

        if (jQuery.inArray(css_actual_segment, ['css-change-address']) !== -1) {
            jQuery(".address-tab.tfs_csd_container__submenu__ul__li i.sub").addClass('fa-chevron-right');
        } else if (jQuery.inArray(css_actual_segment, ['css-change-email']) !== -1) {
            jQuery(".email-tab.tfs_csd_container__submenu__ul__li i").addClass('fa-chevron-right');
        } else if (jQuery.inArray(css_actual_segment, ['css-change-username']) !== -1) {
            jQuery(".username-tab.tfs_csd_container__submenu__ul__li i").addClass('fa-chevron-right');
        } else if (jQuery.inArray(css_actual_segment, ['css-change-password']) !== -1) {
            jQuery(".password-tab.tfs_csd_container__submenu__ul__li i").addClass('fa-chevron-right');
        } else if (jQuery.inArray(css_actual_segment, ['css-payment']) !== -1) {
            jQuery(".payment-tab.tfs_csd_container__submenu__ul__li i").addClass('fa-chevron-right');
        }

        if (jQuery.inArray(css_actual_segment, ['css-subscriptions', 'css-listings', 'css-contact-support']) !== -1) {
            jQuery("li.submenu").slideUp();
            jQuery(".tfs_csd_container__ul--tabs__li.account").removeClass('selected');
            jQuery(".tfs_csd_container__ul--tabs__li.account i").removeClass('fa-chevron-down');
            jQuery(".tfs_csd_container__ul--tabs__li.account i").addClass('fa-chevron-right');
        } else {
            jQuery("li.submenu").slideDown('slow');
            jQuery(".tfs_csd_container__ul--tabs__li.account").addClass('selected');
            jQuery(".tfs_csd_container__ul--tabs__li.account i").removeClass('fa-chevron-right');
            jQuery(".tfs_csd_container__ul--tabs__li.account i").addClass('fa-chevron-down');
        }

        jQuery('*[data-url="' + css_actual_segment + '"]').addClass('selected');
    }



    if (css_actual_segment) {
        if (css_actual_segment == 'css-payment'){
            css_actual_segment = 'css-subscriptions';
        }
        css_open_url(css_actual_segment);
    } else {
        jQuery('.css_open_url').removeClass("disabled");
    }
}

function getTabClass(css_actual_segment){
    switch (css_actual_segment) {
        case 'css-change-address':
            return '.address-tab';
        case 'css-change-email':
            return '.email-tab';
        case 'css-change-username':
            return '.username-tab';
        case 'css-change-password':
            return '.password-tab';
        case 'css-subscriptions':
        case 'css-payment':
            return '.subscriptions-tab';
        case 'css-listings':
            return '.listings-tab';
        case 'css-contact-support':
            return '.support-tab';
        default:
            return false;
    }
}

function toggleChevronsOpen(element) {
    jQuery(element).removeClass('fa-chevron-down');
    jQuery(element).addClass('fa-chevron-up');
}

function toggleChevronsClose(element) {
    jQuery(element).removeClass('fa-chevron-up');
    jQuery(element).addClass('fa-chevron-down');
}

function css_open_url(file) {
    //ajax request

    jQuery.ajax({
        url: tfs_css_localized_frontend_data.css_ajax_url,
        type: "POST",
        data: {
            'action': 'css_open_url',
            'security': tfs_css_localized_frontend_data.security_css_open_url,
            'template': file
        },

        beforeSend: function (data) {
            loadSpinner(file);
            jQuery('.tfs_css_content_area').remove();
        },

        success: function (data) {  //result
            // Hack to avoid expired nonces
            if (data === "-1" || data === "0") {
                location.reload();
            } else {
                loadAjaxData(data, file);
            }
        },

        error: function (errorThrown) {
            //console.log("ERROR: " + errorThrown);
            console.log(errorThrown);
            jQuery('.css_open_url').removeClass("disabled");
        }
    });
}

function loadSpinner(template) {
    if(jQuery("#tfs_css_alt_theme").length) {
        jQuery(".tfs_css_content").fadeOut(500, function () {
            jQuery("." + template).html(tfs_css_localized_frontend_data.txt_css_loading);
            jQuery("." + template).fadeIn(500);
        });
    } else {
        jQuery("#tfs_css_content").fadeOut(500, function () {
            jQuery("#tfs_css_content").html(tfs_css_localized_frontend_data.txt_css_loading);
            jQuery("#tfs_css_content").fadeIn(500);
        });
    }
}

function loadAjaxData(data, template){
    if(jQuery("#tfs_css_alt_theme").length) {
        jQuery("." + template).fadeOut(500, function () {
            jQuery("." + template).html(data);
            jQuery("." + template).fadeIn(500);
        });
    } else {
        jQuery("#tfs_css_content").fadeOut(500, function () {
            jQuery("#tfs_css_content").html(data);
            jQuery("#tfs_css_content").fadeIn(500);
		
			if (jQuery('.tfs_css_change_email_address_container').length !== 0) {
				jQuery.featherlight(jQuery('.tfs_css_change_email_address_container'), {});		
			}			
        });
    }
    setTimeout(function(){jQuery('.css_open_url').removeClass("disabled")}, 1250);
}

function modal_title_bar(message) {
    title = jQuery("#tfs_css_header").html();
    return /*"<div class='tfs_css_header_modal'>" + title + "</div>" +*/ message;
}

function updating(message) {
    return modal_title_bar(message);
}

function error_msg_modal(message) {
    return modal_title_bar("<div class='tfs_css_error_msg_modal'></div>" +
        "<div class='tfs_css_error_msg_modal_content'><h2>Error</h2><p>" + message +
        "</p><button class='featherlight-close corner-fl-button'>Close</button></div>");
}

function success_msg_modal(message) {
    return modal_title_bar("<div class='tfs_css_success_msg_modal'></div>" +
        "<div class='tfs_css_success_msg_modal_content'><h2>Success</h2><p>" + message +
        "</p></div><div class='modal-response-btn-container'><button class='featherlight-close corner-fl-button'>Close</button></div>");
}

function error_msg(message) {
    return "<div class='tfs_css_error_msg'></div>" + message + "<br><div class='modal-response-btn-container'><button class='featherlight-close corner-fl-button'>Close</button></div>";
}

function success_msg(message) {
    return "<div class='tfs_css_success_msg'>" + message + "</div>";
}


// BEGIN #css-change-address ======================================================================
jQuery(document).ready(function () {
    display_county_state();
    var changed = false;

    jQuery(document).on('change', '#tfs_css_countryCode', function(event) {
        css_getState();
        display_county_state();
    });

    jQuery(document).on('click', '.tfs_css_change_address_submit', function(event) {
        event.preventDefault();

        if ( changed === true ) {
			jQuery('#changeAddress-error').remove();
            if ( jQuery("#tfs_css_change_address_form").valid() ) {
                css_address_send_form();
            } else {
                jQuery('html, body').animate({
                    scrollTop: jQuery(".address-tab").offset().top
                }, 1000);
            }
        } else {
            var tooltip = '<label id="changeAddress-error" class="error" for="submitButton">' + tfs_css_localized_frontend_data.txt_css_no_change + '</label>';
            jQuery(tooltip).insertAfter('#tfs_css_change_address_form');
        }


    });

    // Enable submit button if something has been typed in the form
    jQuery(document).on('change keyup', '#tfs_css_change_address_form', function(event) {
        changed = true;
		jQuery('#changeAddress-error').remove();
    });

    // Open password prompt if form is validated
    jQuery(document).on('click', '.tfs_css_change_address_submit_prompt', function(event) {
        event.preventDefault();

        if ( changed === true ) {
			jQuery('#changeAddress-error').remove();
            if ( jQuery("#tfs_css_change_address_form").valid() ) {
                jQuery.featherlight(jQuery('#tfs_css_prompt_password_enter'), {});
            } else {
                jQuery('html, body').animate({
                    scrollTop: jQuery(".address-tab").offset().top
                }, 1000);
            }
        } else {
            var tooltip = '<label id="changeAddress-error" class="error" for="submitButton">' + tfs_css_localized_frontend_data.txt_css_no_change + '</label>';
            jQuery(tooltip).insertAfter('#tfs_css_change_address_form');
        }
    });
});

jQuery(document).change(function () {
    jQuery("#tfs_css_change_address_form").validate({
        rules: {
            firstName: "required",
            lastName: "required",
            phoneNumber: {
                phoneRegEx: /^(?=.*[0-9])[- +()0-9]+$|^$/
            }
        },
        messages: {
            firstName: tfs_css_localized_frontend_data.txt_css_enter_firstname,
            lastName: tfs_css_localized_frontend_data.txt_css_enter_lastname,
            phoneNumber: tfs_css_localized_frontend_data.txt_css_phonenumber
        }
    });
});

function css_address_send_form() {
    var parameters = "";
    var state = '';
    var countryCode = '';

	var formatDate = function( date ) {
		var d = new Date( date ),
			month = '' + ( d.getMonth() + 1 ),
			day = '' + d.getDate(),
			year = d.getFullYear();

		if ( month.length < 2 ) month = '0' + month;
		if ( day.length < 2 ) day = '0' + day;

		return [ year, month, day ].join( '-' );
	};
	

    jQuery('#tfs_css_change_address_form input[type]').each(function () {
        var css_name = jQuery(this).attr("name");
        if (css_name == 'state') {
            state = true;
        }
        if (css_name == 'countryCode') {
            countryCode = true;
        }
		
        var css_value = jQuery(this).val();
		
		if (css_name == 'birthDate') {
			css_value = formatDate(jQuery(this).val());
		}		
		
        parameters = parameters + css_name + "=" + encodeURIComponent(css_value) + "&";
    });

    if( countryCode === '' && jQuery('#tfs_css_countryCode').length ) {
        countryCode = jQuery('#tfs_css_countryCode').find(":selected").attr('value');
        parameters = parameters + 'countryCode=' + countryCode + '&';
    }

    if ( state === '' && jQuery('.tfs_css_state_dropdown').length ) {
        state = 'state=' + jQuery('#tfs_css_state option:selected').val();
        parameters = parameters + state;
    }	
	
    jQuery.ajax({ //ajax request

        url: tfs_css_localized_frontend_data.css_ajax_url,
        type: "POST",
        data: {
            'action': 'css_change_address',
            'security': tfs_css_localized_frontend_data.security_css_change_address,
            'css_data': parameters
        },

        beforeSend: function (data) {
            jQuery(".featherlight-close").click();
            jQuery.featherlight('text');
            jQuery(".featherlight-inner").html(updating(tfs_css_localized_frontend_data.txt_css_loading));
        },

        success: function (data) {  //result
            if (data === 'true') {
                //jQuery(".featherlight-close").click();
                if(jQuery('#tfs_css_alt_theme').length){
                    jQuery(".css-change-address").html(success_msg(tfs_css_localized_frontend_data.txt_success_css_change_address));
                } else {
                    jQuery(".featherlight-inner").html(success_msg_modal(tfs_css_localized_frontend_data.txt_success_css_change_address));
                }
            } else {
                jQuery(".featherlight-inner").html(error_msg_modal(data));
            }
        },

        error: function (errorThrown) {
            console.log("ERROR: " + errorThrown);
        }
    });
}

function display_county_state() {
    var display_county = jQuery('#tfs_css_countryCode').find(":selected").attr('county');

    if(display_county==0){
        jQuery("#tfs_css_county_display").slideDown();
        jQuery("#tfs_css_state_display").slideDown();
    }
    if(display_county==1){
        jQuery("#tfs_css_county_display").slideUp();
        jQuery("#tfs_css_state_display").slideDown();
    }
    if(display_county==2){
        jQuery("#tfs_css_county_display").slideDown();
        jQuery("#tfs_css_state_display").slideUp();
    }
}

function css_getState() {

    var countryCode = jQuery("#tfs_css_countryCode").attr('value');

    jQuery.ajax({ //ajax request

        url: tfs_css_localized_frontend_data.css_ajax_url,
        type: "POST",
        data: {
            'countryCode': countryCode,
            'action': 'css_get_state',
            'security': tfs_css_localized_frontend_data.security_css_get_state
        },

        beforeSend: function (data) {
            jQuery("#tfs_css_state").html("Loading...");
        },

        success: function (data) {  //result
            jQuery("#tfs_css_state").html(data);
        },

        error: function (errorThrown) {
            console.log("ERROR: " + errorThrown);
        }
    });
}
// END #css-change-address ======================================================================

// BEGIN #css-change-email ======================================================================
jQuery(document).ready(function () {
    // Process submission of the form
    jQuery(document).on('click', '.tfs_css_change_email_submit', function(event) {
        event.preventDefault();

        if ( jQuery(this).parent("#tfs_css_change_email_address_form").valid() ) {
            css_email_send_form();
        }
    });

    // Passing url via form to avoid ajax problems
    //jQuery(".actual_url_change_email").val('http://' + window.location.hostname + window.location.pathname + '#css-change-email');

    // Enable submit button if something has been typed in the form
//    jQuery(document).on('change keyup', '#tfs_css_change_email_address_form', function(event) {
//        jQuery(".tfs_css_change_email_submit").removeAttr("disabled");
//        jQuery(".tfs_css_change_email_submit_prompt").removeAttr("disabled");
//    });

    // Open password prompt if form is validated
    jQuery(document).on('click', '.tfs_css_change_email_submit_prompt', function(event) {
        event.preventDefault();

        if ( jQuery(this).parent("#tfs_css_change_email_address_form").valid() ) {
            jQuery.featherlight(jQuery('#tfs_css_prompt_password_enter'), {});
        }
    });
});


jQuery(document).change(function () {
    jQuery(".featherlight #tfs_css_change_email_address_form").validate({
        rules: {
            new_email: {
                required: true,
                email: true
            },
            new_email_repeat: {
                equalTo : '.featherlight [name="new_email"]'
            }
        },
        messages: {
            new_email: {
                required: tfs_css_localized_frontend_data.txt_css_email_error_insert_email
            },
            new_email_repeat: {
                equalTo: tfs_css_localized_frontend_data.txt_css_email_error_match_emails
            }
        }
    });
});

function css_email_send_form() {
    var parameters = "";

    jQuery('.featherlight input[type="email"]').each(function () {
        var css_name = jQuery(this).attr("name");
        var css_value = jQuery(this).val();
        parameters = parameters + css_name + "=" + encodeURIComponent(css_value) + "&";
    });

    parameters = parameters + 'actual_url' + "=" + jQuery(location).attr('href');

    jQuery.ajax({ //ajax request
        url: tfs_css_localized_frontend_data.css_ajax_url,
        type: "POST",
        data: {
            'action': 'css_request_email_change',
            'security': tfs_css_localized_frontend_data.security_css_change_email,
            'css_data': parameters
        },
        beforeSend: function (data) {
            jQuery.featherlight('text');
            jQuery(".featherlight-inner").html(updating(tfs_css_localized_frontend_data.txt_css_loading));
        },
        success: function (data) { //result
			jQuery(".featherlight-close").first().click();
            if (data === 'true') {
				jQuery('.subs_change_email').siblings('input').val('PENDING (15 minutes remaining)');
				jQuery('.subs_change_email').remove();
				
                if(jQuery('#tfs_css_alt_theme').length){
                    jQuery(".tfs_css_change_email_address_container").html(success_msg(tfs_css_localized_frontend_data.txt_css_email_sent_success));
                } else {
                    jQuery(".featherlight-inner").html(success_msg_modal(tfs_css_localized_frontend_data.txt_css_email_sent_success));
                }
            } else {
                jQuery(".featherlight-inner").html(data);
            }
        },

        error: function (errorThrown) {
            console.log("ERROR: " + errorThrown);
        }
    });
}
// END #css-change-email ======================================================================


// BEGIN #css-change-password ======================================================================
jQuery(document).ready(function () {
    jQuery(document).on('click', '.css_password_send_form', function(event) {
        event.preventDefault();

        if(jQuery(this).parent("#tfs_css_change_password_form").valid()){
            css_password_send_form();
        }
    });

    // Enable submit button if something has been typed in the form
//    jQuery(document).on('change keyup', '#tfs_css_change_password_form', function(event) {
//        jQuery(".css_password_send_form").removeAttr("disabled");
//    });
});

jQuery(document).change(function () {
    jQuery(".featherlight #tfs_css_change_password_form").validate({
        rules: {
            newPassword: {
                minlength : tfs_css_localized_frontend_data.min_pwd_length,
                required: true
            },
            newPassword_repeat: {
                equalTo : '.featherlight [name="newPassword"]'
            },
            existingPassword: {
                required: true
            }

        },
        messages: {
            existingPassword: tfs_css_localized_frontend_data.txt_css_pwd_existing_pwd_placeholder,
            newPassword: {
                required: tfs_css_localized_frontend_data.txt_css_pwd_new_pwd_placeholder,
                minlength: jQuery.validator.format("{0} characters minimum")
            },
            newPassword_repeat: {
                equalTo: tfs_css_localized_frontend_data.txt_css_pwd_match
            }
        },
		ignore: []
    });
});


function css_password_send_form() {
    var parameters = "";

    jQuery('.featherlight input[type=password]').each(function () {
        var css_name = jQuery(this).attr("name");
        var css_value = jQuery(this).val();
        parameters = parameters + css_name + "=" + css_value + "&";
    });
    jQuery.ajax({ //ajax request
        url: tfs_css_localized_frontend_data.css_ajax_url,
        type: "POST",
        data: {
            'action': 'css_change_password',
            'security': tfs_css_localized_frontend_data.security_css_change_password,
            'css_data': parameters
        },

        beforeSend: function (data) {
            jQuery.featherlight('text');
            jQuery(".featherlight-inner").last().html(updating(tfs_css_localized_frontend_data.txt_css_loading));
        },

        success: function (data) {
            //results
            if (data === 'true') {
                jQuery(".featherlight-close").click();
                if(jQuery('#tfs_css_alt_theme').length){
                    jQuery("#tfs_css_account").html(success_msg(tfs_css_localized_frontend_data.txt_success_css_change_pwd));
                } else {
                    jQuery("#tfs_css_content").html(success_msg(tfs_css_localized_frontend_data.txt_success_css_change_pwd));
                    jQuery('#tfs_css_menu').hide();
                }
            } else if (data == 'wrongPass') {
                jQuery(".featherlight-close").last().click();
                var tooltip = '<label id="existingPassword-error" class="error" for="existingPassword">' + tfs_css_localized_frontend_data.txt_css_ensure_password_correct + '</label>';
                jQuery(tooltip).insertAfter('input[name="existingPassword"]');
                jQuery('input[name="existingPassword"]').addClass('error');
            } else if (data == 'mismatch') {
                newPasswordErrorDisplay( tfs_css_localized_frontend_data.txt_css_pwd_match );
            } else if ( data.indexOf('short') === 0 ){
                newPasswordErrorDisplay( data.substring(5) );
            }
            else {
                jQuery(".featherlight-inner").html(error_msg_modal(data));
            }
        },

        error: function (errorThrown) {
            console.log("ERROR: " + errorThrown);
        }
    });
}

function newPasswordErrorDisplay( message ) {
    jQuery(".featherlight-close").click();
    var tooltip = '<label id="newPassword-error" class="error" for="newPassword">' + message + '</label>';
    jQuery(tooltip).insertAfter('input[name="newPassword"]');
    jQuery('input[name="newPassword"]').addClass('error');
    var tooltipRepeat = '<label id="newPassword-repeat-error" class="error" for="newPassword_repeat">' + message + '</label>';
    jQuery(tooltipRepeat).insertAfter('input[name="newPassword_repeat"]');
    jQuery('input[name="newPassword_repeat"]').addClass('error');
}

// BEGIN #css-change-password ======================================================================


// BEGIN #css-change-username ======================================================================
jQuery(document).ready(function () {
    // edit username view
    jQuery(document).on('click', '.tfs_css_change_username_submit', function(event) {
        event.preventDefault();
        if(jQuery(this).parent("#tfs_css_change_username_form").valid()){
            css_username_send_form();
        }
    });

    // Enable submit button if something has been typed in the form
//    jQuery(document).on('change keyup', '#tfs_css_change_username_form', function(event) {
//        jQuery(".tfs_css_change_username_submit").removeAttr("disabled");
//        jQuery(".tfs_css_change_username_submit_prompt").removeAttr("disabled");
//    });


    // Open password prompt if form is validated
    jQuery(document).on('click', '.tfs_css_change_username_submit_prompt', function(event) {
        event.preventDefault();

        if ( jQuery(this).parent("#tfs_css_change_username_form").valid() ) {
            jQuery.featherlight(jQuery('#tfs_css_prompt_password_enter'), {});
        }
    });
});

jQuery(document).change(function () {
    jQuery(".featherlight #tfs_css_change_username_form").validate({
        rules: {
            new_username: {
                required: true,
                email: true
            }
        },
        messages: {
            new_username: {
                required: tfs_css_localized_frontend_data.txt_css_username_insert_new,
                email: tfs_css_localized_frontend_data.txt_css_username_email_address

            }
        },
		ignore: []
    });
});

function css_username_send_form() {
    var parameters = "";

    jQuery('.featherlight input[type="text"]').each(function () {
        var css_name = jQuery(this).attr("name");
        var css_value = jQuery(this).val();
        parameters = parameters + css_name + "=" + encodeURIComponent(css_value) + "&";
    });

    jQuery.ajax({ //ajax request

        url: tfs_css_localized_frontend_data.css_ajax_url,
        type: "POST",
        data: {
            'action': 'css_change_username',
            'security': tfs_css_localized_frontend_data.security_css_change_username,
            'css_data': parameters
        },

        beforeSend: function (data) {
            jQuery(".featherlight-close").click();
            jQuery.featherlight('text');
            jQuery(".featherlight-inner").html(updating(tfs_css_localized_frontend_data.txt_css_loading));
        },

        success: function (data) {  //
            switch(data) {
                case '1':
                    jQuery(".featherlight-close").click();

                    if(jQuery('#tfs_css_alt_theme').length){
                        jQuery(".css-change-username").html(success_msg(tfs_css_localized_frontend_data.txt_css_username_success));
                    } else {
                        jQuery("#tfs_css_content").html(success_msg(tfs_css_localized_frontend_data.txt_css_username_success));
                    }
                    break;
                default:
                    jQuery(".featherlight-inner").html(error_msg_modal(data.replace(/"/g, '')));
            }
        },

        error: function (errorThrown) {
            console.log("ERROR: " + errorThrown);
        }
    });
}
// END #css-change-username ======================================================================



// BEGIN #css-display-address ======================================================================
jQuery(document).ready(function () {
    jQuery(document).on('click', '.css_open_url_int', function(event) {
        event.preventDefault();
        var css_link = jQuery(this);
        var css_url = css_link.data('url');
        var css_title = jQuery("[data-url='" + css_url + "']").data('title');
        jQuery("#tfs_css_header").html(css_title);

        history.pushState(null, null, "#" + css_url);

        css_check_url();
    });
});
// END #css-display-address ======================================================================



// BEGIN #css-email-change-other-updates ======================================================================
function css_request_change_updates(old_email) {
    var parameters = "";

    jQuery('input:checked').each(function () {
        var css_name = jQuery(this).attr("name");
        parameters = parameters + ',' + css_name;
    });

    jQuery.ajax({ //ajax request

        url: tfs_css_localized_frontend_data.css_ajax_url,
        type: "POST",
        data: {
            'action': 'css_request_change_updates',
            'security': tfs_css_localized_frontend_data.security_css_request_change_updates,
            'css_data': parameters,
            'old_email': old_email
        },

        beforeSend: function (data) {
            jQuery(".tfs_css_change_email_address_container").html(tfs_css_localized_frontend_data.txt_css_loading);
        },

        success: function (data) {  //result
            if (data === 'true') {
                if(jQuery('#tfs_css_alt_theme').length){
                    jQuery(".tfs_css_change_email_address_container").html(success_msg(tfs_css_localized_frontend_data.txt_css_email_success));
                } else {
                    jQuery(".featherlight-inner").html(success_msg_modal(tfs_css_localized_frontend_data.txt_css_email_success));
                }
            } else {
                jQuery(".tfs_css_change_email_address_container").html(data);
            }
        },

        error: function (errorThrown) {
            console.log("ERROR: " + errorThrown);
        }
    });
}
// END #css-email-change-other-updates ======================================================================


// BEGIN #css-change-social ======================================================================
jQuery(document).ready(function () {
	jQuery(document).on('change', '#socialMedia', function() {
		jQuery('#socialNetwork').attr('name', jQuery(this).children("option:selected").val());
	});
	
	jQuery(document).on('click', '.add_social', function(event) {		
		event.preventDefault();
		
		if (jQuery('#socialNetwork').val() === '') return;		
		
		jQuery.ajax({ //ajax request

			url: tfs_css_localized_frontend_data.css_ajax_url,
			type: "POST",
			data: {
				'action': 'add_social_profile',
				'security': tfs_css_localized_frontend_data.security_css_change_social,
				'css_data': 'network=' + jQuery('#socialNetwork').attr('name') + "&url=" + jQuery('#socialNetwork').val()
			},

			beforeSend: function (data) {
				jQuery.featherlight('text');
				jQuery(".featherlight-inner").html(updating(tfs_css_localized_frontend_data.txt_css_loading));
			},

			success: function (data) {  //result
				data = jQuery.parseJSON(data);
				if (data.status === 'success') {
					jQuery('.tfs_css_social_container .tfs_css_input_section').append(data.content);
					jQuery('#socialNetwork').val('');
					jQuery(".featherlight-close").click();
				} else {
					jQuery(".featherlight-inner").html(error_msg_modal(data.content));
				}
			},

			error: function (errorThrown) {
				console.log("ERROR: " + errorThrown);
			}
		});
	});
	
	jQuery(document).on('click', '.delete_social', function(event) {		
		event.preventDefault();		
		
		jQuery.ajax({ //ajax request

			url: tfs_css_localized_frontend_data.css_ajax_url,
			type: "POST",
			data: {
				'action': 'remove_social_profile',
				'security': tfs_css_localized_frontend_data.security_css_remove_social,
				'css_data': 'network=' + jQuery(this).data('network')
			},

			beforeSend: function (data) {
				jQuery.featherlight('text');
				jQuery(".featherlight-inner").html(updating(tfs_css_localized_frontend_data.txt_css_loading));
			},

			success: function (data) {  //result
				data = jQuery.parseJSON(data);
				if (data.status === 'success') {
					jQuery('.social_display.' + data.network).next('.subs_delete_social').remove()
					jQuery('.social_display.' + data.network).remove();
					jQuery(".featherlight-close").click();
				} else {
					jQuery(".featherlight-inner").html(error_msg_modal(data.content));
				}
			},

			error: function (errorThrown) {
				console.log("ERROR: " + errorThrown);
			}
		});
	});	
});
// END #css-change-address ======================================================================


// BEGIN #css-listings ======================================================================
jQuery(document).ready(function () {
    jQuery(document).on('click', '.listing', function(event) {
        jQuery("div.tfs_css_updating_modal").html(jQuery("#tfs_css_header").html());
        jQuery("input[name=lists_listCode]").val(jQuery(this).attr('data-list-listcode'));
        jQuery("input[name=lists_oldMail]").val(jQuery(this).attr('data-list-oldmail'));
    });
});

function css_add_remove_customer_list(enlace) {
	event.preventDefault();
	
    var listCode = jQuery(enlace).data('list-code');
    var listAction = jQuery(enlace).data('list-action');
    var listEmail = jQuery(enlace).data('list-email');
    var listXcode = jQuery(enlace).data('list-xcode');
	var listSubname = jQuery(enlace).data('list-subname');
	
    jQuery.ajax({ //ajax request
        url: tfs_css_localized_frontend_data.css_ajax_url,
        type: "POST",
        data: {
            'list_action': listAction,
            'list_code': listCode,
            'list_email': listEmail,
            'list_xcode': listXcode,
			'list_subname': listSubname,
            'action': 'css_add_remove_customer_list',
            'security': tfs_css_localized_frontend_data.security_css_add_remove_customer_list
        },

        beforeSend: function (data) {
			jQuery.featherlight.close();
            jQuery.featherlight('text');
            jQuery(".featherlight-inner").html(updating(tfs_css_localized_frontend_data.txt_css_loading));
			
			var postData = JSON.parse('{"' + this.data.replace(/&/g, '","').replace(/=/g,'":"') + '"}', function(key, value) { return key===""?value:decodeURIComponent(value) });
			
			if ( postData['list_code'].indexOf('_SMS') !== -1 && postData['list_email'] === '' ) {
				jQuery(".featherlight-inner").html(error_msg_modal('Please enter your Phone Number on the My Account Tab to subscribe.'));

				return false;
			}			
        },

        success: function (data) {  //result
            if (data === 'true') {
                if (listAction === 'add') {
                    jQuery(".featherlight-inner").html(success_msg_modal(tfs_css_localized_frontend_data.txt_css_list_subscribed));
                } else {
                    jQuery(".featherlight-inner").html(success_msg_modal(tfs_css_localized_frontend_data.txt_css_list_unsubscribed));
                }
				// JW Fix grab correct template
                css_open_url('css-subscriptions');
            } else {
                jQuery(".featherlight-inner").html(error_msg_modal(data));
            }
        },

        error: function (errorThrown) {
            console.log("ERROR: " + errorThrown);
        }
    });
}

function css_change_listings_email() {
    listcode = jQuery("input[name=lists_listCode]").last().val();
    newMail = jQuery("input[name=lists_newEmail]").last().val();
    oldMail = jQuery("input[name=lists_oldMail]").last().val();

    jQuery.ajax({ //ajax request
        url: tfs_css_localized_frontend_data.css_ajax_url,
        type: "POST",
        data: {
            'action': 'css_change_single_listing_email',
            'security': tfs_css_localized_frontend_data.security_css_change_listings_email,
            'new_mail': newMail,
            'old_mail': oldMail,
            'listcode': listcode
        },

        beforeSend: function (data) {
            jQuery(".featherlight-inner").html(updating(tfs_css_localized_frontend_data.txt_css_loading));
        },

        success: function (data) {  //result
            if (data === 'true') {
                jQuery("#email" + listcode).html(newMail);
                jQuery('*[data-list-listcode="'+listcode+'"]').attr('data-list-newmail', newMail);
                jQuery('*[data-list-listcode="'+listcode+'"]').attr('data-list-oldmail', newMail);
                jQuery('input[data-list-code="'+listcode+'"]').attr('data-list-email', newMail);
                jQuery(".featherlight-inner").html(success_msg_modal(tfs_css_localized_frontend_data.txt_css_attached_email_success));
            } else {
                jQuery(".featherlight-inner").html(error_msg_modal(data));
            }
        },

        error: function (errorThrown) {
            console.log("ERROR: " + errorThrown);
        }
    });
}
// END #css-listings ======================================================================



// BEGIN #css-subscriptions ======================================================================
jQuery(document).ready(function () {
    jQuery(document).on('click', '.subscription', function(event) {
        jQuery("div.tfs_css_updating_modal").html(jQuery("#tfs_css_header").html());
        jQuery("input[name=submit_subref]").val(jQuery(this).data('subref'));
    });
});


function css_change_subscription_email() {
    subRef = jQuery("input[name=submit_subref]").last().val();
    newMail = jQuery("input[name=subs_chg_email_addr]").last().val();

    jQuery.ajax({ //ajax request

        url: tfs_css_localized_frontend_data.css_ajax_url,
        type: "POST",
        data: {
            'action': 'css_change_subs_email',
            'security': tfs_css_localized_frontend_data.security_css_change_subs_email,
            'new_mail': newMail,
            'subref': subRef
        },

        beforeSend: function (data) {
            jQuery(".featherlight-inner").html(updating(tfs_css_localized_frontend_data.txt_css_loading));
        },


        success: function (data) {  //result
            if (data === 'true') {
                jQuery("#email" + subRef).html(newMail);
                jQuery(".featherlight-inner").html(success_msg_modal(tfs_css_localized_frontend_data.txt_css_attached_email_success));
            } else {
                jQuery(".featherlight-inner").html(error_msg_modal(data));
            }
        },

        error: function (errorThrown) {
            console.log("ERROR: " + errorThrown);
        }
    });
}

function css_auto_renew_prompt(data_item) {
    if( jQuery(data_item).hasClass('auto_renew_arrow_on') ) {
        jQuery.featherlight(jQuery('#tfs_css_prompt_auto_renew'), {});
        jQuery(data_item).addClass('auto_renew_arrow_off');
        jQuery(data_item).removeClass('auto_renew_arrow_on');
        jQuery(data_item).text('Off');
        jQuery('#css_auto_renew_item').data('sub', data_item);
    }
}

function css_auto_renew() {
    var data_item = jQuery('#css_auto_renew_item').data("sub");
    var subRef = jQuery(data_item).attr('data-sub');
    console.log(data_item);
    console.log(subRef);
    jQuery.ajax({ //ajax request
        url: tfs_css_localized_frontend_data.css_ajax_url,
        type: "POST",
        data: {
            'action': 'css_cancel_auto_renew',
            'security': tfs_css_localized_frontend_data.security_css_request_disable_auto_renew,
            'subref': subRef
        },
        beforeSend: function (data) {
            jQuery(".featherlight-inner").html(updating(tfs_css_localized_frontend_data.txt_css_loading));
        },
        success: function (data) {
            data = jQuery.parseJSON(data);
            if (data['status'] !== 200 ) {
                css_auto_renew_error_revert(data_item, data['message']);
            } else {
                jQuery(".featherlight-inner").html(success_msg_modal(data['message']));
            }
        },
        error: function (errorThrown) {
            console.log("ERROR: " + errorThrown);
            css_auto_renew_error_revert(data_item, tfs_css_localized_frontend_data.txt_css_general_error);
        }
    });
}

function css_auto_renew_stop() {
    jQuery(".featherlight-close").click();
    var data_item = jQuery('#css_auto_renew_item').data('sub');
    css_auto_renew_error_revert(data_item, '');
}

function css_auto_renew_error_revert(data_item, data_value) {
    jQuery(data_item).addClass('auto_renew_arrow_on');
    jQuery(data_item).removeClass('auto_renew_arrow_off');
    jQuery(data_item).text('On');
    if( data_value.length ) {
        jQuery(".featherlight-inner").html(error_msg_modal(data_value));
    }
}

// END #css-subscriptions ======================================================================
