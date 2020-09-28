(function() {
    tinymce.create('tinymce.plugins.symbolsS', {
        createControl : function(id, controlManager) {
            if (id == 'symbolsS') {
                // creates the button
                var button = controlManager.createButton('symbolsS', {
                    title : '#1', // title of the button
		    image: '../wp-content/plugins/symbols-widget/img/1.png',
                    onclick : function() {
                        // triggers the thickbox
                        var width = jQuery(window).width(), H = jQuery(window).height(), W = ( 720 < width ) ? 720 : width;
                        W = W - 80;
                        H = H - 84;
                        tb_show( 'rwc_chart_single', '#TB_inline?width=' + W + '&height=' + H + '&inlineId=symbolsS-form' );
                    }
                });
                return button;
            }
            return null;
        },
        getInfo : function() {
            return {
                longname : 'rwc_chart_single',
                author : 'Andrei Shevel',
                version : "0.1"
            };
        }
    });

    tinymce.PluginManager.add('symbolsS', tinymce.plugins.symbolsS);

    jQuery(function(){
        // creates a form to be displayed everytime the button is clicked
        // you should achieve this using AJAX instead of direct html code like this
        var formV = jQuery('<div id="symbolsS-form">\
            <table id="symbolsS-table" class="form-table">\
			<tr>\
				<th><label for="symbolsS_1-columns">Symbols</label></th>\
				<td><input type="text" id="symbolsS_1" name="symbolsS_1" /><br />\
			</tr>\
			<tr>\
			    <th></th>\
			    <td>\
                    <div id="symbolsS_1-place">\
                    </div>\
			    </td>\
			</tr>\
		</table>\
		<p class="submit">\
			<input type="button" id="symbolsS-submit" class="button-primary" value="Insert Symbols" name="submit" />\
		</p>\
		</div>');

        var table = formV.find('table');
        formV.appendTo('body').hide();

        // handles the click event of the submit button
        formV.find('#symbolsS-submit').click(function(){
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

            if (jQuery.trim(jQuery('input.symbolsS_1_name').val()) != '') {
                var shortcode = '[rwc_chart_single';

                shortcode += ' name="';
                shortcode += jQuery('input.symbolsS_1_name').val();
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


        jQuery('input#symbolsS_1').each(function() {
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
                        jQuery('#symbolsS_1-place').html(
                            '<div><div class="tagchecklist"><span><a class="ntdelbutton" id="post_tag-check-num-0" onclick="jQuery(this).parent().parent().parent().remove();">X</a>&nbsp;'+ui.item.value.name+'</span></div>' +
                                '<input type="hidden" class="symbolsS_1_name" name="symbolsS_1_name" value="'+ui.item.value.name+'">' +
                                '<input type="hidden" class="symbolsS_1_id" name="symbolsS_1_id" value="'+ui.item.value.symbol_id+'"></div>'
                        );
                        this.value = '';

                        return false;
                    }
                });
        });
    });

})();