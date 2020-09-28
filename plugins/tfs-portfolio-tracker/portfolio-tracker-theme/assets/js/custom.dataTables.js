jQuery(function(){
    jQuery(document).ready(function() {
        jQuery('#pttable').dataTable({
            "columnDefs": [
                {"width": "12%", "targets": 0 },
                {"width": "10%", "targets": 1 },
                {"width": "10%", "targets": 2 },
                {"width": "10%", "targets": 8 },
                {"sType": "date-mixed", "targets": 3}
            ],
            "aaSorting": [[3, "asc"]],
            "bPaginate": false,
            "bFilter": false
        });
    } );
});

jQuery(function() {
    jQuery.extend(jQuery.fn.dataTableExt.oSort, {
        "date-mixed-pre": function (a) {
            // remove leading spaces and extract date string

            var dateString = a.replace(/^\s*/, '').substring(0, 10).split('/');
            if(dateString == ''){
                return 25000101;
            }
            return (dateString[2] + dateString[0] + dateString[1]) * 1;
        },

        "date-mixed-asc": function (a, b) {
            return ((a < b) ? -1 : ((a > b) ? 1 : 0));
        },

        "date-mixed-desc": function (a, b) {
            return ((a < b) ? 1 : ((a > b) ? -1 : 0));
        }
    });
});

function initPortfolioEvents() {
   jQuery('.portfolio-toggle').on('click', function () {
        let portfolio_toggle = jQuery(this).data('portfolio');
        jQuery(this).find('.fa').toggle();
        jQuery('.portfolio-content[data-portfolio="' + portfolio_toggle + '"]').toggle();
    });
	
	jQuery('.portfolio-expand span').on('click', function(){
		if (jQuery(this).html() === '<i class="fa fa-plus-square-o"></i>') {
			jQuery(this).html('<i class="fa fa-minus-square-o"></i>');
		} else {
			jQuery(this).html('<i class="fa fa-plus-square-o"></i>');
			jQuery(this).parents('.tfs-portfolio-table-control').next().find('.rotated').removeClass('rotated');
//			jQuery(this).parents('.tfs-portfolio-table-control').next().find('tr').removeClass('active');
		}
		
		jQuery(this).parents('.tfs-portfolio-table-control').next().find('tr').not('.portfolio-expand, .portfolio-recommendation-row, .portfolio-recommendation-row-mobile').toggle();		
	});
	
	jQuery('.portfolio-main-row').on('click', function(element){
		if (element.target.tagName === 'SPAN' || element.target.tagName === 'A') return;
		jQuery(this).find('.portfolio-security i.fa-caret-right').toggleClass('rotated');
		
		if (jQuery(this).find('.closed').hasClass('active')) {
			jQuery(this).next().remove();
			jQuery(this).find('.closed').removeClass('active');
		} else {
			var recoRow = jQuery(this).find('.closed').addClass('active').clone().removeClass('closed');

			jQuery(this).after('<tr class="portfolio-recommendation-row closed active">' + recoRow[0].outerHTML + '</tr>');
		}	
//		jQuery(this).parents('.portfolio-main-row').next().toggleClass('active');

//		if (jQuery(this).parents('.portfolio-main-row').next().hasClass('active') && jQuery(this).parents('.portfolio-main-row').next().find('.portfolio-chart-container').html() === '') {
//			jQuery.ajax({
//				context: this,
//				url: admin_ajax_url,
//				type: "POST",
//				data: {
//					'action': 'tfs_get_performance_data',
//                  'symbol': jQuery(this).parent().data('symbol'),
//					'portfolio_id': jQuery(this).parent().data('pid')
//				},
//
//				success: function (data) { 
//					jQuery(this).parents('.portfolio-main-row').next().find('.portfolio-chart-container').html(data);
//				},
//
//				error: function (errorThrown) {
//					console.log(errorThrown);
//				}
//			});			
//		}
	});	
}

jQuery(document).ready( function() {
 initPortfolioEvents();
});