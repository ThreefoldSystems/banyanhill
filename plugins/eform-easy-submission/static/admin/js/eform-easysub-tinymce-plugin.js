( function() {
	"use strict";

	// Direct Submission Edit Shortcode
	iptFSQMTMMenu.addons[iptFSQMTMMenu.addons.length] = function( editor, url, forms, themes ) {
		// Direct Submission Edit shortcode
		var DSbody = [];
		// Add the forms
		DSbody[ DSbody.length ] = {
			type: 'listbox',
			name: 'eFormID',
			label: iptFSQMTML10n.l10n.ifl,
			values: forms
		};
		// Add Checkbox for referal
		DSbody[ DSbody.length ] = {
			type   : 'checkbox',
			name   : 'eFormReferal',
			text   : eFormEasySubShortcode.l10n.dsrf,
			checked : false,
			tooltip : eFormEasySubShortcode.l10n.dsrftt
		};

		// Add Show Login Form Checkbox
		DSbody[ DSbody.length ] = {
			type   : 'checkbox',
			name   : 'eFormShowLogin',
			text   : eFormEasySubShortcode.l10n.slf,
			checked : true
		};
		// Add Do Not Show New Form for non-logged in users
		DSbody[ DSbody.length ] = {
			type   : 'checkbox',
			name   : 'eFormBehavior',
			text   : eFormEasySubShortcode.l10n.dsnf,
			checked : false,
			tooltip : eFormEasySubShortcode.l10n.dsnftt
		};
		// Add Message for Login Form
		DSbody[ DSbody.length ] = {
			type   : 'textbox',
			name   : 'eFormMessage',
			label  : eFormEasySubShortcode.l10n.lgmsg,
			value  : eFormEasySubShortcode.l10n.lgmsg
		};

		// Now return the tinyMCE object
		return {
			text: eFormEasySubShortcode.l10n.dst,
			icon: 'icon ipt-icomoon-edit',
			onclick: function() {
				var height = jQuery(window).height(), width = jQuery(window).width();
				var win = editor.windowManager.open( {
					title: eFormEasySubShortcode.l10n.dst,
					height: 200,
					width: ( width < 600 ) ? ( width - 50 ) : 600,
					autoScroll: true,
					classes: 'ipt-fsqm-panel',
					body: DSbody,
					onsubmit: function( e ) {
						var shortcode = '[ipt_fsqm_sutb id="' + e.data.eFormID + '" msg="' + e.data.eFormMessage + '" ' +
										'show_login="' + ( true === e.data.eFormShowLogin ? '1' : '0' ) + '" ' +
										'referer="' + ( true === e.data.eFormReferal ? '1' : '0' ) + '" ' +
										'block_for_non_logged="' + ( true === e.data.eFormBehavior ? '1' : '0' ) + '"]';

						editor.insertContent( '<br />' + shortcode + '<br />' );
					}
				} );
			}
		}
	}
} )();
