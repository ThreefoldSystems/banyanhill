// JavaScript Document
(function() {
    'use strict';
    
	var buttonArray = ['bh_quote', 'bh_accordion', 'bh_transcript'];
	
	for (var i = 0; i < buttonArray.length; i++) {
		// Add the BH buttons to the HTML/Text Editor
    	QTags.addButton( 'bh_quicktag', buttonArray[i], bh_quicktag);
	}
	
    function bh_quicktag(e, c, ed) {
        var start = ed.canvas.selectionStart;
        var end = ed.canvas.selectionEnd;
        var selected = jQuery("#content").val().slice(start, end);

        QTags.insertContent('[' + e.value + ']'+selected+'[/' + e.value + ']');
    }	
	
	//Banyan Hill Custom Blockquote	
    tinymce.create('tinymce.plugins.bh_quote', {
        init : function(ed, url) {
            ed.addButton('bh_quote', {
                title : 'BH Quote',
                image : url + '/img/bh_custom_button.png',
                onclick : function() {
                     ed.selection.setContent('[bh_quote]' + ed.selection.getContent() + '[/bh_quote]');
 
                }
            });
        },
        createControl : function(n, cm) {
            return null;
        },
    });
    tinymce.PluginManager.add('bh_quote', tinymce.plugins.bh_quote);
	
	//Banyan Hill Custom Accordion
    tinymce.create('tinymce.plugins.bh_accordion', {
        init : function(ed, url) {
            ed.addButton('bh_accordion', {
                title : 'BH Accordion',
                image : url + '/img/bh_custom_button.png',
                onclick : function() {
                     ed.selection.setContent('[bh_accordion]' + ed.selection.getContent() + '[/bh_accordion]');
 
                }
            });
        },
        createControl : function(n, cm) {
            return null;
        },
    });
    tinymce.PluginManager.add('bh_accordion', tinymce.plugins.bh_accordion);
	
	//Banyan Hill Custom Transcript
    tinymce.create('tinymce.plugins.bh_transcript', {
        init : function(ed, url) {
            ed.addButton('bh_transcript', {
                title : 'BH Transcript',
                image : url + '/img/bh_custom_button.png',
                onclick : function() {
                     ed.selection.setContent('[bh_transcript]' + ed.selection.getContent() + '[/bh_transcript]');
 
                }
            });
        },
        createControl : function(n, cm) {
            return null;
        },
    });
    tinymce.PluginManager.add('bh_transcript', tinymce.plugins.bh_transcript);	
})();