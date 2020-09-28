(function() {
    tinymce.create('tinymce.plugins.symbolsC', {

       /* init : function(ed, url) {

            ed.addButton('symbols', {
                title : '#4', // title of the button
                image: '../wp-content/plugins/symbols-widget/img/4.png',
                onclick : function() {
                    // triggers the thickbox
                    var width = jQuery(window).width(), H = jQuery(window).height(), W = ( 720 < width ) ? 720 : width;
                    W = W - 80;
                    H = H - 84;
                    tb_show( 'rwc_chart_compare', '#TB_inline?width=' + W + '&height=' + H + '&inlineId=symbolsC-form' );
                }
            });
        },*/
        createControl : function(id, controlManager) {
            if (id == 'symbolsC') {
                // creates the button
                var button = controlManager.createButton('symbolsC', {
                    title : '#4', // title of the button
		    image: '../wp-content/plugins/symbols-widget/img/4.png',
                    onclick : function() {
                        // triggers the thickbox
                        var width = jQuery(window).width(), H = jQuery(window).height(), W = ( 720 < width ) ? 720 : width;
                        W = W - 80;
                        H = H - 84;
                        tb_show( 'rwc_chart_compare', '#TB_inline?width=' + W + '&height=' + H + '&inlineId=symbolsC-form' );
                    }
                });
                return button;
            }
            return null;
        },
        getInfo : function() {
            return {
                longname : 'rwc_chart_compare',
                author : 'Andrei Shevel',
                version : "0.1"
            };
        }
    });

    tinymce.PluginManager.add('symbolsC', tinymce.plugins.symbolsC);

    jQuery(function(){
        // creates a form to be displayed everytime the button is clicked
        // you should achieve this using AJAX instead of direct html code like this
        var formV = jQuery('<div id="symbolsC-form">\
            <table id="symbolsC-table" class="form-table">\
			<tr>\
				<th><label for="symbolsC_1-columns">Symbols 1</label></th>\
				<td><input type="text" id="symbolsC_1" name="symbolsC_1" /><br />\
			</tr>\
			<tr>\
			    <th></th>\
			    <td>\
                    <div id="symbolsC_1-place">\
                    </div>\
			    </td>\
			</tr>\
			<tr>\
				<th><label for="symbolsC_2-columns">Symbols 2</label></th>\
				<td><input type="text" id="symbolsC_2" name="symbolsC_2" /><br />\
			</tr>\
			<tr>\
			    <th></th>\
			    <td>\
                    <div id="symbolsC_2-place">\
                    </div>\
			    </td>\
			</tr>\
		</table>\
		<p class="submit">\
			<input type="button" id="symbolsC-submit" class="button-primary" value="Insert Symbols" name="submit" />\
		</p>\
		</div>');

        var table = formV.find('table');
        formV.appendTo('body').hide();

        // handles the click event of the submit button
        formV.find('#symbolsC-submit').click(function(){
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

            if (jQuery.trim(jQuery('input.symbolsC_1_name').val()) != '') {
                var shortcode = '[rwc_chart_compare';

                shortcode += ' name1="';
                shortcode += jQuery('input.symbolsC_1_name').val();
                shortcode += '"';

                if (jQuery.trim(jQuery('input.symbolsC_2_name').val()) != '') {
                    shortcode += ' name2="';
                    shortcode += jQuery('input.symbolsC_2_name').val();
                    shortcode += '"';
                }

                shortcode += ' /]';

                // inserts the shortcode into the active editor
                tinyMCE.activeEditor.execCommand('mceInsertContent', 0, shortcode);

                // closes Thickbox
                tb_remove();
            } else {
                alert('Please choose symbol.');
            }
        });


        jQuery('input#symbolsC_1').each(function() {
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
                        jQuery('#symbolsC_1-place').html(
                            '<div><div class="tagchecklist"><span><a class="ntdelbutton" id="post_tag-check-num-0" onclick="jQuery(this).parent().parent().parent().remove();">X</a>&nbsp;'+ui.item.value.name+'</span></div>' +
                                '<input type="hidden" class="symbolsC_1_name" name="symbolsC_1_name" value="'+ui.item.value.name+'">' +
                                '<input type="hidden" class="symbolsC_1_id" name="symbolsC_1_id" value="'+ui.item.value.symbol_id+'"></div>'
                        );
                        this.value = '';

                        return false;
                    }
                });
        });

        jQuery('input#symbolsC_2').each(function() {
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
                        jQuery('#symbolsC_2-place').html(
                            '<div><div class="tagchecklist"><span><a class="ntdelbutton" id="post_tag-check-num-0" onclick="jQuery(this).parent().parent().parent().remove();">X</a>&nbsp;'+ui.item.value.name+'</span></div>' +
                                '<input type="hidden" class="symbolsC_2_name" name="symbolsC_2_name" value="'+ui.item.value.name+'">' +
                                '<input type="hidden" class="symbolsC_2_id" name="symbolsC_2_id" value="'+ui.item.value.symbol_id+'"></div>'
                        );
                        this.value = '';

                        return false;
                    }
                });
        });
    });

})();