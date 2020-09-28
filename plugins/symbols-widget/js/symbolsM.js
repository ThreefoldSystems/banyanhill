(function() {
    tinymce.create('tinymce.plugins.symbolsM', {
        createControl : function(id, controlManager) {
            if (id == 'symbolsM') {
                // creates the button
                var button = controlManager.createButton('symbolsM', {
                    title : '#6', // title of the button
		    image: '../wp-content/plugins/symbols-widget/img/6.png',
                    onclick : function() {
                        // triggers the thickbox
                        var width = jQuery(window).width(), H = jQuery(window).height(), W = ( 720 < width ) ? 720 : width;
                        W = W - 80;
                        H = H - 84;
                        tb_show( 'rwc_morning_data', '#TB_inline?width=' + W + '&height=' + H + '&inlineId=symbolsM-form' );
                    }
                });
                return button;
            }
            return null;
        },
        getInfo : function() {
            return {
                longname : 'rwc_morning_data',
                author : 'Andrei Shevel',
                version : "0.1"
            };
        }
    });

    tinymce.PluginManager.add('symbolsM', tinymce.plugins.symbolsM);

    jQuery(function(){
        // creates a form to be displayed everytime the button is clicked
        // you should achieve this using AJAX instead of direct html code like this
        var form = jQuery('<div id="symbolsM-form">\
            <table id="symbolsM-table" class="form-table">\
			<tr>\
				<th><label for="symbolsM-columns">Font size</label></th>\
				<td><input type="text" id="simbolsM_fontSize" class="simbolsM_fontSize" /><br />\
			</tr>\
			<tr>\
				<th><label for="symbolsM-columns">Symbols</label></th>\
				<td><input type="text" id="symbolsM" name="symbolsM" /><br />\
			</tr>\
			<tr>\
			    <th></th>\
			    <td>\
                    <div id="symbolsM-place">\
                    </div>\
			    </td>\
			</tr>\
		</table>\
		<p class="submit">\
			<input type="button" id="symbolsM-submit" class="button-primary" value="Insert Symbols" name="submit" />\
		</p>\
		</div>');

        var table = form.find('table');
        form.appendTo('body').hide();

        // handles the click event of the submit button
        form.find('#symbolsM-submit').click(function(){
            // defines the options and their default values
            // again, this is not the most elegant way to do this
            // but well, this gets the job done nonetheless
            var options = {
                'columns'    : '3',
                'id'         : '',
                'size'       : 'thumbnail',
                'orderby'    : 'menu_order ASC, ID ASC',
                'itemtag'    : 'dl',
                'icontag'    : 'dt',
                'captiontag' : 'dd',
                'link'       : '',
                'include'    : '',
                'exclude'    : ''
            };

            if (jQuery('input.symbolsM_name').length > 0) {
                var shortcode = '[rwc_morning_data';

                if (jQuery('input.simbolsM_fontSize').val() !='') {
                    shortcode += ' fontsize="' + jQuery('input.simbolsM_fontSize').val() + '"';
                }

                shortcode += ' name="';
                jQuery('input.symbolsM_name').each(function() {
                    shortcode += jQuery(this).val() + ',';
                });
                shortcode = shortcode.substring(0, shortcode.length-1);
                shortcode += '"';

                shortcode += ' /]';

                // inserts the shortcode into the active editor
                tinyMCE.activeEditor.execCommand('mceInsertContent', 0, shortcode);

                // closes Thickbox
                tb_remove();
            } else {
                alert('Please choose at least one symbol.');
            }
        });


        function split( val ) {
            return val.split( /,\s*/ );
        }
        function extractLast( term ) {
            return split( term ).pop();
        }

        jQuery('input#symbolsM').each(function() {
            jQuery(this).bind( "keydown", function( event ) {
                if ( event.keyCode === jQuery.ui.keyCode.TAB &&
                    jQuery( this ).data( "autocomplete" ).menu.active ) {
                    event.preventDefault();
                }
            })
                .autocomplete({
                    minLength: 0,
                    source: function( request, response ) {
                        jQuery.ajax({
                            type: "GET",
                            url: "/wp-content/plugins/symbols-widget/ajax/as-symbols.php?term=" + extractLast( request.term ),
                            dataType: "json",
                            success: function (data) {
                                response(
                                    jQuery.map(data, function (item) {
                                        return {
                                            label: item.name,
                                            value: item
                                        }
                                    }))
                            }
                        });
                    },
                    focus: function() {
                        // prevent value inserted on focus
                        return false;
                    },
                    select: function( event, ui ) {
                        jQuery('#symbolsM-place').append(
                            '<div><div class="tagchecklist"><span><a class="ntdelbutton" id="post_tag-check-num-0" onclick="jQuery(this).parent().parent().parent().remove();">X</a>&nbsp;'+ui.item.value.name+'</span></div>' +
                                '<input type="hidden" class="symbolsM_name" name="symbolsM_name[]" value="'+ui.item.value.name+'">' +
                        '<input type="hidden" class="symbolsM_id" name="symbolsM_id[]" value="'+ui.item.value.symbol_id+'"></div>'
                        );
                        this.value = '';

                        return false;
                    }
                });
        });
    });

})();