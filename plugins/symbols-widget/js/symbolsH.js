(function() {
    tinymce.create('tinymce.plugins.symbolsH', {
        createControl : function(id, controlManager) {
            if (id == 'symbolsH') {
                // creates the button
                var button = controlManager.createButton('symbolsH', {
                    title : '#2', // title of the button
		        image: '../wp-content/plugins/symbols-widget/img/2.png',
                    onclick : function() {
                        // triggers the thickbox
                        var width = jQuery(window).width(), H = jQuery(window).height(), W = ( 720 < width ) ? 720 : width;
                        W = W - 80;
                        H = H - 84;
                        tb_show( 'rwc_horizontal', '#TB_inline?width=' + W + '&height=' + H + '&inlineId=symbolsH-form' );
                    }
                });
                return button;
            }
            return null;
        },
        getInfo : function() {
            return {
                longname : 'rwc_horizontal',
                author : 'Andrei Shevel',
                version : "0.1"
            };
        }
    });

    tinymce.PluginManager.add('symbolsH', tinymce.plugins.symbolsH);

    jQuery(function(){
        // creates a form to be displayed everytime the button is clicked
        // you should achieve this using AJAX instead of direct html code like this
        var formV = jQuery('<div id="symbolsH-form">\
            <table id="symbolsH-table" class="form-table">\
			<tr>\
				<th><label for="symbolsH-columns">Symbols</label></th>\
				<td><input type="text" id="symbolsH" name="symbolsH" /><br />\
			</tr>\
			<tr>\
			    <th></th>\
			    <td>\
                    <div id="symbolsH-place">\
                    </div>\
			    </td>\
			</tr>\
		</table>\
		<p class="submit">\
			<input type="button" id="symbolsH-submit" class="button-primary" value="Insert Symbols" name="submit" />\
		</p>\
		</div>');

        var table = formV.find('table');
        formV.appendTo('body').hide();

        // handles the click event of the submit button
        formV.find('#symbolsH-submit').click(function(){
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

            if (jQuery.trim(jQuery('input.symbolsH_name').val()) != '') {
                var shortcode = '[rwc_horizontal';

                shortcode += ' name="';
                shortcode += jQuery('input.symbolsH_name').val();
                shortcode += '"';

                shortcode += ' /]';

                // inserts the shortcode into the active editor
                tinyMCE.activeEditor.execCommand('mceInsertContent', 0, shortcode);

                // closes Thickbox
                tb_remove();
            } else {
                alert('Please choose symbol.');
            }
        });


        jQuery('input#symbolsH').each(function() {
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
                            url: "/wp-content/plugins/symbols-widget/ajax/as-symbols.php?term=" + request.term,
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
                        jQuery('#symbolsH-place').html(
                            '<div><div class="tagchecklist"><span><a class="ntdelbutton" id="post_tag-check-num-0" onclick="jQuery(this).parent().parent().parent().remove();">X</a>&nbsp;'+ui.item.value.name+'</span></div>' +
                                '<input type="hidden" class="symbolsH_name" name="symbolsH_name" value="'+ui.item.value.name+'">' +
                                '<input type="hidden" class="symbolsH_id" name="symbolsH_id" value="'+ui.item.value.symbol_id+'"></div>'
                        );
                        this.value = '';

                        return false;
                    }
                });
        });
    });

})();