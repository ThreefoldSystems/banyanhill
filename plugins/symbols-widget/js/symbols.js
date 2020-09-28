(function() {
	
	QTags.addButton( 'bh_quicktag', 'symbols', bh_quicktag);
	
    function bh_quicktag(e, c, ed) {
        var start = ed.canvas.selectionStart;
        var end = ed.canvas.selectionEnd;
        var selected = jQuery("#content").val().slice(start, end);

        QTags.insertContent('[' + e.value + ']'+selected+'[/' + e.value + ']');
    }
	
    tinymce.create('tinymce.plugins.SymbolsPlugin', {
       init : function(ed, url) {
            ed.addButton('symbols', {
                title : 'BH Ticker', // title of the button
                image: url + '/img/bh_custom_button.png',
                onclick : function() {
					ed.selection.setContent('[rwc_multi_symbol name="' + ed.selection.getContent() + '"]');
					
                    // triggers the thickbox
                    // var width = jQuery(window).width(), H = jQuery(window).height(), W = ( 720 < width ) ? 720 : width;
                    // W = W - 80;
                    // H = H - 84;
                    // tb_show( 'rwc_multi_symbol', '#TB_inline?width=' + W + '&height=' + H + '&inlineId=symbols-form' );
                }
            });
        },
        createControl : function(id, controlManager) {
//            if (id == 'symbols') {
//                // creates the button
//                var button = controlManager.createButton('symbols', {
//                    title : '#3', // title of the button
//		    image: '../wp-content/plugins/symbols-widget/img/3.png',
//                    onclick : function() {
//                        // triggers the thickbox
//                        var width = jQuery(window).width(), H = jQuery(window).height(), W = ( 720 < width ) ? 720 : width;
//                        W = W - 80;
//                        H = H - 84;
//                        tb_show( 'rwc_multi_symbol', '#TB_inline?width=' + W + '&height=' + H + '&inlineId=symbols-form' );
//                    }
//                });
//                return button;
//            }
            return null;
        },
        getInfo : function() {
            console.log(1);
            return {
                longname : 'rwc_multi_symbol',
                author : 'Andrei Shevel',
                version : "0.1"
            };
        }
    });
    tinymce.PluginManager.add('symbols', tinymce.plugins.SymbolsPlugin);

//    jQuery(function(){
//        // creates a form to be displayed everytime the button is clicked
//        // you should achieve this using AJAX instead of direct html code like this
//        var form = jQuery('<div id="symbols-form">\
//            <table id="symbols-table" class="form-table">\
//			<tr>\
//				<th><label for="symbols-columns">Symbols</label></th>\
//				<td><input type="text" id="symbols" name="symbols" /><br />\
//			</tr>\
//			<tr>\
//			    <th></th>\
//			    <td>\
//                    <div id="symbols-place">\
//                    </div>\
//			    </td>\
//			</tr>\
//		</table>\
//		<p class="submit">\
//			<input type="button" id="symbols-submit" class="button-primary" value="Insert Symbols" name="submit" />\
//		</p>\
//		</div>');
//
//        var table = form.find('table');
//        form.appendTo('body').hide();
//
//        // handles the click event of the submit button
//        form.find('#symbols-submit').click(function(){
//            // defines the options and their default values
//            // again, this is not the most elegant way to do this
//            // but well, this gets the job done nonetheless
//            var options = {
//                'columns'    : '3',
//                'id'         : '',
//                'size'       : 'thumbnail',
//                'orderby'    : 'menu_order ASC, ID ASC',
//                'itemtag'    : 'dl',
//                'icontag'    : 'dt',
//                'captiontag' : 'dd',
//                'link'       : '',
//                'include'    : '',
//                'exclude'    : ''
//            };
//
//            if (jQuery('input.symbols_name').length > 0) {
//                var shortcode = '[rwc_multi_symbol';
//
//                shortcode += ' name="';
//                jQuery('input.symbols_name').each(function() {
//                    shortcode += jQuery(this).val() + ',';
//                });
//                shortcode = shortcode.substring(0, shortcode.length-1);
//                shortcode += '"';
//
//                shortcode += ' /]';
//
//                // inserts the shortcode into the active editor
//                tinyMCE.activeEditor.execCommand('mceInsertContent', 0, shortcode);
//
//                // closes Thickbox
//                tb_remove();
//            } else {
//                alert('Please choose at least one symbol.');
//            }
//        });
//
//
//        function split( val ) {
//            return val.split( /,\s*/ );
//        }
//        function extractLast( term ) {
//            return split( term ).pop();
//        }
//
//        jQuery('input#symbols').each(function() {
//            jQuery(this).bind( "keydown", function( event ) {
//                if ( event.keyCode === jQuery.ui.keyCode.TAB &&
//                    jQuery( this ).data( "autocomplete" ).menu.active ) {
//                    event.preventDefault();
//                }
//            })
//                .autocomplete({
//                    minLength: 0,
//                    source: function( request, response ) {
//                        jQuery.ajax({
//                            type: "GET",
//                            url: "/wp-content/plugins/symbols-widget/ajax/as-symbols.php?term=" + extractLast( request.term ),
//                            dataType: "json",
//                            success: function (data) {
//                                response(
//                                    jQuery.map(data, function (item) {
//                                        return {
//                                            label: item.name,
//                                            value: item
//                                        }
//                                    }))
//                            }
//                        });
//                    },
//                    focus: function() {
//                        // prevent value inserted on focus
//                        return false;
//                    },
//                    select: function( event, ui ) {
//                        jQuery('#symbols-place').append(
//                            '<div><div class="tagchecklist"><span><a class="ntdelbutton" id="post_tag-check-num-0" onclick="jQuery(this).parent().parent().parent().remove();">X</a>&nbsp;'+ui.item.value.name+'</span></div>' +
//                                '<input type="hidden" class="symbols_name" name="symbols_name[]" value="'+ui.item.value.name+'">' +
//                        '<input type="hidden" class="symbols_id" name="symbols_id[]" value="'+ui.item.value.symbol_id+'"></div>'
//                        );
//                        this.value = '';
//
//                        return false;
//                    }
//                });
//        });
//    });

})();