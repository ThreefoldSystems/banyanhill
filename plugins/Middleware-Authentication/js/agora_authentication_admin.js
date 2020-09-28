// Language variables and their explanations
var language_variables = {
    "txt_welcome": "Displaying welcome message to the user after the user logs in.",
    "txt_hide_content_shortcode": "Used when the user is not allowed to see the content protected using [hidecontent] shortcode.",
    "txt_failed_login": "Displayed when an empty login happens (empty username or password).",
    "txt_login_button": "Text of the 'Login' button on login page/block.",
    "txt_login_remember": "Text of the 'Remember Me' checkbox on login page.",
    "txt_logout": "Text of the 'Logout' link on login widget.",
    "txt_already_logged_in": "Message on the login page for the user that's already logged in.",
    "txt_access_denied": "Message stored in the auth_container when the user does not have access to the pubcode.",
    "txt_multiple_logins_found": "Message will be included in Legacy Mode password reset email if there's more than 1 account associated with the email address.",
    "txt_login_title": "Title on the login page/block.",
    "txt_default_login_message": "Message/subtitle on the login page/block.",
    "txt_login_username_label": "Label for the username/email input on login page/block.",
    "txt_login_username_placeholder": "Placeholder for the username/email input on login page/block.",
    "txt_login_password_label": "Label for the password input on login page/block.",
    "txt_login_password_placeholder": "Placeholder for the password input on login page/block.",
    "txt_invalid_email_address": "Displayed when invalid email address is submitted on the password reset form.",
    "txt_email_already_in_use": "Displayed when the email address is already is use on login page/block.",
    "txt_auto_login_success": "Displayed when the user has logged in using tokenized login successfully.",
    "txt_auto_login_fail": "Displayed when the user has failed to log in using tokenized login.",
    "txt_no_valid_subscriptions": "Displayed on login page/block when no subscriptions came from Middleware.",
    "txt_home_link": "Text for the home link displayed after a successful password reset on password reset page.",
    "txt_reset_pwd_title": "Title on forgot password page.",
    "txt_reset_username_title": "Title on forgot username page.",
    "txt_reset_password_subtitle": "Message/subtitle on forgot password page.",
    "txt_reset_password_username_placeholder": "Placeholder for the username input on password reset form.",
    "txt_reset_password_new_password_placeholder": "Placeholder for the new password input on password reset form.",
    "txt_reset_password_confirm_password_placeholder": "Placeholder for the confirm password input on password reset form.",
    "txt_new_pwd_username_input_label": "Label for the username input on password reset form.",
    "txt_new_pwd_input_label": "Label for the new password input on password reset form.",
    "txt_confirm_pwd_input_label": "Label for the confirm password input on password reset form.",
    "txt_forgot_username_password_email_placeholder": "Label for the password input on forgot password page.",
    "txt_reset_username_subtitle": "Message/subtitle on forgot username page.",
    "txt_default_reset_password_message": "Message on password reset page when secure login link option is disabled.",
    "txt_default_reset_password_magic_link_message": "Message on password reset page when secure login link option is enabled.",
    "txt_forgot_password_email_subject": "Subject and title of the forgot password email (tokenized mode).",
    "txt_forgot_password_plaintext_email_subject": "Subject and title of the forgot password email (legacy mode).",
    "txt_forgot_username_email_subject": "Subject and title of the forgot username email.",
    "txt_magic_link_email_subject": "Subject and title of the secure login link email.",
    "txt_magic_link_button_label": "Text for the button of secure login link on forgot password page.",
    "txt_invalid_link": "Message displayed password reset page after following an invalid link.",
    "txt_pwd_button": "Text for the submit button on password username form.",
    "txt_username_button": "Text for the submit button on forgot username form.",
    "txt_email_input_label": "Label for the email input on the forgot username/password forms.",
    "txt_change_pwd_title": "Title for the password reset page.",
    "txt_change_pwd_button": "Text for the submit button on password reset form.",
    "txt_forgot_username_link": "Text for the forgot username link on forgot password/login page.",
    "txt_forgot_password_link": "Text for the forgot password link on forgot username page.",
    "txt_forgot_password_link_short": "Text for the forgot password link on login page/block.",
    "txt_forgot_link": "Text for the forgot username/password link on forgot password page.",
    "txt_login_pwd": "Label for the password input on login block.",
    "txt_forgot_password_username_email_subject": "Subject and title of the failed login email.",
    "txt_forgot_email_sent": "Default message displayed after a password reset email has been sent out.",
    "txt_email_sent_reset_password": "Message displayed after a forgot password email has been sent out.",
    "txt_email_sent_magic_link": "Message displayed after a single sign on link email has been sent out.",
    "txt_email_sent_forgot_username": "Message displayed after a forgot username email has been sent out.",
    "txt_account_not_found": "Message displayed when email could not be sent out because no account could be found with that email address.",
    "txt_password_reset_invalid_user": "Message displayed when email could not be sent out because no account could be found with that username.",
    "txt_forgot_password_email_from": "FROM text in the emails sent.",
    "txt_password_reset_successful": "Message on password reset page when password is reset successfully.",
    "txt_password_reset_invalid_combination": "Message on password reset page when password entered is invalid.",
    "txt_password_reset_no_match": "Message displayed when the two passwords entered on password reset form do not match.",
    "txt_password_reset_invalid_link": "Message displayed when the link used to reset the password is invalid.",
    "txt_temporary_password": "Message displayed when temporary password has been detected on password reset and forgot password/username forms.",
    "txt_changed_password_recently": "Message displayed when the user is attempting to reset their password and the password has already been changes recently.",
    "txt_forgot_password_email_multiple_accounts": "Message for forgot password (tokenized mode) email when there are multiple accounts for the email address.",
    "txt_forgot_password_plaintext_multiple_accounts": "Message for forgot password (legacy mode) email when there are multiple accounts for the email address.",
    "txt_forgot_username_multiple_accounts": "Message for forgot username email when there are multiple accounts for the email address.",
    "txt_magic_link_multiple_accounts": "Message for forgot secure login link when there are multiple accounts for the email address.",
    "inp_failed_login_email_text": "Email content for the failed logins email.",
    "inp_forgot_password_email_text": "Email content for the forgot password (tokenized mode) email.",
    "inp_password_reminder_email_text": "Email content for the forgot password (legacy mode) email.",
    "inp_forgot_username_text": "Email content for the forgot username email.",
    "inp_magic_link_text": "Email content for the secure login link email.",
    "txt_magic_link_link_expiration": "Message on secure login link email.",
    "txt_forgot_password_email_username_label": "Username label on secure login link and forgot password/username emails for the account(s).",
    "txt_password_email_subscriptions_label": "Subscriptions label on secure login link and forgot password/username emails for the account(s).",
    "txt_reset_password_email_link_label": "Text for the reset link on forgot password emails.",
    "txt_forgot_username_email_link_label": "Text for the login link on forgot username emails.",
    "txt_magic_link_email_link_label": "Text for the login link on secure login link emails.",
    "txt_forgot_password_is": "Text for the password on forgot password (legacy mode) emails.",
    "txt_plus_x_more_subscriptions": "Text for the remaining subscriptions if there's more than 5 subscriptions on the account.",
    "txt_multiple_users": "Text for password reset when there's multiple accounts for the email address.",
    "txt_fb_token_error": "Message displayed when the token used for facebook login is invalid on facebook login.",
    "txt_fb_no_config": "Message displayed when the config used for facebook login is not setup on facebook login.",
    "txt_fb_no_user_mw": "Message displayed when account could not be found in Middleware using the email address retrieved from facebook on facebook login.",
    "txt_fb_no_email": "Message displayed when email address could not be retrieved from facebook on facebook login.",
    "txt_fb_assign_error": "Message displayed when facebook login failed on facebook login.",
    "txt_fb_sharing_error": "Message displayed when facebook login failed on facebook login.",
    "txt_fb_login_using_fb": "Message for facebook login.",
    "txt_fb_multiple_users": "Message for facebook login when multiple accounts have been retrieved.",
    "txt_invalid_fb_link": "Message when facebook login link has expired on facebook login.",
    "txt_invalid_fb_username": "Message when username entered is invalid on facebook login."
};

jQuery(document).ready(function() {
    // Foreach language variable, populate the tooltip on LV page
    jQuery.each( language_variables, function( key, value ) {
        var tooltip = jQuery("." + key + "_tooltip");

        if ( tooltip.length ) {
            tooltip.text(value);

            jQuery(".lv-tooltip_" + key).show();
        }
    });
});

// Toggle mail option
jQuery( document ).ready( function() {
    var mw_mail_toggle = jQuery( '#mw-mail-toggle' );

    if ( mw_mail_toggle.val() == "1" ) {
        jQuery( '.mw-email-default-configuration' ).hide();
        jQuery( '.mw-email-sp-configuration' ).hide();

        jQuery( '.mw-email-mc-configuration' ).show();
    } else if ( mw_mail_toggle.val() == "2" ) {
        jQuery( '.mw-email-default-configuration' ).hide();
        jQuery( '.mw-email-mc-configuration' ).hide();

        jQuery( '.mw-email-sp-configuration' ).show();
    } else {
        jQuery( '.mw-email-mc-configuration' ).hide();
        jQuery( '.mw-email-sp-configuration' ).hide();

        jQuery( '.mw-email-default-configuration' ).show();
    }

    mw_mail_toggle.change( function() {
        if ( mw_mail_toggle.val() == "1" ) {
            jQuery( '.mw-email-default-configuration' ).hide();
            jQuery( '.mw-email-sp-configuration' ).hide();

            jQuery( '.mw-email-mc-configuration' ).show();
        } else if ( mw_mail_toggle.val() == "2" ) {
            jQuery( '.mw-email-default-configuration' ).hide();
            jQuery( '.mw-email-mc-configuration' ).hide();

            jQuery( '.mw-email-sp-configuration' ).show();
        } else {
            jQuery( '.mw-email-mc-configuration' ).hide();
            jQuery( '.mw-email-sp-configuration' ).hide();

            jQuery( '.mw-email-default-configuration' ).show();
        }
    });

    // Submit test email form
    jQuery("#mw_send_test_email").submit(function(e) {
        //prevent Default functionality
        e.preventDefault();

        var email_input = jQuery("#mw_send_test_email_input");

        var returned_message = jQuery('.mw_returned_message_test_email');

        // See if email address has been entered
        if (jQuery.trim(email_input.val()) != '' && validateEmail(email_input.val())) {
            email_input.css({"border": "", "background": ""});

            returned_message.fadeIn(500).html('<div class="mw_test_email_success"><p>Sending email...</p></div>');

            // Data to be sent
            var data = {
                action: "mw_test_email",
                mw_test_email_nonce: jQuery('#mw_test_email_nonce').val(),
                email_address: email_input.val()
            };

            // Ajax submit
            jQuery.post(agora_middleware_authentication.ajaxurl, data, function(response) {
                if ( response ) {
                    response = JSON.parse(response);

                    if ( response.type == 'success' ) {
                        returned_message.fadeIn(500).html('<div class="mw_test_email_success"><p>' + response.message + '</p></div>');
                    } else {
                        returned_message.fadeIn(500).html('<div class="mw_test_email_error"><p>' + response.message + '</p></div>');
                    }
                } else {
                   returned_message.fadeIn(500).html('<div class="mw_test_email_error"><p>Something went wrong. Please try again.</p></div>');

                }
            });
        } else {
            inputs_entered = false;

            email_input.css({ "border": "1px solid red", "background": "#FFE5E5" });

            returned_message.fadeIn(500).html('<div class="mw_test_email_error"><p>Please enter a valid email address.</p></div>');
        }
    });

    // Hide returned message if entering new information in inputs.
    jQuery('form[name="mw_send_test_email"] :input').keydown(function() {
        jQuery('.mw_returned_message_test_email').slideUp();
    });
});

// Closing test email sending modal
jQuery(document).on('closing', '.mw_send_test_email_modal', function (e) {
    jQuery('.mw_returned_message_test_email').slideUp();

    var email_input = jQuery("#mw_send_test_email_input");

    email_input.val("");
    email_input.css({"border": "", "background": ""});
});

function validateEmail(email) {
    var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
}


// Toggle mail option
jQuery( document ).ready( function() {
    var mw_user_webhook_toggle = jQuery( '#mw-user-webhook-toggle' );

    if ( mw_user_webhook_toggle.val() == "1" ) {
        jQuery( '.mw-user-webhook-toggle-area' ).show();
    } else {
        jQuery( '.mw-user-webhook-toggle-area' ).hide();
    }

    mw_user_webhook_toggle.change( function() {
        console.log(mw_user_webhook_toggle.val());

        if ( mw_user_webhook_toggle.val() == "1" ) {
            jQuery( '.mw-user-webhook-toggle-area' ).show();
        } else {
            jQuery( '.mw-user-webhook-toggle-area' ).hide();
        }
    });
});

jQuery(function(){
	attach_event_handlers();
});

function process_ajax_request(form_data, container){
	jQuery.ajax({
		type: 'POST',
		url: agora_middleware_authentication.ajaxurl,
		data: form_data,
		dataType: "html",
		success: function(data, textStatus, XMLHttpRequest){
			jQuery(container).html(data);
			attach_event_handlers();
            jQuery('.ajax_spinner').hide();
            jQuery('.submit_field input[type="submit"]').show();
            if(data.substr(data.length -4) == 'Pass'){
                jQuery('.ajax_message').show();
                jQuery('.ajax_message').html('Authentication code created successfully');
                jQuery('.ajax_message').addClass('ajax_success');
            } else if(data.substr(data.length -4) == 'Fail')  {
                jQuery('.ajax_message').show();
                jQuery('.ajax_message').html('Failed to create Authentication code');
                jQuery('.ajax_message').addClass('ajax_fail');
            }
        },
		error: function(){
            jQuery('.ajax_message').html('Failed to create Authentication code');
        }
	});
}

function ajax_delete_auth_object(object_id, object_type, container, nonce, parent){
	jQuery.ajax({
		type: 'POST',
		url: agora_middleware_authentication.ajaxurl,
		data: { action: object_type + '_delete', id: object_id, security: nonce, parent: parent },
		dataType: "html",
		success: function(data, textStatus, XMLHttpRequest){
			jQuery(container).html(data);
			attach_event_handlers();
        },
		error: function(){ }
	});
}

function get_rule_form(targetContainer, authcode_id, prepend_to){
    jQuery.ajax({
        type: 'POST',
        url: agora_middleware_authentication.ajaxurl,
        data: {
            action: 'get_rule_form',
            target_container: targetContainer,
            authcode_id: authcode_id
        },
        dataType: 'html',
        success: function(data, textStatus, XMLHttpRequest){
            prepend_to.before(data);
            attach_event_handlers();
        }
    });
}

function attach_event_handlers(){
    // Cleanup bindings. Since we add them multiple times after AJAX requests
    jQuery('.edit_link').unbind('click');
    jQuery('.delete_item').unbind('click');
    jQuery('.add_rule').unbind('click');
    jQuery('.cancel_rule_form').unbind('click');
    jQuery('.cancel_authcode_edit').unbind('click');
    jQuery('#pubcodes_admin form').unbind('submit');
    jQuery('.pubcode_ajax_form.update .auth_type').unbind('change');
    jQuery('.authcode_field input').unbind('focus');

    jQuery('.add_rule').click(function(){
        var prepend_to = jQuery(this);
        var target_container = jQuery(this).data('container');
        var authcode_id = jQuery(this).data('authcode');
        get_rule_form(target_container, authcode_id, prepend_to);
        return false;
    });

    jQuery('.authcode_input').focus(function() {
        jQuery(this).next('.tooltip').fadeIn();
    });

    jQuery('.authcode_input').blur(function() {
        jQuery(this).next('.tooltip').fadeOut();
    });

    jQuery('#pubcodes_admin form').on('submit', function(e){
        jQuery('.submit_field input[type="submit"]').hide();
        jQuery(this).find('.ajax_spinner').show();
        jQuery('.ajax_message').fadeOut();
        jQuery('.ajax_message').removeClass('ajax_success');
        jQuery('.ajax_message').removeClass('ajax_fail');
        var container = '#' + jQuery(this).data('container');
        var disabled = jQuery(this).find(':input:disabled').removeAttr('disabled');
        var form_data = jQuery(this).serialize();
        disabled.attr('disabled','disabled');
        process_ajax_request(form_data, container);
        return false;
    });

	jQuery('.edit_link').click(function(){
		var row_id = jQuery(this).data('row-id');
		jQuery('#' + row_id).toggle();
		return false;
	});

	jQuery('.cancel_authcode_edit').click(function(){
		jQuery(this).closest('tr').toggle('300');
		return false;
	});

    jQuery('.cancel_rule_form').click(function(){
        var form_object = jQuery(this).data('cancel');
        jQuery('#' + form_object).remove();
    });

	jQuery('.delete_item').click(function(){
		var object_id = jQuery(this).data('object-id');
        var object_type = jQuery(this).data('object-type');
        var parent = jQuery(this).data('parent');
        var nonce = jQuery(this).data('nonce');
        var container = '#all_pubcodes_rows';
        var confirm_delete = confirm('Are you sure you want to delete this ' + object_type + '?');

        if(confirm_delete == true){
            ajax_delete_auth_object(object_id, object_type, container, nonce, parent);
        }
		return false;
	});

    jQuery('.pubcode_ajax_form.update .auth_type').change(function(){
        alert('Warning: If you change the Type of an authentication code any rules associated with it will need to be changed too.');
    });

}

